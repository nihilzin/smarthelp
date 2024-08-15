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

include(__DIR__ . '/getAbstractRightDropdownValue.php');

// Only users who can update dashboards are allowed to use the "share dashboard" feature
// Users without this right shouldn't be allowed to read this dropdown values
Session::checkRight('dashboard', UPDATE);

show_rights_dropdown(ShareDashboardDropdown::class);
