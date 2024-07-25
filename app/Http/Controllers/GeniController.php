<?php

namespace App\Http\Controllers;

use App\Services\GeniService;

class GeniController extends Controller
{
    public function cron_geni_submission(GeniService $sevice )
    {
        echo "inside"; 
        $sevice->partial_processing_flow0();
    }

   
}
