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

if (isset($_POST['reinit_network'])) {
    if (
        Session::haveRight('internet', UPDATE)
        // Check access to all entities
        && Session::canViewAllEntities()
    ) {
        IPNetwork::recreateTree();
        Session::addMessageAfterRedirect(__('Successfully recreated network tree'));
        Html::back();
    } else {
        Html::displayRightError();
    }
}

$dropdown = new IPNetwork();
include(GLPI_ROOT . "/front/dropdown.common.form.php");
