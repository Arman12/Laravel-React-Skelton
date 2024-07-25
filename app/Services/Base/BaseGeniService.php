<?php
namespace App\Services\Base;

use App\Services\Repository\GeniRepository;
use Illuminate\Support\Facades\Crypt;

class BaseGeniService
{
    protected $geniRepository;

    // public function __construct(GeniRepository $geniRepository)
    // {
    //     $this->geniRepository = $geniRepository;
    // }

    /** Document generation function
     * @param array  $row
     * @param int  $status
     * @param array  $customFields
     * @param array  $documents
     * @param string  $leadSource
     * @return array $response which include payload and response
     * Author: Arman Saleem
     * Date:12 Sep, 2023
     */

    public function submittion_to_geni(array $row , int $status , array $customFieldsArray = [] , array $documents = [] , string $leadSource = '' ) : array
    {
        echo "inside"; exit; 
        $returnData = [
            'payload' => '',
            'response' => '',
            'success' => '',
            'message' => ''
        ];
        $ch2 = curl_init();
        curl_setopt(
            $ch2,
            CURLOPT_URL,
            GENI_CASE_CREATION_URL
        );

        $payload = [];
        $payload['account_id'] = GENI_ACCOUNT_ID;
        $payload['apikey'] = GENI_KEY;
        $payload['case_type'] = GENI_SITE_CASE;
        $payload['procedure'] = GENI_SITE_PROCEDURE;
        $payload['lead_source'] = $leadSource; // i.e 'businessnrgclaims.co.uk'
        dd($this->get_json_data_for_geni($row , $status , $customFieldsArray, $documents ) , 1 );
        $returnData['payload'] = $payload['json-data'] =  json_encode($this->get_json_data_for_geni($row , $status , $customFieldsArray, $documents ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        $returnData['response'] = curl_exec($ch2);
        curl_close($ch2);
    }


    public function get_json_data_for_geni(array $post , int $status , array $customFieldsArray = [] , array $documentsObjArray = []) : object
    {
        $previousAddress = array();
        $dob_month = "";
        $dob_day   = "";
        $dob_year  = "";
        $addressline1 = '';
        $addressline2 = '';
        $addressline3 = '';
        $city = '';
        $country = '';
        $county = '';
        $postcode = '';
        $dob = '';
        $main_object = (object)array(
            "lenders" => array(),
        );

        $signature_src = (isset($post['signature_src']) && $post['signature_src'] != "") ? Crypt::encryptString($post['signature_src']) :  "";

        $intl_number = $post['phone'];
        if (isset($post['phone']) && $post['phone'] != '' && $post['phone'] != 0) {

            if (isset($post['phone']) && $post['phone'] != '') {
                $uk_mob_number = $post['phone'];
                if (
                    strlen($uk_mob_number) < 11 &&
                    substr($uk_mob_number, 0, 1) != '0'
                ) {
                    $uk_mob_number = '0' . $uk_mob_number;
                } elseif (
                    strlen($uk_mob_number) > 11 &&
                    substr($uk_mob_number, 0, 2) == '44'
                ) {
                    $uk_mob_number = preg_replace('/^44/', '0', $uk_mob_number);
                } elseif (
                    strlen($uk_mob_number) > 12 &&
                    substr($uk_mob_number, 0, 3) == '+44'
                ) {
                    $uk_mob_number = str_replace('+44', '0', $uk_mob_number);
                } elseif (
                    strlen($uk_mob_number) > 12 &&
                    substr($uk_mob_number, 0, 4) == '0044'
                ) {
                    $uk_mob_number = preg_replace('/^0044/', '0', $uk_mob_number);
                }
                $intl_number = $uk_mob_number;
            }


            $intl_number = preg_replace('/^0/', '+44', $intl_number);
        }

        // 1975-12-31
        
        if(isset($post['dateOfBirth']) && $post['dateOfBirth'] != ""){
            $dob_Dates = explode('-', $post['dateOfBirth']);
            $dob_month = $dob_Dates[1];
            $dob_day   = $dob_Dates[2];
            $dob_year  = $dob_Dates[0];
            $dob = $dob_year . "-" . $dob_month . "-" . $dob_day;
        }

        if (isset($post['address_line_1']) && $post['address_line_1'] != '') {
            $addressline1 = $post['address_line_1'];
            $addressline2 = (isset($post['address_line_2']) && $post['address_line_2'] != "") ? $post['address_line_2'] : '';
            $addressline3 = (isset($post['address_line_3']) && $post['address_line_3'] != "") ? $post['address_line_3'] : '';
            $city = (isset($post['city']) && $post['city'] != "") ? $post['city'] : '';
            $country = (isset($post['country']) && $post['country'] != "") ? $post['country'] : '';
            $county = (isset($post['county']) && $post['county'] != "") ? $post['county'] : '';
            $postcode = (isset($post['postal_code']) && $post['postal_code'] != "") ? $post['postal_code'] : '';
        } 
        // applicant object array
        $applicantsobject = (object) array(
            "town" => $city,
            "title" => $post['title'],
            "company"=> $post['business_name'],
            "county" => $county,
            "country"  => $country,
            "surname" => $post['lastname'],
            "postcode" => $postcode,
            "verified" => 1,
            "address_1" => $addressline1,
            "address_2" => $addressline2,
            "firstname" => $post['firstname'],
            "middlename" => "",
            "mobile_no" => (isset($intl_number) &&  $intl_number != 0 &&  $intl_number != "") ? $intl_number : "",
            "signature" => $signature_src,
            "dateofbirth" => $dob,
            "telephone_no" => "",
            "email_address"  =>$post['email'],
            "previous_name" => ($post['prevName'] != "" && isset($post['prevName'])) ? $post['prevName'] : "N/A",
            "primary_contact" => 1,
            "national_insurance" => ($post['insurance_number'] != "" && isset($post['insurance_number'])) ? $post['insurance_number'] : "N/A",
            "previous_addresses" => $previousAddress
        );

        $current_object_to_push = (object)array(
            "device" => $post['device'],
            "leadid" => $post['countToPlace'], // local id passing
            "lenderid" => $post['LenderIDs'], // 99
            "sublenderid" => $post['subLenderID'], // 05
            "lender" => $post['energy_provider_c'], // provider
            "browser" => $post['browser'],
            "ip_address" => $post['leadip'],
            "reference" => $post['full_reference'],
            "user_agent_string" =>  $post['user_agent'],
            "account_number" => "", //
            "custom_fields" => (object) $customFieldsArray,
            "status" => (object) array(
                "received" => $status
            ),

            "documents" => $documentsObjArray,
            "applicants" =>  array($applicantsobject),

        );
        array_push($main_object->lenders, $current_object_to_push);
        return $main_object;
    }



    public function statusUpdate($reff, $status_id ){
        $payload = $this->clientIDgetPayloadLus($reff);
        $request_headers = array(
            "Content-Type: multipart/form-data",
        );
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL,"https://api.genilegal.uk/postoffice/v1/out");
        curl_setopt($ch2, CURLOPT_POST,1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER,1);
        $result2=curl_exec ($ch2);
        $decoded = json_decode($result2,true);
        // debug($decoded , 1);
        if(isset($decoded['customers']) || isset($decoded['clients']) ){
            $uid = '';
            foreach($decoded['cases'] as $cases){
                $sourceToMatch = $reff;
                if($cases['source_id'] == $sourceToMatch){
                    $uid = $cases['uid'];
                    break;
                }
            }
            if(isset($uid) && $uid != ''){
                $payloadStatus2 = $this->getPayloadStatus($uid, $status_id);
                $request_headers = array(
                    "Content-Type: multipart/form-data",
                );
                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL,"https://api.genilegal.uk/postoffice/v1/updatestatus");
                curl_setopt($ch2, CURLOPT_POST,1);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, $payloadStatus2);
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER,1);
                $result31=curl_exec ($ch2);
                print_r($result31);
                $result2 = json_decode($result31);
                curl_close($ch2);

                $result2_res = [
                    'response' => $result2 ,
                    'payload' => json_encode($payloadStatus2)];

                print_r($result2_res);

                return $result2_res;
            }
        }
    }

    public function clientIDgetPayloadLus($source_id){
        $payload = array();
        $payload['account_id'] = GENI_ACCOUNT_ID;
        $payload['apikey'] = GENI_KEY;
        $payload['case_type'] = GENI_SITE_CASE;
        $payload['procedure'] = GENI_SITE_PROCEDURE;
        $payload['source_id'] = $source_id;
        return $payload;
    }

    public function getPayloadStatus($uid, $status_id){
        $payload = array();
        $payload['account_id'] = GENI_ACCOUNT_ID;
        $payload['apikey'] = GENI_KEY;
        $payload['case_type'] = GENI_SITE_CASE;
        $payload['procedure'] = GENI_SITE_PROCEDURE;
        $payload['status_id'] = $status_id;
        $payload['uid'] = $uid;
        return $payload;
    }

}
