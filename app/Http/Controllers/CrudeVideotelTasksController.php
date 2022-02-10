<?php

namespace App\Http\Controllers;

use App\CrudeVideotelTask;
use App\Imports\VideotelTaskImport;
use App\User;
use App\Value;
use App\ValueList;
use App\VideotelTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CrudeVideotelTasksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'site'])
            ->except(['index']);
    }

    public function index()
    {
        return response()->json([
            'data'  =>  CrudeVideotelTask::all()
        ]);
    }

    public function uploadVideotelTask(Request $request)
    {
        set_time_limit(0);
        if ($request->hasFile('videoteltasksData')) {
            $file = $request->file('videoteltasksData');

            Excel::import(new VideotelTaskImport, $file);
            return response()->json([
                'data'    =>  CrudeVideotelTask::all(),
                'success' =>  true
            ]);
        }
    }



    public function processVideotelTask()
    {
        set_time_limit(0);
        $crude_videotel_Tasks = CrudeVideotelTask::all();
        $i = 0;
        foreach ($crude_videotel_Tasks as $user) {

            // Ship Value
            $ship_name = $user->location;
            $ValueRank = Value::where('name', '=', 'SHIP')->first();
            if ($ValueRank == '' || $ValueRank == null) {
                $ValueRank = Value::create([
                    'site_id' => '1',
                    'name'   =>  'SHIP',
                ]);
            }
            $value_id = $ValueRank['id'];
            $ship = ValueList::where('description', '=', $ship_name)
                ->orWhere('code', '=', $ship_name)
                ->first();
            if ($ship == '' || $ship == null) {
                $ship = ValueList::create([
                    'site_id' => '1',
                    'value_id' => $value_id,
                    'description'   =>  $ship_name,
                    'code'   =>  $ship_name,
                ]);
            }
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
            $user_data = User::where('unique_id', '=', $user->crew_id)
                ->first();
            $data = [
                // user column name = $user->crude_videotel_Tasks column name
                // 'nationality' => $user->nationality,
                'rank_id'      => $rank->id,
                'first_name'     => $user->first_name,
                'user_name'     => $user->first_name,
                // 'middle_name'        => $user->middle_name,
                'last_name'       => $user->last_name,
                'unique_id'        => $user->crew_id,
                // 'dob'        => $user->dob,
                'active'          =>  1,
                'password' => bcrypt('123456'),
                'password_backup' => bcrypt('123456'),
            ];
            if ($user_data) {
                // Udpate Existing User

                // $user_id = $user_data['id'];

                // $user_data = User::find($user_id);
                // $user_data->update($data);
            } else {
                // Insert New User 
                $user_data = new User($data);
                request()->site->users()->save($user_data);
                $user_data->assignRole(4);
                $user_data->assignSite(1);
            }
            $user_id = $user_data['id'];

            if ($user_id) {
                $videotel_tasks = [
                    'user_id'     => $user_id,
                    'ship_id'     => $ship->id,
                    'training_title' => $user->training_title,
                    'module' =>        $user->module,
                    'type' =>        $user->type,
                    'date' =>       Carbon::createFromFormat('d/m/Y', $user->date)->format('Y-m-d'),
                    'duration' =>        $user->duration,
                    'score' =>        $user->score,
                ];
                $videotel_task_data = new VideotelTask($videotel_tasks);
                request()->site->videotel_tasks()->save($videotel_task_data);
            }
            $i++;
        }
    }

    public function truncate()
    {
        CrudeVideotelTask::truncate();
    }
}
