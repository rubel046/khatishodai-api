<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
   // return $router->app->version();
    return "Welcome To Tizaara API";
});

// API route group account
$router->group(['prefix' => 'account','middleware'=>'InputTrim'], function () use ($router) {
    // Matches "/account
    $router->get('test', function(){ echo 'hi.... api'; });
    $router->post('register', 'AuthController@register');
    $router->get('verify-token/{id}/{token}', 'AuthController@registerTokenVerification');
    $router->post('login', 'AuthController@login');
    $router->post('forget-password', 'PasswordController@forgetPassword');
    $router->get('password-reset/{id}/{verify_token}', 'PasswordController@resetPassword');
    $router->post('password-reset-save', 'PasswordController@resetPasswordSave');
    $router->post('logout', 'AuthController@logout');
    $router->get('profile', 'UserController@profile');
    $router->get('users/{id}', 'UserController@singleUser');
    $router->get('{id}/show', 'UserController@singleUser');
    $router->get('list', 'UserController@allUsers');
    $router->post('verify-otp', 'AuthController@verifyOtp');
    $router->get('test-otp/{phone}', 'AuthController@testOtp');

});

// API route group Business Types
$router->group(['prefix' => 'btype','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'BusinessTypeController@index');
    $router->post('create', 'BusinessTypeController@store');
    $router->get('{id}/show', 'BusinessTypeController@show');
    $router->post('{id}/update','BusinessTypeController@update');
    $router->post('search','BusinessTypeController@search');
    $router->delete('{id}/delete','BusinessTypeController@destroy');

});

// API route group Brands
$router->group(['prefix' => 'brand','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'BrandController@index');
    $router->post('create', 'BrandController@store');
    $router->get('{id}/show', 'BrandController@show');
    $router->post('{id}/update','BrandController@update');
    $router->post('search','BrandController@search');
    $router->delete('{id}/delete','BrandController@destroy');

});

// API route group product/attribute/group
$router->group(['prefix' => 'product/attribute/group','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'AttributeGroupController@index');
    $router->post('create', 'AttributeGroupController@store');
    $router->get('{id}/show', 'AttributeGroupController@show');
    $router->post('{id}/update','AttributeGroupController@update');
    $router->post('search','AttributeGroupController@search');
    $router->delete('{id}/delete','AttributeGroupController@destroy');

});

// API route group product/attribute
$router->group(['prefix' => 'product/attribute','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'AttributeController@index'); //$router->get('/', 'AttributeController@index');
    $router->post('create', 'AttributeController@store'); // $router->post('/', 'AttributeController@store');
    $router->get('{id}/show', 'AttributeController@show'); //$router->get('{id}', 'AttributeController@show');
    $router->post('{id}/update','AttributeController@update');//$router->post('{id}/update','AttributeController@update');
    $router->delete('{id}/delete','AttributeController@destroy');// $router->delete('{id}','AttributeController@destroy');
    $router->post('search','AttributeController@search');

});

// API route Company
$router->group(['prefix' => 'company','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'CompanyController@index');
    $router->post('create', 'CompanyController@store');
    $router->get('{id}/show', 'CompanyController@show');
    $router->post('{id}/update','CompanyController@update');
    $router->post('search','CompanyController@search');
    $router->delete('{id}/delete','CompanyController@destroy');

});

// API route Category
$router->group(['prefix' => 'category','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'CategoryController@index');
    $router->post('create', 'CategoryController@store');
    $router->get('{id}/show', 'CategoryController@show');
    $router->post('{id}/update','CategoryController@update');
    $router->post('search','CategoryController@search');
    $router->delete('{id}/delete','CategoryController@destroy');

});



// API route group mail
$router->group(['prefix' => 'mail'], function () use ($router) {
    $router->get('send-test-email', 'EmailController@sendTestEmail');
    $router->get('user-email', 'UserController@sendEmail');
    $router->post('forget-password', 'PasswordController@forgetPassword');

});

// API route country
$router->group(['prefix' => 'country','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'CountryController@index');
    $router->post('create', 'CountryController@store');
    $router->get('{id}/show', 'CountryController@show');
    $router->post('{id}/update','CountryController@update');
    $router->post('search','CountryController@search');
    $router->delete('{id}/delete','CountryController@destroy');

});

