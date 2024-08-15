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
 * @since 9.2
 */

use Glpi\Application\ErrorHandler;

include('../inc/includes.php');

Session::checkLoginUser();

// Ensure warnings will not break ajax output.
ErrorHandler::getInstance()->disableOutput();

GLPIUploadHandler::uploadFiles($_POST);
