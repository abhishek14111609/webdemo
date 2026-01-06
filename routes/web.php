<?php

use App\Http\Controllers\Admin\AdminAboutController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCollectionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

// Public Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\OtpPasswordController;

// User Controllers
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\ReviewController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminInquiryController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminSliderController;
use App\Http\Controllers\OrderController;
use App\Models\User;


/* ======================================================
|  PUBLIC ROUTES
|======================================================*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/collections', [CollectionController::class, 'index'])->name('collections');
Route::get('/collection/{slug}', [CollectionController::class, 'show'])->name('collection.show');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category');

Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', [InquiryController::class, 'store'])->name('contact.submit')->middleware('throttle:3,1');


/* ======================================================
|  AUTHENTICATION & EMAIL VERIFICATION
|======================================================*/

// Login
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $user = User::where('email', $credentials['email'])->first();

    if ($user && Hash::check($credentials['password'], $user->password)) {
        if (is_null($user->email_verified_at)) {
            if (method_exists($user, 'sendEmailVerificationNotification')) {
                $user->sendEmailVerificationNotification();
            }
            return back()->withErrors([
                'email' => 'Email not verified. A new verification link has been sent.',
            ])->with('unverified', true)->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
    }

    return back()->withErrors([
        'email' => 'Invalid credentials.',
    ])->onlyInput('email');
})->middleware('throttle:5,1'); // 5 attempts per minute

// Register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password Reset (Link)
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');

// Password Reset (OTP)
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password-otp', [OtpPasswordController::class, 'showRequestForm'])->name('password.otp.request');
    Route::post('/forgot-password-otp', [OtpPasswordController::class, 'sendOtp'])->middleware('throttle:3,1')->name('password.otp.send');
    Route::get('/reset-password-otp', [OtpPasswordController::class, 'showResetForm'])->name('password.otp.reset.form');
    Route::post('/reset-password-otp', [OtpPasswordController::class, 'resetWithOtp'])->middleware('throttle:6,1')->name('password.otp.reset');
});

// Resend Email Verification
Route::post('/email/resend', function (Request $request) {
    $data = $request->validate(['email' => ['required', 'email']]);
    $user = User::where('email', $data['email'])->first();

    if (!$user)
        return back()->withErrors(['email' => 'No user found.']);
    if (!is_null($user->email_verified_at))
        return back()->with('status', 'Already verified.');

    if (method_exists($user, 'sendEmailVerificationNotification')) {
        $user->sendEmailVerificationNotification();
    }

    return back()->with('status', 'Verification link sent!')->onlyInput('email');
})->middleware('throttle:3,1')->name('verification.resend');

// Email Verification Callback
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);
    if (!$request->hasValidSignature() || !hash_equals(sha1($user->email), (string) $hash)) {
        abort(403, 'Invalid or expired verification link.');
    }

    if (is_null($user->email_verified_at)) {
        $user->forceFill(['email_verified_at' => now()])->save();
    }

    return redirect()->route('login')->with('status', 'Email verified successfully!');
})->middleware(['throttle:6,1'])->name('verification.verify');


