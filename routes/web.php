<?php

use App\Events\PrivateMessage;
use App\Events\SendMessage;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function () {
    // event(new SendMessage('hello world'));
    return 'done';
});
Route::get('test2', function () {
    event(new PrivateMessage('hello world from private', 1));
    return 'done';
});
Auth::routes();

Route::get('/room', [HomeController::class,'index']);
Route::get('/chat', [HomeController::class,'chat']);
Route::post('/submit', [HomeController::class,'store']);
// search Contact 
Route::get('/search', [ChatController::class,'search'])->name('search');

// User message
Route::get('/messages', [ChatController::class,'messages'])->name('getUserMessages');

// send message
Route::post('/send', [ChatController::class,'sendMessage'])->name('sendMessage');

