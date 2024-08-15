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

Session::checkRight("document", READ);

Html::header(Document::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "management", "document");

Search::show('Document');

Html::footer();
