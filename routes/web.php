<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\KomerceProxyController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\TransferProofController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\DashboardOrderController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Admin\DashboardProductController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\DashboardCategoryController;

/* -----------------------------------------------------------
 | STATIC PAGES
 ----------------------------------------------------------- */
Route::view('/',             'home'        )->name('home');
Route::view('/about',        'about'       )->name('about');
Route::view('/contact',      'contact'     )->name('contact');


/* -----------------------------------------------------------
 | PASSWORD RESET
 ----------------------------------------------------------- */
//  Route::middleware(['auth','verified'])->group(function () {
Route::get ('/reset-password/{token}',[NewPasswordController::class,      'create'])->name('password.reset');
Route::put('/reset-password',        [PasswordController::class,      'update' ])->name('password.update');
// });
Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
Route::get ('/forgot-password',       [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password',       [PasswordResetLinkController::class, 'store' ])->name('password.email');

/* -----------------------------------------------------------
 | PRODUCTS & CATEGORIES (PUBLIC)
 ----------------------------------------------------------- */
Route::get('/product',                [ProductController::class, 'index'])->name('product.index');
Route::get('/product/{product:slug}', [ProductController::class, 'show' ])->name('product.show');
// Review (public endpoint – tetap seperti semula)
Route::post('/product/{product}/review', [ReviewController::class, 'store'])->name('review.store');
Route::get('/category',               [CategoryController::class,'index'])->name('category.index');
Route::get('/category/{slug}',        [CategoryController::class,'show' ])->name('category.show');

/* -----------------------------------------------------------
 | AUTHENTICATION
 ----------------------------------------------------------- */
Route::middleware('guest')->group(function () {
    Route::get ('/login',    [LoginController::class, 'index'])->name('login');
    Route::post('/login',    [LoginController::class, 'authenticate']);
    Route::get ('/register', [RegisterController::class,'index'])->name('register.index');
    Route::post('/register', [RegisterController::class,'store'])->name('register.store');
});
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

/* Email verification */
Route::middleware('auth')->group(function () {
    Route::view('/email/verify', 'auth.verify-email')->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message','Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');
});

/* -----------------------------------------------------------
 | CART
 ----------------------------------------------------------- */
Route::middleware(['auth','verified'])->group(function () {
    Route::get   ('/cart',                    [CartController::class,'index'        ])->name('cart.index');
    Route::post  ('/cart/{product:slug}',     [CartController::class,'addToCart'    ])->name('cart.add');
    Route::delete('/cart/{cartItem}',         [CartController::class,'removeFromCart'])->name('cart.remove');
    Route::post  ('/cart/{cartItem}/update',  [CartController::class,'updateQuantity'])->name('cart.update');
    Route::get   ('/cart/checkout',           [CartController::class,'checkout'     ])->name('cart.checkout');
});

/* -----------------------------------------------------------
 | ORDER + REVIEW (AUTH)
 ----------------------------------------------------------- */
Route::middleware(['auth','verified'])
      ->prefix('order')->name('order.')->group(function () {

    Route::get('/',                [OrderController::class,'index'])->name('index');
    Route::get('/{order}',         [OrderController::class,'show' ])->name('show');
    Route::post('/{order}/repeat', [OrderController::class,'repeatOrder'])->name('repeat');

    Route::put ('/payment-type/{order}',      [OrderController::class,'updatePaymentType'])->name('updatePaymentType');
    Route::post('/{order}/cancel',            [OrderController::class,'cancel'])->name('cancel');
    Route::post('/{order}/upload-corporate-proof', [OrderController::class,'uploadCorporateProof'])->name('uploadCorporateProof');

    Route::post('/update-shipping-details',   [OrderController::class,'updateShippingDetails'])->name('updateShippingDetails');
    Route::post('/{order}/upload-details',    [OrderController::class,'uploadDetails'])->name('uploadDetails');
    Route::put('/{order}/receive',            [OrderController::class, 'markAsReceived'])->name('receive');
    /* Review endpoint kedua (seperti semula) */
    Route::post('/{order}/reviews', [ReviewController::class,'store']) ->name('reviews.store');   // ← hanya menambah {order}
    Route::get('/order-file/{order}/{type}',   // type = design | logo
        [\App\Http\Controllers\OrderController::class, 'showFile'])
        ->middleware(['auth','verified'])
        ->name('file');
});

Route::post('/order/destination-search', [OrderController::class,'searchDestination'])->name('order.destinationSearch');
Route::post('/order/calculate-ongkir',   [OrderController::class,'calculateShipping'])->name('order.calculateShipping');
Route::put ('/order/{order}/confirm-all',[OrderController::class,'confirmAll'])->name('order.confirmAll');

/* -----------------------------------------------------------
 | ADDRESSES (AUTH)  – hanya satu resource
 ----------------------------------------------------------- */
Route::middleware(['auth','verified'])->group(function () {
    Route::resource('addresses', AddressController::class)->except(['show']);
    Route::post('addresses/{address}/set-default', [AddressController::class,'setDefault'])->name('addresses.set-default');
    Route::get ('/get-komerce-postal', [AddressController::class,'getKomercePostal']);
});

/* -----------------------------------------------------------
 | PAYMENT (AUTH)
 ----------------------------------------------------------- */
Route::middleware(['auth','verified'])
      ->prefix('payment')->name('payment.')->group(function () {

    Route::get ('/{order}',             [PaymentController::class,'show'            ])->name('show');
    Route::post('/process/{order}',     [PaymentController::class,'processPayment' ])->name('process');
    Route::post('/choose-stage/{order}',[PaymentController::class,'choosePaymentStage'])->name('chooseStage');
    Route::post('/upload-proof/{order}',[PaymentController::class,'uploadTransferProof'])->name('uploadProof');

    Route::get ('/success/{order}',     [PaymentController::class,'success'         ])->name('success'); // <-- hanya satu definisi

    Route::get ('/pay-off/{transaction}', [PaymentController::class,'showPayOffForm'])->name('payoff.form');
    Route::post('/pay-off/{transaction}', [PaymentController::class,'submitPayOff' ])->name('payoff.submit');

    Route::get ('/complete/{order}',     [PaymentController::class,'showCompletionForm'])->name('complete');
    Route::post('/complete/{order}',     [PaymentController::class,'submitCompletion' ])->name('complete.submit');
    Route::post('/confirm/{orderId}',    [PaymentController::class,'confirm'])->name('confirm');
});

/* -----------------------------------------------------------
 | PROFILE (AUTH)
 ----------------------------------------------------------- */
Route::middleware(['auth','verified'])->group(function () {
    Route::get ('/profile',                [ProfileController::class,'index'        ])->name('profile.index');
    Route::get ('/profile/edit',           [ProfileController::class,'edit'         ])->name('profile.edit');
    Route::post('/profile/update',         [ProfileController::class,'update'       ])->name('profile.update');
    // Route::get ('/profile/change-password',[ProfileController::class,'changePassword'])->name('profile.change-password');
    // Route::post('/profile/update-password',[ProfileController::class,'updatePassword'])->name('profile.update-password');
    Route::delete('/profile/destroy',        [ProfileController::class,'destroy'      ])->name('profile.destroy');
});

/* -----------------------------------------------------------
 | USER TRANSACTION HISTORY (AUTH)
 ----------------------------------------------------------- */
Route::middleware(['auth','verified'])
      ->get('/myorder', [OrderController::class, 'list'])->name('order.list');          // <-- satu-satunya /myorder
Route::middleware(['auth','verified'])
      ->get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

/* -----------------------------------------------------------
 | ADMIN DASHBOARD
 ----------------------------------------------------------- */
Route::prefix('dashboard')->middleware(['auth', AdminMiddleware::class])->name('dashboard.')->group(function () {

    /* HOME */
    Route::get('/',        [DashboardController::class,'index'])->name('index');
    Route::get('/export-pdf',   [DashboardController::class,'exportPdf' ])->name('export.pdf');
    Route::get('/export-excel', [DashboardController::class,'exportExcel'])->name('export.excel');

    /* INVOICE */
    Route::get('/invoice/{order}/preview',  [InvoiceController::class,'preview' ])->name('invoice.preview');
    Route::get('/invoice/{order}/download', [InvoiceController::class,'download'])->name('invoice.download');
    Route::get('/invoice/{order}/send',     [InvoiceController::class,'send'    ])->name('invoice.send');

    /* ORDERS */
    Route::get ('/orders',                 [DashboardOrderController::class,'index' ])->name('order.index');
    Route::get ('/orders/{order}',         [DashboardOrderController::class,'show'  ])->name('order.show');
    Route::post('/orders/{order}/update-status', [DashboardOrderController::class,'updateStatus'])->name('order.update-status');
    Route::get ('/orders/{order}/export-invoice', [DashboardOrderController::class,'exportInvoice'])->name('order.export-invoice');
    Route::post('/orders/bulk-delete',     [DashboardOrderController::class,'bulkDelete'])->name('order.bulk-delete');
    Route::get ('/exports',                [DashboardOrderController::class,'exportsExcel'])->name('order.exports');
    Route::get('/dashboard/order/{order}/pdf',[DashboardOrderController::class, 'downloadPdf'])->name('order.pdf');
    /* SALES */
    Route::get ('/sales',               [SalesReportController::class,'index'     ])->name('sales.index');
    Route::get ('/sales/export-pdf',    [SalesReportController::class,'exportPdf' ])->name('sales.export-pdf');
    Route::get ('/sales/export-excel',  [SalesReportController::class,'exportExcel'])->name('sales.export-excel');

    /* SHIPPING */
    Route::get('shipping', [ShippingController::class, 'index'])->name('shipping.index');
    Route::get('shipping/{order}/edit', [ShippingController::class, 'edit'])->name('shipping.edit');
    Route::put('shipping/{order}', [ShippingController::class, 'update'])->name('shipping.update');

    /* PRODUCTS & CATEGORIES */
    // Route::resource('products',  DashboardProductController::class)->except(['show']);
    Route::resource('products', DashboardProductController::class)->parameters(['products' => 'product:slug']); 
    Route::resource('categories',DashboardCategoryController::class)->except(['show']);

    /* TRANSACTIONS */
    Route::resource('transactions', TransactionController::class);
    Route::post('transactions/{transaction}/verify', [TransactionController::class,'verify'])->name('transactions.verify');
    
    Route::get('/transfer-proof/{transaction}/{stage?}', [TransferProofController::class, 'show'])
    ->middleware(['auth','verified'])
    ->name('transfer-proof.show');
require __DIR__.'/auth.php';
});
