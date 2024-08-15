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

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

Session::checkRight("reports", READ);

Html::header(Report::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "tools", "report");

Report::title();

// Titre

echo "<form name='form' method='post' action='report.year.list.php'>";

echo "<table class='tab_cadre_fixe'>";
echo "<tr><th colspan='4'>" . __("Equipment's report by year") . "</th></tr>";

// 3. Selection d'affichage pour generer la liste

echo "<tr class='tab_bg_2'>";
echo "<td width='20%' class='b center'>" . __('Item type') . "</td>";
echo "<td width='30%'>";
$values = [0 => __('All')];
foreach ($CFG_GLPI["contract_types"] as $itemtype) {
    if ($item = getItemForItemtype($itemtype)) {
        $values[$itemtype] = $item->getTypeName();
    }
}
Dropdown::showFromArray('item_type', $values, ['value'    => 0,
    'multiple' => true
]);
echo "</td>";

echo "<td width='20%' class='center'><p class='b'>" . _n('Date', 'Dates', 1) . "</p></td>";
echo "<td width='30%'>";
$y = date("Y");
$values = [ 0 => __('All')];
for ($i = ($y - 10); $i < ($y + 10); $i++) {
    $values[$i] = $i;
}
Dropdown::showFromArray('year', $values, ['value'    => $y,
    'multiple' => true
]);
echo "</td></tr>";

echo "<tr class='tab_bg_2'><td colspan='4' class='center'>";
echo "<input type='submit' value=\"" . __s('Display report') . "\" class='btn btn-primary'></td></tr>";

echo "</table>";

Html::closeForm();

Html::footer();
