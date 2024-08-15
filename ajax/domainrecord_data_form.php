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
Html::header_nocache();

Session::checkLoginUser();

$domainrecordtype = new DomainRecordType();
if (
    !array_key_exists('domainrecordtypes_id', $_REQUEST)
    || !$domainrecordtype->getFromDB($_REQUEST['domainrecordtypes_id'])
) {
    $domainrecordtype->getEmpty();
}

$domainrecordtype->showDataAjaxForm($_REQUEST['str_input_id'] ?? '', $_REQUEST['obj_input_id'] ?? '');
