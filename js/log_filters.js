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

/* global reloadTab */

$(function() {
    var bindShowFiltersBtn = function () {
        $('.show_log_filters').on('click', showFilters);
    };

    var showFilters = function (event) {
        event.preventDefault();

        // Toggle filters
        if ($('.show_log_filters').hasClass('active')) {
            reloadTab('');
        } else {
            reloadTab('filters[active]=1');
        }
    };

    var delay_timer = null;

    var bindFilterChange = function () {
        // Workaround to prevent opening of dropdown when removing item using the "x" button.
        // Without this workaround, orphan dropdowns remains in page when reloading tab.
        $(document).on('select2:unselecting', '.log_history_filter_row .select2-hidden-accessible', function(ev) {
            if (ev.params.args.originalEvent) {
                ev.params.args.originalEvent.stopPropagation();
            }
        });

        $('.log_history_filter_row [name^="filters\\["]').on('input', function() {
            clearTimeout(delay_timer);
            delay_timer = setTimeout(function() {
                handleFilterChange();
            }, 800);
        });
        $('.log_history_filter_row select[name^="filters\\["]').on('change', handleFilterChange);

        // prevent submit of parent form when pressing enter
        $('.log_history_filter_row [name^="filters\\["]').on('keypress', function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                handleFilterChange();
            }
        });
    };

    var handleFilterChange = function () {
        if (delay_timer !== null) {
            clearTimeout(delay_timer);
        }
        // Prevent dropdown to remain in page after tab has been reload.
        $('.log_history_filter_row .select2-hidden-accessible').select2('close');

        reloadTab($('[name^="filters\\["]').serialize());
    };

    $('main').on('glpi.tab.loaded', function() {
        bindShowFiltersBtn();
        bindFilterChange();
    });
});
