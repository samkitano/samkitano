<?php

/**
 * GENERAL HELPER FUNCTIONS
 */

if (! function_exists('dirSize')) {
    /**
     * Size of a directory
     *
     * https://stackoverflow.com/questions/478121/php-get-directory-size
     *
     * @param $dir
     *
     * @return int
     */
    function dirSize($dir)
    {
        $dir = rtrim(str_replace('\\', '/', $dir), '/');

        if (is_dir($dir)) {
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
        } elseif (is_file($dir)) {
            return (int) filesize($dir);
        }

        return (int) 0;
    }
}

if (! function_exists('formatBytes')) {
    /**
     * @param int $size
     * @param int $precision
     *
     * @return string
     */
    function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['', 'k', 'M', 'G', 'T'];

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)] . 'B';
    }
}

if (! function_exists('stringBetween')) {
    /**
     * http://stackoverflow.com/questions/5696412/get-substring-between-two-strings-php
     *
     * @param string $string Haystack
     * @param string $start  Search start
     * @param string $end    Search end
     *
     * @return string
     */
    function stringBetween($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);

        if ($ini == 0) {
            return '';
        }

        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        return substr($string, $ini, $len);
    }
}

if (! function_exists('stringFrom')) {
    /**
     * Returns a substring from a given char
     *
     * @param string $string
     * @param string $start
     *
     * @return string
     */
    function stringFrom($string, $start)
    {
        return substr($string, strpos($string, $start) + 1);
    }
}

if (! function_exists('stringFromIncluding')) {
    /**
     * Returns a substring from a given char including char
     *
     * @param string $string
     * @param string $start
     *
     * @return string
     */
    function stringFromIncluding($string, $start)
    {
        return substr($string, strpos($string, $start));
    }
}

if (! function_exists('stringHas')) {
    /**
     * Check occurrences of needle in a string
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    function stringHas($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
}

if (! function_exists('digitizeDate')) {
    /**
     * Convert a date to a plain string without symbols
     *
     * @param string $date
     *
     * @return string
     */
    function digitizeDate($date)
    {
        return str_replace([' ', ':', '\\', '/', '-'], '', $date);
    }
}

if (! function_exists('isDirEmpty')) {
    /**
     * Checks if a directory is empty
     *
     * @param string $dir Path to Directory
     *
     * @return bool
     */
    function isDirEmpty($dir)
    {
        return ! (new FilesystemIterator($dir))->valid();
    }
}

if (! function_exists('dirHasFiles')) {
    /**
     * Checks if a directory contains files
     *
     * @param $dir
     *
     * @return bool
     */
    function dirHasFiles($dir)
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        $allFiles = array_filter(iterator_to_array($iterator), function ($file) {
            return $file->isFile();
        });

        return count($allFiles) > 0;
    }
}

if (! function_exists('countFiles')) {
    /**
     * Counts the number of files existing in a given path
     *
     * @param string $path Path to files
     * @param string $ext  File extension to match
     *
     * @return int Number of files
     */
    function countFiles($path, $ext)
    {
        $path = rtrim($path, '/') . '/';
        return sizeof(glob($path.'*.'.$ext));
    }
}

/**
 * APP SPECIFIC HELPER FUNCTIONS
 */
if (! function_exists('remember')) {
    /**
     * Cache helper
     *
     * @param $key
     * @param $value
     *
     * @return mixed
     * @throws \Exception
     */
    function remember($key, $value)
    {
        return cache()->tags('main')
                      ->rememberForever('HLP_' . $key, $value);
    }
}

if (! function_exists('getAllAlbums')) {
    /**
     * Get all albums
     *
     * @return \Illuminate\Support\Collection
     */
    function getAllAlbums()
    {
        return remember(
            __FUNCTION__,
            function () {
                return \App\Album::all(['id', 'name']);
            }
        );
    }
}

if (! function_exists('getAllTags')) {
    /**
     * Get all tags
     *
     * @return \Illuminate\Support\Collection
     */
    function getAllTags()
    {
        return remember(
            __FUNCTION__,
            function () {
                return \App\Tag::all(['id', 'name']);
            }
        );
    }
}

/**
 * ICONS (APP SPECIFIC)
 */
