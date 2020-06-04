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
$router->group(['prefix' => 'account'], function () use ($router) {
    // Matches "/account
    $router->get('test', function () {
        echo 'hi.... api';
    });
    $router->post('register', 'AuthController@register');
    $router->get('verify-token/{id}/{token}', 'AuthController@registerTokenVerification');
    $router->post('login', 'AuthController@login');
    $router->get('login/{provider:facebook|google}', 'SocialAuthController@socialLogin');
    $router->get('login/{provider:facebook|google}/callback', 'SocialAuthController@handleProviderCallback');
    $router->post('forget-password', 'PasswordController@forgetPassword');
    $router->get('password-reset/{id}/{verify_token}', 'PasswordController@resetPassword');
    $router->post('password-reset-save', 'PasswordController@resetPasswordSave');
    $router->post('logout', 'AuthController@logout');
    $router->get('profile', 'UserController@profile');
    $router->get('users/{id}', 'UserController@show');
    $router->get('list', 'UserController@index');
    $router->post('verify-otp', 'AuthController@verifyOtp');
    $router->get('test-otp/{phone}', 'AuthController@testOtp');

});

resource('btype', 'BusinessTypeController');
resource('brand', 'BrandController');
resource('product/attribute', 'AttributeController');
resource('product/attribute_terms', 'AttributeTermsController');
resource('product/attribute_groups', 'AttributeGroupController');
resource('product/attribute_group_assigned_terms', 'AttrGroupAssignedTermsController');
resource('company', 'CompanyController');
resource('category', 'CategoryController');
resource('country', 'CountryController');
resource('division', 'DivisionController');
resource('city', 'CityController');
resource('zone', 'ZoneController');
resource('menu_operations', 'MenuOperationController');
resource('comp_certificates', 'CompanyCertificateController');
resource('comp_details', 'CompanyDetailsController');
resource('comp_factories', 'CompanyFactoriesController');
resource('comp_nearest_ports', 'CompanyNearestPortsController');
resource('comp_photos', 'CompanyPhotosController');
resource('comp_products', 'CompanyProductsController');
resource('comp_trade_infos', 'CompanyTradeInfosController');
resource('comp_trade_memberships', 'CompanyTadeMembershipsController');


// API route group mail
$router->group(['prefix' => 'mail'], function () use ($router) {
    $router->get('send-test-email', 'EmailController@sendTestEmail');
    $router->get('user-email', 'UserController@sendEmail');
    $router->post('forget-password', 'PasswordController@forgetPassword');

});
