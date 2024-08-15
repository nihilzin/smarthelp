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


$item = new OlaLevel();

if (isset($_POST["update"])) {
    $item->check($_POST["id"], UPDATE);

    $item->update($_POST);

    Event::log(
        $_POST["id"],
        "olas",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an ola level'), $_SESSION["glpiname"])
    );

    Html::back();
} else if (isset($_POST["add"])) {
    $item->check(-1, CREATE, $_POST);

    if ($item->add($_POST)) {
        Event::log(
            $_POST["olas_id"],
            "olas",
            4,
            "setup",
            //TRANS: %s is the user login
            sprintf(__('%s adds a link with an item'), $_SESSION["glpiname"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($item->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["purge"])) {
    if (isset($_POST['id'])) {
        $item->check($_POST['id'], PURGE);
        if ($item->delete($_POST, 1)) {
            Event::log(
                $_POST["id"],
                "olas",
                4,
                "setup",
                //TRANS: %s is the user login
                sprintf(__('%s purges an ola level'), $_SESSION["glpiname"])
            );
        }
        $item->redirectToList();
    }

    Html::back();
} else if (isset($_POST["add_action"])) {
    $item->check($_POST['olalevels_id'], UPDATE);

    $action = new OlaLevelAction();
    $action->add($_POST);

    Html::back();
} else if (isset($_POST["add_criteria"])) {
    $item->check($_POST['olalevels_id'], UPDATE);
    $criteria = new OlaLevelCriteria();
    $criteria->add($_POST);

    Html::back();
} else if (isset($_GET["id"]) && ($_GET["id"] > 0)) {
    $menus = ["config", "slm", "olalevel"];
    OlaLevel::displayFullPageForItem($_GET["id"], $menus);
}
