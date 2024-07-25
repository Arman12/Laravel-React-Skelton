<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PhoneNumberRequest;
use App\Http\Requests\PostcodeRequest;
use App\Http\Requests\EmailVerificatioRequest;
use App\Services\Data8Service;

class Data8Controller extends Controller
{
    private Data8Service $service;
    /**
     * Data8Controller constructor.
     * @param \App\Services\Data8Service $service
     * Author: Arman Saleem
     * Date: 31 Aug, 2023
     */
    public function __construct(Data8Service $service)
    {
        $this->service = $service;
    }

    /**
     * Returns the number validation on the basis of given number. To verify Number
     * @param \App\Http\Requests\PhoneNumberRequest $request
     * @return Response
     * Author: Arman Saleem
     * Date: 31 Aug, 2023
     */
    public function verifyNumber(PhoneNumberRequest $request)
    {
        /*** mobile validation service ***/
        // return (response($this->successResponse('Hello this Success')));
        // exit;
        return response($this->service->verifyMobileNumber($request->phoneNumber));

        /*** mobile/landline validation service ***/
        //** pass true if you want to validate mobile and landline both in same call **/
        // return response($this->service->verifyNumber($request->phoneNumber, true));
    }

    /**
     * Returns the addresses on the basis of postcode
     * @param \App\Http\Requests\PostcodeRequest $request
     * @return Response
     * Author: Arman Saleem
     * Date: 31 Aug, 2023
     */
    public function addressLookup(PostcodeRequest $request)
    {
        /*** mobile validation service ***/
        return response($this->service->getFullAddress($request->postcode));
    }

    /**
     * Returns email validations on the basis of given email to verify email address
     * @param \App\Http\Requests\EmailVerificatioRequest $request
     * @return Response
     * Author: Arman Saleem
     * Date: 31 Aug, 2023
     */
    public function verifyEmail(EmailVerificatioRequest $request)
    {
        /*** Email validation service ***/
        return response($this->service->emailDomainVerification($request->email));
    }
}
