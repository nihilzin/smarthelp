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
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

switch ($_REQUEST['action']) {
    case "getItemslist":
        $params = [
            'start'              => $_REQUEST['start'],
            'is_deleted'         => $_REQUEST['is_deleted'],
            'browse'             => 1,
            'as_map'             => 0,
            'showmassiveactions' => true,
            'criteria'           => $_REQUEST['criteria'],
        ];

        $itemtype = $_REQUEST['itemtype'];
        $category_itemtype = $itemtype::getCategoryItemType($itemtype);
        $category_table = $category_itemtype::getTable();
        $item = new $itemtype();
        $so = $item->rawSearchOptions();

        $field = 0;
        foreach ($so as $value) {
            if (isset($value['field'])) {
                if (($value['field'] == 'name' || $value['field'] == 'completename') && $value['table'] == $category_table) {
                    $field = $value['id'];
                }
            }
        }

        $params['criteria'][] = [
            'link'   => "AND",
            'field'  => $field,
            'searchtype'   => "equals",
            'virtual'      => true,
            'value'  => ($_REQUEST['cat_id'] > 0) ? $_REQUEST['cat_id'] : 0,
        ];
        Search::showList($itemtype, $params);
        break;
}
http_response_code(400);
return;
