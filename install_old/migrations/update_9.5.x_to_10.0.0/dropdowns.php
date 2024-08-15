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
 * @var \Migration $migration
 */

$all_dropdowns = Dropdown::getStandardDropdownItemTypes();
$dc_model_dropdowns = [];

foreach ($all_dropdowns as $group) {
    foreach ($group as $dropdown => $dropdown_name) {
        if (is_subclass_of($dropdown, CommonDCModelDropdown::class)) {
            $dc_model_dropdowns[] = $dropdown;
        }
    }
}

foreach ($dc_model_dropdowns as $model_dropdown) {
    $migration->changeSearchOption($model_dropdown, 130, 3);
}
