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
 * Objects that has been renamed, but are used in upgrade scripts
 *
 * @since 9.2
 **/

if (!class_exists('Bookmark')) {
    class Bookmark extends SavedSearch
    {
    }
}
