<?php

namespace App\Http\Controllers;

use App\Program;
use App\ProgramTask;
use App\UserProgramPost;
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


    public function filter(Request $request)
    {
        $user_id = $request->user_id;
        // $Current_user_program_post=UserProgramPost::where('user_id','=',$user_id)->latest()->first();
        // $program_post_id= $Current_user_program_post->program_post_id;
        // $ProgramTasks=ProgramTask::where('program_post_id','=',$program_post_id)->get();
        $user_program_posts = UserProgramPost::where('user_id', '=', $user_id)->get();
        // return $user_program_posts;
        $ProgramTasks =[];
        foreach ($user_program_posts as $key => $user_program_post) {
            $program_post_id = $user_program_post->program_post_id;
            // return $program_post_id ;
            $abc = ProgramTask::where('program_post_id', '=', $program_post_id)->get();
            $ProgramTasks[] = $abc;
        }    
        
        // return $ProgramTasks;
        return response()->json([
            'data'   =>  $ProgramTasks,
            'success' =>  true
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
        $programTask->program_post = $programTask->program_post;
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
