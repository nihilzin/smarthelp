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

Session::checkRight("user", READ);

Html::header(User::getTypeName(Session::getPluralNumber()), '', "admin", "user");

$user = new User();
$user->title();

Search::show('User');

Html::footer();
