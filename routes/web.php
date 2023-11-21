<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// Route::redirect('/', '/dashboard-general-dashboard');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::get('/register', [LoginController::class, 'showRegister'])->middleware('guest');
Route::post('/register', [LoginController::class, 'register'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

Route::group(['middleware' => ['auth', 'isActiveUser']], function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'outlet', 'as' => 'outlet.', 'middleware' => 'role:Super Admin'], function(){
        Route::get('/', [OutletController::class, 'index'])->name('index');
        Route::get('/get-data', [OutletController::class, 'getData'])->name('getData');
        Route::post('/store', [OutletController::class, 'store'])->name('store');
        Route::get('/desync-outlet', [OutletController::class, 'desyncOutlet'])->name('desyncOutlet');
        Route::get('/{outlet}', [OutletController::class, 'show'])->name('show');
        Route::post('/{outlet}/update', [OutletController::class, 'update'])->name('update');
        Route::post('/{outlet}/delete', [OutletController::class, 'delete'])->name('delete');
        Route::get('/{outlet}/sync-outlet', [OutletController::class, 'syncOutlet'])->name('syncOutlet');
    });

    Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => 'role:Super Admin|Owner|Staff'], function(){
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::post('/profile', [UserController::class, 'changeProfile'])->name('changeProfile');
    });

    Route::middleware('hasOutlet')->group(function (){
        Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => 'role:Super Admin|Owner'], function(){
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/get-data', [UserController::class, 'getData'])->name('getData');
            Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggleStatus');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::post('/{user}/update', [UserController::class, 'update'])->name('update');
            Route::post('/{user}/delete', [UserController::class, 'delete'])->name('delete');
        });

        Route::group(['prefix' => 'supplier', 'as' => 'supplier.'], function() {
            Route::get('/', [SupplierController::class, 'index'])->name('index');
            Route::get('/get-data', [SupplierController::class, 'getData'])->name('getData');
            Route::post('/store', [SupplierController::class, 'store'])->name('store');
            Route::get('/{supplier}', [SupplierController::class, 'show'])->name('show');
            Route::post('/{supplier}/update', [SupplierController::class, 'update'])->name('update');
            Route::post('/{supplier}/delete', [SupplierController::class, 'delete'])->name('delete');
        });

        Route::group(['prefix' => 'kategori', 'as' => 'kategori.'], function() {
            Route::get('/', [KategoriController::class, 'index'])->name('index');
            Route::get('/get-data', [KategoriController::class, 'getData'])->name('getData');
            Route::post('/store', [KategoriController::class, 'store'])->name('store');
            Route::get('/{kategori}', [KategoriController::class, 'show'])->name('show');
            Route::post('/{kategori}/update', [KategoriController::class, 'update'])->name('update');
            Route::post('/{kategori}/delete', [KategoriController::class, 'delete'])->name('delete');
        });

        Route::group(['prefix' => 'barang', 'as' => 'barang.'], function() {
            Route::get('/', [BarangController::class, 'index'])->name('index');
            Route::get('/historis-barang', [BarangController::class, 'historisBarang'])->name('historisBarang');
            Route::get('/get-data', [BarangController::class, 'getData'])->name('getData');
            Route::post('/store', [BarangController::class, 'store'])->name('store');
            Route::get('/{barang}', [BarangController::class, 'show'])->name('show');
            Route::post('/{barang}/update', [BarangController::class, 'update'])->name('update');
            Route::post('/{barang}/delete', [BarangController::class, 'delete'])->name('delete');
        });

        Route::group(['prefix' => 'transaksi', 'as' => 'transaksi.'], function() {
            Route::get('/', [TransaksiController::class, 'index'])->name('index');
            Route::get('/get-data', [TransaksiController::class, 'getData'])->name('getData');
            Route::get('/create', [TransaksiController::class, 'create'])->name('create');
            Route::post('/show-invoice', [TransaksiController::class, 'showInvoice'])->name('showInvoice');
            Route::post('/store', [TransaksiController::class, 'store'])->name('store');
            Route::get('/{transaksi}', [TransaksiController::class, 'showDraft'])->name('showDraft');
            Route::get('/{transaksi}/detail', [TransaksiController::class, 'detail'])->name('detail');
            Route::post('/{transaksi}/delete', [TransaksiController::class, 'delete'])->name('delete');
            Route::post('/{transaksi}/update', [TransaksiController::class, 'update'])->name('update');
        });
    });


});
// Dashboard


