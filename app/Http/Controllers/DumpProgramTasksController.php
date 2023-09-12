<?php

namespace App\Http\Controllers;

use App\DumpProgramTask;
use App\Program;
use App\ProgramTask;
use App\User;
use App\UserProgram;
use App\UserProgramTask;
use App\Value;
use App\ValueList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Webklex\IMAP\Facades\Client;

class DumpProgramTasksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'site']);
    }

    public function index()
    {
        $count = 0;
        $dump_program_tasks = request()->site->dump_program_tasks;
        $count = $dump_program_tasks->count();
        return response()->json([
            'data'     =>  $dump_program_tasks,
            'count'    =>   $count
        ], 200);
    }

    /*
     * To store a new dump_program_task
     *
     *@
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject'        =>  'required',
        ]);

        $dump_program_task = new DumpProgramTask(request()->all());
        $request->site->dump_program_tasks()->save($dump_program_task);

        return response()->json([
            'data'    =>  $dump_program_task
        ], 201);
    }

    /*
     * To view a single dump_program_task
     *
     *@
     */
    public function show(DumpProgramTask $dump_program_task)
    {
        return response()->json([
            'data'   =>  $dump_program_task,
            'success' =>  true
        ], 200);
    }

    /*
     * To update a dump_program_task
     *
     *@
     */
    public function update(Request $request, DumpProgramTask $dump_program_task)
    {
        $dump_program_task->update($request->all());

        return response()->json([
            'data'  =>  $dump_program_task
        ], 200);
    }

    public function destroy($id)
    {
        $dump_program_task = DumpProgramTask::find($id);
        $dump_program_task->delete();

        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }

    public function imapInbox()
    {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        /** @var \Webklex\PHPIMAP\Client $client */
        $client = Client::account('default');

        //Connect to the IMAP Server
        $client->connect();

        //Get all Mailboxes
        /** @var \Webklex\PHPIMAP\Support\FolderCollection $folders */
        $folders = $client->getFolders();
        // $previous_date = Carbon::now();
        // day -1
        $previous_date = Carbon::now()->subDays(1);
        //Loop through every Mailbox
        /** @var \Webklex\PHPIMAP\Folder $folder */
        foreach ($folders as $folder) {

            //Get all Messages of the current Mailbox $folder
            /** @var \Webklex\PHPIMAP\Support\MessageCollection $messages */
            $messages = $folder->messages()->since($previous_date)->get();
            // return $messages;
            /** @var \Webklex\PHPIMAP\Message $message */
            foreach ($messages as $message) {
                $message->getAttachments()->each(function ($oAttachment) use ($message) {

                    $check_email_existing = request()->site->dump_program_tasks()
                        ->where('message_id', $message->getMessageId())
                        ->first();
                    // return $check_email_existing;
                    if (!$check_email_existing) {
                        // return 1;
                        $path = 'attachments/' . $message->getMessageId() . '/' . $oAttachment->name;
                        Storage::disk('local')->put($path, $oAttachment->content, 'public');

                        $data = [
                            'subject' => $message->getSubject(),
                            'body' => $message->getHTMLBody(),
                            'imagepath1' => $path,
                            'message_id' => $message->getMessageId(),
                        ];
                        // return $data;

                        $dump_program_task = new DumpProgramTask($data);
                        request()->site->dump_program_tasks()->save($dump_program_task);
                    }
                });
            }
        }
    }

    public function autoAssign($dumpId)
    {
        // Get dump data from given id
        $dumpData = DumpProgramTask::where('id', $dumpId)->first();
        // get attachment from dumpdata
        $dumpAttachment = $dumpData->imagepath1;
        // Extract attachment name by slash /
        $explodeBySlash = explode('/', $dumpAttachment);
        // Extract attachment name by underscore _ to get program short name, task and user
        $explodebyunderscore = explode('_', $explodeBySlash[2]);
        // Program Short Name
        $programShortName = $explodebyunderscore[0];
        // Program Task code
        $programTaskCode = $explodebyunderscore[1];
        // User unique code
        $user = $explodebyunderscore[2];
        // Ship Name
        $ship = $explodebyunderscore[3];
        $msg = '';
        $programId = null;
        $taskId = null;
        $userId = null;
        $shipId = null;
        $userProgramId = null;

        // Get user from our database by user unique id (DANOS ID)
        $user = User::where('unique_id', $user)->first();
        // Check user exist or not
        if ($user) {
            $userId = $user->id;
        } else {
            $msg = 'Provided danos id of user not exist in our database.';
        }

        // Get User program from databse
        $userProgram = UserProgram::where(['user_id' => $userId, 'program_id' => $programId])->first();
        if ($userProgram) {
            $userProgramId = $userProgram->id;

            // Get program from our database by short name
            $program = Program::where('short_name', $programShortName)->first();
            // Check program exist or not
            if ($program) {
                $programId = $program->id;
            } else {
                $msg = 'Provided short name of program not exist in our database.';
            }
            // Get task from our database by Task code
            $task = ProgramTask::where('serial_no', $programTaskCode)->first();
            // Check task is exist or not
            if ($task) {
                if ($task->program_id == $programId) {
                    $taskId = $task->id;
                } else {
                    $msg = 'Provided serial no is not linked with ' . $programShortName . '.';
                }
            } else {
                $msg = 'Provided serial no of task not exist in our database.';
            }



            // Get Ship from database 
            $shipValue = Value::where('name', 'SHIPS')->where('site_id', '=', request()->site->id)->first();
            $shipValueList = ValueList::where('value_id', $shipValue->id)->where('code', $ship)->first();
            if ($shipValueList) {
                $shipId = $shipValueList->id;
            } else {
                $msg = 'Provided ship is not in our databse.';
            }

            $dumpPayload = [
                'site_id' => request()->site->id,
                'ship_id' => $shipId,
                'program_id' => $programId,
                'program_task_id' => $taskId,
                'user_id' => $userId,
                'added_by_id' => Auth::user()->id,
                'user_program_id' => $userProgramId,
            ];

            $userprogramTask = new UserProgramTask($dumpPayload);
            $userprogramTask->save();
            $user_program_task_id = $userprogramTask->id;

            if ($dumpAttachment) {
                $file_name = $dumpAttachment;
                $test = explode('/', $file_name);
                $name =  end($test);
                $new_path = 'lbsm/user-program-task/' .  $user_program_task_id . '/' . $name;
                Storage::move($dumpAttachment, $new_path);

                $userprogramTask->update(['imagepath1' => $new_path]);

                if ($userprogramTask) {
                    $dumpPayloadUpdate = [
                        'site_id' => request()->site->id,
                        'ship_id' => $shipId,
                        'program_id' => $programId,
                        'program_task_id' => $taskId,
                        'user_id' => $userId,
                        'added_by_id' => Auth::user()->id,
                        'user_program_id' => $userProgramId,
                        'attachment' => $explodeBySlash[2],
                        'is_assign' => true
                    ];

                    $dump_program_task = DumpProgramTask::where('id', $dumpId)->first();
                    $dump_program_task->update($dumpPayloadUpdate);
                } else {
                    $msg = 'Provided data not exist in our database. Kindly request you to perform assign task to user manually.';
                }
            }
        } else {
            $dump_program_task = '';
            $msg = 'Provided program is not assign to a provided candidate. Kindly complete the process of assign program to a candidate first.';
        }

        return response()->json([
            'data' => $dump_program_task,
            'message' =>  $msg
        ], 201);
    }
}
