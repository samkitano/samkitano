<?php

namespace App\Kitano\Delogger;

use Carbon\Carbon;

class Delogger
{
    /** @var array */
    protected $classes = [
        'alert' => 'warning',
        'critical' => 'danger',
        'debug' => 'warning',
        'emergency' => 'danger',
        'error' => 'danger',
        'info' => 'info',
        'notice' => 'info',
        'processed' => 'info',
        'warning' => 'warning',
    ];

    /** @var int */
    protected $currentEntry;

    /** @var string */
    protected $currentHeading;

    /** @var string */
    protected $currentLevel;

    /** @var string file */
    protected $file;

    /** @var string */
    protected $fileContent;

    /** @var array */
    protected $images = [
        'alert' => 'fa fa-bell',
        'critical' => 'fa fa-exclamation-triangle',
        'debug' => 'fa fa-bug',
        'emergency' => 'fa fa-flash',
        'error' => 'fa fa-exclamation-triangle',
        'info' => 'fa fa-info-circle',
        'notice' => 'fa fa-flag',
        'processed' => 'fa fa-info-circle',
        'warning' => 'fa fa-exclamation',
    ];

    /** @var array */
    protected $levels = [
        'alert',
        'critical',
        'debug',
        'emergency',
        'error',
        'info',
        'notice',
        'processed',
        'warning',
    ];

    /** @var array */
    protected $logData;

    /** @var array */
    protected $logHeadings;

    /** @var string */
    protected $pattern = '/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*/';


    /**
     * Archive a log file
     *
     * @param   string $file
     *
     * @return bool|string
     */
    public function archive($file)
    {
        $file = base64_decode($file);
        $date = str_slug(Carbon::now()->toDateString());
        $archived = 'l_' . $date . '.log';
        $i = 1;

        while (file_exists(storage_path('logs') . '/' . $archived)) {
            $archived = 'l_' . $date . '_'. $i . '.log';
            $i++;
        }

        $renamed = rename($file, storage_path('logs') . '/' . $archived);

        return $renamed ? $archived : false;
    }

    /**
     * Delete a Log File
     *
     * @param string $file Base64 Encoded full path to log file
     *
     * @return bool|string
     */
    public function destroy($file)
    {
        $file = base64_decode($file);

        if (file_exists($file)) {
            unlink($file);
            return basename($file);
        }

        return false;
    }

    /**
     * Get All logs
     *
     * @return array
     */
    public function getLogs()
    {
        $files = $this->getFiles();
        $logs = [];

        foreach ($files as $file) {
            $t = [];
            $log = $this->getLog($file);
            $t['file'] = $file;
            $t['log'] = $log;
            $t['entries'] = count($this->logData);

            array_push($logs, $t);
        }

        return $logs;
    }


    /**
     * Build log data
     *
     * @return array
     */
    protected function buildLog()
    {
        $log = [];

        foreach ($this->logHeadings as $heading) {
            $this->setCurrentHeading($heading);
            $log = $this->extractEntries();
        }

        return $log;
    }

    /**
     * Build current log row
     *
     * @return array
     */
    protected function buildLogRow()
    {
        $row = [];

        foreach ($this->levels as $level) {
            $this->setCurrentLevel($level);
            if ($this->isLevel($level)) {
                $row = [
                    'class' => $this->classes[$level],
                    'context' => $this->exctractContext(),
                    'date' => $this->extractDate(),
                    'img' => $this->images[$level],
                    'in' => $this->extractIn(),
                    'message' => $this->extractMessage(),
                    'level' => $level,
                    'stack' => $this->getStack(),
                ];
            }
        }

        return $row;
    }

    /**
     * Extract context from current entry
     *
     * @return string
     */
    protected function exctractContext()
    {
        return ltrim(stringBetween($this->currentHeading[$this->currentEntry], ']', '.'));
    }

    /**
     * Extract date from current entry
     *
     * @return string
     */
    protected function extractDate()
    {
        return stringBetween($this->currentHeading[$this->currentEntry], '[', ']');
    }

    /**
     * Extract entries for current log
     *
     * @return array
     */
    protected function extractEntries()
    {
        $built = [];

        for ($i = 0, $j = count($this->currentHeading); $i < $j; $i++) {
            $this->setCurrentEntry($i);
            $built[] = $this->buildLogRow();
        }

        return $built;
    }

