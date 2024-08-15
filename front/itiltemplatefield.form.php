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

include '../inc/includes.php';
Session::checkRight('itiltemplate', UPDATE);

/**
 * @var string|null $itiltype
 * @var string|null $fieldtype
 */

if (!isset($itiltype)) {
    Html::displayErrorAndDie("Missing ITIL type");
}

if (!isset($fieldtype)) {
    Html::displayErrorAndDie("Missing field type");
}

$item_class = $itiltype . 'Template' . $fieldtype . 'Field';
$item = new $item_class();

if ($fieldtype == 'Predefined') {
    $itil_type = $item_class::$itiltype;
    $item_field = getForeignKeyFieldForItemType($itil_type::getItemLinkClass());
    if (isset($_POST[$item_field]) && isset($_POST['add_items_id'])) {
        $_POST[$item_field] = $_POST[$item_field] . "_" . $_POST['add_items_id'];
    }
}

if (isset($_POST["add"]) || isset($_POST['massiveaction'])) {
    $item->check(-1, UPDATE, $_POST);

    if ($item->add($_POST)) {
        $fieldtype_name = '';
        switch ($fieldtype) {
            case 'Hidden':
                $fieldtype_name = __('hidden');
                break;
            case 'Mandatory':
                $fieldtype_name = __('mandatory');
                break;
            case 'Predefined':
                $fieldtype_name = __('predefined');
                break;
        }

        Event::log(
            $_POST[$item::$items_id],
            strtolower($item::$itemtype),
            4,
            "maintain",
            sprintf(
            //TRANS: %1$s is the user login, %2$s the field type
                __('%1$s adds %2$s field'),
                $_SESSION["glpiname"],
                $fieldtype_name
            )
        );
    }
    Html::back();
}

Html::displayErrorAndDie("lost");
