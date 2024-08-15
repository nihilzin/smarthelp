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

if (!isset($_GET['item_type']) || !is_string($_GET['item_type']) || !is_a($_GET['item_type'], CommonGLPI::class, true)) {
    return;
}

$itemtype = $_GET['item_type'];
if ($itemtype === 'AllAssets') {
    Session::checkCentralAccess();
} else {
    Session::checkValidSessionId();
    $item = new $itemtype();
    if (!$item->canView()) {
        Html::displayRightError();
    }
}

if (isset($_GET["display_type"])) {
    if ($_GET["display_type"] < 0) {
        $_GET["display_type"] = -$_GET["display_type"];
        $_GET["export_all"]   = 1;
    }

    switch ($itemtype) {
        case 'KnowbaseItem':
            KnowbaseItem::showList($_GET, $_GET["is_faq"]);
            break;

        case 'Stat':
            if (isset($_GET["item_type_param"])) {
                $params = Toolbox::decodeArrayFromInput($_GET["item_type_param"]);
                switch ($params["type"]) {
                    case "comp_champ":
                        $val = Stat::getItems(
                            $_GET["itemtype"],
                            $params["date1"],
                            $params["date2"],
                            $params["dropdown"]
                        );
                        Stat::showTable(
                            $_GET["itemtype"],
                            $params["type"],
                            $params["date1"],
                            $params["date2"],
                            $params["start"],
                            $val,
                            $params["dropdown"]
                        );
                        break;

                    case "device":
                        $val = Stat::getItems(
                            $_GET["itemtype"],
                            $params["date1"],
                            $params["date2"],
                            $params["dropdown"]
                        );
                        Stat::showTable(
                            $_GET["itemtype"],
                            $params["type"],
                            $params["date1"],
                            $params["date2"],
                            $params["start"],
                            $val,
                            $params["dropdown"]
                        );
                        break;

                    default:
                          $val2 = (isset($params['value2']) ? $params['value2'] : 0);
                          $val  = Stat::getItems(
                              $_GET["itemtype"],
                              $params["date1"],
                              $params["date2"],
                              $params["type"],
                              $val2
                          );
                         Stat::showTable(
                             $_GET["itemtype"],
                             $params["type"],
                             $params["date1"],
                             $params["date2"],
                             $params["start"],
                             $val,
                             $val2
                         );
                }
            } else if (isset($_GET["type"]) && ($_GET["type"] == "hardwares")) {
                Stat::showItems("", $_GET["date1"], $_GET["date2"], $_GET['start']);
            }
            break;

        default:
           // Plugin case
            if ($plug = isPluginItemType($itemtype)) {
                if (Plugin::doOneHook($plug['plugin'], 'dynamicReport', $_GET)) {
                    exit();
                }
            }
            $params = Search::manageParams($itemtype, $_GET);
            Search::showList($itemtype, $params);
    }
}
