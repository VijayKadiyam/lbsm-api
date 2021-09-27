<?php

use Illuminate\Database\Seeder;
use App\Value;
use App\ValueList;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $state = Value::where('name', '=', 'STATE')->first();
        if ($state == '' || $state == null) {
            $state = Value::create([
                'site_id' => '1',
                'name'   =>  'STATE',
            ]);
        }
        $value_id = $state['id'];

        $State_array = [
            'ANDAMAN AND NICOBAR ISLANDS',
            'ANDHRA PRADESH',
            'ARUNACHAL PRADESH',
            'ASSAM',
            'BIHAR',
            'CHHATTISGARH',
            'CHANDIGARH',
            'DADRA AND NAGAR HAVELI AND DAMAN AND DIU',
            'DELHI',
            'GOA',
            'GUJARAT',
            'HARYANA',
            'HIMACHAL PRADESH',
            'JAMMU AND KASHMIR',
            'JHARKHAND',
            'KARNATAKA',
            'KERALA',
            'LADAKH',
            'LAKSHADWEEP',
            'MADHYA PRADESH',
            'MAHARASHTRA',
            'MANIPUR',
            'MEGHALAYA',
            'MIZORAM',
            'NAGALAND',
            'ODISHA',
            'PUDUCHERRY',
            'PUNJAB',
            'RAJASTHAN',
            'SIKKIM',
            'TAMIL NADU',
            'TELENGANA',
            'TRIPURA',
            'UTTAR PRADESH',
            'UTTARAKHAND',
            'WEST BENGAL'
        ];

        foreach ($State_array as $state_name) {
            $state = ValueList::where('description', '=', $state_name)
                ->orWhere('code', '=', $state_name)
                ->first();
            if ($state == '' || $state == null) {
                $state = ValueList::create([
                    'site_id' => '1',
                    'value_id' => $value_id,
                    'description'   =>  $state_name,
                    'code'   =>  $state_name,
                ]);
            }
        }
    }
}
