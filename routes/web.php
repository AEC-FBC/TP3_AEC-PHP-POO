<?php

use App\Routes\Route;


Route::get("/", "HomeController@index");
// Authentification
Route::get("/login", "AuthController@login");
Route::post("/login", "AuthController@login");
Route::post("/logout", "AuthController@logout", "auth");
// Administration
Route::get("/admin", "AdminController@index", "admin");
Route::get("/admin/logs", "VisitLogController@index", "admin");
// Gestion des utilisateurs
Route::get("/admin/users", "UserController@index", "admin");

Route::get("/admin/users/create", "UserController@create", "admin");
Route::post("/admin/users/create", "UserController@store", "admin");

Route::get("/admin/users/edit", "UserController@edit", "admin");
Route::post("/admin/users/edit", "UserController@update", "admin");

Route::post("/admin/users/delete", "UserController@delete", "admin");


// Tâches
Route::get("/tasks", "TaskController@index", "client");
Route::get("/tasks/show", "TaskController@show", "client");

Route::get("/tasks/create", "TaskController@create", "client");
Route::post("/tasks/create", "TaskController@store", "client");

Route::get("/tasks/edit", "TaskController@edit", "client");
Route::post("/tasks/edit", "TaskController@update", "client");

Route::post("/tasks/delete", "TaskController@delete", "client");


// Projets
Route::get("/projects", "ProjectController@index", "client");
Route::get("/projects/show", "ProjectController@show", "client");

// Création : administrateur uniquement
Route::get("/projects/create", "ProjectController@create", "admin");
Route::post("/projects/create", "ProjectController@store", "admin");

// Modification : client et administrateur
Route::get("/projects/edit", "ProjectController@edit", "client");
Route::post("/projects/edit", "ProjectController@update", "client");

// Suppression : administrateur uniquement
Route::post("/projects/delete", "ProjectController@delete", "admin");


// Contacts
Route::get("/contacts", "ContactController@index", "client");

// Création : administrateur uniquement
Route::get("/contacts/create", "ContactController@create", "admin");
Route::post("/contacts/create", "ContactController@store", "admin");

// Modification : client et administrateur
Route::get("/contacts/edit", "ContactController@edit", "client");
Route::post("/contacts/edit", "ContactController@update", "client");

// Suppression : administrateur uniquement
Route::post("/contacts/delete", "ContactController@delete", "admin");


// Établissements
Route::get("/locations", "CompanyLocationController@index", "client");

Route::get("/locations/create", "CompanyLocationController@create", "admin");
Route::post("/locations/create", "CompanyLocationController@store", "admin");

Route::get("/locations/edit", "CompanyLocationController@edit", "client");
Route::post("/locations/edit", "CompanyLocationController@update", "client");

Route::post("/locations/delete", "CompanyLocationController@delete", "admin");


// Statuts
Route::get("/statuses", "StatusController@index", "admin");

Route::get("/statuses/create", "StatusController@create", "admin");
Route::post("/statuses/create", "StatusController@store", "admin");

Route::get("/statuses/edit", "StatusController@edit", "admin");
Route::post("/statuses/edit", "StatusController@update", "admin");

Route::post("/statuses/delete", "StatusController@delete", "admin");

// Services
Route::get("/services", "ServiceController@index", "admin");

Route::get("/services/create", "ServiceController@create", "admin");
Route::post("/services/create", "ServiceController@store", "admin");

Route::get("/services/edit", "ServiceController@edit", "admin");
Route::post("/services/edit", "ServiceController@update", "admin");

Route::post("/services/delete", "ServiceController@delete", "admin");


// Entreprises
Route::get("/companies", "CompanyController@index", "client");
Route::get("/companies/show", "CompanyController@show", "client");

// Création : administrateur uniquement
Route::get("/companies/create", "CompanyController@create", "admin");
Route::post("/companies/create", "CompanyController@store", "admin");

// Modification : client et administrateur
Route::get("/companies/edit", "CompanyController@edit", "client");
Route::post("/companies/edit", "CompanyController@update", "client");

// Suppression : administrateur uniquement
Route::post("/companies/delete", "CompanyController@delete", "admin");

// Exécution du routeur
Route::dispatch();
