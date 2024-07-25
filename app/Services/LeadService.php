<?php

namespace App\Services;

use App\Exceptions\Application\ApplicationException;
use App\Traits\ClientDetails;
use App\Traits\Encryption;
use App\Services\EmailService;
use App\Services\DocumentGenerationService;
use App\Models\Lead;
use App\Models\Counter;

/** repository pattern for model related things **/

use App\Repositories\LeadRepository;
use App\Repositories\EmailTemplateRepository;

class LeadService
{

    private $emailTemplateRepository;
    private $leadRepository;
    private $emailService;
    private $documentGenerationService;

    /**
     * LeadService constructor
     * @param \App\Services\EmailService $emailService
     * @param \App\Repositories\LeadRepository $leadRepository
     * @param \App\Repositories\EmailTemplateRepository $emailTemplateRepository
     * Author: Arman Saleem
     * Date: 24 Aug, 2023
     */
    public function __construct(EmailService $emailService, LeadRepository $leadRepository, EmailTemplateRepository $emailTemplateRepository, DocumentGenerationService $documentGenerationService)
    {
        $this->emailService = $emailService;
        $this->leadRepository = $leadRepository;
        $this->emailTemplateRepository = $emailTemplateRepository;
        $this->documentGenerationService = $documentGenerationService;
    }

    use ClientDetails;
    use Encryption;
    /**
     * Returns encrypted id after saving the leads into the DB, this function accept Form data as request and save into DB to return lead id.
     * @param  array  $request
     * @return string
     * Author: Arman Saleem
     * Date: 07 Sep, 2023
     */

    public function saveLead(array $request): string
    {
        $payload = $this->saveLeadPayload($request);
        if ($request['id']) {
            $id = $this->decryptId($request['id']);
            $this->leadRepository->update($id, $payload);
            return $request['id'];
        } else {
            $counter = Counter::create(['req_time' => date("Y-m-d H:i:s")]);
            $payload['counter'] = $counter->id;
            $id = $this->leadRepository->create($payload)->id;
            return $this->encryptId($id);
        }
    }

    /**
     * Returns Save Lead payload
     * @param  array  $request
     * @return array $payload
     * Author: Arman Saleem
     * Date: 12 Sep, 2023
     */
    private function saveLeadPayload(array $request): array
    {
        $clientDetails = $this->getClientDetails();
        $payload = array(
            'first_name' => $request['firstName'] ? $request['firstName'] : '',
            'last_name' => $request['surName'] ? $request['surName'] : '',
            'email' => $request['email'] ? $request['email'] : '',
            'phone' => $request['telephoneNumber'] ? $request['telephoneNumber'] : '',
            'address' => $request['address'] ? json_encode($request['address'], true) : '',
            'date_of_birth' => $request['dateOfBirth'] ? $request['dateOfBirth'] : '',
            'title' => $request['title'] ? $request['title'] : '',
            'signature_src' => $request['signatureSrc'] ? $request['signatureSrc'] : '',
            'agree_terms' => $request['iAgreeTerms'] == "true" ? Lead::TERMS_TRUE : Lead::TERMS_FALSE,
            'tax_year' => json_encode($request['taxYear'], true),
            'tax_payer' => $request['taxPayer'],
            'status' => $request['finalSubmission'] ? Lead::STATUS_COMPLETED : Lead::STATUS_PARTIAL,
            'ip_address' => $clientDetails['ip_address'],
            'device' => $clientDetails['device'],
            'browser' => $clientDetails['browser'],
            'signature_date_time' => $request['signatureSrc'] ? date("Y-m-d H:i:s") : NULL
        );
        return $payload;
    }


    /**
     * To Get the lead from DB against the ID
     * @param  string  $id
     * @return string $results
     * Author: Arman Saleem
     * Date: 12 Sep, 2023
     */
    public function getLead(string $id): string
    {
        $id = $this->decryptId($id);
        $results = $this->leadRepository->find($id);
        return json_encode($results, true);
    }

    /**
     * To send emails, this will validate the email delivery first, send emails and will generate the EML file against the email message id.
     * @return string
     * Author: Arman Saleem
     * Date: 24 Aug, 2023
     */

