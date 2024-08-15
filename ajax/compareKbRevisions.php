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

use Glpi\RichText\RichText;

include('../inc/includes.php');
header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

Session::checkCentralAccess();

if (!isset($_POST['kbid']) || !isset($_POST['oldid']) || !isset($_POST['diffid'])) {
    throw new \RuntimeException('Required argument missing!');
}

$item = new \KnowbaseItem();
if (!$item->getFromDB($_POST['kbid']) || !$item->can($_POST['kbid'], READ)) {
    return;
}

$oldid = $_POST['oldid'];
$diffid = $_POST['diffid'];
$kbid = $_POST['kbid'];

$revision = new KnowbaseItem_Revision();
$revision->getFromDB($oldid);
$old = [
    'name'   => $revision->fields['name'],
    'answer' => RichText::getSafeHtml($revision->fields['answer'])
];

$revision = $diffid == 0 ? new KnowbaseItem() : new KnowbaseItem_Revision();
$revision->getFromDB($diffid == 0 ? $kbid : $diffid);
$diff = [
    'name'   => $revision->fields['name'],
    'answer' => RichText::getSafeHtml($revision->fields['answer'])
];

echo json_encode([
    'old'  => $old,
    'diff' => $diff
]);
