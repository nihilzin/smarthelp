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

/*
 * Ajax tooltip endpoint for CommonITILObjects
 */

include('../inc/includes.php');
Session::checkLoginUser();

// Read parameters
$itemtype = $_GET['itemtype'] ?? null;
$items_id = $_GET['items_id'] ?? null;

// Validate mandatory parameters
if (is_null($itemtype) || is_null($items_id)) {
    Response::sendError(400, "Missing required parameters");
}

// Validate itemtype (only CommonITILObject allowed for now)
if (!is_a($itemtype, CommonITILObject::class, true)) {
    Response::sendError(400, "Invalid itemtype");
}
$item = new $itemtype();

// Validate item
if (
    !$item->getFromDB($items_id)
    || !$item->canViewItem()
    || !$item->isField('content')
) {
    Response::sendError(404, "Item not found");
}

// Display content
header('Content-type: text/html');
echo RichText::getEnhancedHtml($item->fields['content'], [
    'images_gallery' => false, // Don't show photoswipe gallery
]);
