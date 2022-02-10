<?php

namespace App\Http\Controllers;

use App\CrudeKarcoTask;
use App\Imports\KarcoTaskImport;
use App\KarcoTask;
use App\User;
use App\Value;
use App\ValueList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CrudeKarcoTasksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'site'])
            ->except(['index']);
    }

    public function index()
    {
        return response()->json([
            'data'  =>  CrudeKarcoTask::all()
        ]);
    }

    public function uploadKarcoTask(Request $request)
    {
        set_time_limit(0);
        if ($request->hasFile('karcotasksData')) {
            $file = $request->file('karcotasksData');

            Excel::import(new KarcoTaskImport, $file);
            return response()->json([
                'data'    =>  CrudeKarcoTask::all(),
                'success' =>  true
            ]);
        }
    }



    public function processKarcoTask()
    {
        set_time_limit(0);
        $crude_karco_Tasks = CrudeKarcoTask::all();
        $i = 0;
        foreach ($crude_karco_Tasks as $user) {

            // Ship Value
            $ship_name = $user->vessel_name;
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
            $user_data = User::where('unique_id', '=', $user->employee_id)
                ->first();
            $data = [
                // user column name = $user->crude_karco_Tasks column name
                'nationality' => $user->nationality,
                'rank_id'      => $rank->id,
                'first_name'     => $user->crew_name,
                'user_name'     => $user->crew_name,
                // 'middle_name'        => $user->middle_name,
                // 'last_name'       => $user->last_name,
                'unique_id'        => $user->employee_id,
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
                $karco_tasks = [
                    'user_id'     => $user_id,
                    'ship_id'     => $ship->id,
                    'department' => $user->department,
                    'status' =>        $user->status,
                    'signed_on' =>        Carbon::parse($user->signed_on)->format('Y-m-d'),
                    'video_title' =>        $user->video_title,
                    'no_of_preview_watched' =>        $user->no_of_preview_watched,
                    'no_of_video_watched' =>        $user->no_of_video_watched,
                    'obtained_marks' =>        $user->obtained_marks,
                    'total_marks' =>        $user->total_marks,
                    'percentage' =>        $user->percentage,
                    'done_on' =>        Carbon::parse($user->done_on)->format('Y-m-d'),
                    'due_days' =>        $user->due_days,
                    'assessment_status' =>        $user->assessment_status,
                ];
                $karco_task_data = new KarcoTask($karco_tasks);
                request()->site->karco_tasks()->save($karco_task_data);
            }
            $i++;
        }
    }

    public function truncate()
    {
        CrudeKarcoTask::truncate();
    }
}
