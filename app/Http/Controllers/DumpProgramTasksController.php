<?php

namespace App\Http\Controllers;

use App\DumpProgramTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Webklex\IMAP\Facades\Client;

class DumpProgramTasksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
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

        //Loop through every Mailbox
        /** @var \Webklex\PHPIMAP\Folder $folder */
        foreach ($folders as $folder) {

            //Get all Messages of the current Mailbox $folder
            /** @var \Webklex\PHPIMAP\Support\MessageCollection $messages */
            $messages = $folder->messages()->all()->get();
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



                // return $dump_program_task->toArray();
                // echo $message->getSubject() . '<br />';
                // echo 'Attachments: ' . $message->getAttachments()->count() . '<br />';
                // echo $message->getHTMLBody();
                // echo "<br>";
                // echo "<br>";
                // echo "------------------------------------------------------------------------------------------";
                // echo "<br>";
                // echo "<br>";
                //Move the current Message to 'INBOX.read'
                // if ($message->move('INBOX.read') == true) {
                //     echo 'Message has ben moved';
                // } else {
                //     echo 'Message could not be moved';
                // }
            }
        }
    }
}
