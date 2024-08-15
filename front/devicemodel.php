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

if (!isset($_GET['itemtype']) || !class_exists($_GET['itemtype'])) {
    throw new \RuntimeException(
        'Missing or incorrect device type called!'
    );
}

$dropdown = new $_GET['itemtype']();
include(GLPI_ROOT . "/front/dropdown.common.php");
