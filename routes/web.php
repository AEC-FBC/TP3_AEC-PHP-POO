<?php

use App\Routes\Route;

Route::get("/", "HomeController@index");

Route::get("/tasks", "TaskController@index");
Route::get("/tasks/show", "TaskController@show");

Route::get("/tasks/create", "TaskController@create");
Route::post("/tasks/create", "TaskController@store");

Route::get("/tasks/edit", "TaskController@edit");
Route::post("/tasks/edit", "TaskController@update");

Route::post("/tasks/delete", "TaskController@delete");

Route::get("/projects", "ProjectController@index");
Route::get("/projects/show", "ProjectController@show");

Route::get("/projects/create", "ProjectController@create");
Route::post("/projects/create", "ProjectController@store");

Route::get("/projects/edit", "ProjectController@edit");
Route::post("/projects/edit", "ProjectController@update");

Route::post("/projects/delete", "ProjectController@delete");

Route::get("/contacts", "ContactController@index");

Route::get("/contacts/create", "ContactController@create");
Route::post("/contacts/create", "ContactController@store");

Route::get("/contacts/edit", "ContactController@edit");
Route::post("/contacts/edit", "ContactController@update");

Route::post("/contacts/delete", "ContactController@delete");

// Établissements
Route::get("/locations", "CompanyLocationController@index");
Route::get("/locations/create", "CompanyLocationController@create");
Route::post("/locations/create", "CompanyLocationController@store");
Route::get("/locations/edit", "CompanyLocationController@edit");
Route::post("/locations/edit", "CompanyLocationController@update");
Route::post("/locations/delete", "CompanyLocationController@delete");

// Statuts
Route::get("/statuses", "StatusController@index");
Route::get("/statuses/create", "StatusController@create");
Route::post("/statuses/create", "StatusController@store");
Route::get("/statuses/edit", "StatusController@edit");
Route::post("/statuses/edit", "StatusController@update");
Route::post("/statuses/delete", "StatusController@delete");

// Services
Route::get("/services", "ServiceController@index");
Route::get("/services/create", "ServiceController@create");
Route::post("/services/create", "ServiceController@store");
Route::get("/services/edit", "ServiceController@edit");
Route::post("/services/edit", "ServiceController@update");
Route::post("/services/delete", "ServiceController@delete");

Route::get("/companies", "CompanyController@index");
Route::get("/companies/show", "CompanyController@show");

Route::get("/companies/create", "CompanyController@create");
Route::post("/companies/create", "CompanyController@store");

Route::get("/companies/edit", "CompanyController@edit");
Route::post("/companies/edit", "CompanyController@update");

Route::post("/companies/delete", "CompanyController@delete");

Route::dispatch();
