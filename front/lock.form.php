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

use Glpi\Plugin\Hooks;

/** @var array $CFG_GLPI */
global $CFG_GLPI;

/**
 * @since 0.84
 */

include('../inc/includes.php');

if (isset($_POST['itemtype'])) {
    $itemtype    = $_POST['itemtype'];
    $source_item = new $itemtype();
    if ($source_item->can($_POST['id'], UPDATE)) {
        $devices = Item_Devices::getDeviceTypes();
        $actions = array_merge($CFG_GLPI['inventory_lockable_objects'], array_values($devices));

        if (isset($_POST["unlock"])) {
            foreach ($actions as $type) {
                if (isset($_POST[$type]) && count($_POST[$type])) {
                    $item = new $type();
                    foreach (array_keys($_POST[$type]) as $key) {
                        if (!$item->can($key, UPDATE)) {
                            Session::addMessageAfterRedirect(
                                sprintf(
                                    __('You do not have rights to restore %s item.'),
                                    $type
                                ),
                                true,
                                ERROR
                            );
                            continue;
                        }

                        //Force unlock
                        $item->restore(['id' => $key]);
                    }
                }
            }

           //Execute hook to unlock fields managed by a plugin, if needed
            Plugin::doHookFunction(Hooks::UNLOCK_FIELDS, $_POST);
        } else if (isset($_POST["purge"])) {
            foreach ($actions as $type) {
                if (isset($_POST[$type]) && count($_POST[$type])) {
                    $item = new $type();
                    foreach (array_keys($_POST[$type]) as $key) {
                        if (!$item->can($key, PURGE)) {
                             Session::addMessageAfterRedirect(
                                 sprintf(
                                     __('You do not have rights to delete %s item.'),
                                     $type
                                 ),
                                 true,
                                 ERROR
                             );
                               continue;
                        }

                      //Force unlock
                        $item->delete(['id' => $key], 1);
                    }
                }
            }
        }
    }
}

Html::back();
