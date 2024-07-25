<?php

namespace App\Services;

use App\Traits\CurlRequest;

class DocumentGenerationService
{

    use CurlRequest;

    /** Document generation function
     * @param array  $payload
     * @return array $response which include template_id and pdf
     * Author: Arman Saleem
     * Date:07 Sep, 2023
     */
    public function generateDocument(array $payload): array
    {
        //** Create Document Call **/
        $curlResponse = $this->sendPostRequest(env('ESIGN_CREATE_DOC'), json_encode($payload));

        $response_array = json_decode($curlResponse);
        $document_id = $response_array->documentId;

        $pdf_url = env('ESIGN_DOC_BASE_URL') . $response_array->documentId . '.pdf';

        //** PDF Call **/
        $payloadPdf = array(
            'id' => $document_id,
            'appId' => env('ESIGN_APP_ID')
        );
        $this->sendPostRequest(env('ESIGN_PDF_URL'), json_encode($payloadPdf));

        return (array(
            'document_id' => $document_id,
            'pdf_url' => $pdf_url
        ));
    }



    /**To get Audit Templates
     * @return array templates array
     * Author: Arman Saleem
     * Date:08 Sep, 2023
     */
    public function getAuditTemplates(): array
    {
        return array('64de3257e2a08b1531fd697a');
    }

    /** To get Audit Payload
     * @return array templates array
     * Author: Arman Saleem
     * Date:08 Sep, 2023
     */
    public function getAuditPayload($row): array
    {
        $templatesId = $this->getAuditTemplates();
        $payload = array(
            'mode' => "1",
            'appId' => env('ESIGN_APP_ID'),
            'templates' => json_encode($templatesId),
            'title' => env('ESIGN_PDF_TITLE_PREFIX') . ' Audit Report Document',
            'status' => 'unsigned',
            'follow_up' => '0',
            'message' => env('ESIGN_PDF_TITLE_PREFIX') . ' Audit Report Document',
            'subject' => env('ESIGN_PDF_TITLE_PREFIX') . ' Audit Report Document',
            'signers' => json_encode(array(
                "email_address" => $row['email'],
                "name" => $row['title'] . ' ' . $row['first_name'] . ' ' . $row['last_name']
            )),
            'customFields' => json_encode(array(
                'signDate' => date("d-m-Y", strtotime($row['signature_date_time'])),
                'BY' => env('AUDIT_REPORT_BY'),
                'transectionId' => base64_encode($row['counter']),
                'doc_createdBY' => env('AUDIT_REPORT_BY'),
                'doc_created_date' => "" . date("d-m-Y - H:i:s", strtotime($row['signature_date_time'] . " +1 hour")) . " GMT- IP address: " . $row['leadip'],
                'doc_eSign_by' => $row['title'] . ' ' . $row['first_name'] . ' ' . $row['last_name'],
                'agree_complted' => "" . date("d-m-Y - H:i:s", strtotime($row['signature_date_time'] . " +1 hour")) . " GMT",
                'doc_signature_date' => "" . date("d-m-Y - H:i:s", strtotime($row['signature_date_time'] . " +1 hour")) . " GMT- Time Source: server-IP address: 3.76.224.5"

            ))
        );

        return $payload;
    }


    /**To get Foa Templates
     * @return array templates array
     * Author: Arman Saleem
     * Date:07 Sep, 2023
     */
    public function getFoaTemplates(): array
    {
        return array('64d62448456cd305dfda1205');
    }

    /**To get Foa Payload
     * @return array templates array
     * Author: Arman Saleem
     * Date:07 Sep, 2023
     */
    public function getFoaPayload($row): array
    {
        $templatesId = $this->getFoaTemplates();
        $payload = array(
            'mode' => "1",
            'title' => env('ESIGN_PDF_TITLE_PREFIX') . ' FOA Document',
            'status' => 'signed',
            'follow_up' => '0',
            'appId' => env('ESIGN_APP_ID'),
            'templates' => json_encode($templatesId),
            'message' => env('ESIGN_PDF_TITLE_PREFIX') . ' FOA Document',
            'subject' => env('ESIGN_PDF_TITLE_PREFIX') . ' FOA Document',
            'signers' => json_encode(array(
                "email_address" => $row['email'],
                "name" => $row['title'] . ' ' . $row['first_name'] . ' ' . $row['last_name']
            )),
            'customFields' => json_encode(
                array(
                    'full_name' => $row['title'] . ' ' . $row['first_name'] . ' ' . $row['last_name'],
                    'middle_name' => '',
                    'previous_names' => $row['previous_name'],
                    'date_of_birth' => $row['DateOfBirth'],
                    'cur_address' => $row['address'],
                    'prev_address' => ($row['previous_address1_line1']) ? $row['previous_address1_line1'] . ', ' . $row['previous_postcode1'] : '',
                    'our_ref' => env('REFERENCE_PREFIX') . $row['counter'],
                    'sign' => $row['signature_src'],
                    'doc_signature_date' => "" . date("d-m-Y - H:i:s", strtotime($row['sign_time'] . " +1 hour"))
                )
            )
        );

        return $payload;
    }
}
