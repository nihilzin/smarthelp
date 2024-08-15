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

$contract_item   = new Contract_Item();

if (isset($_POST["add"])) {
    if (!isset($_POST['contracts_id']) || empty($_POST['contracts_id'])) {
        $message = sprintf(
            __('Mandatory fields are not filled. Please correct: %s'),
            Contract::getTypeName(1)
        );
        Session::addMessageAfterRedirect($message, false, ERROR);
        Html::back();
    }

    $contract_item->check(-1, CREATE, $_POST);
    if ($contract_item->add($_POST)) {
        Event::log(
            $_POST["contracts_id"],
            "contracts",
            4,
            "financial",
            //TRANS: %s is the user login
            sprintf(__('%s adds a link with an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

Html::displayErrorAndDie("lost");
