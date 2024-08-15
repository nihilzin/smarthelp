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

$group = new Group();
$group->checkGlobal(UPDATE);
Session::checkRight('user', User::UPDATEAUTHENT);

Html::header(__('LDAP directory link'), $_SERVER['PHP_SELF'], "admin", "group", "ldap");

if (isset($_SESSION["ldap_import"])) {
    unset($_SESSION["ldap_import"]);
}
if (isset($_SESSION["ldap_import_entities"])) {
    unset($_SESSION["ldap_import_entities"]);
}
if (isset($_SESSION["ldap_server"])) {
    unset($_SESSION["ldap_server"]);
}
if (isset($_SESSION["entity"])) {
    unset($_SESSION["entity"]);
}
if (isset($_SESSION["ldap_sortorder"])) {
    unset($_SESSION["ldap_sortorder"]);
}

//Reset session variable related to filters
if (isset($_SESSION["ldap_group_filter"])) {
    unset($_SESSION["ldap_group_filter"]);
}
if (isset($_SESSION["ldap_group_filter2"])) {
    unset($_SESSION["ldap_group_filter2"]);
}

echo TemplateRenderer::getInstance()->render(
    'pages/admin/ldap.groups.html.twig'
);

Html::footer();
