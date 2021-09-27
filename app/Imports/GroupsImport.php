<?php

namespace App\Imports;

use App\CrudGroup;
use App\Group;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class GroupsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $data = [
            'site_id'  => request()->site->id,
            'name' => $row['NAME'],
            'email'    => $row['EMAIL'],
            'phone'    => $row['PHONE'],
            'address_1'    => $row['ADDRESS 1'],
            'address_2'    => $row['ADDRESS 2'],
            'city' => $row['CITY'],
            'state' => $row['STATE'],
            'country'   => $row['COUNTRY'],
            'pincode'  => $row['PINCODE'],
            'gstin_no' => $row['GST IN NO'],
            'pan_no'   => $row['PAN NO'],
        ];

        return new CrudGroup($data);
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
