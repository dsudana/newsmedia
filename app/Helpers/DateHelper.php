<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Get relative time in Indonesian (e.g., "5 jam lalu", "2 hari lalu")
     */
    public static function relativeTime($date): string
    {
        if (!$date) {
            return '';
        }

        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $now = Carbon::now();
        $diff = $now->diffInSeconds($date);

        // Kurang dari 1 menit
        if ($diff < 60) {
            return 'Baru saja';
        }

        // Kurang dari 1 jam
        if ($diff < 3600) {
            $minutes = intval($diff / 60);
            return $minutes === 1 ? '1 menit lalu' : "{$minutes} menit lalu";
        }

        // Kurang dari 1 hari
        if ($diff < 86400) {
            $hours = intval($diff / 3600);
            return $hours === 1 ? '1 jam lalu' : "{$hours} jam lalu";
        }

        // Kurang dari 1 minggu
        if ($diff < 604800) {
            $days = intval($diff / 86400);
            return $days === 1 ? '1 hari lalu' : "{$days} hari lalu";
        }

        // Kurang dari 1 bulan
        if ($diff < 2592000) {
            $weeks = intval($diff / 604800);
            return $weeks === 1 ? '1 minggu lalu' : "{$weeks} minggu lalu";
        }

        // Kurang dari 1 tahun
        if ($diff < 31536000) {
            $months = intval($diff / 2592000);
            return $months === 1 ? '1 bulan lalu' : "{$months} bulan lalu";
        }

        // Lebih dari 1 tahun
        $years = intval($diff / 31536000);
        return $years === 1 ? '1 tahun lalu' : "{$years} tahun lalu";
    }

    /**
     * Format date in Indonesian with relative time
     * Example: "5 jam lalu • 16 Oktober 2026"
     */
    public static function formatWithRelative($date, $includeFullDate = true): string
    {
        if (!$date) {
            return '';
        }

        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $relative = self::relativeTime($date);

        if ($includeFullDate) {
            $fullDate = $date->translatedFormat('d M Y');
            return "{$relative} • {$fullDate}";
        }

        return $relative;
    }
}
