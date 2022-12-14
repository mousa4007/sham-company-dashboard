<?php

use App\Http\Controllers\Api\AdBarController;
use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CashStatementController;
use App\Http\Controllers\Api\ChargeBalanceController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\ProductUserFavoriteController;
use App\Http\Controllers\Api\ProfitsController;
use App\Http\Controllers\Api\PurchaseProductController;
use App\Http\Controllers\Api\ReturnsController;
use App\Models\AdBar;
use App\Models\AppUser;
use App\Models\DiscountException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    Route::post('update-fcm-token', [AuthController::class, 'updateUserFcmToken']);
    Route::get('Categories', [ApiController::class, 'categories']);
    Route::get('products/{id}', [ApiController::class, 'products']);
    Route::post('productCount', [ApiController::class, 'productCount']);
    Route::post('purchaseProduct', [PurchaseProductController::class, 'purchaseProduct']);
    Route::get('webApiKey', [PurchaseProductController::class, 'webApiKey']);
    Route::get('userOrdersByMonth', [PurchaseProductController::class, 'userOrdersByMonth']);
    Route::get('userOrdersByWeek', [PurchaseProductController::class, 'userOrdersByWeek']);
    Route::get('userOrdersByDay', [PurchaseProductController::class, 'userOrdersByDay']);
    Route::post('addToFavorite', [FavoriteController::class, 'addToFavorite']);
    Route::get('getFavorites', [ProductUserFavoriteController::class, 'getFavorites']);
    Route::post('isFavored', [ProductUserFavoriteController::class, 'isFavored']);
    Route::post('toggleFavorite', [ProductUserFavoriteController::class, 'toggleFavorite']);
    Route::post('updateUserBalance', [PurchaseProductController::class, 'updateUserBalance']);
    Route::post('returns', [ReturnsController::class, 'createReturns']);
    Route::apiResource('agent', AgentController::class);
    Route::get('agentUserId', [AgentController::class,'agentUserId']);
    Route::get('messages', [MessageController::class, 'messages']);
    Route::get('getMessagesCount', [MessageController::class, 'getMessagesCount']);
    Route::post('updateWatched', [MessageController::class, 'updateWatched']);
    Route::get('notifications', [NotificationController::class, 'notifications']);
    Route::post('addAppUserFcmTokenKey', [NotificationController::class, 'addAppUserFcmTokenKey']);
    Route::post('create-transfer-product', [PurchaseProductController::class, 'createTransferProduct']);
    Route::get('cash-statement-in-day',[CashStatementController::class,'cashStatementInDay']);
    Route::get('cash-statement-in-week',[CashStatementController::class,'cashStatementInWeek']);
    Route::get('cash-statement-in-month',[CashStatementController::class,'cashStatementInMonth']);
    Route::post('charge-agent-balance',[ChargeBalanceController::class,'chargeAgentBalance']);
    Route::get('getNotificationsCount',[NotificationController::class,'getNotificationsCount']);
    Route::post('updateNotificationsCount',[NotificationController::class,'updateNotificationsCount']);
    Route::get('ads',[AdController::class,'ads']);
    Route::get('unreaded-message',[MessageController::class,'getUnreadedMessage']);
    Route::get('super-user-profits-in-day',[ProfitsController::class,'superUserProfitsInDay']);
    Route::get('super-user-profits-in-week',[ProfitsController::class,'superUserProfitsInWeek']);
    Route::get('super-user-profits-in-month',[ProfitsController::class,'superUserProfitsInMonth']);
    Route::post('profits-from-agents-in-day',[ProfitsController::class,'profitsFromAgentInDay']);
    Route::post('profits-from-agents-in-week',[ProfitsController::class,'profitsFromAgentInWeek']);
    Route::post('profits-from-agents-in-month',[ProfitsController::class,'profitsFromAgentInMonth']);
    Route::post('withdraw-profits',[ProfitsController::class,'withdrawProfits']);
    Route::post('change-password',[PasswordController::class,'changePassword']);
    Route::post('ad-bars',[AdBarController::class,'adBars']);
});


Route::get('user-permissions', function (Request $request) {
    if ($request->user()->hasPermission('add-agent')) {
        return response()->json(['message' => true]);
    } else {
        return false;
    };
    // $request->user()->hasRole('user');
})->middleware('auth:sanctum');

Route::get('permissions', function (Request $request) {

    return AppUser::find(5)->permissions;
})->middleware('auth:sanctum');


Route::get('prices', function () {
    $user = auth()->user();
})->middleware('auth:sanctum');


Route::get('currency', function () {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.apilayer.com/exchangerates_data/latest?symbols=TRY&base=USD",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: text/plain",
            "apikey: Evh72W0yXkGYbmzz5QeWu1QcVP591qxF"
        ),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET"
    ));

    $response = curl_exec($curl);

    $data = json_decode($response);

    return $data->rates->TRY;

    curl_close($curl);
    echo $response;
});


Route::get(
    'brova',
    function () {
        $exception = DiscountException::all()->pluck('product_id')->toArray();

        if (in_array(2, $exception)) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }

        // return $exception;
    }
);

Route::get('orders', function () {
    return auth()->user()->orders;
})->middleware('auth:sanctum');






