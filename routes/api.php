<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HtmlTableRowController;

Route::get('html-table', [HtmlTableRowController::class, 'saveTableToDatabase']);
