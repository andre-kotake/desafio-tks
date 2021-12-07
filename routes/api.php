<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadUploadController;
use App\Http\Controllers\HtmlFormController;
use App\Http\Controllers\HtmlTableRowController;

Route::get('html-table', [HtmlTableRowController::class, 'saveTableToDatabase']);

Route::get('html-form', [HtmlFormController::class, 'fillForm']);

Route::get('download-upload', [DownloadUploadController::class, 'downloadAndUpload']);
