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
    Cache::flush();
    return "Welcome To Tizaara API";
});


// API route group account
$router->group(['prefix' => 'account'], function () use ($router) {
    $router->post('send-registration-otp', 'AuthController@sendRegistrationOTP');
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');

    $router->get('verify-token/{id}/{token}', 'AuthController@registerTokenVerification');
    $router->get('login/{provider:facebook|google}', 'SocialAuthController@socialLogin');
    $router->get('login/{provider:facebook|google}/callback', 'SocialAuthController@handleProviderCallback');
    $router->post('forget-password', 'PasswordController@forgetPassword');
    $router->get('password-reset/{id}/{verify_token}', 'PasswordController@resetPassword');
    $router->post('password-reset-save', 'PasswordController@resetPasswordSave');

    $router->get('profile', 'UserController@profile');
    $router->get('users/{id}', 'UserController@show');
    $router->put('/{id}', 'UserController@update');
    $router->get('company', 'UserController@company');
    $router->get('list', 'UserController@index');
    $router->post('verify-otp', 'AuthController@verifyOtp');
    $router->get('test-otp/{phone}', 'AuthController@testOtp');
});

$router->get('config', 'ConfigController@index');
resource('menu_operations', 'MenuOperationController');
resource('btype', 'BusinessTypeController');
resource('brand', 'BrandController');
resource('category/list', 'CategoryController@allList');
resource('category', 'CategoryController');
resource('product/attribute', 'AttributeController');
resource('product/attribute_terms', 'AttributeTermsController');
resource('product/attribute_groups', 'AttributeGroupController');
resource('product/attribute_group_assigned_terms', 'AttrGroupAssignedTermsController');
resource('country', 'CountryController');
resource('division', 'DivisionController');
resource('city', 'CityController');
resource('area', 'AreaController');
$router->post('company/company_details', 'CompanyDetailsController@companyDetailsCreateOrUpdate');
$router->get('company/company_details/{company_id}', 'CompanyDetailsController@detailsByCompany');
$router->post('company/comp_products', 'CompanyProductsController@companyProductsCreateOrUpdate');
$router->get('company/comp_products/{company_id}', 'CompanyProductsController@companyProductDetails');
resource('company', 'CompanyController');
resource('comp_certificates', 'CompanyCertificateController');
resource('comp_details', 'CompanyDetailsController');
resource('comp_factories', 'CompanyFactoriesController');
resource('comp_nearest_ports', 'CompanyNearestPortsController');
resource('comp_photos', 'CompanyPhotosController');
resource('comp_products', 'CompanyProductsController');
resource('comp_trade_infos', 'CompanyTradeInfosController');
resource('comp_trade_memberships', 'CompanyTadeMembershipsController');
resource('turnover_breakdowns', 'TurnoverBreakdownController');
resource('qc_staff_breakdowns', 'QcStaffBreakdownController');
resource('rnd_staff_breakdowns', 'RndStaffBreakdownController');
resource('revenue_breakdowns', 'RevenueBreakdownController');
resource('export_percentage_breakdowns', 'ExportPercentageBreakdownController');
resource('system_config', 'SystemConfigController');

// API route group mail
$router->group(['prefix' => 'mail'], function () use ($router) {
    $router->get('send-test-email', 'EmailController@sendTestEmail');
    $router->get('user-email', 'UserController@sendEmail');
    $router->post('forget-password', 'PasswordController@forgetPassword');

});
