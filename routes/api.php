<?php

use App\Http\Controllers\Api\ExpenseApiController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('expenses', ExpenseApiController::class);
});

