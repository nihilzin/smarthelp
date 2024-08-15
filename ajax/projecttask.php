<?php

/**
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

use Glpi\Toolbox\Sanitizer;

$AJAX_INCLUDE = 1;

include('../inc/includes.php');
header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (isset($_POST['projecttasktemplates_id']) && ($_POST['projecttasktemplates_id'] > 0)) {
    $template = new ProjectTaskTemplate();
    $template->getFromDB($_POST['projecttasktemplates_id']);

    if (DropdownTranslation::isDropdownTranslationActive()) {
        $template->fields['description'] = DropdownTranslation::getTranslatedValue(
            $template->getID(),
            $template->getType(),
            'description',
            $_SESSION['glpilanguage'],
            $template->fields['description']
        );
    }

    $template->fields = Sanitizer::decodeHtmlSpecialCharsRecursive($template->fields);
    echo json_encode($template->fields);
}
