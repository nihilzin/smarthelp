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

Session::checkRight("datacenter", READ);

Html::header(
    PassiveDCEquipment::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "assets",
    "passivedcequipment"
);

Search::show('PassiveDCEquipment');

Html::footer();
