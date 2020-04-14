<?php


Route::get('incoming.php','SMS\SmsController@incoming');
Route::get('rectify.php','SMS\SmsController@rectify');
Route::get('incomingcall.php' ,'SMS\SmsController@incomingCall');

Route::post('sendShopSMS' ,'SMS\SmsController@sendShopSMS');
Route::post('assessData' ,'Assessment\AssessmentController@assessData');
Route::post('shopData' ,'CovidConfig\ShopController@shopData');

Route::post('login', 'PageController@login')->middleware('guest');
Route::get('logout', 'PageController@logout')->middleware('auth');

Route::group(['prefix' => 'api', 'middleware' => 'auth'], function() {

    Route::get('dashboard', 'DashboardController@index');

    Route::resource('covid_complaint', 'Complaint\CovidComplaintController');
    Route::resource('covid_config', 'CovidConfig\CovidConfigController');  
    Route::resource('assessment', 'Assessment\AssessmentController');  


    Route::group(['prefix' => 'search'], function() {       
        Route::get('complaintType', 'ComplaintTypeController@search');
        Route::get('complaintStatus', 'Complaint\ComplaintStatusController@search');
        Route::get('Covid19Category', 'CovidConfig\Covid19CategoryController@search');
    });

    // admin routes
    Route::group(['middleware' => 'admin'], function() {
        Route::get('settings', 'SettingsController@show');
        Route::post('settings', 'SettingsController@store');
        Route::resource('users', 'Settings\UserController');
   });


});


Route::get('{vue?}', 'PageController@index')->where('vue', '[\/\w\.-]*');

