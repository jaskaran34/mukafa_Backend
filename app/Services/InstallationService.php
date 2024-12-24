<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;

/**
 * Class InstallationService
 *
 * Handles the installation process of the application.
 *
 * Example usage:
 * use App\Services\InstallationService;
 *
 * class PageController extends Controller
 * {
 *     public function redirLocale(Request $request, InstallationService $installationService)
 *     {
 *         // Get preferred locale
 *         $requirements = $InstallationService->getServerRequirements();
 *     }
 * }
 */
class InstallationService
{
    /**
     * Get server requirements.
     *
     * @return array Array with requirements
     */
    public function getServerRequirements()
    {
        $requirements = [
            'PHP >= 8.2.0 ('.PHP_VERSION.')' => version_compare(PHP_VERSION, '8.2.0') >= 0,
            'Bcmath (ext-bcmath)' => extension_loaded('bcmath'),
            'Ctype (ext-ctype)' => extension_loaded('ctype'),
            'cURL (ext-curl)' => extension_loaded('curl'),
            'DOM (ext-dom)' => extension_loaded('dom'),
            'Exif (ext-exif)' => extension_loaded('exif'),
            'Fileinfo (ext-fileinfo)' => extension_loaded('fileinfo'),
            'Filter (ext-filter)' => extension_loaded('filter'),
            'GD (ext-gd)' => extension_loaded('gd'),
            'Hash (ext-hash)' => extension_loaded('hash'),
            'Iconv (ext-iconv)' => extension_loaded('iconv'),
            'Intl (ext-intl)' => extension_loaded('intl'),
            'JSON (ext-json)' => extension_loaded('json'),
            'Libxml (ext-libxml)' => extension_loaded('libxml'),
            'Mbstring (ext-mbstring)' => extension_loaded('mbstring'),
            'OpenSSL (ext-openssl)' => extension_loaded('openssl'),
            'PCRE (ext-pcre)' => extension_loaded('pcre'),
            'PDO (ext-pdo)' => extension_loaded('pdo'),
            'PDO SQLite (ext-pdo_sqlite)' => extension_loaded('pdo_sqlite'),
            'Session (ext-session)' => extension_loaded('session'),
            'Tokenizer (ext-tokenizer)' => extension_loaded('tokenizer'),
            'XML (ext-xml)' => extension_loaded('xml'),
            'Zlib (ext-zlib)' => extension_loaded('zlib'),
        ];

        $allRequirementsMet = ! in_array(false, $requirements);

        return [
            'allMet' => $allRequirementsMet,
            'requirements' => $requirements,
        ];
    }

    /**
     * Install the script.
     *
     * @param  array  $request An array with request data
     * @return void
     */
    public function installScript($request)
    {
        set_time_limit(0);

        // Delete log
        File::delete(storage_path('logs/laravel.log'));

        // Check if sqlite file exists, if not, create
        if ($request['DB_CONNECTION'] === 'sqlite') {
            $sqlite = database_path('database.sqlite');

            if (! File::exists($sqlite)) {
                File::put($sqlite, '');
            }
        }

        // Get the blueprint
        $env = File::get(base_path('.env.blueprint'));

        // Filter form values not used in env file
        $all = [];
        $filter = ['ADMIN_MAIL', 'ADMIN_TIMEZONE', 'ADMIN_PASS', 'ADMIN_PASS_CONFIRM'];
        foreach ($request as $key => $value) {
            if (! in_array($key, $filter)) {
                $all[$key] = $value;
            }
        }

        // Add env variables
        $all['APP_URL'] = Request::getSchemeAndHttpHost();
        $all['APP_KEY'] = 'base64:'.base64_encode(Encrypter::generateKey(config('app.cipher')));
        $all['APP_INSTALLATION_DATE'] = date('Y-m-d H:i:s');
        $all['SNOWFLAKE_EPOCH'] = date('Y-m-d H:i:s');
        $all['APP_DEBUG'] = 'false';
        $all['APP_ENV'] = 'production';
        // Replace .env.blueprint values with user-provided values
        $new_env = preg_replace_callback('/^(\w+)=(.*)$/m', function ($matches) use ($all) {
            $key = $matches[1];

            if (array_key_exists($key, $all)) {
                $value = $all[$key];

                return $key.'='.(is_numeric($value) || $value === 'true' || $value === 'false' ? $value : '"'.$value.'"');
            }

            return $matches[0];
        }, $env);

        // Override database config before migrating and seeding db
        putenv('APP_ENV=development');

        config([
            'app.env' => 'development',
            'database.default' => $all['DB_CONNECTION'],
            'database.connections.mysql.host' => $all['DB_HOST'],
            'database.connections.mysql.port' => $all['DB_PORT'],
            'database.connections.mysql.database' => $all['DB_DATABASE'],
            'database.connections.mysql.username' => $all['DB_USERNAME'],
            'database.connections.mysql.password' => $all['DB_PASSWORD'],
        ]);

        // Run database migration and seeding
        Artisan::call('install');

        // Update root admin
        $admin = Admin::take(1)->first();
        $admin->email = $request['ADMIN_MAIL'];
        $admin->password = bcrypt($request['ADMIN_PASS']);
        $admin->time_zone = $request['ADMIN_TIMEZONE'];
        $admin->save();

        // Update .env file, causes restart with `php artisan serve`
        File::put(base_path('.env'), $new_env);
    }
}
