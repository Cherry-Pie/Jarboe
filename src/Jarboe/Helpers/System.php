<?php

namespace Yaro\Jarboe\Helpers;

class System
{
    private $cpus;
    private $averageLoadSamples;
    private $memoryTotal;
    private $memoryFree;
    private $swapTotal;
    private $swapFree;

    public function __construct()
    {
        $this->cpus = $this->cpusCount();
        $this->averageLoadSamples = sys_getloadavg();
        list($this->memoryTotal, $this->memoryFree, $this->swapTotal, $this->swapFree) = $this->getMemoryData();
    }

    public function memoryTotal()
    {
        return $this->memoryTotal;
    }

    public function memoryFree()
    {
        return $this->memoryFree;
    }

    public function memoryUsed()
    {
        if (is_null($this->memoryTotal())) {
            return null;
        }

        return $this->memoryTotal() - $this->memoryFree();
    }

    public function swapTotal()
    {
        return $this->swapTotal;
    }

    public function swapFree()
    {
        return $this->swapFree;
    }

    public function swapUsed()
    {
        if (is_null($this->swapTotal())) {
            return null;
        }

        return $this->swapTotal() - $this->swapFree();
    }

    public function cpus(): int
    {
        return $this->cpus;
    }

    public function systemLoadSamples(): array
    {
        return $this->averageLoadSamples;
    }

    public function systemLoadSamplesInPercentages(): array
    {
        $samples = $this->systemLoadSamples();
        array_walk($samples, function (&$load) {
            $load = round(($load * 100) / $this->cpus, 2);
        });

        return $samples;
    }

    private function cpusCount(): int
    {
        $cores = 1;
        if ($this->isLinux() || $this->isMacOS()) {
            $cores = shell_exec('getconf _NPROCESSORS_ONLN');
        } elseif ($this->isBSD()) {
            $cores = shell_exec('getconf NPROCESSORS_ONLN');
        } elseif ($this->isWindows()) {
            $stdout = shell_exec('wmic computersystem get NumberOfLogicalProcessors');
            $cores = array_sum(explode("\n", $stdout));
        }

        return (int) $cores;
    }

    public function isWindows(): bool
    {
        return PHP_OS_FAMILY == 'Windows';
    }

    public function isLinux(): bool
    {
        return PHP_OS_FAMILY == 'Linux';
    }

    public function isMacOS(): bool
    {
        return PHP_OS_FAMILY == 'Darwin';
    }

    public function isBSD(): bool
    {
        return PHP_OS_FAMILY == 'BSD';
    }

    public function readableSize($size, $precision = 2)
    {
        $units = [
            'B',
            'kB',
            'MB',
            'GB',
            'TB',
            'PB',
            'EB',
            'ZB',
            'YB',
        ];
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            $i++;
        }
        return sprintf('%s %s', round($size, $precision), $units[$i]);
    }

    private function getMemoryData(): array
    {
        $default = [
            null,
            null,
            null,
            null,
        ];
        if (!$this->isLinux()) {
            return $default;
        }

        exec('cat /proc/meminfo', $output, $errorCode);
        if ($errorCode) {
            return $default;
        }

        $memoryTotal = preg_replace('~[^\d]~', '', $output[0]); // kb
        $memoryFree = preg_replace('~[^\d]~', '', $output[1]);
        $swapTotal = preg_replace('~[^\d]~', '', $output[14]);
        $swapFree = preg_replace('~[^\d]~', '', $output[15]);

        return [
            $memoryTotal * 1024,
            $memoryFree * 1024,
            $swapTotal * 1024,
            $swapFree * 1024,
        ];
    }

    public function memoryPercentage(): int
    {
        if (!$this->memoryTotal()) {
            return 0;
        }

        return $this->memoryUsed() / ($this->memoryTotal() / 100);
    }

    public function swapPercentage(): int
    {
        if (!$this->swapTotal()) {
            return 0;
        }

        return $this->swapUsed() / ($this->swapTotal() / 100);
    }
}
