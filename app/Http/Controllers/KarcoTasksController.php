<?php

namespace App\Http\Controllers;

use App\KarcoTask;
use App\Site;
use Illuminate\Http\Request;

class KarcoTasksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }

    public function index(Request $request)
    {
        // return $request->month. ' ' . $request->year;
        $count = 0;
        $karco_tasks = request()->site->karco_tasks();
        if ($request->month) {
            $karco_tasks = $karco_tasks->whereMonth('done_on', '=', $request->month);
            // return $karco_tasks;
        }
        if ($request->year) {
            $karco_tasks = $karco_tasks->whereYear('done_on', '=', $request->year);
        }
        $karco_tasks = $karco_tasks->get();
        $count = $karco_tasks->count();
        return response()->json([
            'data'     =>  $karco_tasks,
            'count'    =>   $count
        ], 200);
    }

    /*
     * To store a new karco_task
     *
     *@
     */
    public function store(Request $request)
    {
        $request->validate([
            'department'        =>  'required',
        ]);

        $karco_task = new KarcoTask($request->all());
        $request->site->karco_tasks()->save($karco_task);

        return response()->json([
            'data'    =>  $karco_task
        ], 201);
    }

    /*
     * To view a single karco_task
     *
     *@
     */
    public function show(KarcoTask $karco_task)
    {
        return response()->json([
            'data'   =>  $karco_task,
            'success' =>  true
        ], 200);
    }

    /*
     * To update a karco_task
     *
     *@
     */
    public function update(Request $request, KarcoTask $karco_task)
    {
        $karco_task->update($request->all());

        return response()->json([
            'data'  =>  $karco_task
        ], 200);
    }

    public function destroy($id)
    {
        $karco_task = KarcoTask::find($id);
        // $karco_task->delete();
        $karco_task->is_deleted = true;
        $karco_task->update();

        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }
}
