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

use Glpi\Application\View\TemplateRenderer;

include('../inc/includes.php');

Session::checkLoginUser();

if (($_POST['action'] ?? null) === 'change_task_state') {
    header("Content-Type: application/json; charset=UTF-8");

    if (
        !isset($_POST['tasks_id'])
        || !isset($_POST['parenttype']) || ($parent = getItemForItemtype($_POST['parenttype'])) === false
    ) {
        exit();
    }

    $taskClass = $parent->getType() . "Task";
    $task = new $taskClass();
    $task->getFromDB(intval($_POST['tasks_id']));
    if (!in_array($task->fields['state'], [0, Planning::INFO])) {
        $new_state = ($task->fields['state'] == Planning::DONE)
                        ? Planning::TODO
                        : Planning::DONE;
        $foreignKey = $parent->getForeignKeyField();
        $task->update([
            'id'        => intval($_POST['tasks_id']),
            $foreignKey => intval($_POST[$foreignKey]),
            'state'     => $new_state,
            'users_id_editor' => Session::getLoginUserID()
        ]);
        $new_label = Planning::getState($new_state);
        echo json_encode([
            'state'  => $task->fields['state'],
            'label'  => $new_label
        ]);
    }
} else if (($_REQUEST['action'] ?? null) === 'viewsubitem') {
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
    if (!isset($_REQUEST['type'])) {
        exit();
    }
    if (!isset($_REQUEST['parenttype'])) {
        exit();
    }

    $item = getItemForItemtype($_REQUEST['type']);
    $parent = getItemForItemtype($_REQUEST['parenttype']);

    $twig = TemplateRenderer::getInstance();
    $template = null;
    if (isset($_REQUEST[$parent::getForeignKeyField()])) {
        $parent->getFromDB($_REQUEST[$parent::getForeignKeyField()]);
    }
    $id = isset($_REQUEST['id']) && (int)$_REQUEST['id'] > 0 ? $_REQUEST['id'] : null;
    if ($id) {
        $item->getFromDB($id);
    }
    $params = [
        'item'      => $parent,
        'subitem'   => $item
    ];

    if ($_REQUEST['type'] === ITILFollowup::class) {
        $template = 'form_followup';
    } else if ($_REQUEST['type'] === ITILSolution::class) {
        $template = 'form_solution';
        $params['kb_id_toload'] = $_REQUEST['load_kb_sol'] ?? 0;
    } else if (is_subclass_of($_REQUEST['type'], CommonITILTask::class)) {
        $template = 'form_task';
    } else if (is_subclass_of($_REQUEST['type'], CommonITILValidation::class)) {
        $template = 'form_validation';
        $params['form_mode'] = $_REQUEST['item_action'] === 'validation-answer' ? 'answer' : 'request';
    } else if ($id !== null && $parent->getID() >= 0) {
        $ol = ObjectLock::isLocked($_REQUEST['parenttype'], $parent->getID());
        if ($ol && (Session::getLoginUserID() != $ol->fields['users_id'])) {
            ObjectLock::setReadOnlyProfile();
        }
        $foreignKey = $parent->getForeignKeyField();
        $params[$foreignKey] = $_REQUEST[$foreignKey];
        $parent::showSubForm($item, $_REQUEST["id"], ['parent' => $parent, $foreignKey => $_REQUEST[$foreignKey]]);
        Html::ajaxFooter();
        exit();
    }
    if ($template === null) {
        echo __('Access denied');
        Html::ajaxFooter();
        exit();
    }
    $twig->display("components/itilobject/timeline/{$template}.html.twig", $params);
}
