<?php

use App\Http\Controllers\Admin\AdminCoachController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\TicketTypeController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Api\CoachController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PostCommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PostReactController;

/*
#####################################################################
#	Nhân quả không nợ chúng ta thứ gì, cho nên xin đừng oán giận	#
#                                _oo0oo_							#
#                               088888880							#
#                               88" . "88							#
#                               (| o o |)							#
#                                0\ = /0							#
#                             ___/'---'\___							#
#                           .' \\|     |// '. 						#
#                          / \\|||  :  |||// \\						#
#                         /_ ||||| -:- |||||- \\					#
#                        |   | \\\  -  /// |   |					#
#                        | \_|  ''\---/''  |_/ |					#
#                        \  .-\__  '-'  __/-.  /					#
#                      ___'. .'  /--.--\  '. .'___					#
#                   ."" '<  '.___\_<|>_/___.' >'  "". 				#
#                  | | : '-  \'.;'\ _ /';.'/ - ' : | |				#
#                  \  \ '_.   \_ __\ /__ _/   .-' /  /				#
#           =========='-.____'.___ \_____/___.-'____.-'==========	#
#       	                    '=---='								#
#																	#
#            ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
Đức Phật nơi đây phù hộ code con chạy không Bug. Nam mô a di đà Phật
*/

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
Route::prefix('/auth')->group(function(){
    Route::post('/register', [UserAuthController::class, 'userRegister']);
    Route::post('/login', [UserAuthController::class, 'userLogin']);
    Route::post('/logout', [UserAuthController::class, 'userLogout'])->middleware('auth:sanctum');
    Route::get('/user', [UserAuthController::class, 'getInfo'])->middleware('auth:users');
    Route::prefix('/admin')->group(function(){
        Route::post('/login', [AdminAuthController::class, 'adminLogin']);
        Route::get('/info', [AdminAuthController::class, 'getInfo'])->middleware('auth:admins');
    });
});

Route::resource('/payments', PaymentMethodController::class)->middleware('auth:sanctum');

Route::prefix('/user')->middleware('auth:sanctum')->group( function(){
    Route::patch('/update', [UserAuthController::class, 'update']);
    Route::post('/update/change_password', [UserAuthController::class, 'changePassword']);
    Route::post('/update/update_avatar', [UserAuthController::class, 'updateAvatar']);
    Route::resource('/stores', ProductController::class);

    // cart-controller
    Route::post('/carts/delete_all_cart', [CartController::class, 'deleteAllCart']);
    Route::resource('/carts', CartController::class);
    // order-controller
    Route::resource('/orders', OrderController::class);

    //Ticket
    Route::post('/tickets/check_exists', [TicketController::class, 'checkTicket']);
    Route::resource('/tickets', TicketController::class);
    Route::resource('/ticket_types', TicketTypeController::class);

    //POSTS
    Route::resource('/posts/comments', PostCommentController::class);
    Route::resource('/posts/reacts', PostReactController::class);
    Route::resource('/posts', PostController::class);

    //Notification
    Route::resource('/notifications', NotificationController::class);

    // COACH
    Route::resource('/coachs', CoachController::class);

});

Route::prefix('/admin')->middleware('auth:admins')->group( function(){
    Route::resource('/stores', ProductController::class);

    // product-controller
    Route::post('/products/update_image/{id}', [AdminProductController::class, 'updateImageProduct']);
    Route::post('/products/create_image/{name}', [AdminProductController::class, 'createImageProduct']);
    Route::resource('/products', AdminProductController::class);
    // order-controller
    Route::resource('/orders', AdminOrderController::class);
    //Service-Ticket
    Route::resource('/tickets', AdminTicketController::class);
    //User
    Route::post('/users/updata_avatar/{id}',[AdminUserController::class, 'updateImageUser']);
    Route::resource('/users', AdminUserController::class);
    //Coach
    Route::post('/coachs/update_avatar/{id}', [AdminCoachController::class, 'updateAvatarCoach']);
    Route::post('/coachs/create_avatar/{name}', [AdminCoachController::class, 'createAvatarCoach']);
    Route::resource('/coachs', AdminCoachController::class);

    // POST
    Route::post('/posts/accept_all', [AdminPostController::class, 'accept_all']);
    Route::post('/posts/delete_image/{id}', [AdminPostController::class, 'delete_image']);
    Route::resource('/posts', AdminPostController::class);
});

// Route::middleware('auth:sanctum')->get()

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
