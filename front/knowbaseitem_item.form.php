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

use Glpi\Event;

include('../inc/includes.php');

Session::checkLoginUser();

$item = new KnowbaseItem_Item();

if (isset($_POST["add"])) {
    if (!isset($_POST['knowbaseitems_id']) || !isset($_POST['items_id']) || !isset($_POST['itemtype'])) {
        $message = __('Mandatory fields are not filled!');
        Session::addMessageAfterRedirect($message, false, ERROR);
        Html::back();
    }

    $item->check(-1, CREATE, $_POST);

    if ($item->add($_POST)) {
        Event::log(
            $_POST["knowbaseitems_id"],
            "knowbaseitem",
            4,
            "tracking",
            sprintf(__('%s adds a link with an knowledge base'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

Html::displayErrorAndDie("lost");
