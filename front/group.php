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

Session::checkRight("group", READ);

Html::header(Group::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "admin", "group");

$group = new Group();
$group->title();

Search::show('Group');

Html::footer();
