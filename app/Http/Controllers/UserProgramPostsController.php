<?php

namespace App\Http\Controllers;

use App\UserProgramPost;
use Illuminate\Http\Request;

class UserProgramPostsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }

    public function masters(Request $request)
    {
        $UsersController = new UsersController();
        $UsersResponse = $UsersController->index($request);

        $ProgramsController = new ProgramsController();
        $ProgramsResponse = $ProgramsController->index($request);

        // $ProgramPostsController = new ProgramPostsController();
        // $ProgramPostsResponse = $ProgramPostsController->index($request);

        return response()->json([
            'users'  =>  $UsersResponse->getData()->data,
            'programs'      =>  $ProgramsResponse->getData()->data,
            'program_posts'      =>  $ProgramsResponse->getData()->data,
        ], 200);
    }
    public function index(Request $request)
    {
        $count = 0;
        if ($request->search) {
            $user_program_posts = request()->site->user_program_posts()
                ->where('user_id', 'LIKE', '%' . $request->search . '%')
                ->get();
            $count = $user_program_posts->count();
        } else if (request()->page && request()->rowsPerPage) {
            $user_program_posts = request()->site->user_program_posts();
            $count = $user_program_posts->count();
            $user_program_posts = $user_program_posts->paginate(request()->rowsPerPage)->toArray();
            $user_program_posts = $user_program_posts['data'];
        } else {
            $user_program_posts = request()->site->user_program_posts;
            $count = $user_program_posts->count();
        }

        return response()->json([
            'data'     =>  $user_program_posts,
            'count'    =>   $count
        ], 200);
    }

    /*
     * To store a new userProgramPost
     *
     *@
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'        =>  'required',
        ]);

        $userProgramPost = new UserProgramPost(request()->all());
        $request->site->user_program_posts()->save($userProgramPost);

        return response()->json([
            'data'    =>  $userProgramPost
        ], 201);
    }

    /*
     * To view a single userProgramPost
     *
     *@
     */
    public function show(UserProgramPost $userProgramPost)
    {
        $userProgramPost->user = $userProgramPost->user;
        $userProgramPost->program = $userProgramPost->program;
        $userProgramPost->program_post = $userProgramPost->program_post;
        return response()->json([
            'data'   =>  $userProgramPost,
            'success' =>  true
        ], 200);
    }

    /*
     * To update a userProgramPost
     *
     *@
     */
    public function update(Request $request, UserProgramPost $userProgramPost)
    {
        $userProgramPost->update($request->all());

        return response()->json([
            'data'  =>  $userProgramPost
        ], 200);
    }

    public function destroy($id)
    {
        $userProgramPost = UserProgramPost::find($id);
        $userProgramPost->delete();

        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }
}
