<?php

use App\Models\Division;
use Illuminate\Http\Request;
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

Route::get('/', 'DashboardController@index')->name('entrypoint');
Route::post('/','DashboardController@accessLink')->name('accesslink');

Route::get('/logout','DashboardController@logout')->name('logout');
Route::get('/switchToFront','DashboardController@switchToFront')->name('switchToFront');


    /*
    |--------------------------------------------------------------------------
    | 1. SUPER ADMIN
    |--------------------------------------------------------------------------
    |
    */
    Route::middleware(['auth','sdg.superadmintracker'])->group(function () {
        Route::namespace('Backend')->group(function () {
            Route::resource('companies', CompanyController::class);
            Route::post('companies/import/store', 'CompanyController@importStore')->name('companies.store.import');
            Route::post('companies/status/{id}/{status}', 'CompanyController@updateStatus')->name('companies.status');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | 2. ADMIN
    |--------------------------------------------------------------------------
    |
    */
    Route::middleware(['auth','sdg.admintracker'])->group(function () {
        Route::namespace('Backend')->group(function () {
            Route::resource('divisions', DivisionController::class);

            //omusegad
            Route::resource('review-cycle', ReviewCyclecController::class);

            Route::post('divisions/import/store', 'DivisionController@importStore')->name('divisions.store.import');

            Route::resource('sdg-topics', SDGTopicController::class);
            Route::post('sdg-topics/import/store', 'SDGTopicController@importStore')->name('sdg-topics.store.import');

            Route::resource('users', UserController::class);
            Route::post('users/import/store', 'UserController@importStore')->name('users.store.import');

            Route::resource('kpis', KPIController::class);
            Route::post('kpis/import/store', 'KPIController@importStore')->name('kpis.store.import');

            Route::resource('gris', GRIController::class);
            Route::post('gris/import/store', 'GRIController@importStore')->name('gris.store.import');

            Route::resource('responses', ResponseController::class);
            Route::post('responses/filter', 'ResponseController@index')->name('responses.filter');
            Route::get('responses/status/{id}/{status}', 'ResponseController@status')->name('responses.status');

            Route::get('sdg_response/export', 'ResponseController@export')->name('sdg_response.export');

            Route::get('faq/admin', 'FAQAdminController@faqAdmin')->name('faq.admin');

            Route::get('/overview','OverviewController@index')->name('frontend.overview');


        });
    });

    /*
    |--------------------------------------------------------------------------
    | 3. CHAMPION
    |--------------------------------------------------------------------------
    |
    */
    Route::middleware(['auth','sdg.employeetracker','sdg.permissiontracker'])->group(function () {
        Route::namespace('Backend')->group(function () {
            Route::resource('champions', ChampionController::class);
            Route::post('champions/filter', 'ChampionController@index')->name('champions.filter');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | 4. EMPLOYEE
    |--------------------------------------------------------------------------
    |
    */
    Route::middleware(['auth','sdg.employeetracker'])->group(function () {
        Route::namespace('Frontend')->group(function () {
            Route::get('/overview','OverviewController@index')->name('frontend.overview');
            Route::resource('reviews', ReviewController::class);
            Route::post('reviews/filter', 'ReviewController@index')->name('reviews.filter');
            Route::get('reviews/status/{id}/{status}', 'ReviewController@status')->name('reviews.status');



            // possibly pass year and division id
            $divisions = Division::all();
            foreach ($divisions as $key => $division) {
                Route::get(''.\Str::slug($division->label, '-').'/{id}/{year?}','DashboardController@index')->name(''.\Str::slug($division->label, '-').'.index');
            }
        });
    });
