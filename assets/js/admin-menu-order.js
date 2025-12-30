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
                /**
                 * Helper function to show admin notices
                 */
                function showNotice(type, message, autoHide) {
                    var $notice = $('<div class="notice is-dismissible"><p></p></div>');
                    $notice.addClass('notice-' + type);
                    $notice.find('p').text(message);
                    $('.wp-header-end').after($notice);
                    
                    if (autoHide) {
                        setTimeout(function() {
                            $notice.fadeOut(function() {
                                $(this).remove();
                            });
                        }, 3000);
                    }
                }
                
                // Make the table rows sortable
                $table.sortable({
                    items: 'tr',
                    cursor: 'move',
                    axis: 'y',
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
                                    showNotice('success', huapaiMenuOrder.successText, true);
                                } else {
                                    showNotice('error', huapaiMenuOrder.errorText, false);
                                }
                            },
                            error: function() {
                                showNotice('error', huapaiMenuOrder.errorText, false);
                            }
                        });
                    }
                });
                
                // Add a notice to inform users they can drag and drop
                var $dragNotice = $('<div class="notice notice-info"><p></p></div>');
                $dragNotice.find('p').html('<strong>Tip:</strong> ' + $('<div>').text(huapaiMenuOrder.dragTipText).html());
                $('.wp-header-end').after($dragNotice);
            }
        }
    });

})(jQuery);
