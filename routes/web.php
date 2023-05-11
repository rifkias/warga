<?php
Use App\Http\Controllers;
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
    return redirect('/login');
});
Auth::routes(['register'=>false]);
Route::group(['prefix'=>'dashboard','middleware'=>['auth','checkMenuPermission']],function(){
    Route::get('/',[Controllers\HomeController::class,'index']);
    Route::group(['prefix'=>'config','namespace'=>'Admin'],function(){
        Route::group(['prefix'=>'menu'],function(){
            Route::get('/','MenuController@index');
            Route::get('/get-data','MenuController@dataTables');
            Route::post('/add','MenuController@store');
            Route::post('/show','MenuController@show');
            Route::post('/update','MenuController@update');
            Route::post('/delete','MenuController@destroy');
            Route::get('/test','MenuController@test');
        });
        Route::group(['prefix'=>'menu-access'],function(){
            Route::get('/','MenuPermissionController@index');
            Route::get('/get-data','MenuPermissionController@dataTables');
            Route::post('/add','MenuPermissionController@store');
            Route::post('/delete','MenuPermissionController@destroy');
        });
        Route::group(['prefix'=>'organisation-type'],function(){
            Route::get('/','OrganisationTypeController@index');
            Route::get('/get-data','OrganisationTypeController@dataTables');
            Route::get('/get-list','OrganisationTypeController@getList');
            Route::post('/add','OrganisationTypeController@store');
            Route::post('/delete','OrganisationTypeController@destroy');
        });
        Route::group(['prefix'=>'wilayah'],function(){
            Route::get('/','WilayahController@index');
            Route::get('/get-data','WilayahController@dataTables');
            Route::post('/add','WilayahController@store');
            Route::post('/show','WilayahController@show');
            Route::post('/update','WilayahController@update');
            Route::post('/delete','WilayahController@destroy');
        });
    });
    Route::group(['prefix'=>'master-data'],function(){
        Route::group(['prefix'=>'role','namespace'=>'Admin'],function(){
            Route::get('/','RoleController@index');
            Route::get('/get-data','RoleController@dataTables');
            Route::post('/add','RoleController@store');
            Route::post('/delete','RoleController@destroy');
        });
        Route::group(['prefix'=>'user','namespace'=>'Admin'],function(){
            Route::get('/','UserController@index');
            Route::get('/get-data','UserController@dataTables');
            Route::post('/add','UserController@store');
            Route::post('/show','UserController@show');
            Route::post('/update','UserController@update');
            Route::post('/delete','UserController@destroy');
        });
        Route::group(['prefix'=>'organisation','namespace'=>'Admin'],function(){
            Route::get('/','OrganisationController@index');
            Route::get('/get-data','OrganisationController@dataTables');
            Route::post('/add','OrganisationController@store');
            Route::post('/show','OrganisationController@show');
            Route::post('/update','OrganisationController@update');
            Route::post('/delete','OrganisationController@destroy');
        });
    });
    Route::group(['prefix'=>'master-wilayah','namespace'=>'MasterWilayah'],function(){
        Route::get('/',function(){
            return redirect('/dashboard');
        });
        Route::get('/get-province',"MainController@getProvince");
        Route::get('/get-city',"MainController@getCity");
        Route::get('/get-district',"MainController@getDistrict");
        Route::get('/get-village',"MainController@getVillage");
        Route::group(['prefix'=>'province'],function(){
            Route::get('/','ProvinceController@index');
            Route::get('/get-data','ProvinceController@dataTables');
            Route::get('/get-province','ProvinceController@getProvice');
            Route::post('/add','ProvinceController@store');
            Route::post('/delete','ProvinceController@destroy');
        });
        Route::group(['prefix'=>'city'],function(){
            Route::get('/','CityController@index');
            Route::get('/get-data','CityController@dataTables');
            Route::get('/get-city','CityController@getCity');
            Route::post('/add','CityController@store');
            Route::post('/delete','CityController@destroy');
        });
        Route::group(['prefix'=>'district'],function(){
            Route::get('/','DistrictController@index');
            Route::get('/get-data','DistrictController@dataTables');
            Route::post('/add','DistrictController@store');
            Route::post('/delete','DistrictController@destroy');
        });
        Route::group(['prefix'=>'village'],function(){
            Route::get('/','VillageController@index');
            Route::get('/get-data','VillageController@dataTables');
            Route::post('/add','VillageController@store');
            Route::post('/delete','VillageController@destroy');
        });
    });
});
// Route::fallback('ApiLogController@fallback');

