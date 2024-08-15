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

namespace Glpi\Application\View;

use Glpi\Application\ErrorHandler;
use Glpi\Application\View\Extension\ConfigExtension;
use Glpi\Application\View\Extension\DataHelpersExtension;
use Glpi\Application\View\Extension\DocumentExtension;
use Glpi\Application\View\Extension\FrontEndAssetsExtension;
use Glpi\Application\View\Extension\I18nExtension;
use Glpi\Application\View\Extension\ItemtypeExtension;
use Glpi\Application\View\Extension\PhpExtension;
use Glpi\Application\View\Extension\PluginExtension;
use Glpi\Application\View\Extension\RoutingExtension;
use Glpi\Application\View\Extension\SearchExtension;
use Glpi\Application\View\Extension\SecurityExtension;
use Glpi\Application\View\Extension\SessionExtension;
use Glpi\Application\View\Extension\TeamExtension;
use Glpi\Debug\Profiler;
use Plugin;
use Session;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;
use Twig\Loader\FilesystemLoader;

/**
 * @since 10.0.0
 */
class TemplateRenderer
{
    /**
     * @var Environment
     */
    private $environment;

    public function __construct(string $rootdir = GLPI_ROOT, string $cachedir = GLPI_CACHE_DIR)
    {
        $loader = new FilesystemLoader($rootdir . '/templates', $rootdir);

        $active_plugins = Plugin::getPlugins();
        foreach ($active_plugins as $plugin_key) {
           // Add a dedicated namespace for each active plugin, so templates would be loadable using
           // `@my_plugin/path/to/template.html.twig` where `my_plugin` is the plugin key and `path/to/template.html.twig`
           // is the path of the template inside the `/templates` directory of the plugin.
            $loader->addPath(Plugin::getPhpDir($plugin_key . '/templates'), $plugin_key);
        }

        $env_params = [
            'debug'       => $_SESSION['glpi_use_mode'] ?? null === Session::DEBUG_MODE,
            'auto_reload' => true, // Force refresh
        ];

        $tpl_cachedir = $cachedir . '/templates';
        if (
            (file_exists($tpl_cachedir) && !is_writable($tpl_cachedir))
            || (!file_exists($tpl_cachedir) && !is_writable($cachedir))
        ) {
            trigger_error(sprintf('Cache directory "%s" is not writeable.', $tpl_cachedir), E_USER_WARNING);
        } else {
            $env_params['cache'] = $tpl_cachedir;
        }

        $this->environment = new Environment(
            $loader,
            $env_params
        );
       // Vendor extensions
        $this->environment->addExtension(new DebugExtension());
        $this->environment->addExtension(new StringExtension());
       // GLPI extensions
        $this->environment->addExtension(new ConfigExtension());
        $this->environment->addExtension(new SecurityExtension());
        $this->environment->addExtension(new DataHelpersExtension());
        $this->environment->addExtension(new DocumentExtension());
        $this->environment->addExtension(new FrontEndAssetsExtension());
        $this->environment->addExtension(new I18nExtension());
        $this->environment->addExtension(new ItemtypeExtension());
        $this->environment->addExtension(new PhpExtension());
        $this->environment->addExtension(new PluginExtension());
        $this->environment->addExtension(new RoutingExtension());
        $this->environment->addExtension(new SearchExtension());
        $this->environment->addExtension(new SessionExtension());
        $this->environment->addExtension(new TeamExtension());

       // add superglobals
        $this->environment->addGlobal('_post', $_POST);
        $this->environment->addGlobal('_get', $_GET);
        $this->environment->addGlobal('_request', $_REQUEST);
    }

    /**
     * Return singleton instance of self.
     *
     * @return TemplateRenderer
     */
    public static function getInstance(): TemplateRenderer
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Return Twig environment used to handle templates.
     *
     * @return Environment
     */
    public function getEnvironment(): Environment
    {
        return $this->environment;
    }

    /**
     * Renders a template.
     *
     * @param string $template
     * @param array  $variables
     *
     * @return string
     */
    public function render(string $template, array $variables = []): string
    {
        try {
            Profiler::getInstance()->start($template, Profiler::CATEGORY_TWIG);
            return $this->environment->load($template)->render($variables);
        } catch (\Twig\Error\Error $e) {
            ErrorHandler::getInstance()->handleTwigError($e);
        } finally {
            Profiler::getInstance()->stop($template);
        }
        return '';
    }

    /**
     * Displays a template.
     *
     * @param string $template
     * @param array  $variables
     *
     * @return void
     */
    public function display(string $template, array $variables = []): void
    {
        try {
            Profiler::getInstance()->start($template, Profiler::CATEGORY_TWIG);
            $this->environment->load($template)->display($variables);
        } catch (\Twig\Error\Error $e) {
            ErrorHandler::getInstance()->handleTwigError($e);
        } finally {
            Profiler::getInstance()->stop($template);
        }
    }
}
