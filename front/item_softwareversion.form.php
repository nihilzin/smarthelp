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

Session::checkRight('software', UPDATE);
$inst = new Item_SoftwareVersion();

// From asset - Software tab (add form)
if (isset($_POST['add'])) {
    if (
        isset($_POST['itemtype']) && isset($_POST['items_id']) && $_POST['items_id']
        && isset($_POST['softwareversions_id']) && $_POST['softwareversions_id']
    ) {
        if (
            $inst->add([
                'itemtype'        => $_POST['itemtype'],
                'items_id'        => $_POST['items_id'],
                'softwareversions_id' => $_POST['softwareversions_id']
            ])
        ) {
            Event::log(
                $_POST["items_id"],
                $_POST['itemtype'],
                5,
                "inventory",
                //TRANS: %s is the user login
                sprintf(__('%s installs software'), $_SESSION["glpiname"])
            );
        }
    } else {
        $message = null;
        if (!isset($_POST['softwares_id']) || !$_POST['softwares_id']) {
            $message = __('Please select a software!');
        } else if (!isset($_POST['softwareversions_id']) || !$_POST['softwareversions_id']) {
            $message = __('Please select a version!');
        }

        Session::addMessageAfterRedirect($message, true, ERROR);
    }
    Html::back();
}
Html::displayErrorAndDie('Lost');
