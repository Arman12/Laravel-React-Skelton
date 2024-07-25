<?php

namespace App\Repositories;
//use Your Model
use App\Models\Lead;

/**
 * Class LeadRepository.
 */
class LeadRepository extends BaseRepository
{

    /**
     * LeadRepository constructor.
     * Author: Arman Saleem
     * Date:07 Sep, 2023
     */
    public function __construct(Lead $lead)
    {
        parent::__construct($lead);
    }
}
