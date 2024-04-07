<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Fetch menu items
Route::get('/menu', [MenuController::class, 'index']);

// Fetch categories for the menu
Route::get('/categories', [CategoryController::class, 'index']);

// Fetch all products by category
Route::get('/products/{categoryId}', [ProductController::class, 'showByCategory']);

// Get help information
Route::get('/help', [HelpController::class, 'index']);

// Get all flavors by category
Route::get('/categories/{category}/flavors', [CategoryController::class, 'flavors']);

// Get all add-ons by category
Route::get('/categories/{category}/add-ons', [CategoryController::class, 'addOns']);

// Fetch order summary
Route::get('/order/summary', [OrderController::class, 'fetchOrderSummary']);

// Update order
Route::post('/order/update', [OrderController::class, 'updateOrder']);

// Clear cart (cancel order)
Route::post('/order/cancel', [OrderController::class, 'cancelOrder']);

// Define a route to fetch an order by ID
Route::get('/orders/{orderId}', [OrderController::class, 'fetchOrder']);

// Set Awaiting Payment Status
Route::post('/orders/{order}/awaiting-payment', [OrderController::class, 'setAwaitingPayment'])->name('orders.awaiting-payment');

// Set payment confirmation status
Route::post('/payments/confirm', [OrderController::class, 'confirmPayment'])->name('payments.confirm');

// Create Order
Route::post('/orders/create', [OrderController::class, 'createOrder']);

// Initialize or retrieve an existing order for the customer
Route::post('/orders/init', [OrderController::class, 'initializeOrder']);

// Add item to order (part of Order Creation step)
Route::post('/orders/{order}/addItem', [OrderController::class, 'addItemToOrder']);

// Manage Order Preparation - Fetch orders ready for preparation
Route::get('/orders/ready-for-preparation', [OrderController::class, 'ordersReadyForPreparation']);

// Update individual order item status by kitchen staff
Route::post('/order-items/{orderItem}/update-status', [OrderController::class, 'updateOrderItemStatus']);

// Update Order Pickup Status - Mark order as ready for pickup
Route::post('/orders/{order}/ready-for-pickup', [OrderController::class, 'setOrderReadyForPickup']);

Route::resource('/users', UserController::class);

Route::resource('/roles', RoleController::class);

// Feedbacks
Route::get('/feedbacks', [FeedbackController::class, 'index']);
Route::post('/feedbacks/respond', [FeedbackController::class, 'respondToFeedback']);

// Reporting
Route::get('/reports/sales', [ReportController::class, 'salesReport']);
Route::get('/reports/financial', [ReportController::class, 'financialReport']);
