<?php

namespace api\helpers;

class DateHelper
{
    public static function formatApi($timestamp)
    {
        return $timestamp ? date('d.m.Y H:i', $timestamp) : null;
    }
}