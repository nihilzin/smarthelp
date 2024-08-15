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

/**
 * @since 0.85
 */

if (strpos($_SERVER['PHP_SELF'], "getDropdownConnect.php")) {
    include('../inc/includes.php');
    header("Content-Type: application/json; charset=UTF-8");
    Html::header_nocache();
} else if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

echo Dropdown::getDropdownConnect($_POST);
