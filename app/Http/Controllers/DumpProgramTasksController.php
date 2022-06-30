<?php

namespace App\Http\Controllers;

use App\DumpProgramTask;
use Illuminate\Http\Request;

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
}
