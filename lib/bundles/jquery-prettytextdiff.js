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

// diff-match-patch dependency
// 'diff_match_patch' function and DIFF_* constants have to be declared in global scope
window.diff_match_patch = require('diff-match-patch').diff_match_patch;
window.DIFF_DELETE = require('diff-match-patch').DIFF_DELETE;
window.DIFF_INSERT = require('diff-match-patch').DIFF_INSERT;
window.DIFF_EQUAL = require('diff-match-patch').DIFF_EQUAL;

// PrettyTextDiff jQuery plugin
require('jquery-prettytextdiff');

