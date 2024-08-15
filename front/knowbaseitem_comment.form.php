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

Session::checkLoginUser();

$comment = new KnowbaseItem_Comment();
if (!isset($_POST['knowbaseitems_id'])) {
    $message = __('Mandatory fields are not filled!');
    Session::addMessageAfterRedirect($message, false, ERROR);
    Html::back();
}
$kbitem = new KnowbaseItem();
$kbitem->getFromDB($_POST['knowbaseitems_id']);
if (!$kbitem->canComment()) {
    Html::displayRightError();
}

if (isset($_POST["add"])) {
    if (!isset($_POST['knowbaseitems_id']) || !isset($_POST['comment'])) {
        $message = __('Mandatory fields are not filled!');
        Session::addMessageAfterRedirect($message, false, ERROR);
        Html::back();
    }

    if ($newid = $comment->add($_POST)) {
        Event::log(
            $_POST["knowbaseitems_id"],
            "knowbaseitem_comment",
            4,
            "tracking",
            sprintf(__('%s adds a comment on knowledge base'), $_SESSION["glpiname"])
        );
        Session::addMessageAfterRedirect(
            "<a href='#kbcomment$newid'>" . __('Your comment has been added') . "</a>",
            false,
            INFO
        );
    }
    Html::back();
}

if (isset($_POST["edit"])) {
    if (!isset($_POST['knowbaseitems_id']) || !isset($_POST['id']) || !isset($_POST['comment'])) {
        $message = __('Mandatory fields are not filled!');
        Session::addMessageAfterRedirect($message, false, ERROR);
        Html::back();
    }

    $comment->getFromDB($_POST['id']);
    $data = array_merge($comment->fields, $_POST);
    if ($comment->update($data)) {
        Event::log(
            $_POST["knowbaseitems_id"],
            "knowbaseitem_comment",
            4,
            "tracking",
            sprintf(__('%s edit a comment on knowledge base'), $_SESSION["glpiname"])
        );
        Session::addMessageAfterRedirect(
            "<a href='#kbcomment{$comment->getID()}'>" . __('Your comment has been edited') . "</a>",
            false,
            INFO
        );
    }
    Html::back();
}

Html::displayErrorAndDie("lost");
