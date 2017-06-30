<?php

namespace App\Kitano\Depender;

class Depender
{
    /**
     * @return array
     */
    public static function getInfo()
    {
        $package = static::readComposerJson();

        return [
            'composer' => self::getPackageInfo($package),
            'dev-dependencies' => self::getDev($package),
            'dependencies' => self::getProd($package),
            'laravel' => self::getLaravelInfo(),
            'server' => self::getServerEnv(),
            'vendor' => self::getInstalled(self::getProd($package)),
        ];
    }


    /**
     * @param   array $package
     *
     * @return array
     */
    protected static function getPackageInfo($package)
    {
        return [
            'name' => isset($package['name']) ? $package['name'] : false,
            'description' => isset($package['description']) ? $package['description'] : false,
            'version' => isset($package['version']) ? $package['version'] : false,
            'keywords' => isset($package['keywords']) ? implode(', ', $package['keywords']) : false,
            'license' => isset($package['license']) ? $package['license'] : false,
            'type' => isset($package['type']) ?  $package['type'] : false,
            'homepage' => isset($package['homepage']) ? $package['homepage'] : false,
            'minimum-stability' => isset($package['minimum-stability']) ?  $package['minimum-stability'] : false,
            'prefer-stable' => isset($package['prefer-stable']) ?  $package['prefer-stable'] : false,
        ];
    }

    /**
     * @param   array $package
     *
     * @return mixed
     */
    protected static function getDev($package)
    {
        return $package['require-dev'];
    }

    /**
     * @param   array $package
     *
     * @return mixed
     */
    protected static function getProd($package)
    {
        return $package['require'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected static function readComposerJson()
    {
        $composerPath = base_path('composer.json');

        if (! file_exists($composerPath)) {
            app()->abort(404);
        }

        $packages = file_get_contents($composerPath);

        return json_decode($packages, true);
    }

    /**
     * @return array
     */
    protected static function getLaravelInfo()
    {
        $size = function_exists('dirSize') && function_exists('formatBytes')
                ? formatBytes(dirSize(base_path()))
                : self::formatBytes(self::dirSize(base_path()));

        return [
            'version' => app()->version(),
            'timezone' => config('app.timezone'),
            'debug' => config('app.debug'),
            'storage writable' => is_writable(base_path('storage')),
            'cache writable' => is_writable(base_path('bootstrap/cache')),
            'application size' => $size,
        ];
    }

    /**
     * @return array
     */
    protected static function getServerEnv()
    {
        return [
            'php version' => phpversion(),
            'software' => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : false,
            'os' => php_uname(),
            'database connection' => config('database.default'),
            'ssl' => self::checkSslIsInstalled(),
            'cache driver' => config('cache.default'),
            'session driver' => config('session.driver'),
            'openssl' => extension_loaded('openssl'),
            'pdo' => extension_loaded('pdo'),
            'mbstring' => extension_loaded('mbstring'),
            'tokenizer' => extension_loaded('tokenizer'),
            'xml' => extension_loaded('xml'),
            'opcache' => extension_loaded('opcache'),
        ];
    }

    /**
     * @return bool
     */
    protected static function checkSslIsInstalled()
    {
        return ! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    }

    /**
     * @param   array $pack
     *
     * @return array
     */
    protected static function getInstalled($pack)
    {
        $installed = [];

        foreach ($pack as $key => $value) {
            $file = base_path("/vendor/{$key}/composer.json");

            if ($key !== 'php' && file_exists($file)) {
                $jsn = file_get_contents($file);
                $deps = json_decode($jsn, true);

                $installed[] = [
                    'name' => $key,
                    'version' => $value,
                    'production' => array_key_exists('require', $deps) ? $deps['require'] : [],
                    'development' => array_key_exists('require-dev', $deps) ? $deps['require-dev'] : [],
                ];
            }
        }

        return $installed;
    }

    /**
     * @param int $size
     * @param int $precision
     *
     * @return string
     */
    protected static function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['', 'k', 'M', 'G', 'T'];

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)] . 'B';
    }

    /**
     * https://stackoverflow.com/a/18568222
     *
     * @param   string $dir
     *
     * @return int
     */
    protected static function dirSize($dir)
    {
        $dir = rtrim(str_replace('\\', '/', $dir), '/');

        if (is_file($dir)) {
            return (int) filesize($dir);
        }

        $totalSize = 0;
        $os = strtoupper(substr(PHP_OS, 0, 3));

        // If on a Unix Host (Linux, Mac OS)
        if ($os !== 'WIN') {
            $io = popen('/usr/bin/du -sb ' . $dir, 'r');
            if ($io) {
                $totalSize = intval(fgets($io, 80));
                pclose($io);

                return (int) $totalSize;
            }
        }

        // If on a Windows Host (WIN32, WINNT, Windows)
        if ($os === 'WIN' && extension_loaded('com_dotnet')) {
            $obj = new \COM('scripting.filesystemobject');
            if (is_object($obj)) {
                $ref = $obj->getfolder($dir);
                $totalSize = $ref->size;
                $obj = null;

                return (int) $totalSize;
            }
        }

        // If System calls did't work, use slower PHP 5
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));

        foreach ($files as $file) {
            $totalSize += $file->getSize();
        }

        return (int) $totalSize;
    }
}
