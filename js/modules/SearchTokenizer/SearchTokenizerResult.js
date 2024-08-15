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

export default class SearchTokenizerResult {

    constructor() {
        /**
       * @type {SearchToken[]}
       */
        this.tokens = [];
    }

    /**
    * Get all tokens with a specific tag
    * @param name
    * @return {SearchToken[]}
    */
    getTag(name) {
        return this.tokens.filter(t => t.tag === name);
    }

    /**
    * Get all tokens with a tag
    * @return {SearchToken[]}
    */
    getTaggedTerms() {
        return this.tokens.filter(t => t.tag !== null);
    }

    /**
    * Get all tokens without a tag
    * @return {SearchToken[]}
    */
    getUntaggedTerms() {
        return this.tokens.filter(t => t.tag === null);
    }

    /**
    * Get all untagged terms as a concatenated string
    *
    * The terms in the resulting string should be in the same order they appeared in the tokenizer input string.
    * @return {string}
    */
    getFullPhrase() {
        let full_phrase = '';
        this.getUntaggedTerms().forEach(t => full_phrase += ' ' + t.term);
        return full_phrase.trim();
    }
}