const ICON_FLAG_GREEN = '<span class="text-success"><i class="fa fa-flag"></i></span>';
const ICON_FLAG_RED = '<span class="text-danger"><i class="fa fa-flag"></i></span>';
const ICON_FLAG_BLACK = '<i class="fa fa-flag"></i>';
const ICON_CHECK_MARK = '<i class="fa fa-check"></i>';
const ICON_CHECK_MARK_GREEN = '<span class="text-success"><i class="fa fa-check"></i></span>';
const ICON_X_MARK = '<i class="fa fa-times"></i>';
const ICON_X_MARK_RED = '<span class="text-danger"><i class="fa fa-times"></i></span>';
const ICON_PENCIL = '<i class="fa fa-pencil"></i>';
const ICON_HEART = '<i class="fa fa-heart"></i>';
const ICON_COMMENT = '<i class="fa fa-comments"></i>';

/**
 * SVG PATHS (APP SPECIFIC)
 */
const PATH_SVG_LIKE = '<path d="'
    . 'M10 20c5.523 0 10-4.477 10-10S15.523 0 10 0 0 4.477 0 10s4.477 10 10 10zm0-2c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 '
    . '3.582-8 8 3.582 8 8 8zM6.5 9C7.328 9 8 8.328 8 7.5S7.328 6 6.5 6 5 6.672 5 7.5 5.672 9 6.5 9zm7 0c.828 0 1.5-.'
    . '672 1.5-1.5S14.328 6 13.5 6 12 6.672 12 7.5 12.672 9 13.5 9zm2.16 3c-.825 2.33-3.048 4-5.66 4s-4.835-1.67-5.'
    . '66-4h11.32z'
    . ' "fill-rule="evenodd"/>';

const PATH_SVG_FACEBOOK = '<path d="'
    . 'M612.6 356.8c21.1 0 43.7 6.5 43.7 6.5l13.5-80.4s-28.8-9.8-97.3-9.8c-42 '
    . '0-66.4 16-84.2 39.5-16.9 22.2-17.5 58.1-17.5 81.3v52.7h-54.2v78.5h54.2V798h101.7V525.3H653l6-78.5h-86.5v-61.3c.'
    . '1-21.1 19-28.7 40.1-28.7z"/>';

const PATH_SVG_TWITTER = '<path d="'
    . 'M21.967 11.8c.018 5.93-4.607 11.18-11.177 11.18-2.172 0-4.25-.62-6.047-1.76l-.268.422-.038.5.186.013.168.012c.3.'
    . '02.44.032.6.046 2.06-.026 3.95-.686 5.49-1.86l1.12-.85-1.4-.048c-1.57-.055-2.92-1.08-3.36-2.51l-.48.146-.05.5c.'
    . '22.03.48.05.75.08.48-.02.87-.07 1.25-.15l2.33-.49-2.32-.49c-1.68-.35-2.91-1.83-2.91-3.55 0-.05 0-.01-.01.03l-.'
    . '49-.1-.25.44c.63.36 1.35.57 2.07.58l1.7.04L7.4 13c-.978-.662-1.59-1.79-1.618-3.047a4.08 4.08 0 0 1 .524-1.8l-.'
    . '825.07a12.188 12.188 0 0 0 8.81 4.515l.59.033-.06-.59v-.02c-.05-.43-.06-.63-.06-.87a3.617 3.617 0 0 1 6.27-2.'
    . '45l.2.21.28-.06c1.01-.22 1.94-.59 2.73-1.09l-.75-.56c-.1.36-.04.89.12 1.36.23.68.58 1.13 1.17.85l-.21-.45-.42-.'
    . '27c-.52.8-1.17 1.48-1.92 2L22 11l.016.28c.013.2.014.35 0 .52v.04zm.998.038c.018-.22.017-.417 0-.66l-.498.034.'
    . '284.41a8.183 8.183 0 0 0 2.2-2.267l.97-1.48-1.6.755c.17-.08.3-.02.34.03a.914.914 0 0 1-.13-.292c-.1-.297-.13-.'
    . '64-.1-.766l.36-1.254-1.1.695c-.69.438-1.51.764-2.41.963l.48.15a4.574 4.574 0 0 0-3.38-1.484 4.616 4.616 0 0 0-4.'
    . '61 4.613c0 .29.02.51.08.984l.01.02.5-.06.03-.5c-3.17-.18-6.1-1.7-8.08-4.15l-.48-.56-.36.64c-.39.69-.62 1.48-.65 '
    . '2.28.04 1.61.81 3.04 2.06 3.88l.3-.92c-.55-.02-1.11-.17-1.6-.45l-.59-.34-.14.67c-.02.08-.02.16 0 .24-.01 2.12 '
    . '1.55 4.01 3.69 4.46l.1-.49-.1-.49c-.33.07-.67.12-1.03.14-.18-.02-.43-.05-.64-.07l-.76-.09.23.73c.57 1.84 2.29 '
    . '3.14 4.28 3.21l-.28-.89a8.252 8.252 0 0 1-4.85 1.66c-.12-.01-.26-.02-.56-.05l-.17-.01-.18-.01L2.53 21l1.694 '
    . '1.07a12.233 12.233 0 0 0 6.58 1.917c7.156 0 12.2-5.73 12.18-12.18l-.002.04z"/>';

