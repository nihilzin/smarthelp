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

use Glpi\SocketModel;

include('../inc/includes.php');

$dropdown = new SocketModel();
include(GLPI_ROOT . "/front/dropdown.common.php");
