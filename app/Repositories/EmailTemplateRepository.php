<?php

namespace App\Repositories;
//use Your Model
use App\Models\EmailTemplate;
// use App\Http\Resources\Api\AboutResource;

/**
 * Class EmailTemplateRepository.
 */
class EmailTemplateRepository extends BaseRepository
{

    public function __construct(EmailTemplate $emailTemplate)
    {
        parent::__construct($emailTemplate);
    }

    // Add more methods.
}
