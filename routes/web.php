<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    HomeController,
    RoleController,
    FederalController,
    RegionController,
    ZoneController,
    WoredaController,
    KebeleController,
    UserController,
    ProductController,
    ProfileController,
    ShopController,
    CategoryController,
    CartController,
    CustomerController,
    PermissionController,
    OrderController,
    ReportController,
};

// Home route
Route::get('/logout', fn () => view('auth.login'));
Route::get('/logout', [HomeController::class, 'logout']);
// Authentication routes
Auth::routes();

// Home route
Route::get('/', fn () => view('auth.login'));

Route::get('/home', [HomeController::class, 'index'])->name('home');
// Guest routes
// Guest routes
Route::middleware('guest')->group(function () {
    Route::resources([
        'users' => UserController::class,

    ]);

});

// Middleware for authenticated users
Route::middleware('auth')->group(function () {
    // Resource routes
    Route::resources([
        'roles' => RoleController::class,
       'users' => UserController::class,
        'products' => ProductController::class,
        'shops' => ShopController::class,
        'category' => CategoryController::class,
        'permissions' => PermissionController::class,
        'federals' => FederalController::class,
        'regions' => RegionController::class,
        'zones' => ZoneController::class,
        'woredas' => WoredaController::class,
        'kebeles' => KebeleController::class,
    ]);
    Route::get('/orders/{order}/receipt', function (\App\Models\Orders $order) {
    $pdf = Pdf::loadView('pdf.order_receipt', ['order' => $order]);
    return $pdf->download('order_receipt_' . $order->id . '.pdf');})->name('orders.receipt');


    // Report routes
    Route::prefix('reports')->group(function () {
        Route::get('/daily', [ReportController::class, 'indexDaily'])->name('dailyreport.index');
        Route::get('/monthly', [ReportController::class, 'indexMonthly'])->name('monthlyreport.index');
        Route::get('/yearly', [ReportController::class, 'indexYearly'])->name('yearlyreport.index');
        Route::get('/reports/daily/download-pdf', [ReportController::class, 'downloadDailyPdf'])->name('dailyreport.downloadPdf');
        Route::get('/reports/monthly/download-pdf', [ReportController::class, 'downloadMonthlyPdf'])->name('monthlyreport.downloadPdf');
        Route::get('/reports/yearly/download-pdf', [ReportController::class, 'downloadYearlyPdf'])->name('yearlyreport.downloadPdf');
 });

    // User import/export routes
    Route::get('users/import', [UserController::class, 'import'])->name('users.import');
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');

    // Order routes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index'); // View orders
        Route::get('/create', [OrderController::class, 'create'])->name('create'); // Create order form
        Route::post('/', [OrderController::class, 'store'])->name('store'); // Store new order
        Route::get('/{order}', [OrderController::class, 'show'])->name('show'); // Show specific order
        Route::put('/{order}', [OrderController::class, 'update'])->name('update'); // Update order
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy'); // Delete order

        // Additional Routes for Product Ordering
        Route::post('/products/add', [OrderController::class, 'addProductToOrder'])->name('addProduct'); // Add product to order
        Route::post('/products/remove', [OrderController::class, 'removeProductFromOrder'])->name('removeProduct'); // Remove product from order
        Route::get('/products/{order}', [OrderController::class, 'listOrderProducts'])->name('listProducts'); // List products in an order

        // Approval and Cancellation of Orders
        Route::post('/{order}/approve', [OrderController::class, 'approveOrder'])->name('orders.updateStatus');
        Route::post('/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('cancel'); // Cancel an order
        Route::post('/orders/update-status/{order}', [OrderController::class, 'approveOrder'])->name('updateStatus');
    });

    // Cart routes
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::post('/{productId}/add', [CartController::class, 'addToCart'])->name('add');
        Route::get('/', [CartController::class, 'index'])->name('index');
    });

    // Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
    });

    // Shop routes
    Route::prefix('shops')->name('shops.')->group(function () {
        Route::get('/{shop}/download-license', [ShopController::class, 'downloadLicense'])->name('downloadLicense');
        Route::get('/{shop}/view-license', [ShopController::class, 'viewLicense'])->name('viewLicense');
        Route::get('/{shop}/status', [ShopController::class, 'showStatus'])->name('showStatus');
        Route::patch('/{shop}/status', [ShopController::class, 'changeStatus'])->name('changeStatus');
    });

    // Nested resources (Kebeles -> Shops)
    Route::resource('kebeles.shops', ShopController::class)->except(['show']);

    // Admin Assignment Routes
    Route::prefix('assign-admin')->group(function () {
        Route::post('/federals', [FederalController::class, 'assignadmin'])->name('federals.assignadmin');
        Route::get('/federals', [FederalController::class, 'showAssignAdminForm'])->name('federals.assignAdminForm');
        Route::post('/regions/{region}', [RegionController::class, 'storeAdmin'])->name('regions.assignadmin');
        Route::get('/regions/{region}', [RegionController::class, 'assignAdmin'])->name('regions.assignAdmin');
        Route::post('/zones/{zone}', [ZoneController::class, 'storeAdmin'])->name('zones.storeAdmin');
        Route::get('/zones/{zone}', [ZoneController::class, 'assignAdmin'])->name('zones.assignAdmin');
        Route::post('/woredas/{woreda}', [WoredaController::class, 'storeAdmin'])->name('woredas.storeAdmin');
        Route::get('/woredas/{woreda}', [WoredaController::class, 'assignAdmin'])->name('woredas.assignAdmin');
        Route::post('/kebeles/{kebele}', [KebeleController::class, 'storeAdmin'])->name('kebeles.storeAdmin');
        Route::get('/kebeles/{kebele}', [KebeleController::class, 'assignAdmin'])->name('kebeles.assignAdmin');
    });

    // Location routes
    Route::get('/zones/{region}', [UserController::class, 'getZones']);
    Route::get('/woredas/{zone}', [UserController::class, 'getWoredas']);
    Route::get('/kebeles/{woreda}', [UserController::class, 'getKebeles']);
});


Route::middleware('web')->group(function () {
 Route::get('lang/{locale}', function ($locale) {
    session(['locale' => $locale]);
    return redirect()->back();
})->name('lang.switch');

    // Your other routes...
});
Route::get('/test-session', function () {
    session(['locale' => 'am']);
    return 'Locale set to ' . session('locale');
});

Route::get('/read-session', function () {
    return 'Current locale: ' . session('locale');
});
Route::post('/notifications/mark-as-read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.markAsRead')->middleware('auth');


// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register/c', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/register/customer', [CustomerController::class, 'register'])->name('customer.register');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

});
