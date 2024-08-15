<?php

/*!
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


// Verifique a versão PHP para não ter problemas.
// Precisa ser o primeiro passo antes de qualquer inclusão
if (
    version_compare(PHP_VERSION, '7.4.0', '<') ||
    version_compare(PHP_VERSION, '8.4.0', '>=')
) {
    die('PHP 7.4.0 - 8.4.0 (exclusivo) necessário');
}

use Glpi\Application\View\TemplateRenderer;
use Glpi\Plugin\Hooks;
use Glpi\Toolbox\Sanitizer;

//Load GLPI constants
define('GLPI_ROOT', __DIR__);
include(GLPI_ROOT . "/inc/based_config.php");

// If config_db doesn't exist -> start installation
if (!file_exists(GLPI_CONFIG_DIR . "/config_db.php")) {
    if (file_exists(GLPI_ROOT . '/install/install.php')) {
        Html::redirect("install/install.php");
    } else {
        // Init session (required by header display logic)
        Session::setPath();
        Session::start();
        Session::loadLanguage('', false);
        // Prevent inclusion of debug informations in footer, as they are based on vars that are not initialized here.
        $_SESSION['glpi_use_mode'] = Session::NORMAL_MODE;

        // no translation
        $title_text        = 'O Sistema parece não estar configurado corretamente';
        $missing_conf_text = sprintf('O arquivo de configuração do banco de dados "%s" está ausente.', GLPI_CONFIG_DIR . '/config_db.php');
        $hint_text         = 'Você deve reiniciar o processo de instalação ou restaurar este arquivo.';

        Html::nullHeader('Configuração Faltando');
        echo '<div class="container-fluid mb-4">';
        echo '<div class="row justify-content-center">';
        echo '<div class="col-xl-6 col-lg-7 col-md-9 col-sm-12">';
        echo '<h2>' . $title_text . '</h2>';
        echo '<p class="mt-2 mb-n2 alert alert-warning">';
        echo $missing_conf_text;
        echo ' ';
        echo $hint_text;
        echo '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        Html::nullFooter();
    }
    die();
} else {
    include(GLPI_ROOT . "/inc/includes.php");
    $_SESSION["glpicookietest"] = 'testcookie';

    //Try to detect GLPI agent calls
    $rawdata = file_get_contents("php://input");
    if (!empty($rawdata) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        include_once(GLPI_ROOT . '/front/inventory.php');
        die();
    }

    // For compatibility reason
    if (isset($_GET["noCAS"])) {
        $_GET["noAUTO"] = $_GET["noCAS"];
    }

    if (!isset($_GET["noAUTO"])) {
        Auth::redirectIfAuthenticated();
    }

    $redirect = array_key_exists('redirect', $_GET) ? Sanitizer::unsanitize($_GET['redirect']) : '';

    Auth::checkAlternateAuthSystems(true, $redirect);

    $theme = $_SESSION['glpipalette'] ?? 'auror';

    $errors = "";
    if (isset($_GET['error']) && $redirect !== '') {
        switch ($_GET['error']) {
            case 1: // cookie error
                $errors .= __('Você deve aceitar os cookies para acessar essa aplicação');
                break;

            case 2: // GLPI_SESSION_DIR not writable
                $errors .= __('Verificando permissões de gravação para arquivos de sessão');
                break;

            case 3:
                $errors .= __('Usuário ou Senha Incorretos');
                break;
        }
    }

    // redirect to ticket
    if ($redirect !== '') {
        Toolbox::manageRedirect($redirect);
    }

    // Random number for html id/label
    $rand = mt_rand();

    TemplateRenderer::getInstance()->display('pages/login.html.twig', [
        'rand'                => $rand,
        'card_bg_width'       => true,
        'lang'                => $CFG_GLPI["languages"][$_SESSION['glpilanguage']][3],
        'title'               => __('Login'),
        'noAuto'              => $_GET["noAUTO"] ?? 0,
        'redirect'            => $redirect,
        'text_login'          => $CFG_GLPI['smart_login'],
        'namfield'            => ($_SESSION['namfield'] = uniqid('fielda')),
        'pwdfield'            => ($_SESSION['pwdfield'] = uniqid('fieldb')),
        'rmbfield'            => ($_SESSION['rmbfield'] = uniqid('fieldc')),
        'show_lost_password'  => $CFG_GLPI["notifications_mailing"]
                              && countElementsInTable('glpi_notifications', [
                                  'itemtype'  => 'User',
                                  'event'     => 'passwordforget',
                                  'is_active' => 1
                              ]),
        'languages_dropdown'  => Dropdown::showLanguages('language', [
            'display'             => false,
            'rand'                => $rand,
            'display_emptychoice' => true,
            'emptylabel'          => __('Default (from user profile)'),
            'width'               => '100%'
        ]),
        'right_panel'         => strlen($CFG_GLPI['text_login']) > 0
                               || count($PLUGIN_HOOKS[Hooks::DISPLAY_LOGIN] ?? []) > 0
                               || $CFG_GLPI["use_public_faq"],
        'auth_dropdown_login' => Auth::dropdownLogin(false, $rand),
        'copyright_message'   => Html::getCopyrightMessage(false),
        'errors'              => $errors
    ]);
}
// call cron
if (!GLPI_DEMO_MODE) {
    CronTask::callCronForce();
}

echo "</body></html>";