const PATH_SVG_CALENDAR = '<path d="'
    . 'M57 4h-7V1c0-.553-.447-1-1-1h-7c-.553 0-1 .447-1 1v3H19V1c0-.553-.447-1-1-1h-7c-.553 0-1 '
    . '.447-1 1v3H3c-.553 0-1 .447-1 1v54c0 .553.447 1 1 1h54c.553 0 1-.447 1-1V5c0-.553-.447-1-1-1zM43 2h5v6h-5V2zM12 '
    . '2h5v6h-5V2zM4 6h6v3c0 .553.447 1 1 1h7c.553 0 1-.447 1-1V6h22v3c0 .553.447 1 1 1h7c.553 0 1-.447 '
    . '1-1V6h6v9H4V6zm0 52V17h52v41H4z"/>'
    . '<path d="'
    . 'M38 23H11v29h38V23H38zm-7 2h7v7h-7v-7zm7 16h-7v-7h7v7zm-16-7h7v7h-7v-7zm0-9h7v7h-7v-7zm-9 '
    . '0h7v7h-7v-7zm0 9h7v7h-7v-7zm7 16h-7v-7h7v7zm9 0h-7v-7h7v7zm9 0h-7v-7h7v7zm9 0h-7v-7h7v7zm0-9h-7v-7h7v7zm0-16v7h'
    . '-7v-7h7z"/>';

const PATH_SVG_GITHUB = '<path d="'
    . 'M512 0C229.25 0 0 229.25 0 512c0 226.25 146.688 418.125 350.156 485.812 25.594 4.688 34.938-11.125 34.938-24.625'
    . ' 0-12.188-.47-52.562-.72-95.312C242 908.812 211.907 817.5 211.907 817.5c-23.312-59.125-56.844-74.875-56.844-74.'
    . '875-46.53-31.75 3.53-31.125 3.53-31.125 51.406 3.562 78.47 52.75 78.47 52.75 45.688 78.25 119.875 55.625 149 '
    . '42.5 4.654-33 17.904-55.625 32.5-68.375-113.656-12.937-233.218-56.875-233.218-253.063 0-55.938 19.97-101.562 52.'
    . '656-137.406-5.22-13-22.844-65.094 5.062-135.562 0 0 42.938-13.75 140.812 52.5 40.812-11.406 84.594-17.03 128.'
    . '125-17.22 43.5.19 87.31 5.876 128.187 17.282 97.688-66.312 140.688-52.5 140.688-52.5 28 70.53 10.375 122.562 5.'
    . '125 135.5 32.812 35.844 52.625 81.47 52.625 137.406 0 196.688-119.75 240-233.812 252.688 18.438 15.875 34.75 47 '
    . '34.75 94.75 0 68.438-.688 123.625-.688 140.5 0 13.625 9.312 29.562 35.25 24.562C877.438 930 1024 738.125 1024 '
    . '512 1024 229.25 794.75 0 512 0z"/>';

