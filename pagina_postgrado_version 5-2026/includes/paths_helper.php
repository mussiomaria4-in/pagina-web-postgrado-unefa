<?php
/**
 * Rutas públicas del proyecto respecto al host (subcarpetas de Laragon, etc.)
 */
if (!function_exists('app_web_base')) {
    function app_web_base()
    {
        $script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', (string) $_SERVER['SCRIPT_NAME']) : '/';
        $dir = dirname($script);
        if ($dir === '/' || $dir === '\\' || $dir === '.') {
            return '';
        }
        if (preg_match('#/includes$#', $dir)) {
            $dir = dirname($dir);
            $dir = str_replace('\\', '/', $dir);
            $dir = rtrim($dir, '/');
            if ($dir === '/' || $dir === '') {
                return '';
            }
            return $dir;
        }
        $dir = rtrim(str_replace('\\', '/', $dir), '/');
        return $dir === '/' ? '' : $dir;
    }
}

if (!function_exists('app_url')) {
    /**
     * @param string $path ruta relativa al directorio público del proyecto (ej. includes/foo.php)
     */
    function app_url($path)
    {
        $path = ltrim(str_replace('\\', '/', (string) $path), '/');
        $b = app_web_base();

        return ($b === '' ? '' : $b) . '/' . $path;
    }
}
