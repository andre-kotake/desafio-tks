<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadUploadController;
use App\Http\Controllers\HtmlFormController;
use App\Http\Controllers\HtmlTableRowController;
use App\Http\Controllers\PdfToCsvController;

Route::get('html-table', [HtmlTableRowController::class, 'saveTableToDatabase']);

Route::get('html-form', [HtmlFormController::class, 'fillForm']);

Route::get('download-upload', [DownloadUploadController::class, 'downloadAndUpload']);

Route::get('convert', [PdfToCsvController::class, 'convert']);
