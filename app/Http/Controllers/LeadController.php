<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LeadService;
use App\Http\Requests\LeadsRequest;


class LeadController extends Controller
{
    private LeadService $leadService;

    /**
     * Data8Controller constructor.
     * @param \App\Services\LeadService $service
     * Author: Arman Saleem
     * Date: 24 Aug, 2023
     */
    public function __construct(LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    /**
     * Save leads to save the leads into the DB for the provided request after validate
     * @param \App\Http\Requests\LeadsRequest $request
     * @return Response
     * Author: Arman Saleem
     * Date: 24 Aug, 2023
     */
    public function saveLead(LeadsRequest $request)
    {
        $data = $request->all();
        return response($this->leadService->saveLead($data));
    }

    /**
     * Returns the lead
     *
     * @param id [lead id]
     *
     * @return Response leads row data
     * 
     * Author: Arman Saleem
     * Date: 12 Sep, 2023
     */
    public function getLead($id)
    {
        return response($this->leadService->getLead($id));
    }

    /**
     * Returns the response of email sending functionality, used lead service to send emails.
     * @return Response
     * Author: Arman Saleem
     * Date: 31 Aug, 2023
     */
    public function sendEmail()
    {
        return response($this->leadService->sendEmail());
    }  
    
    /**
     * Document generation function to generate the documents, used lead service
     * @return Response
     * Author: Arman Saleem
     * Date: 07 Sep, 2023
     */
    public function documentGeneration()
    {
        return response($this->leadService->documentGeneration());
    }
}