    /**
     * Extract error from current entry
     *
     * @return string
     */
    protected function extractMessage()
    {
        $str = $this->currentHeading[$this->currentEntry];

        switch ($this->currentLevel) {
            case 'alert':
                return trim(substr($str, strpos($str, 'ALERT:') + 6));
            case 'critical':
                return trim(substr($str, strpos($str, 'CRITICAL:') + 9));
            case 'debug':
                return trim(substr($str, strpos($str, 'DEBUG:') + 6));
            case 'emergency':
                return trim(substr($str, strpos($str, 'EMERGENCY:') + 10));
            case 'error':
                return stringHas($str, ' in ')
                    ? trim(stringBetween($str, ': ', ' in '))
                    : trim(substr($str, strpos($str, 'ERROR:') + 6));
            case 'info':
                return trim(substr($str, strpos($str, 'INFO:') + 5));
            case 'notice':
                return trim(substr($str, strpos($str, 'NOTICE:') + 7));
            case 'processed':
                return trim(substr($str, strpos($str, 'PROCESSED:') + 10));
            case 'warning':
                return trim(substr($str, strpos($str, 'WARNING:') + 8));
            default:
                return '';
        }
    }

    /**
     * Extract error location from current entry
     *
     * @return string
     */
    protected function extractIn()
    {
        $str = $this->currentHeading[$this->currentEntry];

        if (stringHas($str, ' in ')) {
            return trim(stringFromIncluding($str, ' in '));
        }

        return '';
    }

    /**
     * @param     $size
     * @param int $precision
     *
     * @return string
     */
    protected function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['', 'k', 'M', 'G', 'T'];

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)] . 'B';
    }

    /**
     * Retrieve all log files
     *
     * @return array
     */
    protected function getFiles()
    {
        $files = glob(storage_path('logs') . '/*.log');
        $files = array_reverse($files);
        $res = [];

        foreach ($files as $key => $file) {
            $size = filesize($file);

            $t['path'] = $file;
            $t['name'] = basename($file);
            $t['slug'] = str_slug(basename($file));
            $t['size'] = $size;
            $t['bytes'] = $this->formatBytes($size, 0);
            $t['is_big'] = $size > 10485760;
            $t['link'] = base64_encode($file);

            array_push($res, $t);
        }

        return $res;
    }

    /**
     * Get a log file. If a log file is bigger than 20MB, null is returned.
     * This way we'll have an indicator for an optional download feature.
     *
     * @param $file
     *
     * @return array|null
     */
    protected function getLog($file)
    {
        $this->file = $file['path'];

        // 20 MB limit
        if (filesize($this->file) > 20971520) {
            return null;
        }

        return $this->setFileContent()
                    ->setLogHeadings()
                    ->setLogData()
                    ->buildLog();
    }

    /**
     * Get current entry stack
     *
     * @return mixed
     */
    protected function getStack()
    {
        return trim(str_replace(["\r\n","\r"], '', $this->logData[$this->currentEntry]));
    }

    /**
     * Check current level
     *
     * @param $level
     *
     * @return bool
     */
    protected function isLevel($level)
    {
        return strpos(strtolower($this->currentHeading[$this->currentEntry]), '.' . $level)
               || strpos(strtolower($this->currentHeading[$this->currentEntry]), $level . ':');
    }

    /**
     * @param $entry
     */
    protected function setCurrentEntry($entry)
    {
        $this->currentEntry = $entry;
    }

    /**
     * @param   string $heading
     */
    protected function setCurrentHeading($heading)
    {
        $this->currentHeading = $heading;
    }

    /**
     * @param   string $level
     */
    protected function setCurrentLevel($level)
    {
        $this->currentLevel = $level;
    }

    /**
     * @return $this
     */
    protected function setFileContent()
    {
        $this->fileContent = file_get_contents($this->file);

        return $this;
    }

    /**
     * @return $this
     */
    protected function setLogData()
    {
        $data = preg_split($this->pattern, $this->fileContent);

        if ($data[0] < 1) {
            array_shift($data);
        }

        $this->logData = $data;

        return $this;
    }

    /**
     * @return $this
     */
    protected function setLogHeadings()
    {
        preg_match_all($this->pattern, $this->fileContent, $this->logHeadings);

        return $this;
    }
}
