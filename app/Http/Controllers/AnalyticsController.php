<?php

namespace App\Http\Controllers;

use App\Program;
use App\User;
use App\UserProgramTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\ErrorHandler\Error\UndefinedFunctionError;

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

    public function total_tasks_performed()
    {
        $year = request()->year;
        $months = [];
        $jan_count = 0;
        $feb_count = 0;
        $mar_count = 0;
        $apr_count = 0;
        $may_count = 0;
        $jun_count = 0;
        $july_count = 0;
        $aug_count = 0;
        $sept_count = 0;
        $oct_count = 0;
        $nov_count = 0;
        $dec_count = 0;
        $total_task = UserProgramTask::whereYear('completion_date', '=', $year)->where('is_completed', '=', true)->get();
        foreach ($total_task as $key => $task) {
            $task_month = Carbon::parse($task['completion_date'])->month;
            switch ($task_month) {
                case '1':
                    $jan_count++;
                    break;
                case '2':
                    $feb_count++;
                    break;
                case '3':
                    $mar_count++;
                    break;
                case '4':
                    $apr_count++;
                    break;
                case '5':
                    $may_count++;
                    break;
                case '6':
                    $jun_count++;
                    break;
                case '7':
                    $july_count++;
                    break;
                case '8':
                    $aug_count++;
                    break;
                case '9':
                    $sept_count++;
                    break;
                case '10':
                    $oct_count++;
                    break;
                case '11':
                    $nov_count++;
                    break;
                case '12':
                    $dec_count++;
                    break;

                default:
                    # code...
                    break;
            }
        }

        $total_tasks_performed = [
            "January " . $year => $jan_count,
            "February " . $year => $feb_count,
            "March " . $year => $mar_count,
            "April " . $year => $apr_count,
            "May " . $year => $may_count,
            "June " . $year => $jun_count,
            "July " . $year => $july_count,
            "August " . $year => $aug_count,
            "September " . $year => $sept_count,
            "October " . $year => $oct_count,
            "November " . $year => $nov_count,
            "December " . $year => $dec_count,
        ];
        return response()->json([
            'data'     =>  $total_tasks_performed,
        ], 200);
    }

    public function top_performer()
    {
        $year = request()->year;
    }
}
