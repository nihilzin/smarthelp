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
 * Following variables have to be defined before inclusion of this file:
 * @var CommonITILValidation $validation
 */

use Glpi\Event;

// autoload include in objecttask.form (ticketvalidation, changevalidation,...)
if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

Session::checkLoginUser();

if (!($validation instanceof CommonITILValidation)) {
    Html::displayErrorAndDie('');
}
if (!$validation->canView()) {
    Html::displayRightError();
}

$itemtype = $validation->getItilObjectItemType();
$fk       = getForeignKeyFieldForItemType($itemtype);

if (isset($_POST["add"])) {
    $validation->check(-1, CREATE, $_POST);
    if (
        isset($_POST['users_id_validate'])
        && (count($_POST['users_id_validate']) > 0)
    ) {
        $users = $_POST['users_id_validate'];
        foreach ($users as $user) {
            $_POST['users_id_validate'] = $user;
            $validation->add($_POST);
            Event::log(
                $validation->getField($fk),
                strtolower($itemtype),
                4,
                "tracking",
                //TRANS: %s is the user login
                sprintf(__('%s adds an approval'), $_SESSION["glpiname"])
            );
        }
    }
    Html::back();
} else if (isset($_POST["update"])) {
    $validation->check($_POST['id'], UPDATE);
    $validation->update($_POST);
    Event::log(
        $validation->getField($fk),
        strtolower($itemtype),
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s updates an approval'), $_SESSION["glpiname"])
    );
    Html::back();
} else if (isset($_POST["purge"])) {
    $validation->check($_POST['id'], PURGE);
    $validation->delete($_POST, 1);

    Event::log(
        $validation->getField($fk),
        strtolower($itemtype),
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s purges an approval'), $_SESSION["glpiname"])
    );
    Html::back();
} else if (isset($_POST['approval_action'])) {
    if ($_POST['users_id_validate'] == Session::getLoginUserID()) {
        $validation->update($_POST + [
            'status' => ($_POST['approval_action'] === 'approve') ? CommonITILValidation::ACCEPTED : CommonITILValidation::REFUSED
        ]);
        Html::back();
    }
}
Html::displayErrorAndDie('Lost');
