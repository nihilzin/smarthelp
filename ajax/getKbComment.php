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

Session::checkCentralAccess();

if (!isset($_POST['kbitem_id'])) {
    throw new \RuntimeException('Required argument missing!');
}

$item = new \KnowbaseItem();
if (!$item->getFromDB($_POST['kbitem_id']) || !$item->can($_POST['kbitem_id'], READ)) {
    return;
}

$kbitem_id = $_POST['kbitem_id'];
$lang = $_POST['language'] ?? null;

$edit = $_POST['edit'] ?? false;

$answer = $_POST['answer'] ?? false;

echo KnowbaseItem_Comment::getCommentForm($kbitem_id, $lang, $edit, $answer);
