<?php

namespace App\Http\Controllers;

use App\Program;
use App\ProgramTask;
use App\UserProgram;
use App\UserProgramPost;
use App\UserProgramTask;
use App\Value;
use Illuminate\Http\Request;

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
        // return $count_program_posts;

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
        foreach ($user_program_tasks as $key => $value) {
            // return $value;
            // $count_is_completed = $value->is_completed;
            if ($value->is_completed == 1) {
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
            // $average_score = $total_marks_obtained / $total_completed_task;
            $Final_average_score = $average_score;

            $final_total_pending_program_tasks = $count_program_tasks - $final_total_completed_task;

            // $user_program_tasks[] = $user_program_tasks;
        }

        $total_completed_task = $final_total_completed_task;
        $total_pending_task = $final_total_pending_task;
        $average_score = $Final_average_score;
        $total_marks_obtained = $final_total_marks_obtained;
        $total_pending_program_tasks = $final_total_pending_program_tasks;
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
        ], 200);
    }

    /*
     * To store a new userprogramTask
     *
     *@
     */
    public function store(Request $request, UserProgram $userProgram)
    {
        $request->validate([
            'program_id'        =>  'required',
        ]);

        $userprogramTask = new UserProgramTask(request()->all());
        $userProgram->user_program_tasks()->save($userprogramTask);

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
}
