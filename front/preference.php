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

$user = new User();

Session::checkLoginUser();

if (
    isset($_POST["update"])
    && ($_POST["id"] == Session::getLoginUserID())
) {
    $user->update($_POST);
    Event::log(
        $_POST["id"],
        "users",
        5,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    if (Session::getCurrentInterface() == "central") {
        Html::header(Preference::getTypeName(1), $_SERVER['PHP_SELF'], 'preference');
    } else {
        Html::helpHeader(Preference::getTypeName(1));
    }

    $pref = new Preference();
    $pref->display(['main_class' => 'tab_cadre_fixe']);

    if (Session::getCurrentInterface() == "central") {
        Html::footer();
    } else {
        Html::helpFooter();
    }
}
