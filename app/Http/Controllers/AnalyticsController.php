<?php

namespace App\Http\Controllers;

use App\Program;
use App\ProgramPost;
use App\User;
use App\UserProgram;
use App\UserProgramTask;
use App\Value;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\ErrorHandler\Error\UndefinedFunctionError;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }
    public function masters(Request $request)
    {
        $shipValue = Value::where('name', '=', 'SHIPS')
            ->where('site_id', '=', $request->site->id)
            ->first();
        $ships = [];
        if ($shipValue)
            $ships = $shipValue->active_value_lists;
        $postValue = Value::where('name', '=', 'POST')
            ->where('site_id', '=', $request->site->id)
            ->first();
        $posts = [];
        if ($postValue)
            $posts = $postValue->active_value_lists;

        return response()->json([
            'ships' => $ships,
            'posts' => $posts,
        ], 200);
    }


    public function userCounts()
    {
        if (request()->year != '') {
            $user_counts = User::whereYear('created_at', '=', request()->year)->groupBy('rank')->select('rank', DB::raw('count(id) as count'))->get();
            $program_counts = Program::whereYear('created_at', '=', request()->year)->get()->count();
            $total_task_completed_counts = UserProgramTask::whereYear('completion_date', '=', request()->year)->where('is_completed', '=', true)->get()->count();
            $inActive_user_counts = User::whereYear('created_at', '=', request()->year)->where('active', '=', false)->get()->count();
        }

        return response()->json([
            'data'     =>  $user_counts,
            'program_count'     =>  $program_counts,
            'total_task_completed'     =>  $total_task_completed_counts,
            'inActive_user'     =>  $inActive_user_counts,
        ], 200);
    }

    public function total_tasks_performed()
    {
        $year = request()->year;
        $total_tasks_performed = [];
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
        $total_task = UserProgramTask::whereYear('completion_date', '=', $year)->where('is_completed', '=', true);
        if (request()->ship) {
            $ships=explode(',',request()->ship);
            $total_task = $total_task->whereIn('ship_id', $ships);
        }
        if (request()->rank != null) {
            $user_program_ids = [];
            $rank_id = request()->rank;
            // Rank Wise All Program Post
            $AllProgramPost = ProgramPost::where('post_id', '=', $rank_id)->get();
            foreach ($AllProgramPost as $key => $program_post) {
                // Program Wise All UserProgram 
                $AllUserProgram = UserProgram::where('program_id', '=', $program_post->program_id)->get();

                foreach ($AllUserProgram as $key => $user_program) {
                    // User Program Wise All UserProgramTask
                    $user_program_ids[] = $user_program->id;
                }
            }
            $total_task = $total_task->whereIn('user_program_id', $user_program_ids);
        }
        $total_task = $total_task->get();
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

        // $total_tasks_performed = [
        // "January " . $year => $jan_count,
        //     "February " . $year => $feb_count,
        //     "March " . $year => $mar_count,
        //     "April " . $year => $apr_count,
        //     "May " . $year => $may_count,
        //     "June " . $year => $jun_count,
        //     "July " . $year => $july_count,
        //     "August " . $year => $aug_count,
        //     "September " . $year => $sept_count,
        //     "October " . $year => $oct_count,
        //     "November " . $year => $nov_count,
        //     "December " . $year => $dec_count,
        // ];

        $total_tasks_performed = [
            $jan_count,
            $feb_count,
            $mar_count,
            $apr_count,
            $may_count,
            $jun_count,
            $july_count,
            $aug_count,
            $sept_count,
            $oct_count,
            $nov_count,
            $dec_count,
        ];

        return response()->json([
            'data'     =>  $total_tasks_performed,
            'success' => true
        ], 200);
    }

    public function top_performers_by_Average()
    {
        $year = request()->year;
        $total_task = UserProgramTask::whereYear('completion_date', '=', $year)->where('is_completed', '=', true);
        if (request()->rank) {
            $user_program_ids = [];
            $rank_id = request()->rank;
            // Rank Wise All Program Post
            $AllProgramPost = ProgramPost::where('post_id', '=', $rank_id)->get();
            foreach ($AllProgramPost as $key => $program_post) {
                // Program Wise All UserProgram 
                $AllUserProgram = UserProgram::where('program_id', '=', $program_post->program_id)->get();
                foreach ($AllUserProgram as $key => $user_program) {
                    // User Program Wise All UserProgramTask
                    $user_program_ids[] = $user_program->id;
                }
            }
            $total_task = $total_task->whereIn('user_program_id', $user_program_ids);
        }
        $total_task = $total_task->get();
        // Array Alignment 
        $users = [];
        foreach ($total_task as $key => $task) {
            $user_key = null;
            $user_id = $task['user_id'];
            $user = $task->user->toArray();
            unset($task['user']);
            $user_key = array_search($user_id, array_column($users, 'id'));
            $task_id = $task->program_task_id;
            if ($user_key !== 0 && $user_key == null) {
                //    Push User in Users Array
                $user['tasks'][$task_id] = $task;
                $users[] = $user;
            } else {
                // Update User using User key
                $users[$user_key]["tasks"][$task_id] = $task;
            }
        }

        // Average Calculation
        $u = [];
        foreach ($users as $key => $user) {
            $tasks_performed = sizeof($user['tasks']);
            $total_marks = 0;
            foreach ($user['tasks'] as $key => $task) {
                $total_marks += $task->marks_obtained;
            }
            $average = $total_marks / $tasks_performed;
            $user['task_perfomed'] = $tasks_performed;
            $user['total_marks'] = $total_marks;
            $user['average'] = $average;
            $u[] = $user;
        }

        // Sorting Descending by Average
        usort($u, function ($a, $b) {
            return $b['average'] - $a['average'];
        });
        return response()->json([
            'data'     =>  $u,
            'user_count'     =>  sizeof($u),
        ], 200);
    }

    public function top_performers_by_Task()
    {
        $year = request()->year;
        $total_task = UserProgramTask::whereYear('completion_date', '=', $year)->where('is_completed', '=', true);
        if (request()->rank) {
            $user_program_ids = [];
            $rank_id = request()->rank;
            // Rank Wise All Program Post
            $AllProgramPost = ProgramPost::where('post_id', '=', $rank_id)->get();
            foreach ($AllProgramPost as $key => $program_post) {
                // Program Wise All UserProgram 
                $AllUserProgram = UserProgram::where('program_id', '=', $program_post->program_id)->get();
                foreach ($AllUserProgram as $key => $user_program) {
                    // User Program Wise All UserProgramTask
                    $user_program_ids[] = $user_program->id;
                }
            }
            $total_task = $total_task->whereIn('user_program_id', $user_program_ids);
        }
        $total_task = $total_task->get();
        // Array Alignment 
        $users = [];
        foreach ($total_task as $key => $task) {
            $user_key = null;
            $user_id = $task['user_id'];
            $user = $task->user->toArray();
            unset($task['user']);
            $user_key = array_search($user_id, array_column($users, 'id'));
            $task_id = $task->program_task_id;
            if ($user_key !== 0 && $user_key == null) {
                //    Push User in Users Array
                $user['tasks'][$task_id] = $task;
                $users[] = $user;
            } else {
                // Update User using User key
                $users[$user_key]["tasks"][$task_id] = $task;
            }
        }

        // Average Calculation
        $u = [];
        foreach ($users as $key => $user) {
            $tasks_performed = sizeof($user['tasks']);
            $total_marks = 0;
            foreach ($user['tasks'] as $key => $task) {
                $total_marks += $task->marks_obtained;
            }
            $average = $total_marks / $tasks_performed;
            $user['task_perfomed'] = $tasks_performed;
            $user['total_marks'] = $total_marks;
            $user['average'] = $average;
            $u[] = $user;
        }

        // Sorting Descending by Average
        usort($u, function ($a, $b) {
            return $b['task_perfomed'] - $a['task_perfomed'];
        });
        return response()->json([
            'data'     =>  $u,
            'user_count'     =>  sizeof($u),
        ], 200);
    }
}
