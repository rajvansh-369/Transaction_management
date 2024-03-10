<?php

use App\Http\Controllers\PDFController;
use App\Http\Controllers\Cronjobs\CronjobController;
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

   return redirect('/admin');
    // return view('welcome');
});


Route::get('generate-pdf/{id}', [PDFController::class, 'generatePDF'])->name('pdf');
Route::get('add-tax', [CronjobController::class, 'addTaxOnLedger'])->name('ledger.tax');
Route::get('adjust-invoice', [CronjobController::class, 'adjustInvoice'])->name('adjustInvoice');
Route::post('adjust-invoice', [CronjobController::class, 'adjustInvoicePost'])->name('adjustInvoicePost');
