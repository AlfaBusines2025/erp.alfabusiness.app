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

use Illuminate\Support\Facades\Route;
use Workdo\DairyCattleManagement\Http\Controllers\AnimalController;
use Workdo\DairyCattleManagement\Http\Controllers\BirthRecordsController;
use Workdo\DairyCattleManagement\Http\Controllers\BreedingController;
use Workdo\DairyCattleManagement\Http\Controllers\CommulativeMilkSheetController;
use Workdo\DairyCattleManagement\Http\Controllers\DailyMilkSheetController;
use Workdo\DairyCattleManagement\Http\Controllers\EquipmentManageController;
use Workdo\DairyCattleManagement\Http\Controllers\ExpenseTrackingController;
use Workdo\DairyCattleManagement\Http\Controllers\FeedConsumptionController;
use Workdo\DairyCattleManagement\Http\Controllers\FeedScheduleController;
use Workdo\DairyCattleManagement\Http\Controllers\FeedTypeController;
use Workdo\DairyCattleManagement\Http\Controllers\HealthController;
use Workdo\DairyCattleManagement\Http\Controllers\MilkInventoryController;
use Workdo\DairyCattleManagement\Http\Controllers\MilkProductController;
use Workdo\DairyCattleManagement\Http\Controllers\SalesDistributionController;
use Workdo\DairyCattleManagement\Http\Controllers\VaccinationController;
use Workdo\DairyCattleManagement\Http\Controllers\WeightController;

Route::group(['middleware' => ['web', 'auth', 'verified','PlanModuleCheck:DairyCattleManagement']], function () {
    Route::resource('animal', AnimalController::class);
    Route::post('store/animal-milk/{id}', [AnimalController::class, 'storeAnimalMilk'])->name('store.animal.milk');
    Route::post('animal-milk/destroy', [AnimalController::class, 'AnimalMilkDestroy'])->name('animal.milk.destroy');

    Route::resource('health', HealthController::class);
    Route::resource('breeding', BreedingController::class);
    Route::resource('weight', WeightController::class);
    Route::resource('dailymilksheet', DailyMilkSheetController::class);
    Route::resource('milkinventory', MilkInventoryController::class);
    Route::get('milkinventory/product/list', [MilkInventoryController::class, 'MilkinventoryProductList'])->name('milkinventory.product.list');

    Route::post('milk/section/type', [MilkInventoryController::class, 'milkSectionGet'])->name('milk.section.get');

    Route::resource('commulativemilksheet', CommulativeMilkSheetController::class);
    Route::resource('milkproduct', MilkProductController::class);
    Route::get('get-animal-data', [MilkProductController::class, 'getAnimalData'])->name('animal.data.get');

    Route::resource('feeds_type', FeedTypeController::class);
    Route::resource('feeds_schedule', FeedScheduleController::class);
    Route::resource('feeds_consumption', FeedConsumptionController::class);
    Route::resource('vaccinations', VaccinationController::class);
    Route::resource('sales_distribution', SalesDistributionController::class);
    Route::resource('expense_tracking', ExpenseTrackingController::class);
    Route::resource('birth_records', BirthRecordsController::class);
    Route::resource('dairy-equipments', EquipmentManageController::class);
});
