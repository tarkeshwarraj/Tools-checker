<?php

use App\Http\Controllers\HelpController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\SMTPCheckController;

Route::get('/', function () {
    return view('welcome');
})->name('smtp-check');

Route::post('/check-smtp', [SMTPCheckController::class, 'checkSMTP']);
Route::post('/smtp-check/stop', [SMTPCheckController::class, 'stopSMTPCheck']);  // Stop route

Route::get('/sendmail', function(){
    return view('sendMail');
})->name('send-mail');

Route::post('/send-mail', [MailController::class, 'sendMail']);


Route::get('/help', function(){
    return view('comments');
})->name('comment');

Route::post('/save-comment', [HelpController::class, 'store']);

Route::get('/get-comments', [HelpController::class, 'getComments']);

