<?php

namespace App\Http\Controllers;

use App\Program;
use App\ProgramTask;
use App\UserProgram;
use App\UserProgramPost;
use App\UserProgramTask;
use App\Value;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProgramTasksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }

    public function masters(Request $request)
    {
        $shipValue = Value::where('name', '=', 'SHIPS')
            ->where('site_id', '=', $request->site->id)
            ->first();
        $ships = [];
        if ($shipValue)
            $ships = $shipValue->active_value_lists;

        return response()->json([
            'ships' => $ships,
        ], 200);
    }

    public function index(UserProgram $userProgram)
    {
        // return 1;
        $count = 0;
        $user_id = request()->user_id;
        $count_program_tasks = 0;
        // $user_program_tasks = [];
        $user_program_tasks = $userProgram->user_program_tasks;
        $count = $user_program_tasks->count();

        // $Current_user_program_post = UserProgramPost::where('user_id', '=', $user_id)->latest()->first();
        // $program_post_id = $Current_user_program_post->program_post_id;
        // $count_program_tasks = ProgramTask::where('program_post_id', '=', $program_post_id)->get()->count();


        $Current_user_program_post = request()->site->user_program_posts()->with('program_post')->where('user_id', '=', $user_id)->latest()->get();

        $count_program_tasks = sizeof($Current_user_program_post) ? sizeof($Current_user_program_post[0]['program_post']['program_tasks']) : 0;
        $user_ships = $user_ships = request()->site->user_ships()
            ->where('user_id', '=', $user_id)->get();

        $total_completed_task = 0;
        $total_pending_task = 0;
        $total_pending_program_tasks = 0;
        $total_marks_obtained = 0;
        $average_score = 0;
        $final_total_completed_task = 0;
        $final_total_pending_task = 0;
        $Final_average_score = 0;
        $final_total_marks_obtained = 0;
        $final_total_pending_program_tasks = 0;
        // return $user_program_tasks;
        $completed_ppt = [];
        foreach ($user_program_tasks as $key => $value) {
            // return $value;e
            if ($value->active == true) {
                if ($value->is_completed == 1) {
                    $completed_ppt[] = $value->program_task_id;
                    $total_completed_task += $value->is_completed;
                    $final_total_completed_task = $total_completed_task;
                } else {
                    $pending_task = 1;
                    $total_pending_task += $pending_task;
                    $final_total_pending_task = $total_pending_task;
                }
                $total_marks_obtained += $value->marks_obtained;
                $final_total_marks_obtained = $total_marks_obtained;
                $average_score = $total_marks_obtained / $count;
                $Final_average_score = $average_score;
                $final_total_pending_program_tasks = $count_program_tasks - $final_total_completed_task;
            }
        }
        $All_program_tasks = $Current_user_program_post[0]['program_post']['program_tasks'];
        $pending_program_task = [];
        // return $completed_ppt;
        foreach ($All_program_tasks as $key => $pt) {
            $task_id = $pt->id;
            if (!in_array($task_id, $completed_ppt)) {
                $pending_program_task[] = $pt;
            }
        }
        $total_completed_task = $final_total_completed_task;
        $total_pending_task = $final_total_pending_task;
        $average_score = $Final_average_score;
        $total_marks_obtained = $final_total_marks_obtained;
        // $total_pending_program_tasks = $final_total_pending_program_tasks;
        $total_pending_program_tasks = sizeof($pending_program_task);
        // return $user_program_tasks;
        // $count = $user_program_tasks->count();
        $arrays = $user_program_tasks->toArray();
        usort($arrays, function ($a, $b) {
            return $a['program_task_id'] - $b['program_task_id'];
        });
        return response()->json([
            'data'     =>  $arrays,
            'total_completed_task'     =>  $total_completed_task,
            'total_pending_task'     =>  $total_pending_task,
            'average_score'     =>  $average_score,
            'total_marks_obtained'     =>  $total_marks_obtained,
            'total_pending_program_tasks'     =>  $total_pending_program_tasks,
            'count'    =>   $count,
            'user_ships' => $user_ships,
            'pending_program_task' => $pending_program_task,
        ], 200);
    }

    /*
     * To store a new userprogramTask
     *
     *@
     */
    public function store(Request $request, UserProgram $userProgram)
    {
        // return request()->all();
        $request->validate([
            'program_id'        =>  'required',
        ]);

        $userprogramTask = new UserProgramTask(request()->all());
        $userProgram->user_program_tasks()->save($userprogramTask);
        $user_program_task_id = $userprogramTask->id;

        if (request()->attachment) {
            $file_name = request()->attachment;
            $test = explode('/', $file_name);
            $name =  end($test);
            $new_path = 'lbsm/user-program-task/' .  $user_program_task_id . '/' . $name;
            Storage::move(request()->attachment, $new_path);

            $userprogramTask->update(['imagepath1' => $new_path]);
        }

        return response()->json([
            'data'    =>  $userprogramTask
        ], 201);
    }


    public function show(UserProgram $userProgram, UserProgramTask $userProgramTask)
    {
        // $userProgramTask->userProgram = $userProgram;
        // $userProgramTask->user = $userProgramTask->user;
        // $userProgramTask->program = $userProgramTask->program;
        // $userProgramTask->program_task = $userProgramTask->program_task;
        // $userProgramTask->ship = $userProgramTask->ship;
        return response()->json([
            'data'   =>  $userProgramTask,
            'success' =>  true
        ], 200);
    }

    /*
     * To update a userProgramTask$userProgramTaskDocument
     *
     *@
     */
    public function update(Request $request, UserProgram $userProgram, UserProgramTask $userProgramTask)
    {
        // return 1;
        // return $userProgramTask;
        $userProgramTask->update($request->all());

        return response()->json([
            'data'  =>  $userProgramTask
        ], 200);
    }

    public function destroy($id)
    {
        $userprogramTask = UserProgramTask::find($id);
        $userprogramTask->delete();

        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }

    public function DeleteImage()
    {
        $field = request()->imageField;
        $UserProgramTask = UserProgramTask::where('id', '=', request()->user_program_task_id)
            ->where($field, '=', request()->imageName)->first();
        $UserProgramTask->$field = null;
        $UserProgramTask->update();
        // return $UserProgramTask;
        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }
    public function userProgramUsers()
    {
        if (request()->search) {
            ini_set('max_execution_time', 0);
            ini_set("memory_limit", "-1");
            set_time_limit(0);
            $user_program_tasks_users = request()->site->user_program_tasks()
                ->where('ship_id', '=', request()->search)
                ->get();
            $count = $user_program_tasks_users->count();
            // return $user_program_tasks_users;
        }
        // return $UserProgramTask;
        return response()->json([
            'data'   =>  $user_program_tasks_users,
            'success' =>  true
        ], 200);
    }
}
