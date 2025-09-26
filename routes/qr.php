<?php
use App\Http\Controllers\QrController;

Route::middleware('auth')->group(function () {
    //Affichage du form
    Route::get('/generate', [QrController::class, 'index'])->name('qr.form');
    //Génération du qr code
    Route::post('/generate-qr', [QrController::class, 'generateQr'])->name('qr.generate');

    Route::view('/qr/scan', 'qr.scan')->name('qr.scan');
    Route::post('/qr/store', [App\Http\Controllers\QrController::class, 'store'])->name('qr.store');
});
