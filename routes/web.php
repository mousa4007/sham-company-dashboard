<?php

use App\Http\Controllers\Auth\LogoutController;
use App\Http\Livewire\Agents;
use App\Http\Livewire\AppUsers;
use App\Http\Livewire\Categories;
use App\Http\Livewire\ChargeBalance;
use App\Http\Livewire\ApiProducts;
use App\Http\Livewire\Discounts;
use App\Http\Livewire\Home;
use App\Http\Livewire\Messages;
use App\Http\Livewire\OfficialAgentBalance;
use App\Http\Livewire\ProcessTransferProduct;
use App\Http\Livewire\StockedProducts;
use App\Http\Livewire\ProductPrices;
use App\Http\Livewire\Products;
use App\Http\Livewire\ProductsDiscounts;
use App\Http\Livewire\Returns;
use App\Http\Livewire\Sales;
use App\Http\Livewire\SubAgentBalance;
use App\Http\Livewire\TransferProducts;
use App\Http\Livewire\UpdateProductsDiscount;
use App\Http\Livewire\UsersDiscounts;
use App\Models\Agent;
use App\Models\AppUser;
use App\Models\Product;
use App\Models\StockedProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('livewire.home');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', Home::class);
    Route::get('/users', AppUsers::class);
    Route::get('/subAgentBalance', SubAgentBalance::class);
    Route::get('/officialAgentBalance', OfficialAgentBalance::class);
    Route::get('/categories', Categories::class);
    Route::get('/products', Products::class);
    Route::get('/stockedProducts', StockedProducts::class);
    Route::get('/transferProducts', TransferProducts::class);
    Route::get('/processTransferProducts', ProcessTransferProduct::class);
    Route::get('/productPrices', ProductPrices::class);
    Route::get('/agents', Agents::class);
    Route::get('/discounts', Discounts::class);
    Route::get('/usersDiscounts', UsersDiscounts::class);
    Route::get('/productsDiscounts', ProductsDiscounts::class);
    Route::get('/updateProductsDiscounts', UpdateProductsDiscount::class);
    Route::get('/api-products', ApiProducts::class);
    Route::get('/sales', Sales::class);
    Route::get('/returns', Returns::class);

    Route::get('/messages', Messages::class);
    Route::get('/logout', [LogoutController::class, 'logout']);
});

require __DIR__ . '/auth.php';


Route::get('prod', function () {

    $prod = Product::all();

    return $prod;
});
