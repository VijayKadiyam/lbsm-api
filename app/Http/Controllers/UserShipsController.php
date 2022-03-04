<?php

namespace App\Http\Controllers;

use App\UserShip;
use App\Value;
use Illuminate\Http\Request;

class UserShipsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }

    public function masters(Request $request)
    {
        $UsersController = new UsersController();
        $UsersResponse = $UsersController->index($request);

        $shipValue = Value::where('name', '=', 'SHIPS')
            ->where('site_id', '=', $request->site->id)
            ->first();
        $ships = [];
        if ($shipValue)
            $ships = $shipValue->active_value_lists;

        return response()->json([
            'users'  =>  $UsersResponse->getData()->data,
            'ships' => $ships,
        ], 200);
    }

    public function index(Request $request)
    {
        $count = 0;
        if ($request->user_id) {
            $user_ships = request()->site->user_ships()
                ->where('user_id', '=', $request->user_id)
                ->get();
        }else{

            $user_ships = request()->site->user_ships;
            $count = $user_ships->count();
        }

        return response()->json([
            'data'     =>  $user_ships,
            'count'    =>   $count
        ], 200);
    }

    /*
     * To store a new user_ship
     *
     *@
     */
    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'user_id'        =>  'required',
        ]);

        $user_ship = new UserShip(request()->all());
        $request->site->user_ships()->save($user_ship);

        return response()->json([
            'data'    =>  $user_ship
        ], 201);
    }

    /*
     * To view a single user_ship
     *
     *@
     */
    public function show(UserShip $user_ship)
    {
        return response()->json([
            'data'   =>  $user_ship,
            'success' =>  true
        ], 200);
    }

    /*
     * To update a user_ship
     *
     *@
     */
    public function update(Request $request, UserShip $user_ship)
    {
        $user_ship->update($request->all());

        return response()->json([
            'data'  =>  $user_ship
        ], 200);
    }

    public function destroy($id)
    {
        $user_ship = UserShip::find($id);
        $user_ship->delete();

        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }
}
