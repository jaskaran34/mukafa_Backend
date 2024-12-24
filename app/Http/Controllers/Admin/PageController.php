<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;

class PageController extends Controller
{
    /**
     * Display the admin index page.
     *
     * @param string $locale The locale for the current request.
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(string $locale, Request $request)
    {
        // Define a container for dashboard blocks
        $dashboardBlocks = [];

        if (auth('admin')->user()->role != 1) {
            $dashboardBlocks[] = [
                'link' => route('admin.data.list', ['name' => 'account']),
                'icon' => 'user-circle',
                'title' => trans('common.account_settings'),
                'desc' => trans('common.adminDashboardBlocks.account_settings')
            ];
        }

        if (auth('admin')->user()->role == 1) {
            $dashboardBlocks[] = [
                'link' => route('admin.data.list', ['name' => 'admins']),
                'icon' => 'users',
                'title' => trans('common.administrators'),
                'desc' => trans('common.adminDashboardBlocks.administrators', ['localeSlug' => '<span class="underline">/' . app()->make('i18n')->language->current->localeSlug . '/admin/</span>'])
            ];

            $dashboardBlocks[] = [
                'link' => route('admin.data.list', ['name' => 'networks']),
                'icon' => 'cube-transparent',
                'title' => trans('common.networks'),
                'desc' => trans('common.adminDashboardBlocks.networks')
            ];
        }

        $dashboardBlocks[] = [
            'link' => route('admin.data.list', ['name' => 'partners']),
            'icon' => 'building-storefront',
            'title' => trans('common.partners'),
            'desc' => trans('common.adminDashboardBlocks.partners', ['localeSlug' => '<span class="underline">/' . app()->make('i18n')->language->current->localeSlug . '/partner/</span>'])
        ];

        if (auth('admin')->user()->role == 1) {
            $dashboardBlocks[] = [
                'link' => route('admin.data.list', ['name' => 'members']),
                'icon' => 'user-group',
                'title' => trans('common.members'),
                'desc' => trans('common.adminDashboardBlocks.members')
            ];
        }

        // Check if there are pending database migrations
        $hasMigrations = auth('admin')->user()->role == 1 ? $this->hasPendingMigrations() : false;

        return view('admin.index', compact('dashboardBlocks', 'hasMigrations'));
    }

    /**
     * Check if there are pending database migrations.
     *
     * @return bool
     */
    private function hasPendingMigrations(): bool
    {
        // Extract only the table name from the configuration array
        $tableName = config('database.migrations.table');
    
        // Initialize the DatabaseMigrationRepository with the correct table name
        $repository = new DatabaseMigrationRepository(app('db'), $tableName);
    
        // Fetch the list of migrations that have been run
        $ran = $repository->getRan();
    
        // Get all migration files
        $migrations = app('migrator')->getMigrationFiles(database_path('migrations'));
    
        // Determine pending migrations
        $pendingMigrations = array_diff(array_keys($migrations), $ran);
    
        return !empty($pendingMigrations);
    }

    /**
     * Run database migration.
     *
     * @param string $locale The locale for the current request.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function runMigrations(string $locale, Request $request): RedirectResponse
    {
        try {
            $this->ensureConsoleOutputConstantsDefined();

            // Run the database migrations
            Artisan::call('migrate', ['--force' => true]); 

            // Create a toast message for the result of the migrations
            $toast = [
                'type' => 'success',
                'size' => 'lg',
                'text' => trans('common.database_migrations_success'),
            ];
        } catch (\Exception $e) {
            // Report any exceptions that occur during the migration
            report($e); 

            // Create a toast message for the result of the migrations
            $toast = [
                'type' => 'warning',
                'size' => 'lg',
                'text' => trans('common.database_migrations_failure'),
            ];
        }

        // Redirect to the admin index page with the toast message
        return redirect(route('admin.index'))->with('toast', $toast);
    }

    /**
     * Ensure the STDOUT, STDERR, and STDIN constants are defined for Artisan.
     *
     * @return void
     */
    private function ensureConsoleOutputConstantsDefined(): void
    {
        if (!defined('STDIN')) {
            define('STDIN', fopen('php://stdin', 'rb'));
        }

        if (!defined('STDOUT')) {
            define('STDOUT', fopen('php://stdout', 'wb'));
        }

        if (!defined('STDERR')) {
            define('STDERR', fopen('php://stderr', 'wb'));
        }
    }
}
