<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class GeniRepository
{
    public function FetchNonConflictedLead()
    {
        $data = DB::table('primary_leads')
            ->select('primary_leads.*', 'lenders_leads.*', 'lenders_leads.id as lender_primary_id')
            ->leftJoin('lenders_leads', 'lenders_leads.primary_lead_id', '=', 'primary_leads.id')
            ->where('lenders_leads.is_conflicted', 0)
            ->whereNotNull('primary_leads.email')
            ->where('primary_leads.email', '<>', '')
            ->whereNotNull('primary_leads.energy_provider_c')
            ->where('primary_leads.energy_provider_c', '<>', '')
            ->whereNotNull('primary_leads.business_name')
            ->where('primary_leads.business_name', '<>', '')
            ->whereNotNull('primary_leads.phone')
            ->where('primary_leads.phone', '<>', '')
            ->where('primary_leads.is_eligible', 1)
            ->orderBy('primary_leads.id')
            ->first();
            return $data;
    }

    function RejectedCheckQuery($lender_id ,$email , $energy_provider_c , $business_name){

        $data =  DB::table('primary_leads')
        ->select(DB::raw('count(lenders_leads.id) as total'), 'full_reference')
        ->leftJoin('lenders_leads', 'lenders_leads.primary_lead_id', '=', 'primary_leads.id')
        ->where('primary_leads.email', '=', $email)
        ->where('lenders_leads.energy_provider_c', '=', $energy_provider_c)
        ->where('primary_leads.business_name', '=', $business_name)
        ->where('lenders_leads.id', '<>', $lender_id)
        ->whereIn('lenders_leads.is_conflicted', [1, 2])
        ->groupBy('primary_leads.email')
        ->orderBy('primary_leads.id')
        ->limit(1)
        ->first();
        return $data;
    }

    function totalValidCasesAginstEmailApartFromCurrent($lender_id ,$email){
        return DB::table('primary_leads')
        ->select(DB::raw('count(lenders_leads.id) as total_valid_cases'), 'full_reference')
        ->leftJoin('lenders_leads', 'lenders_leads.primary_lead_id', '=', 'primary_leads.id')
        ->where('primary_leads.email', '=', $email)
        ->where('lenders_leads.id', '<>', $lender_id)
        ->whereNotNull('primary_leads.countToPlace')
        ->where('primary_leads.countToPlace', '<>', '')
        ->whereNotNull('lenders_leads.full_reference')
        ->where('lenders_leads.full_reference', '<>', '')
        ->whereIn('lenders_leads.is_conflicted', [1, 2])
        ->groupBy('primary_leads.email', 'lenders_leads.full_reference')
        ->get();
    }

    function getcountToPlace(){
        $insertData = ['status' => 1];
        DB::table('CounterTable')->insert($insertData);
        $insertId = DB::getPdo()->lastInsertId();

        return $insertId;
     }


     function update($table, $where, $data)
    {
        if (!isset($where) || $where == "") {
            return false;
        }

        $affectedRows = DB::table($table)
            ->where($where)
            ->update($data);

        if ($affectedRows >= 1) {
            return true;
        } else {
            return false;
        }
    }



    function returnNotProcessedLimit(){
        $query = DB::table('primary_leads')
        ->select('primary_leads.*', 'lenders_leads.*', 'lenders_leads.id as lender_primary_id')
        ->leftJoin('lenders_leads', 'lenders_leads.primary_lead_id', '=', 'primary_leads.id')
        ->whereNotNull('primary_leads.countToPlace')
        ->where('primary_leads.countToPlace', '<>', '')
        ->whereNotNull('lenders_leads.full_reference')
        ->where('lenders_leads.full_reference', '<>', '')
        ->where('primary_leads.is_eligible', 1)
        ->where('primary_leads.is_landing_page_submitted', 1)
        ->where('lenders_leads.is_geni_submitted', 0)
        ->whereIn('lenders_leads.is_conflicted', [1, 2])
        ->orderBy('primary_leads.id')
        ->limit(1);

        $results = $query->get();

        return $results;
    }

    function save($table, $data)
    {
        DB::table($table)->insert($data);

        $insertId = DB::getPdo()->lastInsertId();

        if ($insertId > 0) {
            return $insertId;
        } else {
            return false;
        }
    }


    function modelFetchLeadsAfterFileAnHour()
    {
        return DB::table('primary_leads')
            ->select('primary_leads.*', 'lenders_leads.*', 'lenders_leads.id as lender_primary_id')
            ->leftJoin('lenders_leads', 'lenders_leads.primary_lead_id', '=', 'primary_leads.id')
            ->whereNotNull('primary_leads.countToPlace')
            ->where('primary_leads.countToPlace', '<>', '')
            ->whereNotNull('lenders_leads.full_reference')
            ->where('lenders_leads.full_reference', '<>', '')
            ->where('primary_leads.is_eligible', 1)
            ->where('primary_leads.is_landing_page_submitted', 1)
            ->where('lenders_leads.is_geni_submitted', 1)
            ->where('lenders_leads.is_geni_status_changes_after_file', 0)
            ->whereIn('lenders_leads.is_conflicted', [1, 2])
            ->whereNotNull('primary_leads.merged_id_pdf_url')
            ->where('primary_leads.merged_id_pdf_url', '<>', '')
            ->where('primary_leads.is_id_upload', 1)
            ->whereRaw('primary_leads.updatedAt <= NOW() - INTERVAL 60 MINUTE')
            ->orderBy('primary_leads.id')
            ->limit(1)
            ->get();
    }


}
