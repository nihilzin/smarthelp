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

use Glpi\Toolbox\Sanitizer;

if (!defined('GLPI_ROOT')) {
    include('../inc/includes.php');
}

Session::checkRight("user", User::IMPORTEXTAUTHUSERS);

// Need REQUEST to manage initial walues and posted ones
if (isset($_REQUEST['basedn'])) {
    $_REQUEST['basedn'] = Sanitizer::unsanitize($_REQUEST['basedn']);
}
if (isset($_REQUEST['ldap_filter'])) {
    $_REQUEST['ldap_filter'] = Sanitizer::unsanitize($_REQUEST['ldap_filter']);
}

AuthLDAP::manageValuesInSession($_REQUEST);

if (isset($_SESSION['ldap_import']['_in_modal']) && $_SESSION['ldap_import']['_in_modal']) {
    $_REQUEST['_in_modal'] = 1;
}

Html::header(__('LDAP directory link'), $_SERVER['PHP_SELF'], "admin", "user", "ldap");

if (isset($_GET['start'])) {
    $_SESSION['ldap_import']['start'] = $_GET['start'];
}
if (isset($_GET['order'])) {
    $_SESSION['ldap_import']['order'] = $_GET['order'];
}
if ($_SESSION['ldap_import']['action'] == 'show') {
    $authldap = new AuthLDAP();
    $authldap->getFromDB($_SESSION['ldap_import']['authldaps_id']);

    AuthLDAP::showUserImportForm($authldap);

    if (
        isset($_SESSION['ldap_import']['authldaps_id'])
        && ($_SESSION['ldap_import']['authldaps_id'] != NOT_AVAILABLE)
        && (isset($_POST['search']) || isset($_GET['start']) || isset($_POST['glpilist_limit']))
    ) {
        echo "<br />";
        AuthLDAP::searchUser($authldap);
    }
}

Html::footer();
