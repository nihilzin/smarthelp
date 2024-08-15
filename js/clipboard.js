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

$(function() {
    // set a function to track drag hover event
    $(document).on("click", ".copy_to_clipboard_wrapper", function(event) {

        // find the good element
        var target = $(event.target);
        if (target.attr('class') == 'copy_to_clipboard_wrapper') {
            target = target.find('*');
        }

        // copy text
        target.select();
        var succeed;
        try {
            succeed = document.execCommand("copy");
        } catch (e) {
            succeed = false;
        }
        target.blur();

        // indicate success
        if (succeed) {
            $('.copy_to_clipboard_wrapper.copied').removeClass('copied');
            target.parent('.copy_to_clipboard_wrapper').addClass('copied');
        } else {
            target.parent('.copy_to_clipboard_wrapper').addClass('copyfail');
        }
    });
});

/**
 * Copy a text to the clipboard
 *
 * @param {string} text
 *
 * @return {void}
 */
function copyTextToClipboard (text) {
    // Create a textarea to be able to select its content
    var textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.setAttribute('readonly', ''); // readonly to prevent focus
    textarea.style = {position: 'absolute', visibility: 'hidden'};
    document.body.appendChild(textarea);

    // Select and copy text to clipboard
    textarea.select();
    document.execCommand('copy');

    // Remove textarea
    document.body.removeChild(textarea);
}
