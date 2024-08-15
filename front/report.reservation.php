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

Session::checkRight("reports", READ);

Html::header(Report::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "tools", "report");

if (!isset($_GET["id"])) {
    $_GET["id"] = 0;
}

Report::title();

echo "<form method='get' name='form' action='report.reservation.php'>";
echo "<table class='tab_cadre' width='500'><tr class='tab_bg_2'>";
echo "<td class='center' width='300'>";
User::dropdown(['name'   => 'id',
    'value'  => $_GET["id"],
    'right'  => 'reservation'
]);

echo "</td>";
echo "<td class='center'><input type='submit' class='btn btn-primary' name='submit' value='" .
      __s('Display report') . "'></td></tr>";
echo "</table>";
Html::closeForm();

if ($_GET["id"] > 0) {
    Reservation::showForUser($_GET["id"]);
}
Html::footer();
