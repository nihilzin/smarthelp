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

include('../inc/includes.php');

Session::checkLoginUser();

Html::popHeader(__('Setup'), $_SERVER['PHP_SELF']);

$params = Search::manageParams('DocumentType', $_GET);

$params['target'] = $_SERVER['PHP_SELF'];
Search::showList('DocumentType', $params);

Html::popFooter();
