<?php

Route::get('/', function () {
    return redirect('/projects');
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('projects', 'ProjectsController');
	
	Route::post('/projects/{project}/tasks', 'ProjectTasksController@store');
	Route::patch('/projects/{project}/tasks/{task}', 'ProjectTasksController@update');
	
	Route::get('/home', 'HomeController@index')->name('home');
});

Auth::routes();
