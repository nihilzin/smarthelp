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

// Direct access to file
if (strpos($_SERVER['PHP_SELF'], "ticketiteminformation.php")) {
    $AJAX_INCLUDE = 1;
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
}

Session::checkLoginUser();

if (isset($_POST["my_items"]) && !empty($_POST["my_items"])) {
    $splitter = explode("_", $_POST["my_items"]);
    if (count($splitter) == 2) {
        $_POST["itemtype"] = $splitter[0];
        $_POST["items_id"] = $splitter[1];
    }
}

if (
    isset($_POST['itemtype'])
    && isset($_POST['items_id']) && ($_POST['items_id'] > 0)
) {
   // Security
    if (!class_exists($_POST['itemtype'])) {
        exit();
    }

    $days   = 3;
    $ticket = new Ticket();
    $data   = $ticket->getActiveOrSolvedLastDaysTicketsForItem(
        $_POST['itemtype'],
        $_POST['items_id'],
        $days
    );

    $nb = count($data);
    $badge_helper = sprintf(
        _n(
            '%s ticket in progress or recently solved on this item.',
            '%s tickets in progress or recently solved on this item.',
            $nb
        ),
        $nb
    );
    echo "<span class='badge badge-secondary' title='$badge_helper'>$nb</span>";

    if ($nb) {
        $content = '';
        foreach ($data as $title) {
            $content .= $title . '<br>';
        }
        echo '&nbsp;';
        Html::showToolTip($content);
    }
}
