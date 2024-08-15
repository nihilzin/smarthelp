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
 * Show network port by network equipment
 */

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

Session::checkRight("reports", READ);

// Titre
if (isset($_POST["switch"]) && $_POST["switch"]) {
    Html::header(Report::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "tools", "report");

    Report::title();

    $name = Dropdown::getDropdownName("glpi_networkequipments", $_POST["switch"]);
    echo "<div class='center spaced'><h2>" . sprintf(__('Network report by hardware: %s'), $name) .
        "</h2></div>";
    Report::reportForNetworkInformations(
        'glpi_networkequipments AS ITEM', //from
        ['PORT_1' => 'items_id', 'ITEM' => 'id', ['AND' => ['PORT_1.itemtype' => 'NetworkEquipment']]], //joincrit
        ['ITEM.id' => $_POST['switch']] //where
    );

    Html::footer();
} else {
    Html::redirect($CFG_GLPI['root_doc'] . "/front/report.networking.php");
}
