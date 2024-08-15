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

use Glpi\Http\Response;

$AJAX_INCLUDE = 1;

include('../inc/includes.php');
header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

// Mandatory parameter: itilfollowuptemplates_id
$itilfollowuptemplates_id = $_POST['itilfollowuptemplates_id'] ?? null;
if ($itilfollowuptemplates_id === null) {
    Response::sendError(400, "Missing or invalid parameter: 'itilfollowuptemplates_id'");
} else if ($itilfollowuptemplates_id == 0) {
   // Reset form
    echo json_encode([
        'content' => ""
    ]);
    die;
}

// Mandatory parameter: items_id
$parents_id = $_POST['items_id'] ?? 0;
if (!$parents_id) {
    Response::sendError(400, "Missing or invalid parameter: 'items_id'");
}

// Mandatory parameter: itemtype
$parents_itemtype = $_POST['itemtype'] ?? '';
if (empty($parents_itemtype) || !is_subclass_of($parents_itemtype, CommonITILObject::class)) {
    Response::sendError(400, "Missing or invalid parameter: 'itemtype'");
}

// Load followup template
$template = new ITILFollowupTemplate();
if (!$template->getFromDB($itilfollowuptemplates_id)) {
    Response::sendError(400, "Unable to load template: $itilfollowuptemplates_id");
}

// Load parent item
$parent = new $parents_itemtype();
if (!$parent->getFromDB($parents_id)) {
    Response::sendError(400, "Unable to load parent item: $parents_itemtype $parents_id");
}

// Render template content using twig
$template->fields['content'] = $template->getRenderedContent($parent);

//load requesttypes name (use to create OPTION dom)
//need when template is used and when GLPI preselected type if defined
$template->fields['requesttypes_name'] = "";
if ($template->fields['requesttypes_id']) {
    $requesttype = new RequestType();
    if (
        $requesttype->getFromDBByCrit([
            "id" => $template->fields['requesttypes_id'],
        ])
    ) {
        $template->fields['requesttypes_name'] = Dropdown::getDropdownName(
            getTableForItemType(RequestType::getType()),
            $template->fields['requesttypes_id'],
            0,
            true,
            false,
            //default value like "(id)" is the default behavior of GLPI when field 'name' is empty
            "(" . $template->fields['requesttypes_id'] . ")"
        );
    }
}

// Return json response with the template fields
echo json_encode($template->fields);
