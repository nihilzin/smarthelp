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

Session::checkLoginUser();

Html::header(__('Setup'), $_SERVER['PHP_SELF'], "config", "commondropdown");

echo "<div class='center'>";

$optgroup = Dropdown::getStandardDropdownItemTypes();
if (count($optgroup) > 0) {
    Dropdown::showItemTypeList($optgroup);
} else {
    Html::displayRightError();
}

echo "</div>";
Html::footer();
