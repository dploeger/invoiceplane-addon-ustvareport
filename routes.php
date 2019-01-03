<?php

Route::group(['prefix' => 'addons/report', 'middleware' => ['web', 'auth.admin'], 'namespace' => 'Addons\UstvaReport\Controllers'], function () {
    Route::get('ustva', ['uses' => 'UstvaController@index', 'as' => 'addons.reports.ustva.options']);
    Route::post('ustva/validate', ['uses' => 'UstvaController@validateOptions', 'as' => 'addons.reports.ustva.validate']);
    Route::get('ustva/html', ['uses' => 'UstvaController@html', 'as' => 'addons.reports.ustva.html']);
    Route::get('ustva/pdf', ['uses' => 'UstvaController@pdf', 'as' => 'addons.reports.ustva.pdf']);
});