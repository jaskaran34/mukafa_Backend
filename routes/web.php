<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', '\App\Http\Controllers\I18n\LocaleController@redirectToLocale')->name('redir.locale');   //

Route::prefix('{locale}')->group(function () {
    Route::get('scripts/language.js', '\App\Http\Controllers\Javascript\IncludeController@language')->name('javascript.include.language');

    // Set cookie consent
    Route::post('/set-cookie/{value?}', '\App\Http\Controllers\Cookie\CookieController@setConsentCookie')->name('set.consent.cookie.post');

    Route::group(['middleware' => 'installed', 'namespace' => '\App\Http\Controllers'], function () {

        // Authenticated member routes
        Route::group(['middleware' => ['member.auth', 'member.role:1,2,3']], function () {

            // Claim reward
            Route::get('card/{card_id}/{reward_id}/claim', 'Member\CardController@showClaimReward')->name('member.card.reward.claim');

            // Dashboard
            Route::get('dashboard', 'Member\PageController@dashboard')->name('member.dashboard');

            // Wallet
            Route::get('wallet', 'Member\WalletController@showWallet')->name('member.wallet');
            Route::get('wallet/card/{card_id}', 'Member\WalletController@showCardWallet')->name('member.wallet.card');

            // Data Definition
            Route::get('manage/{name}', 'Data\ListController@showList')->name('member.data.list');
            Route::get('manage/export/{name}', 'Data\ExportController@exportList')->name('member.data.export');
            Route::post('manage/{name}/delete/{id?}', 'Data\DeleteController@postDelete')->name('member.data.delete.post');
            Route::get('manage/{name}/view/{id}', 'Data\ViewController@showViewItem')->name('member.data.view');
            Route::get('manage/{name}/insert', 'Data\InsertController@showInsertItem')->name('member.data.insert');
            Route::post('manage/{name}/insert', 'Data\InsertController@postInsertItem')->name('member.data.insert.post');
            Route::get('manage/{name}/edit/{id}', 'Data\EditController@showEditItem')->name('member.data.edit');
            Route::post('manage/{name}/edit/{id}', 'Data\EditController@postEditItem')->name('member.data.edit.post');
            Route::get('manage/{name}/impersonate/{guard}/{id}', 'Data\AuthController@impersonate')->name('member.data.impersonate');
        });

        // Non-authenticated member routes
        Route::get('/', 'Member\PageController@index')->name('member.index');
        Route::get('card/{card_id}', 'Member\CardController@showCard')->name('member.card')->where(['card_id' => '[0-9]+']);
        Route::get('card/{card_id}/{reward_id}', 'Member\CardController@showReward')->name('member.card.reward')->where(['card_id' => '[0-9]+', 'reward_id' => '[0-9]+']);
        Route::get('follow/{card_id}', 'Member\CardController@follow')->name('member.card.follow')->where(['card_id' => '[0-9]+']);
        Route::get('unfollow/{card_id}', 'Member\CardController@unfollow')->name('member.card.unfollow')->where(['card_id' => '[0-9]+']);

        Route::get('about', 'Member\PageController@about')->name('member.about');
        Route::get('contact', 'Member\PageController@contact')->name('member.contact');
        Route::get('faq', 'Member\PageController@faq')->name('member.faq');
        Route::get('terms', 'Member\PageController@terms')->name('member.terms');
        Route::get('privacy', 'Member\PageController@privacy')->name('member.privacy');

        Route::middleware(['guest:member'])->group(function () {
            Route::get('login', 'Member\AuthController@login')->name('member.login');
            Route::post('login', 'Member\AuthController@postLogin')->name('member.login.post');
            Route::get('register', 'Member\AuthController@register')->name('member.register');
            Route::post('register', 'Member\AuthController@postRegister')->name('member.register.post');
            Route::get('password', 'Member\AuthController@forgotPassword')->name('member.forgot_password');
            Route::post('password', 'Member\AuthController@postForgotPassword')->name('member.forgot_password.post');
            Route::get('reset-password', 'Member\AuthController@resetPassword')->name('member.reset_password')->middleware('signed');
            Route::post('reset-password', 'Member\AuthController@postResetPassword')->name('member.reset_password.post')->middleware('signed');
            Route::get('login-link', 'Member\AuthController@loginLink')->name('member.login.link')->middleware('signed');
        });
        Route::get('logout', 'Member\AuthController@logout')->name('member.logout');

        // Authenticated staff routes
        Route::group(['prefix' => 'staff', 'middleware' => ['staff.auth', 'staff.role:1,2,3']], function () {
            Route::get('/', 'Staff\PageController@index')->name('staff.index');

            // Scan QR code
            Route::get('scan', 'Staff\PageController@showQrScanner')->name('staff.qr.scanner');

            // Earn
            Route::get('earn/{member_identifier}/{card_identifier}', 'Staff\EarnController@showEarnPoints')->name('staff.earn.points');
            Route::post('earn/{member_identifier}/{card_identifier}', 'Staff\EarnController@postEarnPoints')->name('staff.earn.points.post');

            // Claim
            Route::get('claim/{member_identifier}/{card_id}/{reward_id}', 'Staff\RewardController@showClaimReward')->name('staff.claim.reward')->middleware('signed:consume');
            Route::post('claim/{member_identifier}/{card_id}/{reward_id}', 'Staff\RewardController@postClaimReward')->name('staff.claim.reward.post');

            // Transactions
            Route::get('transactions/{member_identifier?}/{card_identifier?}', 'Staff\TransactionController@showTransactions')->name('staff.transactions');

            // Data Definition
            Route::get('manage/{name}', 'Data\ListController@showList')->name('staff.data.list');
            Route::get('manage/export/{name}', 'Data\ExportController@exportList')->name('staff.data.export');
            Route::post('manage/{name}/delete/{id?}', 'Data\DeleteController@postDelete')->name('staff.data.delete.post');
            Route::get('manage/{name}/view/{id}', 'Data\ViewController@showViewItem')->name('staff.data.view')->where(['id' => '[0-9]+']);
            Route::get('manage/{name}/insert', 'Data\InsertController@showInsertItem')->name('staff.data.insert');
            Route::post('manage/{name}/insert', 'Data\InsertController@postInsertItem')->name('staff.data.insert.post');
            Route::get('manage/{name}/edit/{id}', 'Data\EditController@showEditItem')->name('staff.data.edit')->where(['id' => '[0-9]+']);
            Route::post('manage/{name}/edit/{id}', 'Data\EditController@postEditItem')->name('staff.data.edit.post')->where(['id' => '[0-9]+']);
            Route::get('manage/{name}/impersonate/{guard}/{id}', 'Data\AuthController@impersonate')->name('staff.data.impersonate');
        });

        // Non-authenticated staff routes
        Route::prefix('staff')->group(function () {
            Route::middleware(['guest:staff'])->group(function () {
                Route::get('login', 'Staff\AuthController@login')->name('staff.login');
                Route::post('login', 'Staff\AuthController@postLogin')->name('staff.login.post');
                Route::get('password', 'Staff\AuthController@forgotPassword')->name('staff.forgot_password');
                Route::post('password', 'Staff\AuthController@postForgotPassword')->name('staff.forgot_password.post');
                Route::get('reset-password', 'Staff\AuthController@resetPassword')->name('staff.reset_password')->middleware('signed');
                Route::post('reset-password', 'Staff\AuthController@postResetPassword')->name('staff.reset_password.post')->middleware('signed');
            });
            Route::get('logout', 'Staff\AuthController@logout')->name('staff.logout');
        });

        // Authenticated partner routes
        Route::group(['prefix' => 'partner',  'middleware' => ['partner.auth', 'partner.role:1,2,3']], function () {
            Route::get('/', 'Partner\PageController@index')->name('partner.index');

            // Transactions
            Route::get('transactions/{member_identifier?}/{card_identifier?}', 'Partner\TransactionController@showTransactions')->name('partner.transactions');
            Route::get('transactions/delete-last/{member_identifier?}/{card_identifier?}', 'Partner\TransactionController@deleteLastTransaction')->name('partner.delete.last.transaction');

            // Analytics
            Route::get('analytics', 'Partner\AnalyticsController@showAnalytics')->name('partner.analytics');
            Route::get('analytics/card/{card_id}', 'Partner\AnalyticsController@showCardAnalytics')->name('partner.analytics.card');

            // Ai
            Route::post('api/ai-response', 'Partner\AiController@getResponse')->name('partner.api.ai-response');

            // Data Definition
            Route::get('manage/{name}', 'Data\ListController@showList')->name('partner.data.list');
            Route::get('manage/export/{name}', 'Data\ExportController@exportList')->name('partner.data.export');
            Route::post('manage/{name}/delete/{id?}', 'Data\DeleteController@postDelete')->name('partner.data.delete.post');
            Route::get('manage/{name}/view/{id}', 'Data\ViewController@showViewItem')->name('partner.data.view')->where(['id' => '[0-9]+']);
            Route::get('manage/{name}/insert', 'Data\InsertController@showInsertItem')->name('partner.data.insert');
            Route::post('manage/{name}/insert', 'Data\InsertController@postInsertItem')->name('partner.data.insert.post');
            Route::get('manage/{name}/edit/{id}', 'Data\EditController@showEditItem')->name('partner.data.edit')->where(['id' => '[0-9]+']);
            Route::post('manage/{name}/edit/{id}', 'Data\EditController@postEditItem')->name('partner.data.edit.post')->where(['id' => '[0-9]+']);
            Route::get('manage/{name}/impersonate/{guard}/{id}', 'Data\AuthController@impersonate')->name('partner.data.impersonate');
        });

        // Non-authenticated partner routes
        Route::prefix('partner')->group(function () {
            Route::middleware(['guest:partner'])->group(function () {
                Route::get('login', 'Partner\AuthController@login')->name('partner.login');
                Route::post('login', 'Partner\AuthController@postLogin')->name('partner.login.post');
                Route::get('password', 'Partner\AuthController@forgotPassword')->name('partner.forgot_password');
                Route::post('password', 'Partner\AuthController@postForgotPassword')->name('partner.forgot_password.post');
                Route::get('reset-password', 'Partner\AuthController@resetPassword')->name('partner.reset_password')->middleware('signed');
                Route::post('reset-password', 'Partner\AuthController@postResetPassword')->name('partner.reset_password.post')->middleware('signed');
            });
            Route::get('logout', 'Partner\AuthController@logout')->name('partner.logout');
        });

        // Authenticated admin routes
        Route::group(['prefix' => 'admin',  'middleware' => ['admin.auth', 'admin.role:1']], function () {
            Route::get('migrate', 'Admin\PageController@runMigrations')->name('admin.migrate');

            // Transactions
            Route::get('transactions/{member_identifier?}/{card_identifier?}', 'Admin\TransactionController@showTransactions')->name('admin.transactions');
        });

        // Authenticated admin and manager routes
        Route::group(['prefix' => 'admin',  'middleware' => ['admin.auth', 'admin.role:1,2']], function () {
            Route::get('/', 'Admin\PageController@index')->name('admin.index');

            // Data Definition
            Route::get('manage/{name}', 'Data\ListController@showList')->name('admin.data.list');
            Route::get('manage/export/{name}', 'Data\ExportController@exportList')->name('admin.data.export');
            Route::post('manage/{name}/delete/{id?}', 'Data\DeleteController@postDelete')->name('admin.data.delete.post');
            Route::get('manage/{name}/view/{id}', 'Data\ViewController@showViewItem')->name('admin.data.view')->where(['id' => '[0-9]+']);
            Route::get('manage/{name}/insert', 'Data\InsertController@showInsertItem')->name('admin.data.insert');
            Route::post('manage/{name}/insert', 'Data\InsertController@postInsertItem')->name('admin.data.insert.post');
            Route::get('manage/{name}/edit/{id}', 'Data\EditController@showEditItem')->name('admin.data.edit')->where(['id' => '[0-9]+']);
            Route::post('manage/{name}/edit/{id}', 'Data\EditController@postEditItem')->name('admin.data.edit.post')->where(['id' => '[0-9]+']);
            Route::get('manage/{name}/impersonate/{guard}/{id}', 'Data\AuthController@impersonate')->name('admin.data.impersonate');
        });

        // Non-authenticated admin routes
        Route::prefix('admin')->group(function () {
            Route::middleware(['guest:admin'])->group(function () {
                Route::get('login', 'Admin\AuthController@login')->name('admin.login');
                Route::post('login', 'Admin\AuthController@postLogin')->name('admin.login.post');
                Route::get('password', 'Admin\AuthController@forgotPassword')->name('admin.forgot_password');
                Route::post('password', 'Admin\AuthController@postForgotPassword')->name('admin.forgot_password.post');
                Route::get('reset-password', 'Admin\AuthController@resetPassword')->name('admin.reset_password')->middleware('signed');
                Route::post('reset-password', 'Admin\AuthController@postResetPassword')->name('admin.reset_password.post')->middleware('signed');
            });
            Route::get('logout', 'Admin\AuthController@logout')->name('admin.logout');
        });
    });

    Route::group(['namespace' => '\App\Http\Controllers', 'prefix' => 'install', 'middleware' => 'not.installed'], function () {
        Route::get('/', 'Installation\PageController@index')->name('installation.index');   //
        Route::get('log', 'Installation\PageController@downloadLog')->name('installation.log');  //
        Route::post('/', 'Installation\PageController@postInstall')->name('installation.install');  //
    });
});

// Fallback Route
// This route will catch any requests that don't match any of the defined routes.
// It redirects to the 'redir.locale' route, which handles the root URL and is responsible for locale redirection.
// It should be placed at the very bottom of this file to ensure it only runs for undefined routes.
/*
Route::fallback(function () {
    return redirect()->route('redir.locale');
});
*/