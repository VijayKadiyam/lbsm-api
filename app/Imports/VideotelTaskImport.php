<?php

namespace App\Imports;

use App\CrudeVideotelTask;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class VideotelTaskImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $data = [
            // 'crude_users column name' = $row['Excel column name']
            'site_id'  =>  request()->site->id,
            'location' =>  $row['Location'],
            'first_name' =>  $row['First Name'],
            'last_name' =>  $row['Last Name'],
            'crew_id' =>  $row['Crew ID'],
            'rank' =>  $row['Rank'],
            'training_title' =>  $row['Training Title'],
            'module' =>  $row['Module'],
            'type' =>  $row['Type'],
            'date' =>  $row['Date'],
            'duration' =>  $row['Duration'],
            'score' =>  $row['Score'],
        ];
        return new CrudeVideotelTask($data);
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
