<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ShortUrlController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () { return redirect()->route('login'); });
Route::get('/admin', function () { return redirect()->route('login'); });

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/loginsubmit', [AuthController::class, 'loginsubmit'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:web']], function () { 
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('permissions', PermissionController::class); 
    Route::resource('/permissions', PermissionController::class); 
    Route::post('/permissionListAjax', [PermissionController::class, 'permissionListAjax'])->name('permissions.permissionListAjax');
    Route::post('/changeStatusPermission', [PermissionController::class,'changeStatusPermission'])->name('permissions.changeStatusPermission');
    Route::post('/deletePermission', [PermissionController::class, 'deletePermission'])->name('permissions.deletePermission');

    Route::resource('roles', RoleController::class); 
    Route::post('/roleListAjax', [RoleController::class, 'roleListAjax'])->name('roles.roleListAjax');
    Route::post('/changeStatusRole', [RoleController::class,'changeStatusRole'])->name('roles.changeStatusRole');
    Route::post('/deleteRole', [RoleController::class, 'deleteRole'])->name('roles.deleteRole');

    Route::post('userListAjax', [UserController::class, 'userListAjax'])->name('users.userListAjax');
    Route::post('/changeStatusUser', [UserController::class, 'changeStatusUser'])->name('users.changeStatusUser');
    Route::post('/deleteUser', [UserController::class, 'deleteUser'])->name('users.deleteUser');
    Route::resource('users', UserController::class);
    Route::post('/inviteUser', [UserController::class, 'inviteUser'])->name('users.inviteUser');

    Route::resource('companies', CompanyController::class); 
    Route::resource('/companies', CompanyController::class); 
    Route::post('/companyListAjax', [CompanyController::class, 'companyListAjax'])->name('companies.companyListAjax');
    Route::post('/changeStatusCompany', [CompanyController::class,'changeStatusCompany'])->name('companies.changeStatusCompany');
    Route::post('/deleteCompany', [CompanyController::class, 'deleteCompany'])->name('companies.deleteCompany');

    Route::resource('shortUrls', ShortUrlController::class); 
    Route::resource('/shortUrls', ShortUrlController::class); 
    Route::post('/shortUrlsListAjax', [ShortUrlController::class, 'shortUrlsListAjax'])->name('shortUrls.shortUrlsListAjax');
    Route::post('/changeStatusShortUrls', [ShortUrlController::class,'changeStatusShortUrls'])->name('shortUrls.changeStatusShortUrls');
    Route::post('/deleteShortUrl', [ShortUrlController::class, 'deleteShortUrl'])->name('shortUrls.deleteShortUrl');
});
// Route::get('/', function () {
//     return view('welcome');
// });
