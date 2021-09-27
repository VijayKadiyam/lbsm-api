<?php

namespace App\Http\Controllers;

use App\UserProgramTask;
use Illuminate\Http\Request;

class UserProgramTasksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }

    public function index(Request $request)
    {
        $count = 0;
        if ($request->search) {
            $user_program_tasks = request()->site->user_program_tasks()
                ->where('serial_no', 'LIKE', '%' . $request->search . '%')
                ->get();
            $count = $user_program_tasks->count();
        } else if (request()->page && request()->rowsPerPage) {
            $user_program_tasks = request()->site->user_program_tasks();
            $count = $user_program_tasks->count();
            $user_program_tasks = $user_program_tasks->paginate(request()->rowsPerPage)->toArray();
            $user_program_tasks = $user_program_tasks['data'];
        } else {
            $user_program_tasks = request()->site->user_program_tasks;
            $count = $user_program_tasks->count();
        }

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
    public function store(Request $request)
    {
        $request->validate([
            'program_id'        =>  'required',
        ]);

        $userprogramTask = new UserProgramTask(request()->all());
        $request->site->user_program_tasks()->save($userprogramTask);

        return response()->json([
            'data'    =>  $userprogramTask
        ], 201);
    }

    /*
     * To view a single userprogramTask
     *
     *@
     */
    public function show(UserProgramTask $userprogramTask)
    {
        return response()->json([
            'data'   =>  $userprogramTask,
            'success' =>  true
        ], 200);
    }

    /*
     * To update a userprogramTask
     *
     *@
     */
    public function update(Request $request, UserProgramTask $userprogramTask)
    {
        $userprogramTask->update($request->all());

        return response()->json([
            'data'  =>  $userprogramTask
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
