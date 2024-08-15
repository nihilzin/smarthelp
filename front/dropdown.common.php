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

if (!($dropdown instanceof CommonDropdown)) {
    Html::displayErrorAndDie('');
}
if (!$dropdown->canView()) {
   // Gestion timeout session
    Session::redirectIfNotLoggedIn();
    Html::displayRightError();
}

$dropdown::displayCentralHeader();

Search::show(get_class($dropdown));

Html::footer();
