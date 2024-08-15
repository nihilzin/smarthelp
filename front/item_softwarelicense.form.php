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

Session::checkRight("software", UPDATE);
$isl = new Item_SoftwareLicense();

if (isset($_POST["add"])) {
    if (!isset($_POST['itemtype']) || !isset($_POST['items_id']) || $_POST['items_id'] <= 0) {
        $message = sprintf(
            __('Mandatory fields are not filled. Please correct: %s'),
            _n('Item', 'Items', 1)
        );
        Session::addMessageAfterRedirect($message, false, ERROR);
        Html::back();
    }
    if ($_POST['softwarelicenses_id'] > 0) {
        if ($isl->add($_POST)) {
            Event::log(
                $_POST['softwarelicenses_id'],
                "softwarelicense",
                4,
                "inventory",
                //TRANS: %s is the user login
                sprintf(__('%s associates an item and a license'), $_SESSION["glpiname"])
            );
        }
    }
    Html::back();
}
Html::displayErrorAndDie('Lost');
