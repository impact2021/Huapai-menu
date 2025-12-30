/**
 * Drag and Drop Menu Item Ordering
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Check if we're on the menu items list page
        if ($('body').hasClass('edit-php') && $('body').hasClass('post-type-huapai_menu_item')) {
            var $table = $('#the-list');
            
            if ($table.length) {
                // Make the table rows sortable
                $table.sortable({
                    items: 'tr',
                    cursor: 'move',
                    axis: 'y',
                    handle: '.column-title, td:not(.column-cb)',
                    placeholder: 'ui-sortable-placeholder',
                    helper: function(e, tr) {
                        var $originals = tr.children();
                        var $helper = tr.clone();
                        $helper.children().each(function(index) {
                            $(this).width($originals.eq(index).width());
                        });
                        return $helper;
                    },
                    start: function(e, ui) {
                        ui.item.css('background-color', '#f0f0f0');
                        ui.placeholder.height(ui.item.height());
                    },
                    stop: function(e, ui) {
                        ui.item.css('background-color', '');
                    },
                    update: function(e, ui) {
                        // Get the new order
                        var order = [];
                        $table.find('tr').each(function(index) {
                            var postId = $(this).attr('id');
                            if (postId) {
                                postId = postId.replace('post-', '');
                                order.push({
                                    id: postId,
                                    position: index
                                });
                            }
                        });

                        // Send AJAX request to save the new order
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'huapai_menu_save_order',
                                order: order,
                                nonce: huapaiMenuOrder.nonce
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Show success message
                                    var $notice = $('<div class="notice notice-success is-dismissible"><p>Menu order updated successfully.</p></div>');
                                    $('.wp-header-end').after($notice);
                                    setTimeout(function() {
                                        $notice.fadeOut(function() {
                                            $(this).remove();
                                        });
                                    }, 3000);
                                } else {
                                    // Show error message
                                    var $notice = $('<div class="notice notice-error is-dismissible"><p>Error updating menu order.</p></div>');
                                    $('.wp-header-end').after($notice);
                                }
                            },
                            error: function() {
                                var $notice = $('<div class="notice notice-error is-dismissible"><p>Error updating menu order.</p></div>');
                                $('.wp-header-end').after($notice);
                            }
                        });
                    }
                });

                // Add cursor style to indicate draggable rows
                $table.find('tr').css('cursor', 'move');
                
                // Add a notice to inform users they can drag and drop
                var $dragNotice = $('<div class="notice notice-info"><p><strong>Tip:</strong> You can drag and drop menu items to reorder them.</p></div>');
                $('.wp-header-end').after($dragNotice);
            }
        }
    });

})(jQuery);
