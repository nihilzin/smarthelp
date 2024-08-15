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

Session::checkRight("slm", READ);

Html::header(
    SlaLevel::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "config",
    "sla",
    "slalevel"
);

Search::show('SlaLevel');

Html::footer();
