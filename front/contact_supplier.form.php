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

Session::checkCentralAccess();
$contactsupplier = new Contact_Supplier();
if (isset($_POST["add"])) {
    $contactsupplier->check(-1, CREATE, $_POST);

    if (
        isset($_POST["contacts_id"]) && ($_POST["contacts_id"] > 0)
        && isset($_POST["suppliers_id"]) && ($_POST["suppliers_id"] > 0)
    ) {
        if ($contactsupplier->add($_POST)) {
            Event::log(
                $_POST["contacts_id"],
                "contacts",
                4,
                "financial",
                //TRANS: %s is the user login
                sprintf(__('%s adds a link with a supplier'), $_SESSION["glpiname"])
            );
        }
    }
    Html::back();
}

Html::displayErrorAndDie('Lost');
