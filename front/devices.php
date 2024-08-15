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

Session::checkRight("device", READ);

Html::header(_n('Component', 'Components', Session::getPluralNumber()), $_SERVER['PHP_SELF'], "config", "commondevice");
echo "<div class='center'>";

$optgroup = Dropdown::getDeviceItemTypes();
Dropdown::showItemTypeMenu(_n('Component', 'Components', Session::getPluralNumber()), $optgroup);
Dropdown::showItemTypeList($optgroup);

echo "</div>";
Html::footer();
