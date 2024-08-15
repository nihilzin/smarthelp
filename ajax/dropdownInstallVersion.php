<?php

/**
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

/** @var \DBmysql $DB */
global $DB;

if (strpos($_SERVER['PHP_SELF'], "dropdownInstallVersion.php")) {
    $AJAX_INCLUDE = 1;
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
}

Session::checkRight("software", UPDATE);

if ($_POST['softwares_id'] > 0) {
    if (!isset($_POST['value'])) {
        $_POST['value'] = 0;
    }

    $where = [];
    if (isset($_POST['used'])) {
        $used = $_POST['used'];
        if (count($used)) {
            $where = ['NOT' => ['glpi_softwareversions.id' => $used]];
        }
    }
   // Make a select box
    $iterator = $DB->request([
        'SELECT'    => ['glpi_softwareversions.*', 'glpi_states.name AS sname'],
        'DISTINCT'  => true,
        'FROM'      => 'glpi_softwareversions',
        'LEFT JOIN' => [
            'glpi_states'  => [
                'ON'  => [
                    'glpi_softwareversions' => 'states_id',
                    'glpi_states'           => 'id'
                ]
            ]
        ],
        'WHERE'     => ['glpi_softwareversions.softwares_id' => $_POST['softwares_id']] + $where
    ]);
    $number = count($iterator);

    $values = [];
    foreach ($iterator as $data) {
        $ID = $data['id'];
        $output = $data['name'];

        if (empty($output) || $_SESSION['glpiis_ids_visible']) {
            $output = sprintf(__('%1$s (%2$s)'), $output, $ID);
        }
        if (!empty($data['sname'])) {
            $output = sprintf(__('%1$s - %2$s'), $output, $data['sname']);
        }
        $values[$ID] = $output;
    }

    Dropdown::showFromArray($_POST['myname'], $values, ['display_emptychoice' => true]);
}
