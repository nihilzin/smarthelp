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

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

$translation = new KnowbaseItemTranslation();
if (isset($_POST['add'])) {
    $translation->add($_POST);
    Html::back();
} else if (isset($_POST['update'])) {
    $translation->update($_POST);
    Html::back();
} else if (isset($_POST["purge"])) {
    $translation->delete($_POST, true);
    Html::redirect(KnowbaseItem::getFormURLWithID($_POST['knowbaseitems_id']));
} else if (isset($_GET["id"]) and isset($_GET['to_rev'])) {
    $translation->check($_GET["id"], UPDATE);
    if ($translation->revertTo($_GET['to_rev'])) {
        Session::addMessageAfterRedirect(
            sprintf(
                __('Knowledge base item translation has been reverted to revision %s'),
                $_GET['to_rev']
            )
        );
    } else {
        Session::addMessageAfterRedirect(
            sprintf(
                __('Knowledge base item translation has not been reverted to revision %s'),
                $_GET['to_rev']
            ),
            false,
            ERROR
        );
    }
    Html::redirect($translation->getFormURLWithID($_GET['id']));
} else if (isset($_GET["id"])) {
   // modifier un item dans la base de connaissance
    $translation->check($_GET["id"], READ);

    if (Session::getLoginUserID()) {
        if (Session::getCurrentInterface() == "central") {
            Html::header(KnowbaseItem::getTypeName(1), $_SERVER['PHP_SELF'], "tools", "knowbaseitemtranslation");
        } else {
            Html::helpHeader(__('FAQ'));
        }
        Html::helpHeader(__('FAQ'));
    } else {
        $_SESSION["glpilanguage"] = $CFG_GLPI['language'];
       // Anonymous FAQ
        Html::simpleHeader(__('FAQ'), [
            __('Authentication') => '/',
            __('FAQ')            => '/front/helpdesk.faq.php'
        ]);
    }

    $translation->display(['id' => $_GET['id']]);

    if (Session::getLoginUserID()) {
        if (Session::getCurrentInterface() == "central") {
            Html::footer();
        } else {
            Html::helpFooter();
        }
    } else {
        Html::helpFooter();
    }
}
