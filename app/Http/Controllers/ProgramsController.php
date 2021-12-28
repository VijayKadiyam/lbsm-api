<?php

namespace App\Http\Controllers;

use App\Program;
use Illuminate\Http\Request;

class ProgramsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }

    public function index(Request $request)
    {
        $count = 0;
        $programs = request()->site->programs;
        $count = $programs->count();
        return response()->json([
            'data'     =>  $programs,
            'count'    =>   $count
        ], 200);
    }

    /*
     * To store a new program
     *
     *@
     */
    public function store(Request $request)
    {
        $request->validate([
            'program_name'        =>  'required',
        ]);

        $program = new Program(request()->all());
        $request->site->programs()->save($program);

        return response()->json([
            'data'    =>  $program
        ], 201);
    }

    /*
     * To view a single program
     *
     *@
     */
    public function show(Program $program)
    {
        $program->program_posts = $program->program_posts;
        return response()->json([
            'data'   =>  $program,
            'success' =>  true
        ], 200);
    }

    /*
     * To update a program
     *
     *@
     */
    public function update(Request $request, Program $program)
    {
        $program->update($request->all());

        return response()->json([
            'data'  =>  $program
        ], 200);
    }

    public function destroy($id)
    {
        $program = Program::find($id);
        $program->delete();

        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }
}
