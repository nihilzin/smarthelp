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

$rulecollection = new RuleTicketCollection($_SESSION['glpiactive_entity']);

include(GLPI_ROOT . "/front/rule.common.php");
