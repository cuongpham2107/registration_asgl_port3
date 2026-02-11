<?php

use Illuminate\Support\Facades\Route;



Route::get('registration-vehicles-form', \App\Livewire\RegistrationVehicleForm::class)->name('registration-vehicles-form');


Route::get('registration-success', function () {
    return view('registration-success');
})->name('registration-success');

Route::get('template-file/registration-vehicles', function () {
    return 'file contents here';
})->name('template.file.registration-vehicles');