<?php

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

Route::prefix('{locale}/v1')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::post('login', [App\Http\Controllers\Api\AdminAuthController::class, 'login']);

        Route::middleware('auth:admin_api', 'admin.auth.api')->group(function () {
            Route::get('/', [App\Http\Controllers\Api\AdminAuthController::class, 'getAdmin']);
            Route::post('logout', [App\Http\Controllers\Api\AdminAuthController::class, 'logout']);

            // Partners
            Route::get('partners', [App\Http\Controllers\Api\AdminPartnerController::class, 'getPartners']);
            Route::get('partner/{partnerId}', [App\Http\Controllers\Api\AdminPartnerController::class, 'getPartner']);
            Route::put('partner/{partnerId}', [App\Http\Controllers\Api\AdminPartnerController::class, 'updatePartner']);
        });
    });
    Route::prefix('partner')->group(function () {
        Route::post('login', [App\Http\Controllers\Api\PartnerAuthController::class, 'login']);

        Route::middleware('auth:partner_api', 'partner.auth.api')->group(function () {
            Route::get('/', [App\Http\Controllers\Api\PartnerAuthController::class, 'getPartner']);
            Route::put('/', [App\Http\Controllers\Api\PartnerController::class, 'update']);
            Route::post('logout', [App\Http\Controllers\Api\PartnerAuthController::class, 'logout']);
            Route::get('clubs', [App\Http\Controllers\Api\PartnerClubController::class, 'getClubs']);
            Route::get('clubs/{clubId}', [App\Http\Controllers\Api\PartnerClubController::class, 'getClub']);
            Route::get('cards', [App\Http\Controllers\Api\PartnerCardController::class, 'getCards']);
            Route::get('cards/{cardId}', [App\Http\Controllers\Api\PartnerCardController::class, 'getCard']);
            Route::post('cards/{cardUID}/{memberUID}/transactions/purchases', [App\Http\Controllers\Api\PartnerTransactionController::class, 'addPurchase']);
            Route::post('cards/{cardUID}/{memberUID}/transactions/points', [App\Http\Controllers\Api\PartnerTransactionController::class, 'addPoints']);
            Route::get('staff', [App\Http\Controllers\Api\PartnerStaffController::class, 'getStaff']);
            Route::get('staff/{staffId}', [App\Http\Controllers\Api\PartnerStaffController::class, 'getStaffMember']);
        });
    });
    Route::prefix('member')->group(function () {
        Route::post('login', [App\Http\Controllers\Api\MemberAuthController::class, 'login']);
        Route::post('register', [App\Http\Controllers\Api\MemberAuthController::class, 'register']);

        Route::middleware('auth:member_api', 'member.auth.api')->group(function () {
            Route::get('/', [App\Http\Controllers\Api\MemberAuthController::class, 'getMember']);
            Route::post('logout', [App\Http\Controllers\Api\MemberAuthController::class, 'logout']);
            Route::get('all-cards', [App\Http\Controllers\Api\MemberCardController::class, 'getAllCards']);
            Route::get('followed-cards', [App\Http\Controllers\Api\MemberCardController::class, 'getFollowedCards']);
            Route::get('transacted-cards', [App\Http\Controllers\Api\MemberCardController::class, 'getTransactedCards']);
            Route::get('balance/{cardId}', [App\Http\Controllers\Api\MemberCardController::class, 'getMemberBalance']);
        });
    });
});
