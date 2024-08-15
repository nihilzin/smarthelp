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

if (
    (!isset($_GET['itemtype']) || !class_exists($_GET['itemtype']))
    && (!isset($_POST['itemtype']) || !class_exists($_POST['itemtype']))
) {
    throw new \RuntimeException(
        'Missing or incorrect device type called!'
    );
}

$itemtype = isset($_POST['itemtype']) ? $_POST['itemtype'] : $_GET['itemtype'];
$options = [
    'itemtype' => $itemtype
];
$dropdown = new $itemtype();
include(GLPI_ROOT . "/front/dropdown.common.form.php");
