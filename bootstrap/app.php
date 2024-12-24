<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\InvalidOrderException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Define global middleware that will apply to every request.
        $middleware->use([
            // \App\Http\Middleware\TrustHosts::class,
            \App\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            \App\Http\Middleware\SetDefaultLocaleForUrls::class,
        ]);

        // Define middleware groups that apply to specific types of requests.
        $middleware->group('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\I18nMiddleware::class,
            \App\Http\Middleware\SetDefaultCookie::class,
        ]);
        
        $middleware->group('api', [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \App\Http\Middleware\I18nMiddleware::class,
        ]);

        // Define aliases for route-specific middleware to be used within routes.
        $middleware->alias([
            'dummy' => \App\Http\Middleware\Dummy::class,
            'installed' => \App\Http\Middleware\CheckIfInstalled::class,
            'not.installed' => \App\Http\Middleware\CheckIfNotInstalled::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'admin.auth' => \App\Http\Middleware\AuthenticateAdmin::class,
            'admin.auth.api' => \App\Http\Middleware\AuthenticateAdminApi::class,
            'admin.role' => \App\Http\Middleware\CheckAdminRole::class,
            'partner.auth' => \App\Http\Middleware\AuthenticatePartner::class,
            'partner.auth.api' => \App\Http\Middleware\AuthenticatePartnerApi::class,
            'partner.role' => \App\Http\Middleware\CheckPartnerRole::class,
            'staff.auth' => \App\Http\Middleware\AuthenticateStaff::class,
            'staff.role' => \App\Http\Middleware\CheckStaffRole::class,
            'member.auth' => \App\Http\Middleware\AuthenticateMember::class,
            'member.auth.api' => \App\Http\Middleware\AuthenticateMemberApi::class,
            'member.role' => \App\Http\Middleware\CheckMemberRole::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'signed' => \App\Http\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Handle specific exceptions with custom reporting logic
        $exceptions->report(function (InvalidOrderException $e) {
            // Log to a specific system or perform any custom action
            // Optionally, you can stop further reporting using stop method or returning false
        });

        // Customize the rendering of exceptions
        $exceptions->render(function (InvalidOrderException $e, $request) {
            // Return a custom response for this exception type
            return new Response("Custom message for InvalidOrderException", 500);
        });

        // Global exception context and settings
        $exceptions->context(fn () => [
            'key' => 'value' // Global context for all exceptions
        ]);

        // Define exception handling logic previously in the register method
        if (!app()->runningInConsole()) {
            $locales = explode('-', request()->segment(1));
            $locale = (isset($locales[1])) ? $locales[0].'_'.strtoupper($locales[1]) : config('app.locale');
            if (! File::exists(lang_path().'/'.$locale)) {
                $locale = config('app.locale');
            }
            app()->setLocale($locale);
        }

        // You may also set exception log levels or ignore certain exceptions
        $exceptions->level(InvalidOrderException::class, \Psr\Log\LogLevel::CRITICAL);
        $exceptions->dontReport([
            InvalidOrderException::class, // Add other exception classes to ignore
        ]);

    })->create();
