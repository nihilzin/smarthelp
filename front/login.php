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

use Glpi\Application\View\TemplateRenderer;
use Glpi\Toolbox\Sanitizer;

/** @var array $CFG_GLPI */
global $CFG_GLPI;

$SECURITY_STRATEGY = 'no_check';

include('../inc/includes.php');


if (!isset($_SESSION["glpicookietest"]) || ($_SESSION["glpicookietest"] != 'testcookie')) {
    if (!is_writable(GLPI_SESSION_DIR)) {
        Html::redirect($CFG_GLPI['root_doc'] . "/index.php?error=2");
    } else {
        Html::redirect($CFG_GLPI['root_doc'] . "/index.php?error=1");
    }
}

$_POST = array_map('stripslashes', $_POST);

//Do login and checks
//$user_present = 1;
if (isset($_SESSION['namfield']) && isset($_POST[$_SESSION['namfield']])) {
    $login = $_POST[$_SESSION['namfield']];
} else {
    $login = '';
}
if (isset($_SESSION['pwdfield']) && isset($_POST[$_SESSION['pwdfield']])) {
    $password = Sanitizer::unsanitize($_POST[$_SESSION['pwdfield']]);
} else {
    $password = '';
}
// Manage the selection of the auth source (local, LDAP id, MAIL id)
if (isset($_POST['auth'])) {
    $login_auth = $_POST['auth'];
} else {
    $login_auth = '';
}

$remember = isset($_SESSION['rmbfield']) && isset($_POST[$_SESSION['rmbfield']]) && $CFG_GLPI["login_remember_time"];

// Redirect management
$REDIRECT = "";
if (isset($_POST['redirect']) && (strlen($_POST['redirect']) > 0)) {
    $REDIRECT = "?redirect=" . rawurlencode($_POST['redirect']);
} else if (isset($_GET['redirect']) && strlen($_GET['redirect']) > 0) {
    $REDIRECT = "?redirect=" . rawurlencode($_GET['redirect']);
}

$auth = new Auth();


// now we can continue with the process...
if ($auth->login($login, $password, (isset($_REQUEST["noAUTO"]) ? $_REQUEST["noAUTO"] : false), $remember, $login_auth)) {
    Auth::redirectIfAuthenticated();
} else {
    http_response_code(401);
    TemplateRenderer::getInstance()->display('pages/login_error.html.twig', [
        'errors'    => $auth->getErrors(),
        'login_url' => $CFG_GLPI["root_doc"] . '/front/logout.php?noAUTO=1' . str_replace("?", "&", $REDIRECT),
    ]);
    exit();
}
