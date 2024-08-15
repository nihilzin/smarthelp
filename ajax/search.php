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

$AJAX_INCLUDE = 1;
include('../inc/includes.php');
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (!isset($_REQUEST['action'])) {
    die;
}

// actions without IDOR
switch ($_REQUEST['action']) {
    case "fold_search":
        $user = new User();
        $success = $user->update([
            'id'          => (int) Session::getLoginUserID(),
            'fold_search' => (int) !$_POST['show_search'],
        ]);

        echo json_encode(['success' => $success]);
        break;

    case 'display_results':
        if (!isset($_REQUEST['itemtype'])) {
            http_response_code(400);
            die;
        }

        /** @var CommonDBTM $itemtype */
        $itemtype = $_REQUEST['itemtype'];
        if (!$itemtype::canView()) {
            http_response_code(403);
            die;
        }

        $search_params = Search::manageParams($itemtype, $_REQUEST);

        if (isset($search_params['browse']) && $search_params['browse'] == 1) {
            $itemtype::showBrowseView($itemtype, $search_params, true);
        } else {
            $results = Search::getDatas($itemtype, $search_params);
            $results['searchform_id'] = $_REQUEST['searchform_id'] ?? null;
            Search::displayData($results);
        }
        break;
}

if (!Session::validateIDOR($_REQUEST)) {
    die;
}

// actions with IDOR
switch ($_REQUEST['action']) {
    case "display_criteria":
        Search::displayCriteria($_REQUEST);
        break;

    case "display_meta_criteria":
        Search::displayMetaCriteria($_REQUEST);
        break;

    case "display_criteria_group":
        Search::displayCriteriaGroup($_REQUEST);
        break;

    case "display_searchoption":
        Search::displaySearchoption($_REQUEST);
        break;

    case "display_searchoption_value":
        Search::displaySearchoptionValue($_REQUEST);
        break;
}
