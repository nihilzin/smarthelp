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
 * @var array $CFG_GLPI
 * @var \DBmysql $DB
 */
global $CFG_GLPI, $DB;

include('../inc/includes.php');

Session::checkSeveralRightsOr(["networking" => UPDATE,
    "internet"   => UPDATE
]);

if (!$DB->tableExists('glpi_networkportmigrations')) {
    Session::addMessageAfterRedirect(__('You don\'t need the "migration cleaner" tool anymore...'));
    Html::redirect($CFG_GLPI["root_doc"] . "/front/central.php");
}

Html::header(__('Migration cleaner'), $_SERVER['PHP_SELF'], "tools", "migration");

echo "<div class='spaced' id='tabsbody'>";
echo "<table class='tab_cadre_fixe'>";

echo "<tr><th>" . __('"Migration cleaner" tool') . "</td></tr>";

if (
    Session::haveRight('internet', UPDATE)
    // Check access to all entities
    && Session::canViewAllEntities()
) {
    echo "<tr class='tab_bg_1'><td class='center'>";
    Html::showSimpleForm(
        IPNetwork::getFormURL(),
        'reinit_network',
        __('Reinit the network topology')
    );
    echo "</td></tr>";
}
if (Session::haveRight('networking', UPDATE)) {
    echo "<tr class='tab_bg_1'><td class='center'>";
    echo "<a href='" . $CFG_GLPI['root_doc'] . "/front/networkportmigration.php'>" .
         __('Clean the network port migration errors') . "</a>";
    echo "</td></tr>";
}
echo "</table>";
echo "</div>";


Html::footer();
