<?php

namespace App\Http\Controllers;

use App\UserITest;
use Illuminate\Http\Request;

class UserITestsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }

    public function index(Request $request)
    {
        $count = 0;
        if ($request->user_id) {
            $user_i_tests = request()->site->user_i_tests()
                ->where('user_id', '=', $request->user_id)
                ->get();
        } else {

            $user_i_tests = request()->site->user_i_tests;
            $count = $user_i_tests->count();
        }

        return response()->json([
            'data'     =>  $user_i_tests,
            'count'    =>   $count
        ], 200);
    }

    /*
     * To store a new user_i_test
     *
     *@
     */
    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'user_id'        =>  'required',
        ]);

        $user_i_test = new UserITest(request()->all());
        $request->site->user_i_tests()->save($user_i_test);

        return response()->json([
            'data'    =>  $user_i_test
        ], 201);
    }

    /*
     * To view a single user_i_test
     *
     *@
     */
    public function show(UserITest $user_i_test)
    {
        return response()->json([
            'data'   =>  $user_i_test,
            'success' =>  true
        ], 200);
    }

    /*
     * To update a user_i_test
     *
     *@
     */
    public function update(Request $request, UserITest $user_i_test)
    {
        $user_i_test->update($request->all());

        return response()->json([
            'data'  =>  $user_i_test
        ], 200);
    }

    public function destroy($id)
    {
        $user_i_test = UserITest::find($id);
        $user_i_test->delete();

        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }
}
