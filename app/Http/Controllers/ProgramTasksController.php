<?php

namespace App\Http\Controllers;

use App\ProgramTask;
use Illuminate\Http\Request;

class ProgramTasksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }

    public function index(Request $request)
    {
        $count = 0;
        if ($request->search) {
            $program_tasks = request()->site->program_tasks()
                ->where('serial_no', 'LIKE', '%' . $request->search . '%')
                ->get();
            $count = $program_tasks->count();
        } else if (request()->page && request()->rowsPerPage) {
            $program_tasks = request()->site->program_tasks();
            $count = $program_tasks->count();
            $program_tasks = $program_tasks->paginate(request()->rowsPerPage)->toArray();
            $program_tasks = $program_tasks['data'];
        } else {
            $program_tasks = request()->site->program_tasks;
            $count = $program_tasks->count();
        }

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
    public function store(Request $request)
    {
        $request->validate([
            'program_id'        =>  'required',
        ]);

        $programTask = new ProgramTask(request()->all());
        $request->site->program_tasks()->save($programTask);

        return response()->json([
            'data'    =>  $programTask
        ], 201);
    }

    /*
     * To view a single programTask
     *
     *@
     */
    public function show(ProgramTask $programTask)
    {
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
    public function update(Request $request, ProgramTask $programTask)
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
