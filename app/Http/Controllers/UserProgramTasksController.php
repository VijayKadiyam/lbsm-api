<?php

namespace App\Http\Controllers;

use App\Program;
use App\UserProgram;
use App\UserProgramTask;
use Illuminate\Http\Request;

class UserProgramTasksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }

    public function index(UserProgram $userProgram)
    {
        $count = 0;
        $user_program_tasks = $userProgram->user_program_tasks;
        // return $userProgram;
        // $count = $user_program_tasks->count();

        return response()->json([
            'data'     =>  $user_program_tasks,
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
