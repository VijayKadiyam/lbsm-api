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
        $user_program_posts = request()->site->user_program_posts();
        if ($request->search) {
            $user_program_posts = $user_program_posts->where('user_id', 'LIKE', '%' . $request->search . '%');
        }
        if ($request->user_id) {
            $user_program_posts = $user_program_posts->where('user_id',  $request->user_id);
        }
        if ($request->program_id) {
            $user_program_posts = $user_program_posts->where('program_id',  $request->program_id);
        }
        if (request()->page && request()->rowsPerPage) {
            $user_program_posts = request()->site->user_program_posts();
            $count = $user_program_posts->count();
            $user_program_posts = $user_program_posts->paginate(request()->rowsPerPage)->toArray();
            $user_program_posts = $user_program_posts['data'];
        } else {
            $user_program_posts = $user_program_posts->get();
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
        // return 1;
        $request->validate([
            'user_id'        =>  'required',
        ]);
        // return $request->all();
        $msg = '';
        $userProgramPost = '';
        $UserprogramPosts = request()->site->user_program_posts()
            ->where('user_id', '=', $request->user_id)
            ->where('program_id', '=', $request->program_id)
            ->where('program_post_id', '=', $request->program_post_id)
            ->get()->count();
        // return $UserprogramPosts;
        if ($UserprogramPosts == 0) {
            $userProgramPost = new UserProgramPost(request()->all());
            $request->site->user_program_posts()->save($userProgramPost);
            $all_previous_post = request()->site->user_program_posts()
                ->where('user_id', '=', $request->user_id)
                ->where('program_id', '=', $request->program_id)
                ->whereNot('program_post_id', '=', $request->program_post_id)
                ->get()->each(function ($log) {
                    $log->update([
                        'status' => 2,
                    ]);
                });
        } else {
            $msg = "User Program Post Already Added.";
        }

        return response()->json([
            'data'    =>  $userProgramPost,
            'msg'    =>  $msg,
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
