<?php

namespace common;

use Carbon\Carbon;
use Carbon\CarbonInterval;

class Helper {

    public static function formatDateTime($dateStr, $timeStr) {
        return Carbon::createFromFormat('Y-m-d\Z H:i', $dateStr . ' ' . $timeStr)
            ->format('d.m.Y H:i');
    }

    public static function formatDuration($durationStr) {
        $interval = new CarbonInterval(substr($durationStr, 0, strlen($durationStr)-5) . 'S');
        return $interval->forHumans(['short' => true]);
    }
}