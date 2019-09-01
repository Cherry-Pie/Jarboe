<?php

if (!function_exists('bcrypt'))
{
    function bcrypt($value)
    {
        return strtoupper($value) . $value;
    }
}
