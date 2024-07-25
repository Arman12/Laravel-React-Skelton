<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\SmsTemplateController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GeniController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('generate-link/{id}', [App\Http\Controllers\HomeController::class, 'generateLink']);

Route::group(['middleware' => ['auth']], function () {
    
    Route::get('dashboard', [AdminController::class, 'dashboard']);
    Route::post('dashboard', [AdminController::class, 'dashboard']);
    Route::get('/download-csv', [AdminController::class, 'downloadCSV'])->name('download.csv');
    Route::get('/upload-csv', [AdminController::class, 'uploadCsvIndex'])->name('upload.csv.index');
    Route::post('/upload-csv', [AdminController::class, 'uploadCSV'])->name('upload.csv');
    // email template routes
    Route::get('/email-template', [EmailTemplateController::class, 'index'])->name('email.index');
    Route::get('/email-template-create', [EmailTemplateController::class, 'create'])->name('email.create');
    Route::post('/email-template-store', [EmailTemplateController::class, 'store'])->name('email.store');
    Route::get('/email-template-edit/{emailTemplate}', [EmailTemplateController::class, 'edit'])->name('email.edit');
    Route::post('/email-template-update/{emailTemplate}', [EmailTemplateController::class, 'update'])->name('email.update');
    Route::delete('/email-template-delete/{emailTemplate}', [EmailTemplateController::class, 'delete'])->name('email.delete');
    // sms template routes
    Route::get('/sms-template', [SmsTemplateController::class, 'index'])->name('sms.index');
    Route::get('/sms-template-create', [SmsTemplateController::class, 'create'])->name('sms.create');
    Route::post('/sms-template-store', [SmsTemplateController::class, 'store'])->name('sms.store');
    Route::get('/sms-template-edit/{smsTemplate}', [SmsTemplateController::class, 'edit'])->name('sms.edit');
    Route::post('/sms-template-update/{smsTemplate}', [SmsTemplateController::class, 'update'])->name('sms.update');
    Route::delete('/sms-template-delete/{smsTemplate}', [SmsTemplateController::class, 'delete'])->name('sms.delete');
    Route::get('logout', [AuthController::class, 'logout']);

     // Campaign routes
     Route::get('/campaign', [CampaignController::class, 'index'])->name('campaign.index');
     Route::get('/campaign-create', [CampaignController::class, 'create'])->name('campaign.create');
     Route::post('/campaign-store', [CampaignController::class, 'store'])->name('campaign.store');
     Route::get('/campaign-edit/{campaign}', [CampaignController::class, 'edit'])->name('campaign.edit');
     Route::post('/campaign-update/{campaign}', [CampaignController::class, 'update'])->name('campaign.update');
     Route::delete('/campaign-delete/{campaign}', [CampaignController::class, 'delete'])->name('campaign.delete');
     
    // regenerated docs route
    Route::get('regenerate-docs', [AdminController::class, 'getRegenertedDocs'])->name('docs.index');
    Route::post('regenerate-docs', [AdminController::class, 'getRegenertedDocs']);
    Route::get('regenerate-docs/edit/{dashboard}', [AdminController::class, 'editRegenertedDocs'])->name('docs.edit');
    Route::post('regenerate-docs/update/{dashboard}', [AdminController::class, 'updateRegenertedDocs'])->name('docs.update');

     Route::get('logout', [LoginController::class, 'logout'])->name('logout');
});
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::get('/storage', function () {
    Artisan::call('storage:link');
    return "Storage link";
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear'); //storage:link
    // Artisan::call('route:cache');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    // Artisan::call('storage:link');
    echo "Cache cleared";
});
Route::get('/migrate', function () {
    Artisan::call('migrate');
    echo "done";
});

Route::get('/optimize', function () {
    Artisan::call('optimize');
    return 'optimize';
});

Route::get('/test-root', function () {
    return 'hello this is test route for Root';
});

Route::get('/cron_geni', [GeniController::class, 'cron_geni_submission']);

// Redirection check for except sub-folders
Route::get('/{any}', function ($any) {
    if ($any != 'check1') {
        return view('welcome');
    }
})->where('any', '.*');

