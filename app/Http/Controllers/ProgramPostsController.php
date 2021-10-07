<?php

namespace App\Http\Controllers;

use App\ProgramPost;
use Illuminate\Http\Request;

class ProgramPostsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }

    public function index(Request $request)
    {
        $count = 0;
        if ($request->search) {
            $program_posts = request()->site->program_posts()
                ->where('serial_no', 'LIKE', '%' . $request->search . '%')
                ->get();
            $count = $program_posts->count();
        } else if (request()->page && request()->rowsPerPage) {
            $program_posts = request()->site->program_posts();
            $count = $program_posts->count();
            $program_posts = $program_posts->paginate(request()->rowsPerPage)->toArray();
            $program_posts = $program_posts['data'];
        } else {
            $program_posts = request()->site->program_posts;
            $count = $program_posts->count();
        }

        return response()->json([
            'data'     =>  $program_posts,
            'count'    =>   $count
        ], 200);
    }

    /*
     * To store a new programPost
     *
     *@
     */
    public function store(Request $request)
    {
        $request->validate([
            'program_id'        =>  'required',
        ]);

        $programPost = new ProgramPost(request()->all());
        $request->site->program_posts()->save($programPost);

        return response()->json([
            'data'    =>  $programPost
        ], 201);
    }

    /*
     * To view a single programPost
     *
     *@
     */
    public function show(ProgramPost $programPost)
    {
        return response()->json([
            'data'   =>  $programPost,
            'success' =>  true
        ], 200);
    }

    /*
     * To update a programPost
     *
     *@
     */
    public function update(Request $request, ProgramPost $programPost)
    {
        $programPost->update($request->all());

        return response()->json([
            'data'  =>  $programPost
        ], 200);
    }

    public function destroy($id)
    {
        $programPost = ProgramPost::find($id);
        $programPost->delete();

        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }
}
