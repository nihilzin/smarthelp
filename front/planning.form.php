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

include('../inc/includes.php');

Session::checkLoginUser();

if ($_REQUEST["action"] == "send_add_user_form") {
    Planning::sendAddUserForm($_REQUEST);
}

if ($_REQUEST["action"] == "send_add_group_users_form") {
    Planning::sendAddGroupUsersForm($_REQUEST);
}

if ($_REQUEST["action"] == "send_add_group_form") {
    Planning::sendAddGroupForm($_REQUEST);
}

if ($_REQUEST["action"] == "send_add_external_form") {
    Planning::sendAddExternalForm($_REQUEST);
}

Html::back();
