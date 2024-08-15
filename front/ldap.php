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

include('../inc/includes.php');

Session::checkRight("user", User::IMPORTEXTAUTHUSERS);

Html::header(__('LDAP directory link'), $_SERVER['PHP_SELF'], "admin", "user", "ldap");

if (isset($_SESSION["ldap_sortorder"])) {
    unset($_SESSION["ldap_sortorder"]);
}

AuthLDAP::manageValuesInSession([], true);

echo TemplateRenderer::getInstance()->render(
    'pages/admin/ldap.users.html.twig'
);

Html::footer();
