<?php

if (!function_exists('app_base_url')) {
    function app_base_url(): string
    {
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

        if ($scriptDir === '/' || $scriptDir === '.') {
            $scriptDir = '';
        }

        return preg_replace('#/(controller)$#', '', $scriptDir) ?: '';
    }
}

if (!function_exists('asset_url')) {
    function asset_url(string $path): string
    {
        $cleanPath = '/' . ltrim($path, '/');

        return app_base_url() . $cleanPath;
    }
}
