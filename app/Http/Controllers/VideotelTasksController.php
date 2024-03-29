<?php

namespace App\Http\Controllers;

use App\VideotelTask;
use Illuminate\Http\Request;

class VideotelTasksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }

    public function index(Request $request)
    {
        $count = 0;
        $videotel_tasks = request()->site->videotel_tasks();
        if ($request->month) {
            $videotel_tasks = $videotel_tasks->whereMonth('date', '=', $request->month);
        }
        if ($request->year) {
            $videotel_tasks = $videotel_tasks->whereYear('date', '=', $request->year);
        }
        $videotel_tasks = $videotel_tasks->get();
        $count = $videotel_tasks->count();
        return response()->json([
            'data'     =>  $videotel_tasks,
            'count'    =>   $count
        ], 200);
    }

    /*
     * To store a new videotel_task
     *
     *@
     */
    public function store(Request $request)
    {
        $request->validate([
            'training_title'        =>  'required',
        ]);

        $videotel_task = new VideotelTask($request->all());
        $request->site->videotel_tasks()->save($videotel_task);

        return response()->json([
            'data'    =>  $videotel_task
        ], 201);
    }

    /*
     * To view a single videotel_task
     *
     *@
     */
    public function show(VideotelTask $videotel_task)
    {
        return response()->json([
            'data'   =>  $videotel_task,
            'success' =>  true
        ], 200);
    }

    /*
     * To update a videotel_task
     *
     *@
     */
    public function update(Request $request, VideotelTask $videotel_task)
    {
        $videotel_task->update($request->all());

        return response()->json([
            'data'  =>  $videotel_task
        ], 200);
    }

    public function destroy($id)
    {
        $karco_task = VideotelTask::find($id);
        $karco_task->is_deleted = true;
        $karco_task->update();

        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }
}
