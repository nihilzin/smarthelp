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

/**
 * @since 9.1
 */

use Glpi\RichText\RichText;

include('../inc/includes.php');
header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

Session::checkCentralAccess();

if (!isset($_POST['revid'])) {
    throw new \RuntimeException('Required argument missing!');
}

$revid = $_POST['revid'];

$revision = new KnowbaseItem_Revision();
$revision->getFromDB($revid);

$item = new \KnowbaseItem();
if (
    !$item->getFromDB($revision->fields['knowbaseitems_id'])
    || !$item->can($revision->fields['knowbaseitems_id'], READ)
) {
    return;
}

$rev = [
    'name'   => $revision->fields['name'],
    'answer' => RichText::getEnhancedHtml($revision->fields['answer'])
];

echo json_encode($rev);
