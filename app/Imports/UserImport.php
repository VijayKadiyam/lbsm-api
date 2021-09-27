<?php

namespace App\Imports;

use App\CrudeUser;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class UserImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $data = [
            // 'crude_users column name' = $row['Excel column name']
            'site_id'  =>  request()->site->id,
            'full_name' =>  $row['FNAME'],
            'employee_id' =>  $row['EMPL ID'],
            'email' =>  $row['EMAIL'],
            'dob' =>  $row['DOB'],
            'blood_group' =>  $row['BLOOD GROUP'],
            'father_name' =>  $row['FATHER NAME'],
            'marital_status' =>  $row['MARITAL STATUS'],
            'marriage_date' =>  $row['MARRIAGE DATE'],
            'spouse_name' =>  $row['SPOUSE NAME'],
            'nationality' =>  $row['NATIONALITY'],
            'residential_status' =>  $row['RESIDENTIAL STATUS'],
            'place_of_birth' =>  $row['PLACE OF BIRTH'],
            'country_origin' =>  $row['COUNTRY ORIGIN'],
            'religion' =>  $row['RELIGION'],
            'is_international_employee' =>  $row['IS INTERNATIONAL EMPLOYEE'],
            'is_physically_challanged' =>  $row['IS PHYSICALLY CHALLENGED'],
            'is_director' =>  $row['IS DIRECTOR'],
            'verification_status' =>  $row['VERIFICATION STATUS'],
            'vetification_on' =>  $row['VETIFICATION ON'],
            'agency_name' =>  $row['AGENCY NAME'],
            'remarks' =>  $row['REMARKS'],
            'joined_on' =>  $row['JOINED ON'],
            'confirmation_date' =>  $row['CONFIRMATION DATE'],
            'employee_status' =>  $row['EMPLOYEE STATUS'],
            'probation_period' =>  $row['PROBATION PERIOD'],
            'notice_period' =>  $row['NOTICE PERIOD'],
            'bank_name' =>  $row['BANK NAME'],
            'bank_branch' =>  $row['BANK BRANCH'],
            'bank_acc_no' =>  $row['BANK ACC NO'],
            'bank_ifsc_code' =>  $row['BANK IFSC CODE'],
            'bank_acc_type' =>  $row['BANK ACC TYPE'],
            'bank_payment_type' =>  $row['BANK PAYMENT TYPE'],
            'name_on_bank' =>  $row['NAME ON BANK'],
            'is_esi_applicable' =>  $row['IS ESI APPICABLE'],
            'esi_number' =>  $row['ESI NUMBER'],
            'is_pf_applicable' =>  $row['IS PF APPLICABLE'],
            'uan_no' =>  $row['UAN NO'],
            'pf_no' =>  $row['PF NO'],
            'pf_joining_date' =>  $row['PF JOINING DATE'],
            'family_pf_no' =>  $row['FAMILY PF NO'],
            'is_existing_member_of_eps' =>  $row['IS EXISTING MEMBER OF ESP'],
            'allow_epf_excess_contribution' =>  $row['ALLOW EPF EXCESS CONTRIBUTION'],
            'allow_eps_excess_contribution' =>  $row['ALLOW EPS EXCESS CONTRIBUTION'],
            'is_lwf_applicable' =>  $row['IS LWF APPICABLE'],
            'api_token' =>  $row['API TOKEN'],
            'email_verified_at' =>  $row['EMAIL VERIFIED AT'],
            'mobile' =>  $row['MOBILE'],
            'gender' =>  $row['GENDER'],
            'group' =>  $row['GROUP'],
            'group_division' =>  $row['GROUP DIVISION'],
            'state' =>  $row['STATE'],
            'fixed_basic' =>  $row['FIXED BASIC'],
            'fixed_da' =>  $row['FIXED DA'],
            'fixed_hra' =>  $row['FIXED HRA'],
            'fixed_conveyance' =>  $row['FIXED CONVEYANCE'],
            'fixed_medical_allowance' =>  $row['FIXED MEDICAL ALLOWANCE'],
            'fixed_cca' =>  $row['FIXED CCA'],
            'fixed_mobile_allowance' =>  $row['FIXED MEDICAL ALLOWANCE'],
            'fixed_other_allowance' =>  $row['FIXED OTHER ALLOWANCE'],
            'fixed_washing_allowance' =>  $row['FIXED WASHING ALLOWANCE'],
            'gross_salary' =>  $row['GROSS SALARY'],
            'ot_rate' =>  $row['OT RATE'],
            'leave_encashment' =>  $row['LEAVE ENCASHMENT'],
            'bonus' =>  $row['BONUS'],
            'employee_pf' =>  $row['EMPLOYEE PF'],
            'employee_esic' =>  $row['EMPLOYEE ESIC'],
            'employee_pt' =>  $row['EMPLOYEE PT'],
            'employee_mlwf' =>  $row['EMPLOYEE MLWF'],
            'net_in_hand' =>  $row['NET IN HAND'],
            'employer_pf' =>  $row['EMPLOYER PF'],
            'employer_esic' =>  $row['EMPLOYER ESIC'],
            'employer_mlwf' =>  $row['EMPLOYER MLWF'],
            'employer_bonus' =>  $row['EMPLOYER BONUS'],
            'employer_leave_salary' =>  $row['EMPLOYER LEAVE SALARY'],
            'employer_graduity' =>  $row['EMPLOYER GRADUITY'],
            'employer_uniform_charges' =>  $row['EMPLOYER UNIFORM CHARGES'],
            'employer_administrative_charges' =>  $row['EMPLOYER ADMINISTRATIVE CHARGES'],
            'employer_training_charges' =>  $row['EMPLOYER TRAINING CHARGES'],
            'employer_reliver_charges' =>  $row['EMPLOYER RELIVER CHARGES'],
            'employer_min_wage' =>  $row['EMPLOYER MIN WAGE'],
            'total_cost' =>  $row['TOTAL COST'],
            'management_fees' =>  $row['MANAGEMENT FEES'],
            'total_ctc' =>  $row['TOTAL CTC'],
            'billing_ot_rate' =>  $row['BILLING OT RATE'],
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