// Layout
Route::get('/layout-default-layout', function () {
    return view('pages.layout-default-layout', ['type_menu' => 'layout']);
});

// Blank Page
Route::get('/blank-page', function () {
    return view('pages.blank-page', ['type_menu' => '']);
});

// Bootstrap
Route::get('/bootstrap-alert', function () {
    return view('pages.bootstrap-alert', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-badge', function () {
    return view('pages.bootstrap-badge', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-breadcrumb', function () {
    return view('pages.bootstrap-breadcrumb', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-buttons', function () {
    return view('pages.bootstrap-buttons', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-card', function () {
    return view('pages.bootstrap-card', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-carousel', function () {
    return view('pages.bootstrap-carousel', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-collapse', function () {
    return view('pages.bootstrap-collapse', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-dropdown', function () {
    return view('pages.bootstrap-dropdown', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-form', function () {
    return view('pages.bootstrap-form', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-list-group', function () {
    return view('pages.bootstrap-list-group', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-media-object', function () {
    return view('pages.bootstrap-media-object', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-modal', function () {
    return view('pages.bootstrap-modal', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-nav', function () {
    return view('pages.bootstrap-nav', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-navbar', function () {
    return view('pages.bootstrap-navbar', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-pagination', function () {
    return view('pages.bootstrap-pagination', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-popover', function () {
    return view('pages.bootstrap-popover', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-progress', function () {
    return view('pages.bootstrap-progress', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-table', function () {
    return view('pages.bootstrap-table', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-tooltip', function () {
    return view('pages.bootstrap-tooltip', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-typography', function () {
    return view('pages.bootstrap-typography', ['type_menu' => 'bootstrap']);
});


// components
Route::get('/components-article', function () {
    return view('pages.components-article', ['type_menu' => 'components']);
});
Route::get('/components-avatar', function () {
    return view('pages.components-avatar', ['type_menu' => 'components']);
});
Route::get('/components-chat-box', function () {
    return view('pages.components-chat-box', ['type_menu' => 'components']);
});
Route::get('/components-empty-state', function () {
    return view('pages.components-empty-state', ['type_menu' => 'components']);
});
Route::get('/components-gallery', function () {
    return view('pages.components-gallery', ['type_menu' => 'components']);
});
Route::get('/components-hero', function () {
    return view('pages.components-hero', ['type_menu' => 'components']);
});
Route::get('/components-multiple-upload', function () {
    return view('pages.components-multiple-upload', ['type_menu' => 'components']);
});
Route::get('/components-pricing', function () {
    return view('pages.components-pricing', ['type_menu' => 'components']);
});
Route::get('/components-statistic', function () {
    return view('pages.components-statistic', ['type_menu' => 'components']);
});
Route::get('/components-tab', function () {
    return view('pages.components-tab', ['type_menu' => 'components']);
});
Route::get('/components-table', function () {
    return view('pages.components-table', ['type_menu' => 'components']);
});
Route::get('/components-user', function () {
    return view('pages.components-user', ['type_menu' => 'components']);
});
Route::get('/components-wizard', function () {
    return view('pages.components-wizard', ['type_menu' => 'components']);
});

// forms
Route::get('/forms-advanced-form', function () {
    return view('pages.forms-advanced-form', ['type_menu' => 'forms']);
});
Route::get('/forms-editor', function () {
    return view('pages.forms-editor', ['type_menu' => 'forms']);
});
Route::get('/forms-validation', function () {
    return view('pages.forms-validation', ['type_menu' => 'forms']);
});

// google maps
// belum tersedia

// modules
Route::get('/modules-calendar', function () {
    return view('pages.modules-calendar', ['type_menu' => 'modules']);
});
Route::get('/modules-chartjs', function () {
    return view('pages.modules-chartjs', ['type_menu' => 'modules']);
});
Route::get('/modules-datatables', function () {
    return view('pages.modules-datatables', ['type_menu' => 'modules']);
});
Route::get('/modules-flag', function () {
    return view('pages.modules-flag', ['type_menu' => 'modules']);
});
Route::get('/modules-font-awesome', function () {
    return view('pages.modules-font-awesome', ['type_menu' => 'modules']);
});
Route::get('/modules-ion-icons', function () {
    return view('pages.modules-ion-icons', ['type_menu' => 'modules']);
});
Route::get('/modules-owl-carousel', function () {
    return view('pages.modules-owl-carousel', ['type_menu' => 'modules']);
});
Route::get('/modules-sparkline', function () {
    return view('pages.modules-sparkline', ['type_menu' => 'modules']);
});
Route::get('/modules-sweet-alert', function () {
    return view('pages.modules-sweet-alert', ['type_menu' => 'modules']);
});
Route::get('/modules-toastr', function () {
    return view('pages.modules-toastr', ['type_menu' => 'modules']);
});
Route::get('/modules-vector-map', function () {
    return view('pages.modules-vector-map', ['type_menu' => 'modules']);
});
Route::get('/modules-weather-icon', function () {
    return view('pages.modules-weather-icon', ['type_menu' => 'modules']);
});

// auth
Route::get('/auth-forgot-password', function () {
    return view('pages.auth-forgot-password', ['type_menu' => 'auth']);
});
Route::get('/auth-login', function () {
    return view('pages.auth-login', ['type_menu' => 'auth']);
});
Route::get('/auth-login2', function () {
    return view('pages.auth-login2', ['type_menu' => 'auth']);
});
Route::get('/auth-register', function () {
    return view('pages.auth-register', ['type_menu' => 'auth']);
});
Route::get('/auth-reset-password', function () {
    return view('pages.auth-reset-password', ['type_menu' => 'auth']);
});

// error
Route::get('/error-403', function () {
    return view('pages.error-403', ['type_menu' => 'error']);
});
Route::get('/error-404', function () {
    return view('pages.error-404', ['type_menu' => 'error']);
});
Route::get('/error-500', function () {
    return view('pages.error-500', ['type_menu' => 'error']);
});
Route::get('/error-503', function () {
    return view('pages.error-503', ['type_menu' => 'error']);
});

// features
Route::get('/features-activities', function () {
    return view('pages.features-activities', ['type_menu' => 'features']);
});
Route::get('/features-post-create', function () {
    return view('pages.features-post-create', ['type_menu' => 'features']);
});
Route::get('/features-post', function () {
    return view('pages.features-post', ['type_menu' => 'features']);
});
Route::get('/features-profile', function () {
    return view('pages.features-profile', ['type_menu' => 'features']);
});
Route::get('/features-settings', function () {
    return view('pages.features-settings', ['type_menu' => 'features']);
});
Route::get('/features-setting-detail', function () {
    return view('pages.features-setting-detail', ['type_menu' => 'features']);
});
Route::get('/features-tickets', function () {
    return view('pages.features-tickets', ['type_menu' => 'features']);
});

// utilities
Route::get('/utilities-contact', function () {
    return view('pages.utilities-contact', ['type_menu' => 'utilities']);
});
Route::get('/utilities-invoice', function () {
    return view('pages.utilities-invoice', ['type_menu' => 'utilities']);
});
Route::get('/utilities-subscribe', function () {
    return view('pages.utilities-subscribe', ['type_menu' => 'utilities']);
});

// credits
Route::get('/credits', function () {
    return view('pages.credits', ['type_menu' => '']);
});
