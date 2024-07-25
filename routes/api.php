<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckToken;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::middleware([CheckToken::class])->group(function () {
    Route::post('/signatures/verify', [\App\Http\Controllers\VerifySignController::class, 'verifySignature'])->name('signatures.verify');
    Route::post('/numbers/verify', [\App\Http\Controllers\Data8Controller::class, 'verifyNumber'])->name('numbers.verify');
    Route::post('/emails/verify', [\App\Http\Controllers\Data8Controller::class, 'verifyEmail'])->name('emails.verify');
    Route::post('/addresses/get', [\App\Http\Controllers\Data8Controller::class, 'addressLookup'])->name('addresses.get');
    Route::post('/leads/save', [\App\Http\Controllers\LeadController::class, 'saveLead'])->name('leads.save');
    Route::get('/leads/get/{id}', [\App\Http\Controllers\LeadController::class, 'getLead'])->name('leads.get');
});


//this is for dev purpose, after that if will move to web.php

Route::get('/emails/send', [\App\Http\Controllers\LeadController::class, 'sendEmail'])->name('emails.send');

Route::get('/sms/send', [\App\Http\Controllers\CampaignController::class, 'sendSms'])->name('sms.send');

Route::get('/documents/generate', [\App\Http\Controllers\LeadController::class, 'documentGeneration'])->name('documents.generate');
