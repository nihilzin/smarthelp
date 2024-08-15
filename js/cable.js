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

function refreshAssetBreadcrumb(itemtype, items_id, dom_to_update) {
    // get asset breadcrumb
    $.ajax({
        method: 'GET',
        url: CFG_GLPI.root_doc + "/ajax/cable.php",
        data: {
            action: 'get_item_breadcrum',
            items_id: items_id,
            itemtype: itemtype,
        }
    }).done(function(html_breadcrum) {
        $('#' + dom_to_update).empty();
        $('#' + dom_to_update).append(html_breadcrum);
    });

}

function refreshNetworkPortDropdown(itemtype, items_id, dom_to_update) {
    // get networkport dropdown
    $.ajax({
        method: 'GET',
        url: CFG_GLPI.root_doc + "/ajax/cable.php",
        data: {
            action: 'get_networkport_dropdown',
            items_id: items_id,
            itemtype: itemtype,
        }
    }).done(function(html_data) {
        $('#' + dom_to_update).empty();
        $('#' + dom_to_update).append(html_data);
    });
}

function refreshSocketDropdown(itemtype, items_id, socketmodels_id, dom_name) {
    // get networkport dropdown
    $.ajax({
        method: 'GET',
        url: CFG_GLPI.root_doc + "/ajax/cable.php",
        data: {
            action: 'get_socket_dropdown',
            items_id: items_id,
            itemtype: itemtype,
            socketmodels_id: socketmodels_id,
            dom_name: dom_name
        }
    }).done(function(html_data) {
        var parent_dom = $('select[name="'+dom_name+'"]').parent().parent();
        parent_dom.empty();
        parent_dom.append(html_data);
    });
}
