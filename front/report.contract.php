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

echo "<form name='form' method='post' action='report.contract.list.php'>";

echo "<table class='tab_cadre_fixe' >";
echo "<tr><th colspan='4'>" . __('Hardware under contract') . " </th></tr>";

// 3. Selection d'affichage pour generer la liste
echo "<tr class='tab_bg_1'>";
echo "<td class='center' width='20%'>" . __('Item type') . "</td>";
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
echo "</td> ";

echo "<td class='center' width='20%'>" . _n('Date', 'Dates', 1) . "</td>";
echo "<td width='30%'>";
$y      = date("Y");
$values = [ 0 => __('All')];
for ($i = ($y - 10); $i < ($y + 10); $i++) {
    $values[$i] = $i;
}
Dropdown::showFromArray('year', $values, ['value'    => $y,
    'multiple' => true
]);

echo "</td></tr>";

echo "<tr><td class='tab_bg_1 center' colspan='4'>";
echo "<input type='submit' value=\"" . __s('Display report') . "\" class='btn btn-primary'></td></tr>";

echo "</table>";
Html::closeForm();

Html::footer();
