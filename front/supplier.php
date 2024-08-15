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

Html::header(Supplier::getTypeName(Session::getPluralNumber()), '', "management", "supplier");

Search::show('Supplier');

Html::footer();
