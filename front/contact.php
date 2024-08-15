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

Session::checkRight("contact_enterprise", READ);

Html::header(Contact::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "management", "contact");

Search::show('Contact');

Html::footer();
