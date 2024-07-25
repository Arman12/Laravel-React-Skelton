<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmailService;


class EmailController extends Controller
{

    /**
     * Returns the number validation on the basis of given number. To verify Number
     * @param \App\Services\EmailService $emailService
     * Author: Arman Saleem
     * Date: 31 Aug, 2023
     */
    public function sendEmail(EmailService $emailService)
    {

        $sendTo = '';
        $emailSubject = 'Test Email via Mailgun API';
        $emailMessage = 'This is a test email sent using the Mailgun API with plain PHP.';

        $emailService->to($sendTo)->subject($emailSubject)->message($emailMessage)->sendEmail();
    }

    /**
     * Returns the number validation on the basis of given number. To verify Number
     * @param \App\Services\EmailService $emailService
     * Author: Arman Saleem
     * Date: 31 Aug, 2023
     */
    public function sendEmailWithAttachment(EmailService $emailService)
    {
        $sendTo = '';
        $emailSubject = 'Test Email via Mailgun API';
        $emailMessage = 'This is a test email sent using the Mailgun API with plain PHP.';
        $attachmentPath = 'documents/pdf/Events.pdf';
        $res = $emailService->to($sendTo)->subject($emailSubject)->message($emailMessage)->sendEmailWithAttachment($attachmentPath);
    }
}
