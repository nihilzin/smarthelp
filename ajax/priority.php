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

Html::header_nocache();

Session::checkLoginUser();

if (
    isset($_REQUEST["urgency"])
    && isset($_REQUEST["impact"])
) {
    // Read predefined templates fields
    $predefined_fields  = array_key_exists('_predefined_fields', $_REQUEST) ? Toolbox::decodeArrayFromInput($_REQUEST["_predefined_fields"]) : [];

    // Fallback to Form value -> Template values -> Medium
    $priority = Ticket::computePriority(
        $_REQUEST["urgency"] ?: $predefined_fields['urgency'] ?? 3 /* Medium */,
        $_REQUEST["impact"]  ?: $predefined_fields['impact']  ?? 3 /* Medium */
    );

    if (isset($_REQUEST['getJson'])) {
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(['priority' => $priority]);
    } elseif ($_REQUEST["priority"]) {
        // Send UTF8 Headers
        header("Content-Type: text/html; charset=UTF-8");
        echo "<script type='text/javascript' >\n";
        echo Html::jsSetDropdownValue($_REQUEST["priority"], $priority);
        echo "\n</script>";
    } else {
        echo Ticket::getPriorityName($priority);
    }
}
