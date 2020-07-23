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

Route::redirect('/', '/login');

// Dashboard routes
Route::get('/manage-oauth', 'ManageOAuthController@index')->name('manage-oauth');
Route::get('/sources', 'SourceController@index')->name('sources');
Route::get('/users', 'UserController@index')->name('users');

Route::get('/lang/{lang}', 'LocaleController');

Route::namespace('Dashboard')->group(function () {

    // Dashboard web routes
    Auth::routes(['verify' => true]);
    Route::get('/auth/closed', 'Auth\RegisterController@showRegistrationClosed');
    Route::get('/auth/success', 'Auth\RegisterController@showRegistrationSuccess');

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/maps', 'MapsController@index')->name('maps');
    Route::get('/manage-oauth', 'ManageOAuthController@index')->name('manage-oauth');
    Route::get('/users', 'UserController@index')->name('users');
    Route::get('/sources', 'SourceController@index')->name('sources');
    Route::get('/account/password', 'Auth\UpdatePasswordController@index')
        ->name('account.password');
    Route::post('/account/password/update', 'Auth\UpdatePasswordController@updatePassword')
        ->name('account.password.update');

    // retention policy
    Route::get('/retention', 'RetentionPolicyController@index')->name('retention');

    // Imprint
    Route::get('/imprint/edit', 'ImprintController@edit')->middleware(['auth', 'is_admin']);
    Route::get('/imprint', 'ImprintController@index')->name('imprint');

    // Privacy Policy
    Route::get('/privacy/edit', 'PrivacyPolicyController@edit')->middleware(['auth', 'is_admin']);
    Route::get('/privacy', 'PrivacyPolicyController@index')->name('privacy');

    // Dashboard web api routes
    Route::group(['middleware' => 'expects_json'], function () {
        // users
        Route::get('/users/all', 'UserController@users');
        Route::delete('/users/delete/', 'UserController@delete')->name('deleteUser');
        Route::post('/users/role', 'UserController@role')->name('users.role');

        // sources
        Route::get('/sources/all', 'SourceController@sources');
        Route::delete('/sources/delete/{id}', 'SourceController@delete');
        Route::post('/sources/new', 'SourceController@addSource');
        Route::put('/sources/update', 'SourceController@update');

        // categories
        Route::get('/categories/all', 'CategoryController@categories');
        Route::delete('/categories/delete/{id}', 'CategoryController@delete');
        Route::post('/categories/new', 'CategoryController@addCategory');
        Route::put('/categories/update', 'CategoryController@update');

        // imprint
        Route::post('/imprint/update', 'ImprintController@update')
            ->middleware(['auth', 'is_admin']);
        Route::get('/imprint/get', 'ImprintController@get');

        // privacy policy
        Route::post('/privacy/update', 'PrivacyPolicyController@update')
            ->middleware(['auth', 'is_admin']);
        Route::get('/privacy/get', 'PrivacyPolicyController@get');

        // retention policy
        Route::get('/retention/get', 'RetentionPolicyController@get')
            ->name('retention.get');
        Route::post('/retention/update', 'RetentionPolicyController@update')
            ->name('retention.update');
    });
});
