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

/** @var array $_UGET */
global $_UGET;

// TODO: in GLPI 10.1 if we drop the old tab parameter then we can use
// PLUGINS_INCLUDED=false here to speed up the framework initiation time
include('../inc/includes.php');

if (!basename($_SERVER['SCRIPT_NAME']) == "helpdesk.faq.php") {
    Session::checkLoginUser();
}

// Manage tabs
if (
    isset($_GET['itemtype'])
    && (
        isset($_GET['tab'])
        || isset($_GET['tab_key'])
    )
) {
    if (isset($_GET['tab_key'])) {
        // Prefered way, load tab key directly, avoid unneeded call to Toolbox::getAvailablesTabs
        Session::setActiveTab($_UGET['itemtype'], $_UGET['tab_key']);
    } else {
        // Deprecated, use tab_key if possible
        Toolbox::deprecated("'tab' parameter is deprecated, use 'tab_key' instead");

        $tabs = Toolbox::getAvailablesTabs($_UGET['itemtype'], $_GET['id'] ?? null);
        $current      = 0;
        foreach (array_keys($tabs) as $key) {
            if ($current == $_GET['tab']) {
                Session::setActiveTab($_UGET['itemtype'], $key);
                break;
            }
            $current++;
        }
    }
}