// API route zone
$router->group(['prefix' => 'zone','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'ZoneController@index');
    $router->post('create', 'ZoneController@store');
    $router->get('{id}/show', 'ZoneController@show');
    $router->post('{id}/update','ZoneController@update');
    $router->post('search','ZoneController@search');
    $router->delete('{id}/delete','ZoneController@destroy');

});

// API route menu operation
$router->group(['prefix' => 'menu_operations','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'MenuOperationController@index');
    $router->post('create', 'MenuOperationController@store');
    $router->get('{id}/show', 'MenuOperationController@show');
    $router->post('{id}/update','MenuOperationController@update');
    $router->post('search','MenuOperationController@search');
    $router->delete('{id}/delete','MenuOperationController@destroy');

});

// API route company certificates
$router->group(['prefix' => 'comp_certificates','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'CompanyCertificateController@index');
    $router->post('create', 'CompanyCertificateController@store');
    $router->get('{id}/show', 'CompanyCertificateController@show');
    $router->post('{id}/update','CompanyCertificateController@update');
    $router->post('search','CompanyCertificateController@search');
    $router->delete('{id}/delete','CompanyCertificateController@destroy');

});

// API route company Details
$router->group(['prefix' => 'comp_details','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'CompanyDetailsController@index');
    $router->post('create', 'CompanyDetailsController@store');
    $router->get('{id}/show', 'CompanyDetailsController@show');
    $router->post('{id}/update','CompanyDetailsController@update');
    $router->post('search','CompanyDetailsController@search');
    $router->delete('{id}/delete','CompanyDetailsController@destroy');

});

// API route company Factory
$router->group(['prefix' => 'comp_factories','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'CompanyFactoriesController@index');
    $router->post('create', 'CompanyFactoriesController@store');
    $router->get('{id}/show', 'CompanyFactoriesController@show');
    $router->post('{id}/update','CompanyFactoriesController@update');
    $router->post('search','CompanyFactoriesController@search');
    $router->delete('{id}/delete','CompanyFactoriesController@destroy');

});

// API route company Factory
$router->group(['prefix' => 'comp_nearest_ports','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'CompanyNearestPortsController@index');
    $router->post('create', 'CompanyNearestPortsController@store');
    $router->get('{id}/show', 'CompanyNearestPortsController@show');
    $router->post('{id}/update','CompanyNearestPortsController@update');
    $router->post('search','CompanyNearestPortsController@search');
    $router->delete('{id}/delete','CompanyNearestPortsController@destroy');

});

// API route company Photos
$router->group(['prefix' => 'comp_photos','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'CompanyPhotosController@index');
    $router->post('create', 'CompanyPhotosController@store');
    $router->get('{id}/show', 'CompanyPhotosController@show');
    $router->post('{id}/update','CompanyPhotosController@update');
    $router->post('search','CompanyPhotosController@search');
    $router->delete('{id}/delete','CompanyPhotosController@destroy');

});

// API route company Products
$router->group(['prefix' => 'comp_products','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'CompanyProductsController@index');
    $router->post('create', 'CompanyProductsController@store');
    $router->get('{id}/show', 'CompanyProductsController@show');
    $router->post('{id}/update','CompanyProductsController@update');
    $router->post('search','CompanyProductsController@search');
    $router->delete('{id}/delete','CompanyProductsController@destroy');

});

// API route company trade_infos
$router->group(['prefix' => 'comp_trade_infos','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'CompanyTradeInfosController@index');
    $router->post('create', 'CompanyTradeInfosController@store');
    $router->get('{id}/show', 'CompanyTradeInfosController@show');
    $router->post('{id}/update','CompanyTradeInfosController@update');
    $router->post('search','CompanyTradeInfosController@search');
    $router->delete('{id}/delete','CompanyTradeInfosController@destroy');

});

// API route company trade membership
$router->group(['prefix' => 'comp_trade_memberships','middleware'=>'InputTrim'], function () use ($router) {
    $router->get('list', 'CompanyTadeMembershipsController@index');
    $router->post('create', 'CompanyTadeMembershipsController@store');
    $router->get('{id}/show', 'CompanyTadeMembershipsController@show');
    $router->post('{id}/update','CompanyTadeMembershipsController@update');
    $router->post('search','CompanyTadeMembershipsController@search');
    $router->delete('{id}/delete','CompanyTadeMembershipsController@destroy');

});