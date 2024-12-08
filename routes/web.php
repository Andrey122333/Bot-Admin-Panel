<?php

use App\Http\Controllers\ApplicationFormsEditController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SurveyContoller;
use App\Http\Controllers\tg\BasicController;
use App\Http\Controllers\tg\MainController;


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
    return redirect('/admin');
});
Route::get('/settings', [SettingsController::class, 'view']);

Route::post('/settings', [SettingsController::class, 'settings']);

Route::post('/home', [HomeController::class, 'login']);

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('admin-users')->name('admin-users/')->group(static function() {
            Route::get('/',                                             'AdminUsersController@index')->name('index');
            Route::get('/create',                                       'AdminUsersController@create')->name('create');
            Route::post('/',                                            'AdminUsersController@store')->name('store');
            Route::get('/{adminUser}/impersonal-login',                 'AdminUsersController@impersonalLogin')->name('impersonal-login');
            Route::get('/{adminUser}/edit',                             'AdminUsersController@edit')->name('edit');
            Route::post('/{adminUser}',                                 'AdminUsersController@update')->name('update');
            Route::delete('/{adminUser}',                               'AdminUsersController@destroy')->name('destroy');
            Route::get('/{adminUser}/resend-activation',                'AdminUsersController@resendActivationEmail')->name('resendActivationEmail');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function () {
        Route::get('/', [HomeController::class, 'login'])->name('home');
        Route::get('/application-forms/{id}/', [ApplicationFormsEditController::class, 'view']);
        Route::get('/password', 'ProfileController@editPassword')->name('edit-password');
        Route::get('/profile', 'ProfileController@editProfile')->name('edit-profile');
        Route::get('/settings', [SettingsController::class, 'view']);
        Route::view('/tg-broadcast/{user_id}/', 'tg-broadcast');
        Route::view('/tg-broadcast', 'tg-broadcast');
        Route::post('/application-forms/{id}/save', [ApplicationFormsEditController::class, 'save']);
        Route::post('/get-webhook-info', [SettingsController::class, 'getWebhookInfo'])->name('get-webhook-info');
        Route::post('/password', 'ProfileController@updatePassword')->name('update-password');
        Route::post('/profile', 'ProfileController@updateProfile')->name('update-profile');
        Route::post('/reset-pending-update-count', [SettingsController::class, 'resetPendingUpdateCount'])->name('resetPendingUpdateCount');
        Route::post('/settings', [SettingsController::class, 'settings']);
        Route::post('/application-forms/{id}/', [ApplicationFormsEditController::class, 'save']);
        Route::post('/tg-broadcast', [BroadcastController::class, 'broadcastMessage']);
        Route::view('/application-forms', 'application-forms');
        Route::view('/bans', 'bans');
        Route::view('/geo-position', 'geo-position');
        Route::view('/surveys', 'surveys');
        Route::view('/tg-admins', 'settings/tg-admins');
        Route::view('/tg-emails', 'settings/tg-emails');
        Route::view('/users', 'users');
        Route::view('/variables', 'variables');
    });
});
