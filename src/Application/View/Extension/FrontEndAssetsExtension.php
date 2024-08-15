<?php

/**
 * ---------------------------------------------------------------------
 *
 * Powered by Urich Souza 
 *
 * https://github.com/nihilzin
 *
 * @copyright 2023 Urich Souza and contributors.
 * 
 * ---------------------------------------------------------------------
 */

namespace Glpi\Application\View\Extension;

use DBmysql;
use Entity;
use Glpi\Toolbox\FrontEnd;
use Html;
use Plugin;
use Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @since 10.0.0
 */
class FrontEndAssetsExtension extends AbstractExtension
{
    /**
     * GLPI root dir.
     * @var string
     */
    private $root_dir;

    public function __construct(string $root_dir = GLPI_ROOT)
    {
        $this->root_dir = $root_dir;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset_path', [$this, 'assetPath']),
            new TwigFunction('css_path', [$this, 'cssPath']),
            new TwigFunction('js_path', [$this, 'jsPath']),
            new TwigFunction('custom_css', [$this, 'customCss'], ['is_safe' => ['html']]),
            new TwigFunction('locales_js', [$this, 'localesJs'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Return domain-relative path of an asset.
     *
     * @param string $path
     *
     * @return string
     */
    public function assetPath(string $path): string
    {
        return Html::getPrefixedUrl($path);
    }

    /**
     * Return domain-relative path of a CSS file.
     *
     * @param string $path
     * @param array $options
     *
     * @return string
     */
    public function cssPath(string $path, array $options = []): string
    {
        $is_debug = isset($_SESSION['glpi_use_mode']) && $_SESSION['glpi_use_mode'] === Session::DEBUG_MODE;

        $file_path = parse_url($path, PHP_URL_PATH); // Strip potential quey string from path

        $extra_params = parse_url($path, PHP_URL_QUERY) ?: '';

        if (preg_match('/\.scss$/', $file_path)) {
            $compiled_file = Html::getScssCompilePath($file_path, $this->root_dir);

            if (!$is_debug && file_exists($compiled_file)) {
                $path = str_replace($this->root_dir, '', $compiled_file);
            } else {
                $path = '/front/css.php?file=' . $file_path;
                if ($is_debug) {
                    $extra_params .= ($extra_params !== '' ? '&' : '') . 'debug=1';
                }
            }
        } else {
            $minified_path = str_replace('.css', '.min.css', $file_path);

            if (!$is_debug && file_exists($this->root_dir . '/' . $minified_path)) {
                $path = $minified_path;
            } else {
                $path = $file_path;
            }
        }

        if ($extra_params !== '') {
            // Append query string from initial path, if any
            $path .= (str_contains($path, '?') ? '&' : '?') . $extra_params;
        }

        $path = Html::getPrefixedUrl($path);
        $path = $this->getVersionnedPath($path, $options);

        return $path;
    }

    /**
     * Return domain-relative path of a JS file.
     *
     * @param string $path
     * @param array $options
     *
     * @return string
     */
    public function jsPath(string $path, array $options = []): string
    {
        $is_debug = isset($_SESSION['glpi_use_mode']) && $_SESSION['glpi_use_mode'] === Session::DEBUG_MODE;

        $minified_path = str_replace('.js', '.min.js', $path);

        if (!$is_debug && file_exists($this->root_dir . '/' . $minified_path)) {
            $path = $minified_path;
        }

        $path = Html::getPrefixedUrl($path);
        $path = $this->getVersionnedPath($path, $options);

        return $path;
    }

    /**
     * Get path suffixed with asset version.
     *
     * @param string $path
     *
     * @return string
     */
    private function getVersionnedPath(string $path, array $options = []): string
    {
        $version = $options['version'] ?? GLPI_VERSION;
        $path .= (strpos($path, '?') !== false ? '&' : '?') . 'v=' . FrontEnd::getVersionCacheKey($version);

        return $path;
    }

    /**
     * Return custom CSS for active entity.
     *
     * @return string
     */
    public function customCss(): string
    {
        /** @var \DBmysql $DB */
        global $DB;

        $css = '';

        if ($DB instanceof DBmysql && $DB->connected) {
            $entity = new Entity();
            if (isset($_SESSION['glpiactive_entity'])) {
                // Apply active entity styles
                $entity->getFromDB($_SESSION['glpiactive_entity']);
            } else {
               // Apply root entity styles
                $entity->getFromDB('0');
            }
            $css = $entity->getCustomCssTag();
        }

        return $css;
    }

    /**
     * Return locales JS code.
     *
     * @return string
     */
    public function localesJs(): string
    {
        if (!isset($_SESSION['glpilanguage'])) {
            return '';
        }

       // Compute available translation domains
        $locales_domains = ['glpi' => GLPI_VERSION];
        $plugins = Plugin::getPlugins();
        foreach ($plugins as $plugin) {
            $locales_domains[$plugin] = Plugin::getPluginFilesVersion($plugin);
        }

        $script = <<<JAVASCRIPT
         $(function() {
            i18n.setLocale('{$_SESSION['glpilanguage']}');
         });
JAVASCRIPT;

        foreach ($locales_domains as $locale_domain => $locale_version) {
            $locales_path = Html::getPrefixedUrl(
                '/front/locale.php'
                . '?domain=' . $locale_domain
                . '&v=' . FrontEnd::getVersionCacheKey($locale_version)
                . ($_SESSION['glpi_use_mode'] == Session::DEBUG_MODE ? '&debug' : '')
            );
            $script .= <<<JAVASCRIPT
            $(function() {
               $.ajax({
                  type: 'GET',
                  url: '{$locales_path}',
                  success: function(json) {
                     i18n.loadJSON(json, '{$locale_domain}');
                  }
               });
            });
JAVASCRIPT;
        }

        return Html::scriptBlock($script);
    }
}
