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

export default class SearchToken {
    constructor(term, tag, exclusion, position, raw, prefix = null) {
        this.term = term;
        this.tag = tag || null; // Prevent undefined value
        this.exclusion = exclusion;
        this.position = position;
        this.raw = raw;
        this.prefix = prefix;
    }
}
