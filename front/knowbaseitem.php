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

use Glpi\Toolbox\Sanitizer;

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

if (!Session::haveRightsOr('knowbase', [READ, KnowbaseItem::READFAQ])) {
    Session::redirectIfNotLoggedIn();
    Html::displayRightError();
}
if (isset($_GET["id"])) {
    Html::redirect(KnowbaseItem::getFormURLWithID($_GET["id"]));
}

Html::header(KnowbaseItem::getTypeName(1), $_SERVER['PHP_SELF'], "tools", "knowbaseitem");

// Clean for search
$_GET = Sanitizer::dbUnescapeRecursive($_GET);

// Search a solution
if (
    !isset($_GET["contains"])
    && isset($_GET["item_itemtype"])
    && isset($_GET["item_items_id"])
) {
    if (in_array($_GET["item_itemtype"], $CFG_GLPI['kb_types']) && $item = getItemForItemtype($_GET["item_itemtype"])) {
        if ($item->can($_GET["item_items_id"], READ)) {
            $_GET["contains"] = $item->getField('name');
        }
    }
}

// Manage forcetab : non standard system (file name <> class name)
if (isset($_GET['forcetab'])) {
    Session::setActiveTab('Knowbase', $_GET['forcetab']);
    unset($_GET['forcetab']);
}

$kb = new Knowbase();
$kb->display($_GET);

Html::footer();