/* ======================================================
|  AUTHENTICATED USER ROUTES
|======================================================*/
Route::middleware('auth')->group(function () {

    /* ---- CART ---- */
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add/{product}', [CartController::class, 'addToCart'])->name('cart.add')->middleware('throttle:30,1');
        Route::put('/{cartItem}', [CartController::class, 'update'])->name('cart.update')->middleware('throttle:60,1');
        Route::delete('/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy')->middleware('throttle:60,1');
        Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear')->middleware('throttle:10,1');
    });

    /* ---- WISHLIST ---- */
    Route::prefix('wishlist')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('wishlist');
        Route::post('/add/{product}', [WishlistController::class, 'addToWishlist'])->name('wishlist.add')->middleware('throttle:30,1');
        Route::delete('/remove/{id}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove')->middleware('throttle:60,1');
        Route::post('/move-to-cart/{id}', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart')->middleware('throttle:30,1');
        Route::patch('/update-notes/{id}', [WishlistController::class, 'updateNotes'])->name('wishlist.updateNotes')->middleware('throttle:20,1');
    });




    Route::match(['get', 'post'], '/track-order', [OrderController::class, 'track'])->name('orders.track');

    /* ---- CHECKOUT ---- */
    Route::prefix('checkout')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process')->middleware('throttle:10,1');
        Route::post('/razorpay-payment', [CheckoutController::class, 'razorpayPayment'])->name('checkout.razorpay.payment')->middleware('throttle:10,1');
        Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
        Route::get('/bill/{orderId}', [CheckoutController::class, 'bill'])->name('checkout.bill');
        Route::get('/checkout/bill/{orderId}/download', [CheckoutController::class, 'downloadBill'])->name('checkout.bill.download');
    });

    /* ---- RAZORPAY ---- */
    Route::post('/razorpay/create-order', [RazorpayController::class, 'createOrder'])->name('razorpay.createOrder');

    /* ---- REVIEWS ---- */
    Route::resource('reviews', ReviewController::class)->only(['store', 'update', 'destroy'])->middleware('throttle:10,1');

    /* ---- PROFILE ---- */
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    });

    /* ---- ACCOUNT ---- */
    Route::view('/account', 'account')->name('account');

    /* ---- LOGOUT ---- */
    Route::post('/logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});


/* ======================================================
|  ADMIN ROUTES
|======================================================*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::get('/', fn() => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

        // Users
        Route::resource('users', AdminUserController::class)->except(['index']);
        Route::get('/users', [AdminUserController::class, 'index'])->name('users');

        // Products
        Route::resource('products', AdminProductController::class);
        Route::post('products/bulk-action', [AdminProductController::class, 'bulkAction'])->name('products.bulk-action');

        // Orders
        Route::resource('orders', AdminOrderController::class)->except(['index']);
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
        Route::get('orders/{order}/invoice', [AdminOrderController::class, 'generateInvoice'])->name('orders.invoice');
        Route::post('orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

        // Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

        // About Us
        Route::resource('about', App\Http\Controllers\Admin\AdminAboutController::class)->parameters([
            'about' => 'about'
        ]);

        // About Us Management
        Route::prefix('about-management')->name('about-management.')->group(function () {
            Route::get('/', [AdminAboutController::class, 'index'])->name('index');
            Route::get('/create', [AdminAboutController::class, 'create'])->name('create');
            Route::post('/store', [AdminAboutController::class, 'store'])->name('store');
            Route::get('/{about}/edit', [AdminAboutController::class, 'edit'])->name('edit');
            Route::put('/{about}', [AdminAboutController::class, 'update'])->name('update');
            Route::delete('/{about}', [AdminAboutController::class, 'destroy'])->name('destroy');
        });

        // Inquiries
        Route::prefix('inquiries')->name('inquiries.')->group(function () {
            Route::get('/', [AdminInquiryController::class, 'index'])->name('index');
            Route::get('/{inquiry}', [AdminInquiryController::class, 'show'])->name('show');
            Route::put('/{inquiry}', [AdminInquiryController::class, 'update'])->name('update');
            Route::delete('/{inquiry}', [AdminInquiryController::class, 'destroy'])->name('destroy');
            Route::get('/{inquiry}/respond', [AdminInquiryController::class, 'respond'])->name('respond');
            Route::post('/{inquiry}/send-response', [AdminInquiryController::class, 'sendResponse'])->name('send-response');
            Route::post('/{inquiry}/mark-read', [AdminInquiryController::class, 'markAsRead'])->name('mark-read');
            Route::post('/{inquiry}/mark-unread', [AdminInquiryController::class, 'markAsUnread'])->name('mark-unread');
            Route::post('/bulk-action', [AdminInquiryController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/templates/response', [AdminInquiryController::class, 'getResponseTemplates'])->name('templates');
        });

        // Sliders
        Route::resource('sliders', AdminSliderController::class)->except(['show']);
        Route::post('sliders/update-order', [AdminSliderController::class, 'updateOrder'])->name('sliders.update-order');

        // Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

        // Categories Management
        Route::resource('categories', AdminCategoryController::class)->parameters([
            'categories' => 'category'
        ]);

        // Collections Management
        Route::resource('collections', AdminCollectionController::class)->parameters([
            'collections' => 'collection'
        ]);
        Route::get('collections/{collection}/manage-products', [AdminCollectionController::class, 'manageProducts'])->name('collections.manage-products');
        Route::post('collections/{collection}/update-products', [AdminCollectionController::class, 'updateProducts'])->name('collections.update-products');
        Route::post('collections/update-order', [AdminCollectionController::class, 'updateOrder'])->name('collections.update-order');
    });


/* ======================================================
|  FALLBACK (404)
|======================================================*/
Route::fallback(fn() => view('errors.404'));



//  // Inquiries
//  Route::prefix('inquiries')->name('inquiries.')->group(function () {
//     Route::get('/', [AdminInquiryController::class, 'index'])->name('index');
//     Route::get('/create', [AdminInquiryController::class, 'create'])->name('create');
//     Route::post('/', [AdminInquiryController::class, 'store'])->name('store');
//     Route::get('/{inquiry}', [AdminInquiryController::class, 'show'])->name('show');
//     Route::get('/{inquiry}/edit', [AdminInquiryController::class, 'edit'])->name('edit');
//     Route::put('/{inquiry}', [AdminInquiryController::class, 'update'])->name('update');
//     Route::delete('/{inquiry}', [AdminInquiryController::class, 'destroy'])->name('destroy');
//     Route::get('/{inquiry}/respond', [AdminInquiryController::class, 'respond'])->name('respond');
//     Route::post('/{inquiry}/send-response', [AdminInquiryController::class, 'sendResponse'])->name('send-response');
//     Route::post('/{inquiry}/mark-read', [AdminInquiryController::class, 'markAsRead'])->name('mark-read');
//     Route::post('/{inquiry}/mark-unread', [AdminInquiryController::class, 'markAsUnread'])->name('mark-unread');
//     Route::post('/bulk-action', [AdminInquiryController::class, 'bulkAction'])->name('bulk-action');
//     Route::get('/templates/response', [AdminInquiryController::class, 'getResponseTemplates'])->name('templates');
// });