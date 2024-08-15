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

Session::checkRight("config", UPDATE);

$config = new Config();

Html::header(
    __('External authentication sources'),
    $_SERVER['PHP_SELF'],
    "config",
    "auth",
    "settings"
);
$config->showFormAuthentication();

Html::footer();
