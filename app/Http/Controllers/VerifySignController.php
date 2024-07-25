<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\VerifySignService;
use App\Http\Requests\VerfySignatureRequest;
class VerifySignController extends Controller
{
    private VerifySignService $service;
    /**
     * VerifySignController constructor.
     *
     * @param \App\Services\VerifySignService $service
     */
    public function __construct(VerifySignService $service)
    {
        $this->service = $service;
    }

    /**
     * Verify Signature Source
     *
     * @param \App\Http\Requests\VerfySignatureRequest $request
     *
     * @return Response
     */
    public function verifySignature(VerfySignatureRequest $request) 
    {   
        return response($this->service->verify($request->signatureSrc));
    }
}
