<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class LibraryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::directive('library', function (string $expression): string {
            return "<?php echo \App\Providers\LibraryServiceProvider::render({$expression}); ?>";
        });
    }

    /**
     * Called at render time. Receives library/plugin names as separate strings.
     * Example: @library('datatables','fixedColumns') → render('datatables','fixedColumns')
     */
    public static function render(string ...$names): string
    {
        $config  = config('library', []);
        $env     = App::environment();
        $locale  = App::getLocale();
        $baseUrl = URL::to('/');
        $output  = [];
        $loaded  = [];

        // Build a flat plugin index: pluginName → [parentName, definition]
        $pluginIndex = [];
        foreach ($config as $libName => $def) {
            foreach ($def['plugins'] ?? [] as $pluginName => $pluginDef) {
                $pluginIndex[$pluginName] = [$libName, $pluginDef];
            }
        }

        foreach ($names as $name) {
            $name = trim($name);

            if (isset($loaded[$name])) {
                continue;
            }

            if (isset($config[$name])) {
                $output[]      = self::renderDefinition($name, $config[$name], $env, $locale, $baseUrl);
                $loaded[$name] = true;
            } elseif (isset($pluginIndex[$name])) {
                [$parentName, $pluginDef] = $pluginIndex[$name];
                $output[]      = self::renderDefinition($name, $pluginDef, $env, $locale, $baseUrl, $parentName);
                $loaded[$name] = true;
            }
        }

        return implode("\n", array_filter($output));
    }

    private static function renderDefinition(
        string $name,
        array  $def,
        string $env,
        string $locale,
        string $baseUrl,
        string $parentName = ''
    ): string {
        $html      = [];
        $assetBase = $parentName ?: $name;

        foreach (self::resolveFiles($def['css'] ?? [], $env) as $file) {
            $href   = self::isExternal($file) ? $file : "{$baseUrl}/vendor/{$assetBase}/css/{$file}";
            $html[] = "<link rel=\"stylesheet\" href=\"{$href}\">";
        }

        foreach (self::resolveFiles($def['js'] ?? [], $env) as $file) {
            $src    = self::isExternal($file) ? $file : "{$baseUrl}/vendor/{$assetBase}/js/{$file}";
            $html[] = "<script src=\"{$src}\"></script>";
        }

        foreach ($def['script'] ?? [] as $block) {
            $block  = self::interpolate($block, $locale, $baseUrl);
            $html[] = "<script>{$block}</script>";
        }

        return implode("\n", $html);
    }

    private static function resolveFiles(string|array $files, string $env): array
    {
        if (is_string($files)) {
            return [$files];
        }

        $generic  = [];
        $envMatch = null;

        foreach ($files as $key => $value) {
            if ($key === $env) {
                $envMatch = (array) $value;
            } elseif (is_int($key)) {
                $generic[] = $value;
            }
        }

        return $envMatch ?? $generic;
    }

    private static function isExternal(string $path): bool
    {
        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://');
    }

    private static function interpolate(string $text, string $locale, string $url): string
    {
        return str_replace(['{locale}', '{url}'], [$locale, $url], $text);
    }
}
