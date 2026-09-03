<?php

namespace App\Support;

class DeviceName
{
    /**
     * @return array{browser: string, platform: string, device: string}
     */
    public static function parse(?string $agent): array
    {
        $agent = (string) $agent;

        return [
            'browser' => self::browser($agent),
            'platform' => self::platform($agent),
            'device' => self::device($agent),
        ];
    }

    private static function browser(string $agent): string
    {
        return match (true) {
            $agent === '' => 'Tidak diketahui',
            str_contains($agent, 'Edg/') => 'Edge',
            str_contains($agent, 'OPR/') || str_contains($agent, 'Opera') => 'Opera',
            str_contains($agent, 'Firefox/') => 'Firefox',
            str_contains($agent, 'SamsungBrowser') => 'Samsung Internet',
            str_contains($agent, 'Chrome/') => 'Chrome',
            str_contains($agent, 'Safari/') => 'Safari',
            default => 'Lainnya',
        };
    }

    private static function platform(string $agent): string
    {
        return match (true) {
            $agent === '' => 'Tidak diketahui',
            str_contains($agent, 'Windows NT 10') => 'Windows 10 atau 11',
            str_contains($agent, 'Windows') => 'Windows',
            str_contains($agent, 'Android') => 'Android',
            str_contains($agent, 'iPhone') || str_contains($agent, 'iPad') => 'iOS',
            str_contains($agent, 'Mac OS X') => 'macOS',
            str_contains($agent, 'Linux') => 'Linux',
            default => 'Lainnya',
        };
    }

    private static function device(string $agent): string
    {
        return match (true) {
            $agent === '' => 'Tidak diketahui',
            str_contains($agent, 'iPad') || str_contains($agent, 'Tablet') => 'Tablet',
            str_contains($agent, 'Mobile') || str_contains($agent, 'Android') => 'Ponsel',
            default => 'Komputer',
        };
    }
}
