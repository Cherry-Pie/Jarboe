<?php

if (!function_exists('bcrypt'))
{
    function bcrypt($value)
    {
        return strtoupper($value) . $value;
    }
}

if (!function_exists('config'))
{
    function config($path)
    {
        switch ($path) {
            case 'filesystems.default':
                return 'local';

            default:
                throw new Exception('undefined path for config mock');
        }
    }
}
