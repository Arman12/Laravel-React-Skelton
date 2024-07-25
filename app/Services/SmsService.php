<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Services\Base\BaseSmsService;
use App\Repositories\SmsRepository;
use App\Repositories\LeadRepository;


use App\Traits\Encryption;
use App\Traits\Message;





class SmsService extends BaseSmsService
{


    private $smsRepository;
    private $leadRepository;
    public function __construct(
        SmsRepository $smsRepository,
        LeadRepository $leadRepository
    )
    {
        parent::__construct();
        $this->smsRepository = $smsRepository;
        $this->leadRepository = $leadRepository;

    }

    use Message;
    use Encryption;

    /**
     * @return array $response
     */
    public function send(){

        $template = $this->smsRepository->find(1);
        if(!$template)
            return response()->json([
                'status' => true,
                'message' => 'Template not found!'
            ], Response::HTTP_NOT_FOUND);
        $leads = $this->leadRepository->findAll();

        if(!$leads)
        return response()->json([
            'status' => true,
            'message' => 'Lead not found!'
        ], Response::HTTP_NOT_FOUND);

        $tempalteID =  $template->id;
        $templateText = $template->description;
        foreach ($leads as $lead) {
            $id = $lead->id;
            $encLeadId = $this->encryptId($id);
            $plainLink = $this->getPlainLink($encLeadId, 'sms', $tempalteID);
            $preparedText = $this->replaceDynamicAttributes($lead, $templateText, $plainLink);
        }


        $url = $this->getUrl();
        dd($url);
    }
}
