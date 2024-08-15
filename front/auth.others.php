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
include('../inc/includes.php');

/** @var array $CFG_GLPI */
global $CFG_GLPI;

Session::checkRight("config", UPDATE);

$config = new Config();

//Update CAS configuration
if (isset($_POST["update"])) {
    $_POST['id'] = 1;
    $config->update($_POST);
    Html::redirect($CFG_GLPI["root_doc"] . "/front/auth.others.php");
}

Html::header(__('External authentication sources'), $_SERVER['PHP_SELF'], "config", "auth", "others");

Auth::showOtherAuthList();

Html::footer();
