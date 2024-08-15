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
if (!isset($_POST['itemtype']) || !isset($_POST['items_id']) || (int)$_POST['items_id'] < 1) {
    $result = [
        'success'   => false,
        'message'   => __('Required argument missing!')
    ];
} else {
    $itemtype = $_POST['itemtype'];
    $items_id = $_POST['items_id'];

    if ($itemtype != Location::getType()) {
        $item = new $itemtype();
        $found = $item->getFromDB($items_id);
        if ($found && isset($item->fields['locations_id']) && (int)$item->fields['locations_id'] > 0) {
            $itemtype = Location::getType();
            $items_id = $item->fields['locations_id'];
        } else {
            $result = [
                'success'   => false,
                'message'   => __('Element seems not geolocalized or cannot be found')
            ];
        }
    }

    if (!count($result)) {
        $item = new $itemtype();
        $item->getFromDB($items_id);
        if (!empty($item->fields['latitude']) && !empty($item->fields['longitude'])) {
            $result = [
                'name'   => $item->getName(),
                'lat'    => $item->fields['latitude'],
                'lng'    => $item->fields['longitude']
            ];
        } else {
            $result = [
                'success'   => false,
                'message'   => "<h3>" . __("Location seems not geolocalized!") . "</h3>" .
                           "<a href='" . $item->getLinkURL() . "'>" . __("Consider filling latitude and longitude on this location.") . "</a>"
            ];
        }
    }
}

echo json_encode($result);
