<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CuisineController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\NutriotionFactController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\RecipieController;
use App\Http\Controllers\Admin\KitchenController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\BranchController;
use \App\Http\Controllers\Admin\PackageController;
use \App\Http\Controllers\Admin\SubscribeController;
use \App\Http\Controllers\Admin\PeriodController;
use \App\Http\Controllers\Admin\BankNameController;
use \App\Http\Controllers\Admin\CompanyController;
use \App\Http\Controllers\Admin\GroupNameController;
use \App\Http\Controllers\Admin\MenuController;
use \App\Http\Controllers\Admin\NewMenuController;

Route::get('/storage_shortcut', function () {
//    symlink(storage_path('app/public'), public_path('/storage'));
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    \Illuminate\Support\Facades\Artisan::call('migrate');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    return 'success1';
    return 'success';
});

Route::get('/db_seed', function () {
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('db:seed');
    return 'success';
});

Route::prefix('auth/admin')->middleware('auth:admin')->group(function (){
Route::post('/login',[AuthController::class,'login'])->withoutMiddleware('auth:admin');
Route::post('/logout',[AuthController::class,'logout']);
});

Route::prefix('admin')->middleware('auth:admin')->group(function (){

    Route::get('export_deliveries_export', [\App\Http\Controllers\Admin\ExportExcelController::class, 'exportDeliveriesExport'])->withoutMiddleware('auth:admin');
    Route::get('export_kitchen_today_export', [\App\Http\Controllers\Admin\ExportExcelController::class, 'exportKitchenTodayExport'])->withoutMiddleware('auth:admin');
    Route::get('export_quantities_today', [\App\Http\Controllers\Admin\ExportExcelController::class, 'exportQuantitiesToday'])->withoutMiddleware('auth:admin');
    Route::get('export_subscription_data', [\App\Http\Controllers\Admin\ExportExcelController::class, 'exportSubscriptionData'])->withoutMiddleware('auth:admin');
    Route::post('import_customers', [\App\Http\Controllers\Admin\ImportExcelController::class, 'importCustomers'])->withoutMiddleware('auth:admin');

    Route::post('import_subscribes', [\App\Http\Controllers\Admin\ImportExcelController::class, 'importSubscribes'])->withoutMiddleware('auth:admin');

    /************************** Units **************************/
    Route::prefix('units')->group(function () {
        Route::get('/', [UnitController::class, 'index']);
        Route::get('list_all', [UnitController::class, 'listAll']);
        Route::post('/', [UnitController::class, 'store']);
        Route::get('/{id}', [UnitController::class, 'find']);
        Route::post('/{id}', [UnitController::class, 'update']);
        Route::post('upload_image/{id}', [UnitController::class, 'uploadImage']);
        Route::post('/{id}/update_status', [UnitController::class, 'updateStatus']);
        Route::delete('/{id}', [UnitController::class, 'destroy']);
    });

    /************************** Countries **************************/
    Route::prefix('countries')->group(function () {
        Route::get('/', [CountryController::class, 'index']);
        Route::get('list_all', [CountryController::class, 'listAll']);
        Route::post('/', [CountryController::class, 'store']);
        Route::get('/{id}', [CountryController::class, 'find']);
        Route::post('/{id}', [CountryController::class, 'update']);
        Route::post('/{id}/update_status', [CountryController::class, 'updateStatus']);
        Route::post('upload_image/{id}', [CountryController::class, 'uploadImage']);
        Route::delete('/{id}', [CountryController::class, 'destroy']);
    });

    /************************** cities **************************/
    Route::prefix('cities')->group(function () {
        Route::get('/', [CityController::class, 'index']);
        Route::get('list_all', [CityController::class, 'listAll']);
        Route::get('/list_countries', [CityController::class, 'listCountries']);
        Route::post('/', [CityController::class, 'store']);
        Route::get('/{id}', [CityController::class, 'find']);
        Route::post('/{id}', [CityController::class, 'update']);
        Route::post('/{id}/update_status', [CityController::class, 'updateStatus']);
        Route::delete('/{id}', [CityController::class, 'destroy']);
    });
    /************************** packages **************************/
    Route::prefix('packages')->group(function () {
        Route::get('/', [PackageController::class, 'index']);
        Route::get('list_all', [PackageController::class, 'listAll']);
        Route::get('/list_packages', [PackageController::class, 'listPackages']);
        Route::get('/detail_packages/{package_id}', [PackageController::class, 'detailPackages']);
        Route::post('/', [PackageController::class, 'store']);
        Route::get('/{id}', [PackageController::class, 'find']);
        Route::post('/{id}', [PackageController::class, 'update']);
        Route::post('/{id}/update_status', [PackageController::class, 'updateStatus']);
        Route::delete('/{id}', [PackageController::class, 'destroy']);
    });

    /************************** cuisines **************************/
    Route::prefix('cuisines')->group(function () {
        Route::get('/', [CuisineController::class, 'index']);
        Route::get('list_all', [CuisineController::class, 'listAll']);
        Route::post('/', [CuisineController::class, 'store']);
        Route::get('/{id}', [CuisineController::class, 'find']);
        Route::post('/{id}', [CuisineController::class, 'update']);
        Route::post('/{id}/update_status', [CuisineController::class, 'updateStatus']);
        Route::post('upload_image/{id}', [CuisineController::class, 'uploadImage']);
        Route::delete('/{id}', [CuisineController::class, 'destroy']);
    });

    /************************** Divisions **************************/
    Route::prefix('divisions')->group(function () {
        Route::get('/', [DivisionController::class, 'index']);
        Route::get('list_all', [DivisionController::class, 'listAll']);
        Route::post('/', [DivisionController::class, 'store']);
        Route::get('/{id}', [DivisionController::class, 'find']);
        Route::post('/{id}', [DivisionController::class, 'update']);
        Route::post('/{id}/update_status', [DivisionController::class, 'updateStatus']);
        Route::post('upload_image/{id}', [DivisionController::class, 'uploadImage']);
        Route::delete('/{id}', [DivisionController::class, 'destroy']);
    });

    /************************** groups **************************/
    Route::prefix('groups')->group(function () {
        Route::get('/', [GroupController::class, 'index']);
        Route::get('list_all', [GroupController::class, 'listAll']);
        Route::post('/', [GroupController::class, 'store']);
        Route::get('/{id}', [GroupController::class, 'find']);
        Route::post('/{id}', [GroupController::class, 'update']);
        Route::post('/{id}/update_status', [GroupController::class, 'updateStatus']);
        Route::post('upload_image/{id}', [GroupController::class, 'uploadImage']);
        Route::delete('/{id}', [GroupController::class, 'destroy']);
    });

    /************************** tools **************************/
    Route::prefix('tools')->group(function () {
        Route::get('/', [ToolController::class, 'index']);
        Route::get('list_all', [ToolController::class, 'listAll']);
        Route::get('/list', [ToolController::class, 'list']);
        Route::post('/', [ToolController::class, 'store']);
        Route::get('/{id}', [ToolController::class, 'find']);
        Route::post('/{id}', [ToolController::class, 'update']);
        Route::post('upload_image/{id}', [ToolController::class, 'uploadImage']);
        Route::post('/{id}/update_status', [ToolController::class, 'updateStatus']);
        Route::delete('/{id}', [ToolController::class, 'destroy']);
    });

    /************************** nutrition_facts **************************/
    Route::prefix('nutrition_facts')->group(function () {
        Route::get('/', [NutriotionFactController::class, 'index']);
        Route::get('list_all', [NutriotionFactController::class, 'listAll']);
        Route::post('/', [NutriotionFactController::class, 'store']);
        Route::get('/{id}', [NutriotionFactController::class, 'find']);
        Route::post('/{id}', [NutriotionFactController::class, 'update']);
        Route::post('/{id}/update_status', [NutriotionFactController::class, 'updateStatus']);
        Route::delete('/{id}', [NutriotionFactController::class, 'destroy']);
    });

    /************************** Ingredients **************************/
    Route::prefix('ingredients')->group(function () {
        Route::get('/', [IngredientController::class, 'index']);
        Route::get('list_all', [IngredientController::class, 'listAll']);
        Route::get('/list_units', [IngredientController::class, 'listUnit']);
        Route::get('/list_nutrtion_facts', [IngredientController::class, 'listNutriotionFact']);
        Route::get('/list_divisions', [IngredientController::class, 'listDivision']);
        Route::get('/get_groups', [IngredientController::class, 'getIngredients']);
        Route::post('/', [IngredientController::class, 'store']);
        Route::get('/{id}', [IngredientController::class, 'find']);
        Route::post('/{id}', [IngredientController::class, 'update']);
        Route::post('/{id}/update_status', [IngredientController::class, 'updateStatus']);
        Route::post('upload_image/{id}', [IngredientController::class, 'uploadImage']);
        Route::delete('/{id}', [IngredientController::class, 'destroy']);
    });

    /************************** Recipies **************************/
    Route::prefix('recipies')->group(function () {
        Route::get('/list_cuisines', [CuisineController::class, 'listCuisines']);
        Route::get('/list_ingredients', [CuisineController::class, 'listIngredients']);
        Route::get('/list_primary_ingredients', [CuisineController::class, 'listPrimaryIngredients']);
        Route::get('/list_groups', [CuisineController::class, 'listGroups']);
        Route::get('/list_tools', [CuisineController::class, 'listTools']);
        Route::get('/', [RecipieController::class, 'index']);
        Route::get('list_all', [RecipieController::class, 'listAll']);
        Route::post('/', [RecipieController::class, 'store']);
        Route::get('/{id}', [RecipieController::class, 'find']);
        Route::post('/{id}', [RecipieController::class, 'update']);
        Route::post('/{id}/update_status', [RecipieController::class, 'updateStatus']);
        Route::post('upload_image/{id}', [RecipieController::class, 'uploadImage']);
        Route::delete('/{id}', [RecipieController::class, 'destroy']);
    });

    /************************** Kitchen **************************/
    Route::prefix('kitchens')->group(function () {
        Route::get('/', [KitchenController::class, 'index']);
        Route::get('list_all', [KitchenController::class, 'listAll']);
        Route::get('/list_recipies', [KitchenController::class, 'listRecipies']);
        Route::get('/list_recipies_of_groups', [KitchenController::class, 'listRecipiesOfGroups']);
        Route::post('/', [KitchenController::class, 'store']);
        Route::get('/{id}', [KitchenController::class, 'find']);
        Route::post('/{id}', [KitchenController::class, 'update']);
        Route::delete('/{id}', [KitchenController::class, 'destroy']);
    });

    /************************** menu **************************/
    Route::prefix('menus')->group(function () {
        Route::get('/', [MenuController::class, 'index']);
        Route::get('list_all', [MenuController::class, 'listAll']);
        Route::get('/list_recipies', [MenuController::class, 'listRecipies']);
        Route::get('/list_first_group_ingredients', [MenuController::class, 'listFirstGroupIngredients']);
        Route::get('/list_second_group_ingredients', [MenuController::class, 'listSecondGroupIngredients']);
        Route::post('/', [MenuController::class, 'store']);
        Route::get('/{id}', [MenuController::class, 'find']);
        Route::post('/{id}', [MenuController::class, 'update']);
        Route::delete('/{id}', [MenuController::class, 'destroy']);
    });

    /************************** New Menus **************************/
    Route::prefix('new_menus')->group(function () {
        Route::get('/', [NewMenuController::class, 'index']);
        Route::get('list_all', [NewMenuController::class, 'listAll']);
        Route::get('/list_recipies', [NewMenuController::class, 'listRecipies']);
        Route::get('/list_main_ingredients', [NewMenuController::class, 'listMainIngredients']);
        Route::get('/list_groups', [NewMenuController::class, 'listGroups']);
        Route::get('/list_recipies_of_group/{groupId}', [NewMenuController::class, 'listRecipiesOfGroup']);
        Route::get('/list_recipies_of_group1', [NewMenuController::class, 'listRecipiesOfGroup1']);
        Route::get('/list_protein_recipies/{ingeredientId}', [NewMenuController::class, 'listProteinRecipies']);
        Route::get('/list_protein_recipies1', [NewMenuController::class, 'listProteinRecipies1']);
        Route::get('/list_carb_recipies/{ingeredientId}', [NewMenuController::class, 'listCarbRecipies']);
        Route::get('/list_carb_recipies1', [NewMenuController::class, 'listCarbRecipies1']);
        Route::get('/list_first_group_ingredients', [NewMenuController::class, 'listFirstGroupIngredients']);
        Route::get('/list_second_group_ingredients', [NewMenuController::class, 'listSecondGroupIngredients']);
        Route::post('/', [NewMenuController::class, 'store']);
        Route::get('/{id}', [NewMenuController::class, 'find']);
        Route::post('/{id}', [NewMenuController::class, 'update']);
        Route::delete('/{id}', [NewMenuController::class, 'destroy']);
    });

    /************************** branches **************************/
    Route::prefix('branches')->group(function () {
        Route::get('/', [BranchController::class, 'index']);
        Route::get('list_all', [BranchController::class, 'listAll']);
        Route::get('/list_cities/{id}', [BranchController::class, 'listCities']);
        Route::get('/list_all_cities', [BranchController::class, 'listAllCities']);
        Route::get('/list_branches/{city_id}', [BranchController::class, 'listBranches']);
        Route::post('/', [BranchController::class, 'store']);
        Route::get('/{id}', [BranchController::class, 'find']);
        Route::post('/{id}', [BranchController::class, 'update']);
        Route::post('/{id}/update_status', [BranchController::class, 'updateStatus']);
        Route::delete('/{id}', [BranchController::class, 'destroy']);
    });

    /************************** drivers **************************/
    Route::prefix('drivers')->group(function () {
        Route::get('/', [DriverController::class, 'index']);
        Route::get('list_all', [DriverController::class, 'listAll']);
        Route::get('/list_drivers', [DriverController::class, 'listDrivers']);
        Route::post('/', [DriverController::class, 'store']);
        Route::get('/{id}', [DriverController::class, 'find']);
        Route::post('/{id}', [DriverController::class, 'update']);
        Route::post('/{id}/update_status', [DriverController::class, 'updateStatus']);
        Route::delete('/{id}', [DriverController::class, 'destroy']);
    });

    /************************** drivers **************************/
    Route::prefix('periods')->group(function () {
        Route::get('/', [PeriodController::class, 'index']);
        Route::get('list_all', [PeriodController::class, 'listAll']);
        Route::post('/', [PeriodController::class, 'store']);
        Route::get('/{id}', [PeriodController::class, 'find']);
        Route::post('/{id}', [PeriodController::class, 'update']);
        Route::post('/{id}/update_status', [PeriodController::class, 'updateStatus']);
        Route::delete('/{id}', [PeriodController::class, 'destroy']);
    });


    /************************** bankName **************************/
    Route::prefix('bank_names')->group(function () {
        Route::get('/', [BankNameController::class, 'index']);
        Route::get('list_all', [BankNameController::class, 'listAll']);
        Route::post('/', [BankNameController::class, 'store']);
        Route::get('/{id}', [BankNameController::class, 'find']);
        Route::post('/{id}', [BankNameController::class, 'update']);
        Route::post('/{id}/update_status', [BankNameController::class, 'updateStatus']);
        Route::delete('/{id}', [BankNameController::class, 'destroy']);
    });

    /************************** company **************************/
    Route::prefix('company')->group(function () {
        Route::get('/', [CompanyController::class, 'index']);
        Route::get('list_all', [CompanyController::class, 'listAll']);
        Route::post('/', [CompanyController::class, 'store']);
        Route::get('/{id}', [CompanyController::class, 'find']);
        Route::post('/{id}', [CompanyController::class, 'update']);
        Route::post('/{id}/update_status', [CompanyController::class, 'updateStatus']);
        Route::delete('/{id}', [CompanyController::class, 'destroy']);
    });

    /************************** group_name **************************/
    Route::prefix('group_name')->group(function () {
        Route::post('/subscribe', [SubscribeController::class, 'destroy']);
        Route::get('/', [GroupNameController::class, 'index']);
        Route::get('list_all', [GroupNameController::class, 'listAll']);
        Route::post('/', [GroupNameController::class, 'store']);
        Route::get('/{id}', [GroupNameController::class, 'find']);
        Route::post('/{id}', [GroupNameController::class, 'update']);
        Route::post('/{id}/update_status', [GroupNameController::class, 'updateStatus']);
        Route::delete('/{id}', [GroupNameController::class, 'destroy']);
    });

    /************************** drivers **************************/
    Route::prefix('subscribe')/*->middleware('role:orders')*/->group(function () {
        Route::get('/get_subscribe', [SubscribeController::class, 'getSubscribe']);
        Route::get('/find_subscribe/{id}', [SubscribeController::class, 'findSubscribe']);
        Route::post('/', [SubscribeController::class, 'subscribe']);
        Route::post('/stop_sub/{id}', [SubscribeController::class, 'stopSub']);
        Route::post('/start_sub/{id}', [SubscribeController::class, 'startSub']);
        Route::post('/give_sub/{id}', [SubscribeController::class, 'givSub']);
        Route::get('/detail_packages/{package_id}', [SubscribeController::class, 'detailPackages']);
        Route::post('/table_cooking_date', [SubscribeController::class, 'tableCookingToday']);
        Route::get('/generate_cooking_today', [SubscribeController::class, 'generateCookingToday']);
        Route::get('/report_quantities', [SubscribeController::class, 'reportQuantities']);
        Route::get('/report_deliveries', [SubscribeController::class, 'reportDeliveries']);
        Route::get('/list_periods', [SubscribeController::class, 'listPeriods']);
        Route::get('/list_bank_names', [SubscribeController::class, 'listBankNames']);
        Route::get('/list_companies', [SubscribeController::class, 'listCompanies']);
        Route::get('/list_group_names', [SubscribeController::class, 'listGroupNames']);
        Route::get('/list_first_group_ingredients', [SubscribeController::class, 'listFirstGroupIngredients']);
        Route::get('/list_second_group_ingredients', [SubscribeController::class, 'listSecondGroupIngredients']);
        Route::get('/find_customer', [SubscribeController::class, 'findCustomer']);
        Route::get('list_all', [SubscribeController::class, 'listAll']);
        Route::delete('/{id}', [SubscribeController::class, 'destroy']);
        Route::post('/update_subscribe/{id}', [SubscribeController::class, 'updateSubscribe']);
    });

    /************************** Divisions *************************/
    Route::prefix('admins')->group(function () {
        Route::get('/', [AuthController::class, 'index']);
        Route::get('list_all', [AuthController::class, 'listAll']);
        Route::get('/roles', [AuthController::class, 'roles']);
        Route::get('/{id}', [AuthController::class, 'find']);
        Route::post('/', [AuthController::class, 'store']);
        Route::post('/{id}', [AuthController::class, 'update']);
        Route::delete('/{id}', [AuthController::class, 'destroy']);
    });

    Route::get('home', [\App\Http\Controllers\Admin\HomeController::class, 'home']);
});
