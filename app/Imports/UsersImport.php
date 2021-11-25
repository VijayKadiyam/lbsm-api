<?php

namespace App\Imports;

use App\CrudeUser;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $data = [
            // 'crude_users column name' = $row['Excel column name']
            'site_id'  =>  request()->site->id,
            'nationality' =>  $row['Nationality'],
            'rank'      =>  $row['Rank'],
            'first_name'     =>  $row['First Name'],
            'middle_name'        =>  $row['Middle Name'],
            'last_name'       =>  $row['Last Name'],
            'danaos_id'        =>  $row['DANAOS ID'],
            'dob'        =>  $row['B-Date'],
        ];
        return new CrudeUser($data);
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
