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

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');


Session::checkRightsOr('group', [CREATE, UPDATE]);
Session::checkRight('user', User::UPDATEAUTHENT);

Html::header(__('LDAP directory link'), $_SERVER['PHP_SELF'], "admin", "group", "ldap");

if (isset($_GET['next']) || !isset($_SESSION['ldap_server']) && !isset($_POST['ldap_server'])) {
    AuthLDAP::ldapChooseDirectory($_SERVER['PHP_SELF']);
} else {
    if (isset($_POST["change_ldap_filter"])) {
        if (isset($_POST["ldap_filter"])) {
            $_SESSION["ldap_group_filter"] = $_POST["ldap_filter"];
        }
        if (isset($_POST["ldap_filter2"])) {
            $_SESSION["ldap_group_filter2"] = $_POST["ldap_filter2"];
        }
        Html::redirect($_SERVER['PHP_SELF']);
    } else {
        if (!isset($_GET['start'])) {
            $_GET['start'] = 0;
        }
        if (isset($_SESSION["ldap_import"])) {
            unset($_SESSION["ldap_import"]);
        }

        if (!isset($_SESSION["ldap_server"])) {
            if (isset($_POST["ldap_server"])) {
                $_SESSION["ldap_server"] = $_POST["ldap_server"];
            } else {
                Html::redirect($CFG_GLPI["root_doc"] . "/front/ldap.php");
            }
        }

        if (!AuthLDAP::testLDAPConnection($_SESSION["ldap_server"])) {
            unset($_SESSION["ldap_server"]);
            echo "<div class='center b'>" . __('Unable to connect to the LDAP directory') . "<br>";
            echo "<a href='" . $_SERVER['PHP_SELF'] . "?next=listservers'>" . __('Back') . "</a></div>";
        } else {
            if (!isset($_SESSION["ldap_group_filter"])) {
                $_SESSION["ldap_group_filter"] = '';
            }
            if (!isset($_SESSION["ldap_group_filter2"])) {
                $_SESSION["ldap_group_filter2"] = '';
            }
            if (isset($_GET["order"])) {
                $_SESSION["ldap_sortorder"] = $_GET["order"];
            }
            if (!isset($_SESSION["ldap_sortorder"])) {
                $_SESSION["ldap_sortorder"] = "ASC";
            }

            AuthLDAP::displayLdapFilter($_SERVER['PHP_SELF'], false);

            AuthLDAP::showLdapGroups(
                $_SERVER['PHP_SELF'],
                $_GET['start'],
                0,
                $_SESSION["ldap_group_filter"],
                $_SESSION["ldap_group_filter2"],
                $_SESSION["glpiactive_entity"],
                $_SESSION["ldap_sortorder"]
            );
        }
    }
}

Html::footer();
