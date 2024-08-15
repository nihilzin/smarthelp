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

global $CFG_GLPI;

$AJAX_INCLUDE = 1;
include('../inc/includes.php');

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (
    isset($_POST["itemtype"])
    && isset($_POST["value"])
) {
   // Security
    if (!is_subclass_of($_POST["itemtype"], "CommonDBTM")) {
        exit();
    }

    switch ($_POST["itemtype"]) {
        case User::getType():
            if ($_POST['value'] == 0) {
                $tmpname = [
                    'link'    => $CFG_GLPI['root_doc'] . "/front/user.php",
                    'comment' => "",
                ];
            } else {
                $user = new \User();
                if (is_array($_POST["value"])) {
                    $comments = [];
                    foreach ($_POST["value"] as $users_id) {
                        if ($user->getFromDB($users_id) && $user->canView()) {
                            $username   = getUserName($users_id, 2);
                            $comments[] = $username['comment'] ?? "";
                        }
                    }
                    $tmpname = [
                        'comment' => implode("<br>", $comments),
                    ];
                    unset($_POST['withlink']);
                } else {
                    if ($user->getFromDB($_POST['value']) && $user->canView()) {
                        $tmpname = getUserName($_POST["value"], 2);
                    }
                }
            }
            echo ($tmpname["comment"] ?? '');

            if (isset($_POST['withlink']) && isset($tmpname['link'])) {
                echo "<script type='text/javascript' >\n";
                echo Html::jsGetElementbyID($_POST['withlink']) . ".attr('href', '" . $tmpname['link'] . "');";
                echo "</script>\n";
            }
            break;

        default:
            if ($_POST["value"] > 0) {
                if (
                    !Session::validateIDOR([
                        'itemtype'    => $_POST['itemtype'],
                        '_idor_token' => $_POST['_idor_token'] ?? ""
                    ])
                ) {
                    exit();
                }

                $itemtype = $_POST['itemtype'];
                if (is_subclass_of($itemtype, 'Rule')) {
                    $table = Rule::getTable();
                } else {
                    $table = getTableForItemType($_POST['itemtype']);
                }
                $tmpname = Dropdown::getDropdownName($table, $_POST["value"], 1);
                if (is_array($tmpname) && isset($tmpname["comment"])) {
                    echo $tmpname["comment"];
                }

                if (isset($_POST['withlink'])) {
                    echo "<script type='text/javascript' >\n";
                    echo Html::jsGetElementbyID($_POST['withlink']) . ".
                    attr('href', '" . $_POST['itemtype']::getFormURLWithID($_POST["value"]) . "');";
                    echo "</script>\n";
                }

                if (isset($_POST['with_dc_position'])) {
                    $item = new $_POST['itemtype']();
                    echo "<script type='text/javascript' >\n";

                   //if item have a DC position (reload url to it's rack)
                    if ($rack = $item->isRackPart($_POST['itemtype'], $_POST["value"], true)) {
                        echo Html::jsGetElementbyID($_POST['with_dc_position']) . ".
                  html(\"&nbsp;<a class='fas fa-crosshairs' href='" . $rack->getLinkURL() . "'></a>\");";
                    } else {
                       //remove old dc position
                        echo Html::jsGetElementbyID($_POST['with_dc_position']) . ".empty();";
                    }
                    echo "</script>\n";
                }
            }
    }
}
