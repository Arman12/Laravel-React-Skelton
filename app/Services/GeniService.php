<?php
namespace App\Services;
use App\Repositories\GeniRepository;
use App\Services\Base\BaseGeniService;

use Illuminate\Support\Facades\Crypt;

class GeniService extends BaseGeniService
{
    protected $geniRepository;

    public function __construct(GeniRepository $geniRepository)
    {
        $this->geniRepository = $geniRepository;
    }    
    public function partial_processing_flow0(){
        $leads =  $this->geniRepository->returnNotProcessedLimit();
        return dd($leads);
        foreach ($leads as $l) {
            $lender_id = $l['lender_primary_id'];
            $data = array(
                'is_geni_submitted' => 1
            );

            $where = " id  = '$lender_id' ";
            
            $documentsObjectArray = array();
            $status = 2027;
            $leadSource = 'businessnrgclaims.co.uk';
            $customFields = [
                "energy_supplier" => $l['energy_provider_c'],
                "did_you_use_a_broker" => $l['use_an_energy_broker'],
                "name_of_energy_broker" => $l['energy_broker_c'],
                "did_you_know_commissions_where_hidden" => $l['unit_price_comission_paid'],
            ];
    
            
            $urlHeaders = @get_headers($l['terms_pdf_url']);
            $urlCheck = @$urlHeaders[0];
            if(strpos($urlCheck,'200')){
                $documetObject = (object) array(
                    "url" => $l['terms_pdf_url'],
                    "send"=> "0",
                    "send_to_customer" => "0",
                    "description" => "Client Pack",
                    "client_document" => "1",
                    "short_description"=> "R40CP"
                );
                array_push($documentsObjectArray ,$documetObject);
            }
    
            $leadSource = '';
            $resp = $this->submittion_to_geni($l , $status , $customFields  , $documentsObjectArray , $leadSource);

            if(isset($where) && $where != ""){
                $this->geniRepository->update('lenders_leads', $where, $data);
            }

        }

        
    }

    // public function partial_processing_flow1(){
    //     // can't increase limit more than 1
    //     $leads = $this->geniRepository->modelFetchLeadsAfterFileAnHour();
    //     foreach ($leads as $l) {
    //         $data = [];
    //         $json_payload = [];
    //         $lender_id = $l['lender_primary_id'];
    //         $where = " id  = '$lender_id' ";


    //         $geniResponse = $this->statusUpdate($l['full_reference'], '2032');

    //         $data = array(
    //             'is_geni_status_changes_after_file' => 1
    //         );
    //         if(isset($where) && $where != ""){
    //             $this->geniRepository->update('lenders_leads', $where, $data);
    //         }


    //         $geniData=[];
    //         if(!empty($geniResponse['payload'])){
    //             $geniData['geni_payload_response'] = json_encode($geniResponse['response']);
    //             $geniData['geni_payload'] = json_encode($geniResponse['payload']);
    //             $geniData['lead_id'] = $l['primary_lead_id'];
    //             $geniData['lender_id'] = $l['lender_primary_id'];
    //             $geniData['refrence'] = $l['full_reference'];
    //             $geniData['function_name'] = 'partial_processing_flow1';
    //             $this->geniRepository->save('geni_logs', $geniData);
    //         }

    //     }
    // }

   
    


}
