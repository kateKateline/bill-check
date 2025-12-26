<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\BillHistoryController;

Route::get('/history', [BillHistoryController::class, 'index'])->name('history');

Route::get('/', function () {
    return view('scan');
});

Route::post('/bill/upload', [BillController::class, 'store'])
    ->name('bill.upload');

Route::get('/bill/{bill}', [BillController::class, 'show'])
    ->name('bill.show');

    Route::post('/bill/{bill}/analyze', [AnalysisController::class, 'analyze'])
    ->name('bill.analyze');
