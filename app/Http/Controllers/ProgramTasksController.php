<?php

namespace App\Http\Controllers;

use App\Program;
use App\ProgramTask;
use Illuminate\Http\Request;

class ProgramTasksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }

    public function index(Program $program)
    {
        $count = 0;
        $program_tasks = $program->program_tasks;
        $count = $program_tasks->count();

        return response()->json([
            'data'     =>  $program_tasks,
            'count'    =>   $count
        ], 200);
    }

    /*
     * To store a new programTask
     *
     *@
     */
    public function store(Request $request, Program $program)
    {
        $request->validate([
            'program_id'        =>  'required',
        ]);

        $programTask = new ProgramTask(request()->all());
        $program->program_tasks()->save($programTask);

        return response()->json([
            'data'    =>  $programTask
        ], 201);
    }

    /*
     * To view a single programTask
     *
     *@
     */
    public function show(Program $program, ProgramTask $programTask)
    {
        $programTask->program = $program;
        return response()->json([
            'data'   =>  $programTask,
            'success' =>  true
        ], 200);
    }

    /*
     * To update a programTask
     *
     *@
     */
    public function update(Request $request, Program $program,  ProgramTask $programTask)
    {
        $programTask->update($request->all());

        return response()->json([
            'data'  =>  $programTask
        ], 200);
    }

    public function destroy($id)
    {
        $programTask = ProgramTask::find($id);
        $programTask->delete();

        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }
}
