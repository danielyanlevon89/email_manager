<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmailAccountController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\OutgoingEmailsController;
use App\Http\Controllers\IncomingEmailsController;
use App\Http\Controllers\EmailDetailsController;
use App\Http\Controllers\ExecuteArtisanCommandController;
use App\Services\EmailAccountService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ProviderController;
use App\Services\SendEmailService;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\CommonSettingController;
use App\Http\Controllers\BlackListController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('splade')->group(function () {
    // Registers routes to support the interactive components...
    Route::spladeWithVueBridge();

    // Registers routes to support password confirmation in Form and Link components...
    Route::spladePasswordConfirmation();

    // Registers routes to support Table Bulk Actions and Exports...
    Route::spladeTable();

    // Registers routes to support async File Uploads with Filepond...
    Route::spladeUploads();

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/auth/{provider}/redirect', [ProviderController::class, 'redirect']);

    Route::get('/auth/{provider}/callback',[ProviderController::class, 'callback'] );

    Route::get('/run-command/{name_of_command}', ExecuteArtisanCommandController::class);

    Route::middleware(['auth','verified','add_global_variables_after_auth'])->group(function () {

        Route::resource('/templates', EmailTemplateController::class);
        Route::get('/get_template/{templatesId?}', [EmailTemplateController::class,'getTemplateContent']);

        Route::resource('/email_accounts', EmailAccountController::class);
        Route::get('check_smtp_connection/{id}', [EmailAccountService::class, 'checkSmtpConnection']);
        Route::get('check_imap_connection/{id}', [EmailAccountService::class, 'checkImapConnection']);
        Route::get('set_email_account/{id}', [EmailAccountService::class, 'setEmailAccount'])->name('setEmailAccount');
        Route::get('unset_email_account', [EmailAccountService::class, 'unsetEmailAccount'])->name('unsetEmailAccount');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::resource('/links', LinkController::class);
        Route::resource('/black_lists', BlackListController::class);
        Route::resource('/common_settings', CommonSettingController::class);

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware(['verified'])->name('dashboard');
    });

    Route::middleware(['auth','verified','has_chosen_email_account','add_global_variables_after_auth'])->group(function () {


        Route::get('incoming_emails', [IncomingEmailsController::class, 'index'])->name('incoming_emails.index');
        Route::get('incoming_email_details/show/{email}', [EmailDetailsController::class, 'incomingEmailDetails'])->name('incoming_email_details.show');
        Route::get('outgoing_email_details/show/{email}', [EmailDetailsController::class, 'outgoingEmailDetails'])->name('outgoing_email_details.show');
        Route::get('outgoing_emails', [OutgoingEmailsController::class, 'index'])->name('outgoing_emails.index');

        Route::post('send_email', [SendEmailService::class, 'sendEmail']);

        Route::get('storage/{name}', function ($name) {
            $path = storage_path($name);
            $mime = \File::mimeType($path);
            header('Content-type: ' . $mime);
            return readfile($path);
        })->where('name', '(.*)');

    });

    require __DIR__ . '/auth.php';
});
