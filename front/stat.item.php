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

Html::header(__('Statistics'), '', "helpdesk", "stat");

Session::checkRight("statistic", READ);

if (isset($_GET["date1"])) {
    $_POST["date1"] = $_GET["date1"];
}
if (isset($_GET["date2"])) {
    $_POST["date2"] = $_GET["date2"];
}

if (empty($_POST["date1"]) && empty($_POST["date2"])) {
    $year           = date("Y") - 1;
    $_POST["date1"] = date("Y-m-d", mktime(1, 0, 0, date("m"), date("d"), $year));
    $_POST["date2"] = date("Y-m-d");
}

if (
    !empty($_POST["date1"])
    && !empty($_POST["date2"])
    && (strcmp($_POST["date2"], $_POST["date1"]) < 0)
) {
    $tmp            = $_POST["date1"];
    $_POST["date1"] = $_POST["date2"];
    $_POST["date2"] = $tmp;
}

if (!isset($_GET["start"])) {
    $_GET["start"] = 0;
}

Stat::title();

echo "<div class='center'><form method='post' name='form' action='stat.item.php'>";
echo "<table class='tab_cadre'><tr class='tab_bg_2'>";
echo "<td class='right'>" . __('Start date') . "</td><td>";
Html::showDateField("date1", ['value' => $_POST["date1"]]);
echo "</td><td rowspan='2' class='center'>";
echo "<input type='submit' class='btn btn-primary' name='submit' value='" . __s('Display report') . "'></td></tr>";
echo "<tr class='tab_bg_2'><td class='right'>" . __('End date') . "</td><td>";
Html::showDateField("date2", ['value' => $_POST["date2"]]);
echo "</td></tr>";
echo "</table>";
Html::closeForm();
echo "</div>";

Stat::showItems($_SERVER['PHP_SELF'], $_POST["date1"], $_POST["date2"], $_GET['start']);

Html::footer();
