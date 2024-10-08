<?php

namespace App\Http\Controllers;

use App\KarcoTask;
use App\Program;
use App\ProgramPost;
use App\User;
use App\UserProgram;
use App\UserProgramTask;
use App\Value;
use App\VideotelTask;
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
            // $user_counts = User::whereYear('created_at', '=', request()->year)
            $user_counts = User::groupBy('rank')
                ->select('rank', DB::raw('count(id) as userCount'))
                // ->where('active', '=', true)
                ->get();

            // $user_counts = request()->site->users()->with('rank')
            // ->whereHas('rank',  function ($q) {
            //     $q->select('code', DB::raw('count(`id`) as userCount'))->groupBy('code');
            // })->get();


            // return $user_counts;

            // $program_counts = Program::whereYear('created_at', '=', request()->year)
            $program_counts = request()->site->programs()->get()->count();

            // $total_task_completed_counts = UserProgramTask::whereYear('completion_date', '=', request()->year)
            $total_task_completed_counts = request()->site->user_program_tasks()->where('is_completed', '=', true)
                ->get()->count();

            // $inActive_user_counts = User::whereYear('created_at', '=', request()->year)
            $inActive_user_counts = request()->site->users()->where('active', '=', false)
                ->get()->count();
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

        $total_task = UserProgramTask::whereYear('completion_date', '=', $year)->where('is_completed', '=', true)->where('active', true);
        // $total_karco_tasks = KarcoTask::whereYear('done_on', '=', $year)->where('assessment_status', '=', 'Completed')->where('is_deleted', false);
        $total_videotel_tasks = VideotelTask::whereYear('date', '=', $year)->where('score', '=', '100%')->where('is_deleted', false);

        if (request()->ship) {
            $ships = explode(',', request()->ship);
            $total_task = $total_task->whereIn('ship_id', $ships);
            // $total_karco_tasks = $total_karco_tasks->whereIn('ship_id', $ships);
            $total_videotel_tasks = $total_videotel_tasks->whereIn('ship_id', $ships);
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

            // All User Of that Rank [For Total KARCO TASK & Videotel Task]
            $ranked_users = User::where('rank_id', '=', $rank_id)->where('active', '=', true)->get();
            foreach ($ranked_users as $key => $user) {
                $user_ids[] = $user->id;
            }

            $total_task = $total_task->whereIn('user_program_id', $user_program_ids);
            // $total_karco_tasks = $total_karco_tasks->whereIn('user_id', $user_ids);
            $total_videotel_tasks = $total_videotel_tasks->whereIn('user_id', $user_ids);
        }
        $total_task = $total_task->get();
        // $total_karco_tasks = $total_karco_tasks->get();
        // return $total_karco_tasks->count();
        $total_videotel_tasks = $total_videotel_tasks->get();

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
        // $total_no_of_video_watched_in_jan = 0;
        // $total_no_of_video_watched_in_feb = 0;
        // $total_no_of_video_watched_in_march = 0;
        // $total_no_of_video_watched_in_apr = 0;
        // $total_no_of_video_watched_in_may = 0;
        // $total_no_of_video_watched_in_june = 0;
        // $total_no_of_video_watched_in_july = 0;
        // $total_no_of_video_watched_in_aug = 0;
        // $total_no_of_video_watched_in_sept = 0;
        // $total_no_of_video_watched_in_oct = 0;
        // $total_no_of_video_watched_in_nov = 0;
        // $total_no_of_video_watched_in_dec = 0;
        // foreach ($total_karco_tasks as $key => $karco_task) {
        //     $task_month = Carbon::parse($karco_task['done_on'])->month;
        //     $video_watched = $karco_task->no_of_video_watched;
        //     switch ($task_month) {
        //         case '1':
        //             $total_no_of_video_watched_in_jan += $video_watched;
        //             break;
        //         case '2':
        //             $total_no_of_video_watched_in_feb += $video_watched;
        //             break;
        //         case '3':
        //             $total_no_of_video_watched_in_march += $video_watched;
        //             break;
        //         case '4':
        //             $total_no_of_video_watched_in_apr += $video_watched;
        //             break;
        //         case '5':
        //             $total_no_of_video_watched_in_may += $video_watched;
        //             break;
        //         case '6':
        //             $total_no_of_video_watched_in_june += $video_watched;
        //             break;
        //         case '7':
        //             $total_no_of_video_watched_in_july += $video_watched;
        //             break;
        //         case '8':
        //             $total_no_of_video_watched_in_aug += $video_watched;
        //             break;
        //         case '9':
        //             $total_no_of_video_watched_in_sept += $video_watched;
        //             break;
        //         case '10':
        //             $total_no_of_video_watched_in_oct += $video_watched;
        //             break;
        //         case '11':
        //             $total_no_of_video_watched_in_nov += $video_watched;
        //             break;
        //         case '12':
        //             $total_no_of_video_watched_in_dec += $video_watched;
        //             break;

        //         default:
        //             # code...
        //             break;
        //     }
        // }
        $total_videotel_task_in_jan = 0;
        $total_videotel_task_in_feb = 0;
        $total_videotel_task_in_march = 0;
        $total_videotel_task_in_apr = 0;
        $total_videotel_task_in_may = 0;
        $total_videotel_task_in_june = 0;
        $total_videotel_task_in_july = 0;
        $total_videotel_task_in_aug = 0;
        $total_videotel_task_in_sept = 0;
        $total_videotel_task_in_oct = 0;
        $total_videotel_task_in_nov = 0;
        $total_videotel_task_in_dec = 0;
        foreach ($total_videotel_tasks as $key => $videotel_task) {
            $task_month = Carbon::parse($videotel_task['date'])->month;
            switch ($task_month) {
                case '1':
                    $total_videotel_task_in_jan++;
                    break;
                case '2':
                    $total_videotel_task_in_feb++;
                    break;
                case '3':
                    $total_videotel_task_in_march++;
                    break;
                case '4':
                    $total_videotel_task_in_apr++;
                    break;
                case '5':
                    $total_videotel_task_in_may++;
                    break;
                case '6':
                    $total_videotel_task_in_june++;
                    break;
                case '7':
                    $total_videotel_task_in_july++;
                    break;
                case '8':
                    $total_videotel_task_in_aug++;
                    break;
                case '9':
                    $total_videotel_task_in_sept++;
                    break;
                case '10':
                    $total_videotel_task_in_oct++;
                    break;
                case '11':
                    $total_videotel_task_in_nov++;
                    break;
                case '12':
                    $total_videotel_task_in_dec++;
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
        // $total_karco_tasks_performed = [
        //     $total_no_of_video_watched_in_jan,
        //     $total_no_of_video_watched_in_feb,
        //     $total_no_of_video_watched_in_march,
        //     $total_no_of_video_watched_in_apr,
        //     $total_no_of_video_watched_in_may,
        //     $total_no_of_video_watched_in_june,
        //     $total_no_of_video_watched_in_july,
        //     $total_no_of_video_watched_in_aug,
        //     $total_no_of_video_watched_in_sept,
        //     $total_no_of_video_watched_in_oct,
        //     $total_no_of_video_watched_in_nov,
        //     $total_no_of_video_watched_in_dec,
        // ];
        $total_videotel_tasks_performed = [
            $total_videotel_task_in_jan,
            $total_videotel_task_in_feb,
            $total_videotel_task_in_march,
            $total_videotel_task_in_apr,
            $total_videotel_task_in_may,
            $total_videotel_task_in_june,
            $total_videotel_task_in_july,
            $total_videotel_task_in_aug,
            $total_videotel_task_in_sept,
            $total_videotel_task_in_oct,
            $total_videotel_task_in_nov,
            $total_videotel_task_in_dec,
        ];
        return response()->json([
            'data'     =>  $total_tasks_performed,
            // 'total_karco_tasks_performed' => $total_karco_tasks_performed,
            'total_videotel_tasks_performed' => $total_videotel_tasks_performed,
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

    public function top_performers()
    {
        $year = request()->year;
        $type = request()->type;
        $total_task = request()->site->user_program_tasks_dashboard()
            ->where('is_completed', '=', true)
            ->where('active', '=', true);
        if (request()->rank) {
            $rank_id = request()->rank;
            $total_task = $total_task->whereHas('user',  function ($q) use ($rank_id) {
                $q->where('rank_id', '=', $rank_id);
                $q->where('active', '=', true);
            });
        }
        if (request()->year) {
            $year = request()->year;
            $total_task = $total_task->whereYear('completion_date', '=', $year)
                ->where('active', '=', true);
        }
        $total_task = $total_task->get();
        // return $total_task;
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
            $round_average = round($average, 1);
            $user['average_x10'] = $round_average * 10;
            $user['average'] = $round_average;
            $u[] = $user;
        }

        if ($type == 'true') {
            $filter_type = 'Tasks';
            // Sorting Descending by TASK
            usort($u, function ($a, $b) {
                return $b['task_perfomed'] - $a['task_perfomed'];
            });
        } else {
            $filter_type = 'Marks';
            // Sorting Descending by Average
            usort($u, function ($a, $b) {
                return $b['average_x10'] - $a['average_x10'];
            });
        }
        return response()->json([
            'data'     =>  $u,
            'user_count'     =>  sizeof($u),
            'filter_type' => $filter_type
        ], 200);
    }

    public function kpiData()
    {
        $year = request()->year;
        $kpi_CPP_count = 0;
        $kpi_karco_tasks_count = 0;
        $kpi_videotel_tasks_count = 0;
        $total = 100;
        $total_cpp = 20;
        $period = "";
        $kpi_CPP = UserProgramTask::whereYear('completion_date', '=', $year)->where('is_completed', '=', true)->where('active', '=', true);
        $kpi_karco_tasks = KarcoTask::whereYear('done_on', '=', $year)->where('assessment_status', '=', 'Completed')->where('is_deleted', '=', false);
        $kpi_videotel_tasks = VideotelTask::whereYear('date', '=', $year)->where('score', '=', '100%')->where('is_deleted', '=', false);

        if (request()->from_date && request()->to_date) {
            // If Date Filter
            $from_date = request()->from_date;
            $to_date = request()->to_date;
        } else {
            // Period Filter
            $period = request()->period;
            $from_date = Carbon::now()->subDays($period)->format("Y-m-d");
            $to_date = Carbon::now()->format("Y-m-d");
        }
        $from_month = Carbon::createFromFormat('Y-m-d', $from_date);
        $to_month =  Carbon::createFromFormat('Y-m-d', $to_date);
        $diff = $from_month->diffInMonths($to_month);
        if ($period && $period != 30 && $period != 180) {
            $final_month = $diff;
        } else {
            $final_month = $diff + 1;
        }
        // return 'from_date -'.$from_date. ' - to_date'. $to_date;
        $kpi_CPP = $kpi_CPP->whereBetween('completion_date', [$from_date, $to_date]);
        $kpi_karco_tasks = $kpi_karco_tasks->whereBetween('done_on', [$from_date, $to_date]);
        $kpi_videotel_tasks = $kpi_videotel_tasks->whereBetween('date', [$from_date, $to_date]);

        $kpi_CPP = $kpi_CPP->get();
        $kpi_karco_tasks = $kpi_karco_tasks->get();
        $kpi_videotel_tasks = $kpi_videotel_tasks->get();
        $kpi_CPP_count = $kpi_CPP->count();
        $kpi_karco_tasks_count = $kpi_karco_tasks->sum('no_of_video_watched');
        $kpi_videotel_tasks_count = $kpi_videotel_tasks->count();


        // return $final_month;

        $cpp = $total_cpp * $final_month;
        $karco_videotel = $total * $final_month;

        $CPP_percentage = ($kpi_CPP_count / $cpp) * $total;
        $KARCO_percentage = ($kpi_karco_tasks_count / $karco_videotel) * $total;
        $VIDEOTEL_percentage = ($kpi_videotel_tasks_count / $karco_videotel) * $total;

        // return 'Cpp'.$CPP_percentage . 'kaco' . $KARCO_percentage . 'videotel' . $VIDEOTEL_percentage. 'Cpp'.$kpi_CPP_count . 'kaco' . $kpi_karco_tasks_count . 'videotel' . $kpi_videotel_tasks_count;

        return response()->json([
            'kpi_CPP_percentage'     =>  round($CPP_percentage),
            'kpi_karco_tasks_percentage' => round($KARCO_percentage),
            'kpi_videotel_tasks_percentage' => round($VIDEOTEL_percentage),
            'kpi_CPP'     =>  $kpi_CPP_count,
            'kpi_karco_tasks' => $kpi_karco_tasks_count,
            'kpi_videotel_tasks' => $kpi_videotel_tasks_count,
            'cpp_out_of' => $cpp,
            'karco_videotel_out_of' => $karco_videotel,
            'diff' => $diff,
            'from_month' => $from_date,
            'to_month' => $to_date,
            'final_month' => $final_month,
            'success' => true
        ], 200);
    }


    public function same_user_data_migrations()
    {
        $selectedUserMigrationFrom = explode(',', request()->selectedUserMigrationFrom);  // Selected user(s) for inactive status [2, 3]
        $selectedUserForMigrationTo = request()->selectedUserMigrationTo; // Selected user for data migration
        $dbTables = [
            ['name' => 'user_programs', 'id' => 'program_id'],
            ['name' => 'user_program_posts', 'id' => 'program_post_id'],
            ['name' => 'user_program_tasks', 'id' => 'program_task_id'],
            ['name' => 'user_ships', 'id' => 'ship_id'],
            ['name' => 'videotel_tasks', 'id' => 'user_id'],
        ]; // Array of database tables for migration

        // Get migration data
        $migrationToUserData = [];
        $migrationUserFromData = [];

        $migrationToUserData['user_programs'] = DB::table('user_programs')
            ->where('user_id', $selectedUserForMigrationTo)
            ->get();
        $migrationToUserData['user_program_posts'] = DB::table('user_program_posts')
            ->where('user_id', $selectedUserForMigrationTo)
            ->get();
        $migrationToUserData['user_program_tasks'] = DB::table('user_program_tasks')
            ->where('user_id', $selectedUserForMigrationTo)
            ->get();
        $migrationToUserData['user_ships'] = DB::table('user_ships')
            ->where('user_id', $selectedUserForMigrationTo)
            ->get();
        $migrationToUserData['videotel_tasks'] = DB::table('videotel_tasks')
            ->where('user_id', $selectedUserForMigrationTo)
            ->get();

        // Loop through each selected user for inactivity
        foreach ($selectedUserMigrationFrom as $userId) {
            // Get user data
            foreach ($dbTables as $table) {

                $tableName = $table['name'];
                $tableId = $table['id'];
                $migrationUserFromData[$tableName] = DB::table($tableName)
                    ->where('user_id', $userId)
                    ->get();
                $updateData = [
                    'user_id' => $selectedUserForMigrationTo
                ];
                if (isset($migrationUserFromData[$tableName][0]->status)) {
                    // Check if the Table has Status column to update. If 0 index will have it then rest all will have it as expected.
                    $updateData['status'] = 2;
                }
                if ($migrationToUserData[$tableName]->isNotEmpty() && $migrationUserFromData[$tableName]->isNotEmpty()) {
                    if ($migrationToUserData[$tableName]->isNotEmpty() &&  $migrationUserFromData[$tableName]->isEmpty()) {
                        DB::table('users')->where('id', $userId)
                            ->update(['active' => false]);
                    }
                    if ($migrationUserFromData[$tableName]->isNotEmpty() && $migrationToUserData[$tableName]->isEmpty()) {
                        DB::table($tableName)->where('user_id', $userId)
                            ->update($updateData);
                    }
                    if (
                        isset($migrationUserFromData[$tableName]->$tableId)
                        && isset($migrationToUserData[$tableName]->$tableId)
                        && $migrationUserFromData[$tableName]->$tableId == $migrationToUserData[$tableName]->$tableId
                    ) {
                        // Deactivate the user's record
                        DB::table($tableName)->where($tableId, $migrationUserFromData[$tableName]->$tableId)
                            ->where('user_id', $userId)
                            ->update(['active' => false]);
                    } else {
                        DB::table($tableName)->where('user_id', $userId)
                            ->update($updateData);
                    }
                    DB::table('users')->where('id', $userId)
                        ->update(['active' => false]);
                } else {
                    DB::table('users')->where('id', $userId)
                        ->update(['active' => false]);
                }
            }
        }

        return response()->json([
            'success' => true
        ], 200);
    }
}
