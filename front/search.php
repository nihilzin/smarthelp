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

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

Session::checkCentralAccess();
Html::header(__('Search'), $_SERVER['PHP_SELF']);

if (!$CFG_GLPI['allow_search_global']) {
    Html::displayRightError();
}
if (isset($_GET["globalsearch"])) {
    $searchtext = trim($_GET["globalsearch"]);
    $no_result = [];

    echo "<div class='search_page search_page_global flex-row flex-wrap'>";
    foreach ($CFG_GLPI["globalsearch_types"] as $itemtype) {
        if (
            ($item = getItemForItemtype($itemtype))
            && $item->canView()
        ) {
            $_GET["reset"]        = 'reset';

            $params                 = Search::manageParams($itemtype, $_GET, false, true);
            $params["display_type"] = Search::GLOBAL_SEARCH;

            $count                  = count($params["criteria"]);

            $params["criteria"][$count]["field"]       = 'view';
            $params["criteria"][$count]["searchtype"]  = 'contains';
            $params["criteria"][$count]["value"]       = $searchtext;

            $data = Search::getDatas($itemtype, $params);
            if ($data['data']['count'] > 0) {
                echo "<div class='search-container w-100 disable-overflow-y' counter='" . $data['data']['count'] . "'>";
                Search::displayData($data);
                echo "</div>";
            } else {
                $no_result[] = $itemtype::getTypeName(1);
            }
        }
    }

    echo "<div class='search-container w-100 disable-overflow-y' counter='0'>";
    echo "<div class='ajax-container search-display-data'>";
    echo "<div class='card card-sm mt-0 search-card'>";
    echo "<div class='card-header d-flex justify-content-between search-header pe-0'>";
    echo "<h2>" . __('Other searches with no item found') . "</h2>";
    echo "</div>";
    echo '<ul>';
    foreach ($no_result as $itemtype) {
        echo "<li>" . $itemtype . "</li>";
    }
    echo '</ul>';
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

Html::footer();
