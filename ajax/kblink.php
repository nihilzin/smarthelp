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

include('../inc/includes.php');

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (
    isset($_POST["table"])
    && isset($_POST["value"])
) {
   // Security
    if (!$DB->tableExists($_POST['table'])) {
        exit();
    }

    if (isset($_POST['withlink'])) {
        $itemtype = getItemTypeForTable($_POST["table"]);
        if (
            !Session::validateIDOR([
                'itemtype'    => $itemtype,
                '_idor_token' => $_POST['_idor_token'] ?? ""
            ])
        ) {
            exit();
        }
        $item = new $itemtype();
        $item->getFromDB(intval($_POST["value"]));
        echo '&nbsp;' . $item->getLinks();
    }
}
