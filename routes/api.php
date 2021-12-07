<?php

use App\Http\Controllers\HtmlDownloadUploadController;
use App\Http\Controllers\HtmlFormController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HtmlTableRowController;

Route::get('html-table', [HtmlTableRowController::class, 'saveTableToDatabase']);

Route::get('html-form', [HtmlFormController::class, 'fillForm']);

Route::get('download', [HtmlDownloadUploadController::class, 'download']);
