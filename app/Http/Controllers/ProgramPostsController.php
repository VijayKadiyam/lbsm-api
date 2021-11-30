<?php

namespace App\Http\Controllers;

use App\Program;
use App\ProgramPost;
use App\Value;
use App\ValueList;
use Illuminate\Http\Request;

class ProgramPostsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }

    public function masters(Request $request)
    {
        $programsController = new ProgramsController();
        $programsResponse = $programsController->index($request);

        $post = Value::where('name', '=', 'POST')->first();

        $posts = [];
        if($post)
            $posts = ValueList::where('value_id', '=', $post->id)
                ->get();

        return response()->json([
            'programs'  =>  $programsResponse->getData()->data,
            'posts' => $posts,
        ], 200);
    }

    public function index(Request $request, Program $program)
    {
        $count = 0;
        $programPosts = $program->program_posts();
        $programPosts = $request->showOnlyActive ? $programPosts->where('is_active', '=', 1) : $programPosts;
        if (request()->page && request()->rowsPerPage) {
            $count = $programPosts->count();
            $programPosts = $programPosts->paginate(request()->rowsPerPage)->toArray();
            $programPosts = $programPosts['data'];
        } else {
            $programPosts = $programPosts->get();
            $count = $programPosts->count();
        }

        return response()->json([
            'data'     =>   $programPosts,
            'count'    =>   $count
        ], 200);
    }

    public function store(Request $request, Program $program)
    {
        $request->validate([
            'program_id'    =>  'required',
            'site_id'     =>  'required',
        ]);

        $programPost = new ProgramPost(request()->all());
        $program->program_posts()->save($programPost);

        return response()->json([
            'data'    =>  $programPost
        ], 201);
    }

    public function storeMultiple(Request $request, Program $program)
    {
        $request->validate([
            'datas.*.program_id'    =>  'required',
            'datas.*.site_id'     =>  'required',
        ]);

        $programPosts = [];
        foreach ($request->datas as $programPost) {
            if (!isset($programPost['id'])) {
                $programPost = new ProgramPost($programPost);
                $program->program_posts()->save($programPost);
                $programPosts[] = $programPost;
            } else {
                $pp = ProgramPost::find($programPost['id']);
                $pp->update($programPost);
                $programPosts[] = $pp;
            }
        }

        return response()->json([
            'data'    =>  $programPosts
        ], 201);
    }

    public function show(Program $program, ProgramPost $programPost)
    {
        return response()->json([
            'data'   =>  $programPost,
            'success' =>  true
        ], 200);
    }

    public function update(Request $request, Program $program, ProgramPost $programPost)
    {
        $programPost->update($request->all());

        return response()->json([
            'data'  =>  $programPost
        ], 200);
    }

    public function destroy(Program $program, $id)
    {
        $programPost = ProgramPost::find($id);
        $programPost->delete();

        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }
}
