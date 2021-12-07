<?php

namespace App\Http\Controllers;

use App\CrudeUser;
use App\Imports\UsersImport;
use App\User;
use App\Value;
use App\ValueList;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CrudeUsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'site'])
            ->except(['index']);
    }

    public function index()
    {
        return response()->json([
            'data'  =>  CrudeUser::all()
        ]);
    }

    public function uploadUser(Request $request)
    {
        set_time_limit(0);
        if ($request->hasFile('usersData')) {
            $file = $request->file('usersData');

            Excel::import(new UsersImport, $file);
            return response()->json([
                'data'    =>  CrudeUser::all(),
                'success' =>  true
            ]);
        }
    }



    public function processUser()
    {
        set_time_limit(0);
        $crude_users = CrudeUser::all();
        $i = 0;
        foreach ($crude_users as $user) {
            // Rank Value
            $rank_name = $user->rank;
            $ValueRank = Value::where('name', '=', 'POST')->first();
            if ($ValueRank == '' || $ValueRank == null) {
                $ValueRank = Value::create([
                    'site_id' => '1',
                    'name'   =>  'POST',
                ]);
            }
            $value_id = $ValueRank['id'];
            $rank = ValueList::where('description', '=', $rank_name)
                ->orWhere('code', '=', $rank_name)
                ->first();
            if ($rank == '' || $rank == null) {
                $rank = ValueList::create([
                    'site_id' => '1',
                    'value_id' => $value_id,
                    'description'   =>  $rank_name,
                    'code'   =>  $rank_name,
                ]);
            }
            //  Check Existing user
            $user_data = User::where('unique_id', '=', $user->danaos_id)
                ->first();
            $data = [
                // user column name = $user->crude_users column name
                'nationality' => $user->nationality,
                'rank'      => $user->rank,
                'rank_id'      => $rank->id,
                'first_name'     => $user->first_name,
                'user_name'     => $user->first_name,
                'middle_name'        => $user->middle_name,
                'last_name'       => $user->last_name,
                'unique_id'        => $user->danaos_id,
                'dob'        => $user->dob,
                'active'          =>  1,
                'password' => bcrypt('123456'),
                'password_backup' => bcrypt('123456'),
            ];
            if ($user_data) {
                // Udpate Existing User
                $user_id = $user_data['id'];

                $user_data = User::find($user_id);
                $user_data->update($data);
            } else {
                // Insert New User 
                $user_data = new User($data);
                request()->site->users()->save($user_data);
                $user_data->assignRole(4);
                $user_data->assignSite(1);
            }
            $user_id = $user_data['id'];

            $i++;
        }
    }

    public function truncate()
    {
        CrudeUser::truncate();
    }
}
