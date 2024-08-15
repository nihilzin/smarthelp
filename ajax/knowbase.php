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

$SECURITY_STRATEGY = 'faq_access';

include('../inc/includes.php');
Html::header_nocache();

$_SESSION['kb_cat_id'] = $_REQUEST['cat_id'] ?? 0;

switch ($_REQUEST['action']) {
    case "getItemslist":
        header("Content-Type: application/json; charset=UTF-8");
        KnowbaseItem::showList([
            'knowbaseitemcategories_id' => (int) $_REQUEST['cat_id'],
            'start'                     => (int) $_REQUEST['start'],
        ], 'browse');
        break;
}
