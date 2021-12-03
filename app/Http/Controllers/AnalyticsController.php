<?php

namespace App\Http\Controllers;

use App\Program;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{

    public function userCounts()
    {
        // return request()->year;
        $user_counts = User::whereYear('created_at', '=', request()->year)->groupBy('rank')->select('rank', DB::raw('count(id) as count'))->get();
        $program_counts = Program::get()->count();
        // $user_counts['program_count'] = $program_counts;

        // return $user_counts;
        return response()->json([
            'data'     =>  $user_counts,
            'program_count'     =>  $program_counts,
        ], 200);
        // return $users;
    }
}
