<?php

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


Route::get('/', function () {
    return view('hackathon');
});

Route::get('/ajax/chart-data/{projectName}', 'AjaxController@chartData');

/*

Route::get('/', function () {
    return view('page');
});

Route::get('/ajax/chart-data/{projectName}', 'AjaxController@chartData');
Route::get('/ajax/chart-data/{projectName}/{sprintName}', 'AjaxController@chartDataSprint');
*/
