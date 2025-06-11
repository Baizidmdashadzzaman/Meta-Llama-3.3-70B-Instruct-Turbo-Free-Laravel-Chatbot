<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
FrontendController
};



Route::get('/ai-assistant', [FrontendController::class, 'ai_assistant'])->name('front.aiassistant');
Route::post('/ai-assistant-call', [FrontendController::class, 'ai_assistant_call'])->name('front.aiassistant.call');
Route::post('/ai-assistant-chat', [FrontendController::class, 'ai_assistant_chat'])->name('front.aiassistant.chat');

