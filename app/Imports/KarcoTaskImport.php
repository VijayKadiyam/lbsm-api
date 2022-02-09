<?php

namespace App\Imports;

use App\CrudeKarcoTask;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');
class KarcoTaskImport implements ToModel,WithHeadingRow
{
    public function model(array $row)
    {
        $data = [
            // 'crude_users column name' = $row['Excel column name']
            'site_id'  =>  request()->site->id,
            'vessel_name' =>  $row['Vessel Name'],
            'crew_name' =>  $row['Crew Name'],
            'employee_id' =>  $row['Employee Id'],
            'rank' =>  $row['Rank'],
            'department' =>  $row['Department'],
            'status' =>  $row['Status'],
            'signed_on' =>  $row['Signed On'],
            'nationality' =>  $row['Nationality'],
            'video_title' =>  $row['Video Title'],
            'no_of_preview_watched' =>  $row['No.Of Preview Watched'],
            'no_of_video_watched' =>  $row['No. Of Video Watched'],
            'obtained_marks' =>  $row['Obtained Marks'],
            'total_marks' =>  $row['Total Marks'],
            'percentage' =>  $row['Percentage'],
            'done_on' =>  $row['Done On'],
            'due_days' =>  $row['Due Days'],
            'assessment_status' =>  $row['Assessment Status'],
        ];
        return new CrudeKarcoTask($data);
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
