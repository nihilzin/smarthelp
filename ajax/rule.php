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

$AJAX_INCLUDE = 1;
include('../inc/includes.php');

Session::checkLoginUser();

switch ($_REQUEST['action']) {
    case "move_rule":
        if (is_subclass_of($_POST['collection_classname'], RuleCollection::getType())) {
            $rule_collection = new $_POST['collection_classname']();
            $rule_collection->moveRule((int) $_POST['rule_id'], (int) $_POST['ref_id'], $_POST['sort_action']);
        }
        break;
}
