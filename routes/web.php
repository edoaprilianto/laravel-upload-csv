<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;


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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [UploadController::class, 'index']);

Route::get('testevent', [UploadController::class, 'testevent']);
Route::post('uploadFile', [UploadController::class, 'upload_csv_records'])->name('uploadFile');

Route::get('getdata', [UploadController::class, 'getdata'])->name('getdata');