const PATH_SVG_PRIVACY = '<path d="M98.438 200.78c-6.696-2.367-13.563-4.345-20.056-7.18-18.524-8.08-34.618-19.503-46.'
    . '844-35.857-9.485-12.69-14.26-26.903-14.162-42.857.17-27.444.043-54.89.05-82.334.002-5.795 1.13-6.545 6.832-6.'
    . '757 11.268-.42 22.64-.428 33.752-2.077C74.395 21.286 87.553 12.162 99.22.782h3.124c11.657 11.396 24.834 20.475 '
    . '41.212 22.93 10.98 1.647 22.224 1.706 33.362 2.09 6.214.216 7.218.82 7.22 7.14.005 26.533-.382 53.074.118 79.'
    . '598.43 22.735-8.304 41.19-24.245 56.63-15.133 14.655-32.994 24.687-53.318 30.306-1.216.337-2.38.866-3.566 1.'
    . '307-1.563-.002-3.126-.002-4.688-.002zM25.78 34.25v4.434c0 25.77.07 51.542-.03 77.313-.045 12.227 3.214 23.44 10.'
    . '082 33.458C51.287 172 73.304 184.94 99.206 191.97c1.286.348 2.855.067 4.2-.28 18.61-4.818 35.068-13.705 49.'
    . '183-26.733 15.59-14.39 23.957-31.764 23.347-53.566-.678-24.19-.155-48.414-.155-72.623v-4.5c-28.107 1.292-53.'
    . '942-3.377-75-23.853-20.992 20.494-46.817 25.177-75 23.836z"/>'
    . '<path fill="#FFF" d="M25.78 34.25c28.185 1.34 54.01-3.342 75-23.838 21.06 20.476 46.894 25.145 75 23.853v4.5c0 '
    . '24.21-.52 48.433.156 72.624.61 21.802-7.758 39.176-23.348 53.565-14.114 13.028-30.572 21.915-49.184 26.732-1.344'
    . '.35-2.913.63-4.2.28-25.9-7.028-47.918-19.97-63.373-42.514-6.867-10.018-10.126-21.23-10.08-33.458.098-25.77.'
    . '03-51.54.03-77.313V34.25z"/>'
    . '<path fill="#FFF" d="M50.063 150.33V47.123H153.27V150.33H50.063zm12.903-6.74h77.217V98.855l-6.266-.276c0-1.21.'
    . '05-2.132-.008-3.047-.388-6.14.087-12.495-1.372-18.37-3.813-15.365-19.055-25.332-34.515-23.434-16.276 1.997-28.'
    . '397 15.44-28.6 31.757-.053 4.375-.007 8.75-.007 13.38h-6.308c-.06.906-.137 1.553-.137 2.2-.006 14.083-.004 28.'
    . '168-.004 42.523z"/>'
    . '<path d="M62.966 143.59c0-14.354-.002-28.44.005-42.524 0-.646.08-1.292.138-2.198h6.308c0-4.63-.046-9.006.008-13.'
    . '38.202-16.32 12.323-29.76 28.6-31.76 15.46-1.897 30.702 8.07 34.514 23.434 1.46 5.875.984 12.232 1.37 18.37.058.'
    . '916.01 1.837.01 3.047l6.265.275v44.735H62.966zm58.003-45.022c0-4.988.204-9.543-.044-14.074-.504-9.26-7.932-16.'
    . '898-17.135-17.923-9.388-1.044-18.663 4.895-20.578 14.17-1.188 5.754-.827 11.83-1.165 17.828h38.922z"/>'
    . '<path fill="#FFF" d="M120.97 98.568H82.047c.338-6-.023-12.074 1.165-17.83 1.915-9.273 11.19-15.212 20.58-14.167 '
    . '9.202 1.026 16.63 8.666 17.134 17.924.247 4.53.042 9.086.042 14.074z"/>';

const PATH_SVG_COMMENT = '<path d="M2.002 2C1.45 2 1 2.45 1 3.007v12.986C1 16.55 1.443 17 2 17h2l2.817 3.275c.36.42.954.'
    . '415 1.317 0L11 17h10.998C22.55 17 23 16.55 23 15.993V3.007C23 2.45 22.56 2 21.998 2H2.002zM2 3v13h2.516l2.956 '
    . '3.562L10.712 16H22V3H2zm3.22 3C5.1 6 5 6.1 5 6.222v.556C5 6.9 5.097 7 5.22 7h12.56c.12 0 .22-.1.22-.222v-.'
    . '556C18 6.1 17.903 6 17.78 6H5.22zm0 3C5.1 9 5 9.1 5 9.222v.556c0 .123.097.222.22.222h12.56c.12 0 .22-.1.22-.'
    . '222v-.556C18 9.1 17.903 9 17.78 9H5.22zm-.01 3c-.116 0-.21.1-.21.222v.556c0 .123.095.222.21.222h8.58c.116 0 .'
    . '21-.1.21-.222v-.556c0-.123-.095-.222-.21-.222H5.21z" fill-rule="evenodd"/>';
