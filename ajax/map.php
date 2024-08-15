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

include('../inc/includes.php');
header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

$result = [];
if (!isset($_POST['itemtype']) || !isset($_POST['params'])) {
    http_response_code(500);
    $result = [
        'success'   => false,
        'message'   => __('Required argument missing!')
    ];
} else {
    $itemtype = $_POST['itemtype'];
    $params   = $_POST['params'];

    $data = Search::prepareDatasForSearch($itemtype, $params);
    Search::constructSQL($data);
    Search::constructData($data);

    if ($itemtype == 'Location') {
        $lat_field = $itemtype . '_21';
        $lng_field = $itemtype . '_20';
        $name_field = $itemtype . '_1';
    } else if ($itemtype == 'Entity') {
        $lat_field = $itemtype . '_67';
        $lng_field = $itemtype . '_68';
        $name_field = $itemtype . '_1';
    } else {
        $lat_field = $itemtype . '_998';
        $lng_field = $itemtype . '_999';
        $name_field = $itemtype . '_3';
    }
    if ($itemtype == 'Ticket') {
       //duplicate search options... again!
        $name_field = $itemtype . '_83';
    }

    $rows = $data['data']['rows'];
    $points = [];
    foreach ($rows as $row) {
        $idx = $row['raw']["ITEM_$lat_field"] . ',' . $row['raw']["ITEM_$lng_field"];
        if (isset($points[$idx])) {
            $points[$idx]['count'] += 1;
        } else {
            if ($itemtype == 'Entity') {
                $points[$idx] = [
                    'lat'    => $row['raw']["ITEM_$lat_field"],
                    'lng'    => $row['raw']["ITEM_$lng_field"],
                    'title'  => $row['raw']["ITEM_$name_field"],
                    'count'  => 1
                ];
            } else {
                $points[$idx] = [
                    'lat'    => $row['raw']["ITEM_$lat_field"],
                    'lng'    => $row['raw']["ITEM_$lng_field"],
                    'title'  => $row['raw']["ITEM_$name_field"],
                    'loc_id' => $row['raw']['loc_id'],
                    'count'  => 1
                ];
            }
        }

        if ($itemtype == AllAssets::getType()) {
            $curtype = $row['TYPE'];
            if (isset($points[$idx]['types'][$curtype])) {
                $points[$idx]['types'][$curtype]['count']++;
                $points[$idx]['types'][$curtype]['name'] = strtolower($curtype::getTypeName(Session::getPluralNumber()));
            } else {
                $points[$idx]['types'][$curtype] = [
                    'name'   => strtolower($curtype::getTypeName(1)),
                    'count'  => 1
                ];
            }
        }
    }
    $result['points'] = $points;
}

echo json_encode($result);
