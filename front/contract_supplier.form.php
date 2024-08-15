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
$contractsupplier = new Contract_Supplier();
if (isset($_POST["add"])) {
    if (!isset($_POST['contracts_id']) || empty($_POST['contracts_id'])) {
        $message = sprintf(
            __('Mandatory fields are not filled. Please correct: %s'),
            _n('Contract', 'Contracts', 1)
        );
        Session::addMessageAfterRedirect($message, false, ERROR);
        Html::back();
    }
    $contractsupplier->check(-1, CREATE, $_POST);

    if (
        isset($_POST["contracts_id"]) && ($_POST["contracts_id"] > 0)
        && isset($_POST["suppliers_id"]) && ($_POST["suppliers_id"] > 0)
    ) {
        if ($contractsupplier->add($_POST)) {
            Event::log(
                $_POST["contracts_id"],
                "contracts",
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
