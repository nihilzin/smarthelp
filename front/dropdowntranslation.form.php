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

$translation = new DropdownTranslation();
if (isset($_POST['add'])) {
    $translation->add($_POST);
} else if (isset($_POST['update'])) {
    $translation->update($_POST);
} else if (isset($_POST['purge'])) {
    $translation->delete($_POST, 1);
}
Html::back();
