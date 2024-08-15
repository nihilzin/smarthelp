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

/**
 * @since 0.85
 */

/** @var \DBmysql $DB */
global $DB;

include('../inc/includes.php');

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkCentralAccess();

// Make a select box
if (
    $_POST['items_id']
    && $_POST['itemtype'] && class_exists($_POST['itemtype'])
) {
    $devicetype = $_POST['itemtype'];
    $linktype   = $devicetype::getItem_DeviceType();

    if (count($linktype::getSpecificities())) {
        $keys = array_keys($linktype::getSpecificities());
        array_walk($keys, static function (&$val) use ($DB) {
            return $DB->quoteName($val);
        });
        $name_field = new QueryExpression(
            "CONCAT_WS(' - ', " . implode(', ', $keys) . ")"
            . "AS " . $DB->quoteName("name")
        );
    } else {
        $name_field = 'id AS name';
    }
    $result = $DB->request(
        [
            'SELECT' => ['id', $name_field],
            'FROM'   => $linktype::getTable(),
            'WHERE'  => [
                $devicetype::getForeignKeyField() => $_POST['items_id'],
                'itemtype'                        => '',
            ]
        ]
    );
    echo "<table class='w-100'><tr><td>" . __('Choose an existing device') . "</td><td rowspan='2'>" .
        __('and/or') . "</td><td>" . __('Add new devices') . '</td></tr>';
    echo "<tr><td>";
    if ($result->count() == 0) {
        echo __('No unaffected device!');
    } else {
        $devices = [];
        foreach ($result as $row) {
            $name = $row['name'];
            if (empty($name)) {
                $name = $row['id'];
            }
            $devices[$row['id']] = $name;
        }
        Dropdown::showFromArray($linktype::getForeignKeyField(), $devices, ['multiple' => true]);
    }
    echo "</td><td>";
    Dropdown::showNumber('new_devices', ['min'   => 0, 'max'   => 10]);
    echo "</td></tr></table>";
}