Route::group(['prefix' => 'template'], function(){
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::group(['prefix' => 'email'], function(){
        Route::get('inbox', function () { return view('pages.email.inbox'); });
        Route::get('read', function () { return view('pages.email.read'); });
        Route::get('compose', function () { return view('pages.email.compose'); });
    });

    Route::group(['prefix' => 'apps'], function(){
        Route::get('chat', function () { return view('pages.apps.chat'); });
        Route::get('calendar', function () { return view('pages.apps.calendar'); });
    });

    Route::group(['prefix' => 'ui-components'], function(){
        Route::get('alerts', function () { return view('pages.ui-components.alerts'); });
        Route::get('badges', function () { return view('pages.ui-components.badges'); });
        Route::get('breadcrumbs', function () { return view('pages.ui-components.breadcrumbs'); });
        Route::get('buttons', function () { return view('pages.ui-components.buttons'); });
        Route::get('button-group', function () { return view('pages.ui-components.button-group'); });
        Route::get('cards', function () { return view('pages.ui-components.cards'); });
        Route::get('carousel', function () { return view('pages.ui-components.carousel'); });
        Route::get('collapse', function () { return view('pages.ui-components.collapse'); });
        Route::get('dropdowns', function () { return view('pages.ui-components.dropdowns'); });
        Route::get('list-group', function () { return view('pages.ui-components.list-group'); });
        Route::get('media-object', function () { return view('pages.ui-components.media-object'); });
        Route::get('modal', function () { return view('pages.ui-components.modal'); });
        Route::get('navs', function () { return view('pages.ui-components.navs'); });
        Route::get('navbar', function () { return view('pages.ui-components.navbar'); });
        Route::get('pagination', function () { return view('pages.ui-components.pagination'); });
        Route::get('popovers', function () { return view('pages.ui-components.popovers'); });
        Route::get('progress', function () { return view('pages.ui-components.progress'); });
        Route::get('scrollbar', function () { return view('pages.ui-components.scrollbar'); });
        Route::get('scrollspy', function () { return view('pages.ui-components.scrollspy'); });
        Route::get('spinners', function () { return view('pages.ui-components.spinners'); });
        Route::get('tabs', function () { return view('pages.ui-components.tabs'); });
        Route::get('tooltips', function () { return view('pages.ui-components.tooltips'); });
    });

    Route::group(['prefix' => 'advanced-ui'], function(){
        Route::get('cropper', function () { return view('pages.advanced-ui.cropper'); });
        Route::get('owl-carousel', function () { return view('pages.advanced-ui.owl-carousel'); });
        Route::get('sweet-alert', function () { return view('pages.advanced-ui.sweet-alert'); });
    });

    Route::group(['prefix' => 'forms'], function(){
        Route::get('basic-elements', function () { return view('pages.forms.basic-elements'); });
        Route::get('advanced-elements', function () { return view('pages.forms.advanced-elements'); });
        Route::get('editors', function () { return view('pages.forms.editors'); });
        Route::get('wizard', function () { return view('pages.forms.wizard'); });
    });

    Route::group(['prefix' => 'charts'], function(){
        Route::get('apex', function () { return view('pages.charts.apex'); });
        Route::get('chartjs', function () { return view('pages.charts.chartjs'); });
        Route::get('flot', function () { return view('pages.charts.flot'); });
        Route::get('morrisjs', function () { return view('pages.charts.morrisjs'); });
        Route::get('peity', function () { return view('pages.charts.peity'); });
        Route::get('sparkline', function () { return view('pages.charts.sparkline'); });
    });

    Route::group(['prefix' => 'tables'], function(){
        Route::get('basic-tables', function () { return view('pages.tables.basic-tables'); });
        Route::get('data-table', function () { return view('pages.tables.data-table'); });
    });

    Route::group(['prefix' => 'icons'], function(){
        Route::get('feather-icons', function () { return view('pages.icons.feather-icons'); });
        Route::get('flag-icons', function () { return view('pages.icons.flag-icons'); });
        Route::get('mdi-icons', function () { return view('pages.icons.mdi-icons'); });
    });

    Route::group(['prefix' => 'general'], function(){
        Route::get('blank-page', function () { return view('pages.general.blank-page'); });
        Route::get('faq', function () { return view('pages.general.faq'); });
        Route::get('invoice', function () { return view('pages.general.invoice'); });
        Route::get('profile', function () { return view('pages.general.profile'); });
        Route::get('pricing', function () { return view('pages.general.pricing'); });
        Route::get('timeline', function () { return view('pages.general.timeline'); });
    });

    Route::group(['prefix' => 'auth'], function(){
        Route::get('login', function () { return view('pages.auth.login'); });
        Route::get('register', function () { return view('pages.auth.register'); });
    });

    Route::group(['prefix' => 'error'], function(){
        Route::get('404', function () { return view('pages.error.404'); });
        Route::get('500', function () { return view('pages.error.500'); });
    });

    Route::get('/clear-cache', function() {
        Artisan::call('cache:clear');
        return "Cache is cleared";
    });

    // 404 for undefined routes
    Route::any('/{page?}',function(){
        return View::make('pages.error.404');
    })->where('page','.*');
});
