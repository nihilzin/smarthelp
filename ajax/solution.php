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
use Glpi\RichText\RichText;

$AJAX_INCLUDE = 1;

include('../inc/includes.php');
header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

// Mandatory parameter: solutiontemplates_id
$solutiontemplates_id = $_POST['solutiontemplates_id'] ?? null;
if ($solutiontemplates_id === null) {
    Response::sendError(400, "Missing or invalid parameter: 'solutiontemplates_id'");
} else if ($solutiontemplates_id == 0) {
   // Reset form
    echo json_encode([
        'content' => ""
    ]);
    die;
}

// We can't render the twig template at this state for some cases (e.g. massive
// actions: we don't have one but multiple items so it net possible to parse the
// values yet).
$apply_twig = true;

// Mandatory parameter: items_id
$parents_id = $_POST['items_id'] ?? 0;
if ($parents_id == 0) {
    $apply_twig  = false;
}

// Mandatory parameter: itemtype
$parents_itemtype = $_POST['itemtype'] ?? '';
if (empty($parents_itemtype) || !is_subclass_of($parents_itemtype, CommonITILObject::class)) {
    $apply_twig  = false;
}

// Load solution template
$template = new SolutionTemplate();
if (!$template->getFromDB($solutiontemplates_id)) {
    Response::sendError(400, "Unable to load template: $solutiontemplates_id");
}

if ($apply_twig) {
   // Load parent item
    $parent = new $parents_itemtype();
    if (!$parent->getFromDB($parents_id)) {
        Response::sendError(400, "Unable to load parent item: $parents_itemtype $parents_id");
    }

   // Render template content using twig
    $template->fields['content'] = $template->getRenderedContent($parent);
} else {
    $content = $template->fields['content'];
    if (DropdownTranslation::isDropdownTranslationActive()) {
        $content = DropdownTranslation::getTranslatedValue(
            $template->getID(),
            $template->getType(),
            'content',
            $_SESSION['glpilanguage'],
            $content
        );
    }
    $template->fields['content'] = RichText::getSafeHtml($content);
}

//load solutiontype name (use to create OPTION dom)
//need when template is used and when GLPI preselcted type if defined

$template->fields['solutiontypes_name'] = "";
if ($template->fields['solutiontypes_id']) {
    $entityRestrict = getEntitiesRestrictCriteria(
        getTableForItemType(SolutionType::getType()),
        "",
        $parent->fields['entities_id'] ?? 0,
        true
    );

    $solutiontype = new SolutionType();
    if (
        $solutiontype->getFromDBByCrit([
            "id" => $template->fields['solutiontypes_id'],
        ] + $entityRestrict)
    ) {
        $template->fields['solutiontypes_name'] = Dropdown::getDropdownName(
            getTableForItemType(SolutionType::getType()),
            $template->fields['solutiontypes_id'],
            0,
            true,
            false,
            //default value like "(id)" is the default behavior of GLPI when field 'name' is empty
            "(" . $template->fields['solutiontypes_id'] . ")"
        );
    }
}

// Return json response with the template fields
echo json_encode($template->fields);
