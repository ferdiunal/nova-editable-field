<?php

use Ferdiunal\NovaEditableField\Http\Controllers\ConfirmResourceController;
use Illuminate\Support\Facades\Route;

Route::post('/confirm/{resource}/{resourceId}/{attribute}', ConfirmResourceController::class)
    ->name('nova-editable-field.confirm');