    public function sendEmail()
    {
        //** get email template **/
        $emailTemplateArray = $this->emailTemplateRepository->find(1);

        $where = ['status' => Lead::STATUS_DOCUMENTED];

        $leadsData = $this->leadRepository->findAllWhere($where);

        if (!empty($leadsData) && $emailTemplateArray) {
            $emailTemplateId = $emailTemplateArray['id'];
            $emailTemplate = $emailTemplateArray['description'];
            $emailSubject = $emailTemplateArray["subject"];

            foreach ($leadsData as $lead) {
                $dbData = [];

                $id = $lead['id'];
                $encLeadId = $this->encryptId($id);
                $url = env('APP_URL');

                $uniqueLink = $url . '?key=' . $encLeadId . '&email=' . $emailTemplateId;
                $unsubscribeLink = $url . 'unsubscribe-email/' . $encLeadId;

                $html = $this->getText($lead, $emailTemplate, $uniqueLink, $unsubscribeLink);

                $sendTo = $lead['email'];
                /** validate email address delivery **/
                $emailValidation = json_decode($this->emailService->to($sendTo)->validateEmail());
                if ($emailValidation && isset($emailValidation->risk) && $emailValidation->risk == 'low' && $emailValidation->result == 'deliverable') {
                    $sendEmail = $this->emailService->to($sendTo)->subject($emailSubject)->message($html)->sendEmail();
                    if ($sendEmail['msg_id']) {
                        $meesageId = $sendEmail['msg_id'];
                        /** EML file generation functionality **/
                        $emlFile = $this->emailService->meesageId($meesageId)->subject($emailSubject)->message($html)->generateEmlFile();

                        /** FOR DB Updation **/
                        $dbData['eml_file'] = $emlFile;
                        $dbData['message_id'] = $meesageId;
                        $dbData['status'] = Lead::STATUS_EMAILED;

                        echo $emlFile;
                        echo ('Email sent to ' . $sendTo . ', Message ID: ' . $meesageId);
                    } else {
                        $dbData['status'] = Lead::EMAILED_FAILED;
                        echo 'email not sent due to some reasons';
                    }

                    exit;
                } else {
                    $dbData['status'] = Lead::EMAILED_FAILED;
                    echo 'email devleivery issue';
                }

                //** Update DB **/
                dd($dbData);
                // if($id) {
                //     $updateLead = $this->leadRepository->update($id, $dbData);
                //     echo 'Email sending';
                //     exit;
                // }
            }
        } else {
            echo 'empty';
        }
    }


    /**
     * Document generation function to generate the documents 
     * @return string
     * Author: Arman Saleem
     * Date:07 Sep, 2023
     */

    public function documentGeneration()
    {
        $where = ['status' => Lead::STATUS_COMPLETED];
        $leads = $this->leadRepository->findAllWhere($where);

        if ($leads) {
            foreach ($leads as $row) {
                $dbData = [];
                /*** to get the FOA payload ***/
                $foaPayload = $this->documentGenerationService->getFoaPayload($row);
                // To generate the FOA docs
                $foaDoc = $this->documentGenerationService->generateDocument($foaPayload);

                $dbData['document_url'] = $foaDoc['pdf_url'];
                $dbData['document_date_time'] = date("Y-m-d H:i:s");

                //** Audit report geneation **/
                $auditReportPayload = $this->documentGenerationService->getAuditPayload($row);
                // To generate the Audit Report docs
                $auditPdf = $this->documentGenerationService->generateDocument($auditReportPayload);
                $dbData['audit_pdf_url'] = $auditPdf['pdf_url'];
                $dbData['status'] = Lead::STATUS_DOCUMENTED;
                $leadId = $row['id'];
                if ($leadId) {
                    $updateLead = $this->leadRepository->update($leadId, $dbData);
                    echo 'document generated successfully!';
                    exit;
                }
            }
        }
    }
    /**
     * Returns the dynamic content of the email as HTML for email sending
     * @param Object $object of arrays
     * @param string $text emailTemplate
     * @param string $shortenURL unique short URL
     * @param string $unsubscribeLink unique short URL for unsubscribe
     * @return string $text dynamic email template
     * Author: Arman Saleem
     * Date: 24 Aug, 2023
     */
    private function getText(Object $object, string $text, string $shortenURL = '', string $unsubscribeLink = ''): string
    {
        $object = (object)$object->getAttributes();
        foreach ($object as $key => $value) {
            $text = str_replace("[[" . $key . "]]", $value, $text);
            $text = str_replace("[[LINK0]]", $shortenURL, $text);
            $text = str_replace("[[unsubscribe]]", $unsubscribeLink, $text);
        }
        return $text;
    }
}
