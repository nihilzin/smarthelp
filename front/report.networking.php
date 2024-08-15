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

/**
 * Show choices for network reports
 */

use Glpi\Socket;

include('../inc/includes.php');

Session::checkRight("reports", READ);

Html::header(Report::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "tools", "report");

Report::title();

// Titre

echo "<table class='tab_cadre' >";
echo "<tr><th colspan='3'>&nbsp;" . __('Network report') . "</th></tr>";
echo "</table><br>";

// 3. Selection d'affichage pour generer la liste

echo "<form name='form' method='post' action='report.location.list.php'>";
echo "<table class='tab_cadre' width='500'>";
echo "<tr class='tab_bg_1'><td width='120'>" . __('By location') . "</td>";
echo "<td>";
Location::dropdown(['entity' => $_SESSION["glpiactive_entity"]]);
echo "</td><td class='center' width='120'>";
echo "<input type='submit' value=\"" . __s('Display report') . "\" class='btn btn-primary'>";
echo "</td></tr>";
echo "</table>";
Html::closeForm();

echo "<form name='form2' method='post' action='report.switch.list.php'>";
echo "<table class='tab_cadre' width='500'>";
echo "<tr class='tab_bg_1'><td width='120'>" . __('By hardware') . "</td>";
echo "<td>";
NetworkEquipment::dropdown(['name' => 'switch']);
echo "</td><td class='center' width='120'>";
echo "<input type='submit' value=\"" . __s('Display report') . "\" class='btn btn-primary'>";
echo "</td></tr>";
echo "</table>";
Html::closeForm();

if (countElementsInTableForMyEntities("glpi_sockets") > 0) {
    echo "<form name='form3' method='post' action='report.socket.list.php'>";
    echo "<table class='tab_cadre' width='500'>";
    echo "<tr class='tab_bg_1'><td width='120'>" . __('By network socket') . "</td>";
    echo "<td>";
    Socket::dropdown(['name'   => 'prise']);
    echo "</td><td class='center' width='120'>";
    echo "<input type='submit' value=\"" . __s('Display report') . "\" class='btn btn-primary'>";
    echo "</td></tr>";
    echo "</table>";
    Html::closeForm();
}

Html::footer();
