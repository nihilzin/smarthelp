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

$SECURITY_STRATEGY = 'faq_access';

include('../inc/includes.php');

// Redirect management
if (isset($_GET["redirect"])) {
    Toolbox::manageRedirect($_GET["redirect"]);
}

if (Session::getLoginUserID()) {
    Html::helpHeader(__('FAQ'), 'faq');
} else {
    $_SESSION["glpilanguage"] = $_SESSION['glpilanguage'] ?? $CFG_GLPI['language'];
   // Anonymous FAQ
    Html::simpleHeader(__('FAQ'), [
        __('Authentication') => '/',
        __('FAQ')            => '/front/helpdesk.faq.php'
    ]);
}

if (isset($_GET["id"])) {
    $kb = new KnowbaseItem();
    if ($kb->getFromDB($_GET["id"])) {
        $kb->showFull();
    }
} else {
   // Manage forcetab : non standard system (file name <> class name)
    if (isset($_GET['forcetab'])) {
        Session::setActiveTab('Knowbase', $_GET['forcetab']);
        unset($_GET['forcetab']);
    }

    $kb = new Knowbase();
    $kb->display($_GET);
}

Html::helpFooter();
