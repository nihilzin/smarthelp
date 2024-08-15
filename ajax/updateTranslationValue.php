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

include('../inc/includes.php');

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight("dropdown", UPDATE);

$matching_field = null;

if (isset($_POST['itemtype'], $_POST['field']) && is_a($_POST['itemtype'], CommonDropdown::class, true)) {
    $itemtype = new $_POST['itemtype']();
    $matching_field = $itemtype->getAdditionalField($_POST['field']);
}

if (($matching_field['type'] ?? null) === 'tinymce') {
    Html::textarea([
        'name'              => 'value',
        'enable_richtext'   => true,
        'enable_images'     => false,
        'enable_fileupload' => false,
    ]);
} else {
    echo "<input type='text' name='value' size='50'>";
}
