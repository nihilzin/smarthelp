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

/**
 * @since 0.84
 */

use Glpi\Event;

include('../inc/includes.php');

Session::checkCentralAccess();

$document_item   = new Document_Item();

if (isset($_POST["add"])) {
    $document_item->check(-1, CREATE, $_POST);
    if ($document_item->add($_POST)) {
        Event::log(
            $_POST["documents_id"],
            "documents",
            4,
            "document",
            //TRANS: %s is the user login
            sprintf(__('%s adds a link with an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

Html::displayErrorAndDie("lost");
