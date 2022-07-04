<?php

namespace App\Http\Controllers;

use App\UserProgram;
use Illuminate\Http\Request;

class UserProgramsController extends Controller
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

        return response()->json([
            'users'  =>  $UsersResponse->getData()->data,
            'programs'      =>  $ProgramsResponse->getData()->data,
        ], 200);
    }
    public function index(Request $request)
    {
        $count = 0;
        if ($request->search) {
            $user_programs = request()->site->user_programs()
                ->where('user_id', '=', $request->search)
                ->get();
            $count = $user_programs->count();
        } else if (request()->page && request()->rowsPerPage) {
            $user_programs = request()->site->user_programs();
            $count = $user_programs->count();
            $user_programs = $user_programs->paginate(request()->rowsPerPage)->toArray();
            $user_programs = $user_programs['data'];
        } else if ($request->user_id) {
            // return $request->user_id;
            $user_programs = request()->site->user_programs()
                ->where('user_id', '=', $request->user_id)
                ->get();
            $count = $user_programs->count();
        } else {
            $user_programs = request()->site->user_programs;
            $count = $user_programs->count();
        }
        // return $user_programs;

        return response()->json([
            'data'     =>  $user_programs,
            'count'    =>   $count
        ], 200);
    }

    /*
     * To store a new userProgram
     *
     *@
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'        =>  'required',
        ]);

        $userProgram = new UserProgram(request()->all());
        $request->site->user_programs()->save($userProgram);

        return response()->json([
            'data'    =>  $userProgram
        ], 201);
    }

    /*
     * To view a single userProgram
     *
     *@
     */
    public function show(UserProgram $userProgram)
    {
        $userProgram->user = $userProgram->user;
        $userProgram->program = $userProgram->program;
        return response()->json([
            'data'   =>  $userProgram,
            'success' =>  true
        ], 200);
    }

    /*
     * To update a userProgram
     *
     *@
     */
    public function update(Request $request, UserProgram $userProgram)
    {
        $userProgram->update($request->all());

        return response()->json([
            'data'  =>  $userProgram
        ], 200);
    }

    public function destroy($id)
    {
        $userProgram = UserProgram::find($id);
        $userProgram->delete();

        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }
}
