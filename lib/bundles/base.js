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

// Font-Awesome
require('@fortawesome/fontawesome-free/css/all.css');

// Animate.css
require('animate.css/animate.css');

// jQuery
// '$' and 'jQuery' objects have to be declared in global scope
window.$ = window.jQuery = require('jquery');

require('jquery-migrate');
window.$.migrateMute  = true;
window.$.migrateTrace = false;

// jQuery plugins
require('fittext.js');

// jQuery fancttree
require('jquery.fancytree');
require('jquery.fancytree/dist/modules/jquery.fancytree.grid');
require('jquery.fancytree/dist/modules/jquery.fancytree.filter');
require('jquery.fancytree/dist/modules/jquery.fancytree.glyph');
require('jquery.fancytree/dist/modules/jquery.fancytree.persist');
import 'jquery.fancytree/dist/skin-awesome/ui.fancytree.css';
import PlainScrollbar from 'exports-loader?exports=default|PlainScrollbar!plain-scrollbar';
import 'plain-scrollbar/plain-scrollbar.css';
window.PlainScrollbar = PlainScrollbar;


// jQuery UI widgets required by
// - jquery-file-upload (widget)
require('jquery-ui/ui/widget');

// Tabler
import '@tabler/core';

// qTip2
require('qtip2');
require('qtip2/dist/jquery.qtip.css');

// color input
require('spectrum-colorpicker2/dist/spectrum.css');
require('spectrum-colorpicker2');

// Select2
// use full for compat; see https://select2.org/upgrading/migrating-from-35
require('select2/dist/js/select2.full');
// Apply CSS classes to dropdown based on select tag classes
$.fn.select2.defaults.set(
   'adaptDropdownCssClass',
   function (cls) {
      return cls.replace('form-select', 'select-dropdown');
   }
);

//Loadash
//'_' object has to be declared in global scope
window._ = require('lodash');

// gettext.js
// add translation function into global scope
// signature is almost the same as for PHP functions, but accept extra arguments for string variables
window.i18n = require('gettext.js/lib/gettext').default({domain: 'glpi'});

const escape_msgid = function (msgid) {
    return msgid.replace(/%(\d+)\$/g, '%%$1\$');
};

window.__ = function (msgid, domain /* , extra */) {
    domain = typeof(domain) !== 'undefined' ? domain : 'glpi';
    var text = i18n.dcnpgettext.apply(
        i18n,
        [domain, undefined, escape_msgid(msgid), undefined, undefined].concat(Array.prototype.slice.call(arguments, 2))
    );
    return _.escape(text);
};

window._n = function (msgid, msgid_plural, n, domain /* , extra */) {
    domain = typeof(domain) !== 'undefined' ? domain : 'glpi';
    var text = i18n.dcnpgettext.apply(
        i18n,
        [domain, undefined, escape_msgid(msgid), escape_msgid(msgid_plural), n].concat(Array.prototype.slice.call(arguments, 4))
    );
    return _.escape(text);
};
window._x = function (msgctxt, msgid, domain /* , extra */) {
    domain = typeof(domain) !== 'undefined' ? domain : 'glpi';
    var text = i18n.dcnpgettext.apply(
        i18n,
        [domain, msgctxt, escape_msgid(msgid), undefined, undefined].concat(Array.prototype.slice.call(arguments, 3))
    );
    return _.escape(text);
};
window._nx = function (msgctxt, msgid, msgid_plural, n, domain /* , extra */) {
    domain = typeof(domain) !== 'undefined' ? domain : 'glpi';
    var text = i18n.dcnpgettext.apply(
        i18n,
        [domain, msgctxt, escape_msgid(msgid), escape_msgid(msgid_plural), n].concat(Array.prototype.slice.call(arguments, 5))
    );
    return _.escape(text);
};
