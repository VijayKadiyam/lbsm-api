<?php

namespace App\Http\Controllers;

use App\Program;
use App\UserProgram;
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
        $count_program_tasks = 0;
        $user_program_tasks = $userProgram->user_program_tasks;
        $count = $user_program_tasks->count();
        $program_tasks = $user_program_tasks[0]->program->program_tasks->count();
        $count_program_tasks = $program_tasks->count();
        // $count_program_tasks = $program_tasks->groupBy('program_post_id')->count();
        // return $count_program_tasks;
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

        return response()->json([
            'data'     =>  $user_program_tasks,
            'total_completed_task'     =>  $total_completed_task,
            'total_pending_task'     =>  $total_pending_task,
            'average_score'     =>  $average_score,
            'total_marks_obtained'     =>  $total_marks_obtained,
            'total_pending_program_tasks'     =>  $total_pending_program_tasks,
            'count'    =>   $count
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
        $userProgramTask->userProgram = $userProgram;
        $userProgramTask->user = $userProgramTask->user;
        $userProgramTask->program = $userProgramTask->program;
        $userProgramTask->program_task = $userProgramTask->program_task;
        $userProgramTask->ship = $userProgramTask->ship;
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
}
