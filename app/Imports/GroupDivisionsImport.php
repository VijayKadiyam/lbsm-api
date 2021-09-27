<?php

namespace App\Imports;

use App\CrudGroupDivision;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class GroupDivisionsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $data = [
            'site_id'  => request()->site->id,
            'group_name' => $row['GROUP NAME'],
            'division_name' => $row['DIVISION NAME'],
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

        return new CrudGroupDivision($data);
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
