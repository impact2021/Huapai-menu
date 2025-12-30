<?php
/**
 * Plugin Name: Huapai Menu
 * Plugin URI: https://github.com/impact2021/Huapai-menu
 * Description: A WordPress plugin to create and display restaurant menus with groups (starters, mains, etc.)
 * Version: 1.0.0
 * Author: Huapai
 * Author URI: https://github.com/impact2021
 * License: GPL v2 or later
 * Text Domain: huapai-menu
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('HUAPAI_MENU_VERSION', '1.0.0');
define('HUAPAI_MENU_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HUAPAI_MENU_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Sanitize font size input
 */
function huapai_menu_sanitize_font_size($font_size) {
    // Remove any potentially harmful characters
    $font_size = sanitize_text_field($font_size);
    
    // Check if the value matches a valid CSS font-size pattern
    // Allows: numbers followed by units (px, em, rem, %, pt, vh, vw)
    // or keywords (small, medium, large, etc.)
    if (preg_match('/^(\d+(\.\d+)?|\d*\.\d+)(px|em|rem|%|pt|vh|vw|ex|ch)$|^(xx-small|x-small|small|medium|large|x-large|xx-large|smaller|larger)$/i', $font_size)) {
        return $font_size;
    }
    
    // If invalid, return default empty string (will use CSS default)
    return '';
}

/**
 * Add Settings Submenu
 */
function huapai_menu_add_settings_page() {
    add_submenu_page(
        'edit.php?post_type=huapai_menu_item',
        __('Settings', 'huapai-menu'),
        __('Settings', 'huapai-menu'),
        'manage_options',
        'huapai-menu-settings',
        'huapai_menu_settings_page_callback'
    );
}
add_action('admin_menu', 'huapai_menu_add_settings_page');

/**
 * Settings Page Callback
 */
function huapai_menu_settings_page_callback() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save settings
    if (isset($_POST['huapai_menu_settings_nonce']) && wp_verify_nonce($_POST['huapai_menu_settings_nonce'], 'huapai_menu_settings')) {
        if (isset($_POST['huapai_menu_title_color'])) {
            update_option('huapai_menu_title_color', sanitize_hex_color($_POST['huapai_menu_title_color']));
        }
        if (isset($_POST['huapai_menu_description_color'])) {
            update_option('huapai_menu_description_color', sanitize_hex_color($_POST['huapai_menu_description_color']));
        }
        if (isset($_POST['huapai_menu_price_color'])) {
            update_option('huapai_menu_price_color', sanitize_hex_color($_POST['huapai_menu_price_color']));
        }
        if (isset($_POST['huapai_menu_title_font_size'])) {
            update_option('huapai_menu_title_font_size', huapai_menu_sanitize_font_size($_POST['huapai_menu_title_font_size']));
        }
        if (isset($_POST['huapai_menu_description_font_size'])) {
            update_option('huapai_menu_description_font_size', huapai_menu_sanitize_font_size($_POST['huapai_menu_description_font_size']));
        }
        if (isset($_POST['huapai_menu_price_font_size'])) {
            update_option('huapai_menu_price_font_size', huapai_menu_sanitize_font_size($_POST['huapai_menu_price_font_size']));
        }
        
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Settings saved successfully.', 'huapai-menu') . '</p></div>';
    }

    // Get current settings with defaults
    $title_color = get_option('huapai_menu_title_color', '#000000');
    $description_color = get_option('huapai_menu_description_color', '#666666');
    $price_color = get_option('huapai_menu_price_color', '#000000');
    $title_font_size = get_option('huapai_menu_title_font_size', '1.1em');
    $description_font_size = get_option('huapai_menu_description_font_size', '0.9em');
    $price_font_size = get_option('huapai_menu_price_font_size', '1em');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('huapai_menu_settings', 'huapai_menu_settings_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th colspan="2"><h2><?php _e('Text Colors', 'huapai-menu'); ?></h2></th>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="huapai_menu_title_color"><?php _e('Menu Item Title Color', 'huapai-menu'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="huapai_menu_title_color" name="huapai_menu_title_color" value="<?php echo esc_attr($title_color); ?>" class="huapai-color-picker" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="huapai_menu_description_color"><?php _e('Description Color', 'huapai-menu'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="huapai_menu_description_color" name="huapai_menu_description_color" value="<?php echo esc_attr($description_color); ?>" class="huapai-color-picker" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="huapai_menu_price_color"><?php _e('Price Color', 'huapai-menu'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="huapai_menu_price_color" name="huapai_menu_price_color" value="<?php echo esc_attr($price_color); ?>" class="huapai-color-picker" />
                    </td>
                </tr>
                
                <tr>
                    <th colspan="2"><h2><?php _e('Font Sizes', 'huapai-menu'); ?></h2></th>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="huapai_menu_title_font_size"><?php _e('Menu Item Title Font Size', 'huapai-menu'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="huapai_menu_title_font_size" name="huapai_menu_title_font_size" value="<?php echo esc_attr($title_font_size); ?>" placeholder="e.g., 1.1em, 18px" />
                        <p class="description"><?php _e('Enter font size with units (e.g., 1.1em, 18px, 1.2rem)', 'huapai-menu'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="huapai_menu_description_font_size"><?php _e('Description Font Size', 'huapai-menu'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="huapai_menu_description_font_size" name="huapai_menu_description_font_size" value="<?php echo esc_attr($description_font_size); ?>" placeholder="e.g., 0.9em, 14px" />
                        <p class="description"><?php _e('Enter font size with units (e.g., 0.9em, 14px, 0.9rem)', 'huapai-menu'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="huapai_menu_price_font_size"><?php _e('Price Font Size', 'huapai-menu'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="huapai_menu_price_font_size" name="huapai_menu_price_font_size" value="<?php echo esc_attr($price_font_size); ?>" placeholder="e.g., 1em, 16px" />
                        <p class="description"><?php _e('Enter font size with units (e.g., 1em, 16px, 1rem)', 'huapai-menu'); ?></p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(__('Save Settings', 'huapai-menu')); ?>
        </form>
    </div>
    <?php
}

/**
 * Enqueue admin scripts and styles
 */
function huapai_menu_enqueue_admin_scripts($hook) {
    global $typenow;
    
    // Enqueue color picker for settings page
    if ($hook === 'huapai_menu_item_page_huapai-menu-settings') {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        wp_add_inline_script('wp-color-picker', '
            jQuery(document).ready(function($) {
                $(".huapai-color-picker").wpColorPicker();
            });
        ');
    }
    
    // Enqueue drag and drop scripts for menu items list page
    if ($hook === 'edit.php' && $typenow === 'huapai_menu_item') {
        // Enqueue jQuery UI Sortable
        wp_enqueue_script('jquery-ui-sortable');
        
        // Enqueue custom admin script
        wp_enqueue_script(
            'huapai-menu-order',
            HUAPAI_MENU_PLUGIN_URL . 'assets/js/admin-menu-order.js',
            array('jquery', 'jquery-ui-sortable'),
            HUAPAI_MENU_VERSION,
            true
        );
        
        // Localize script with nonce and translatable strings
        wp_localize_script('huapai-menu-order', 'huapaiMenuOrder', array(
            'nonce' => wp_create_nonce('huapai_menu_order_nonce'),
            'dragTipText' => __('You can drag and drop menu items to reorder them.', 'huapai-menu'),
            'successText' => __('Menu order updated successfully.', 'huapai-menu'),
            'errorText' => __('Error updating menu order.', 'huapai-menu'),
        ));
        
        // Enqueue admin CSS
        wp_enqueue_style(
            'huapai-menu-admin-order',
            HUAPAI_MENU_PLUGIN_URL . 'assets/css/admin-menu-order.css',
            array(),
            HUAPAI_MENU_VERSION
        );
    }
}
add_action('admin_enqueue_scripts', 'huapai_menu_enqueue_admin_scripts');

/**
 * Register Custom Post Type for Menu Items
 */
function huapai_menu_register_post_type() {
    $labels = array(
        'name'                  => _x('Menu Items', 'Post Type General Name', 'huapai-menu'),
        'singular_name'         => _x('Menu Item', 'Post Type Singular Name', 'huapai-menu'),
        'menu_name'             => __('Huapai Menu', 'huapai-menu'),
        'name_admin_bar'        => __('Menu Item', 'huapai-menu'),
        'archives'              => __('Menu Archives', 'huapai-menu'),
        'attributes'            => __('Menu Attributes', 'huapai-menu'),
        'parent_item_colon'     => __('Parent Menu:', 'huapai-menu'),
        'all_items'             => __('All Menu Items', 'huapai-menu'),
        'add_new_item'          => __('Add New Menu Item', 'huapai-menu'),
        'add_new'               => __('Add New', 'huapai-menu'),
        'new_item'              => __('New Menu Item', 'huapai-menu'),
        'edit_item'             => __('Edit Menu Item', 'huapai-menu'),
        'update_item'           => __('Update Menu Item', 'huapai-menu'),
        'view_item'             => __('View Menu Item', 'huapai-menu'),
        'view_items'            => __('View Menu Items', 'huapai-menu'),
        'search_items'          => __('Search Menu Item', 'huapai-menu'),
        'not_found'             => __('Not found', 'huapai-menu'),
        'not_found_in_trash'    => __('Not found in Trash', 'huapai-menu'),
    );

    $args = array(
        'label'                 => __('Menu Item', 'huapai-menu'),
        'description'           => __('Restaurant Menu Items', 'huapai-menu'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'page-attributes'),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 24,
        'menu_icon'             => 'dashicons-food',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
    );

    register_post_type('huapai_menu_item', $args);
}
add_action('init', 'huapai_menu_register_post_type', 0);

/**
 * Register Custom Taxonomy for Menu Groups
 */
function huapai_menu_register_taxonomy() {
    $labels = array(
        'name'                       => _x('Menu Groups', 'Taxonomy General Name', 'huapai-menu'),
        'singular_name'              => _x('Menu Group', 'Taxonomy Singular Name', 'huapai-menu'),
        'menu_name'                  => __('Menu Groups', 'huapai-menu'),
        'all_items'                  => __('All Groups', 'huapai-menu'),
        'parent_item'                => __('Parent Group', 'huapai-menu'),
        'parent_item_colon'          => __('Parent Group:', 'huapai-menu'),
        'new_item_name'              => __('New Group Name', 'huapai-menu'),
        'add_new_item'               => __('Add New Group', 'huapai-menu'),
        'edit_item'                  => __('Edit Group', 'huapai-menu'),
        'update_item'                => __('Update Group', 'huapai-menu'),
        'view_item'                  => __('View Group', 'huapai-menu'),
        'separate_items_with_commas' => __('Separate groups with commas', 'huapai-menu'),
        'add_or_remove_items'        => __('Add or remove groups', 'huapai-menu'),
        'choose_from_most_used'      => __('Choose from the most used', 'huapai-menu'),
        'popular_items'              => __('Popular Groups', 'huapai-menu'),
        'search_items'               => __('Search Groups', 'huapai-menu'),
        'not_found'                  => __('Not Found', 'huapai-menu'),
        'no_terms'                   => __('No groups', 'huapai-menu'),
        'items_list'                 => __('Groups list', 'huapai-menu'),
        'items_list_navigation'      => __('Groups list navigation', 'huapai-menu'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => false,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => false,
        'show_tagcloud'              => false,
    );

    register_taxonomy('huapai_menu_group', array('huapai_menu_item'), $args);
}
add_action('init', 'huapai_menu_register_taxonomy', 0);

/**
 * Add Meta Box for Menu Item Price
 */
function huapai_menu_add_meta_boxes() {
    add_meta_box(
        'huapai_menu_price',
        __('Menu Item Details', 'huapai-menu'),
        'huapai_menu_price_callback',
        'huapai_menu_item',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'huapai_menu_add_meta_boxes');

/**
 * Meta Box Callback Function
 */
function huapai_menu_price_callback($post) {
    wp_nonce_field('huapai_menu_price_nonce', 'huapai_menu_price_nonce');
    
    $price = get_post_meta($post->ID, '_huapai_menu_price', true);
    $gluten_free = get_post_meta($post->ID, '_huapai_menu_gluten_free', true);
    $nut_free = get_post_meta($post->ID, '_huapai_menu_nut_free', true);
    ?>
    <p>
        <label for="huapai_menu_price"><?php _e('Price:', 'huapai-menu'); ?></label><br>
        <input type="text" id="huapai_menu_price" name="huapai_menu_price" value="<?php echo esc_attr($price); ?>" style="width: 100%;" placeholder="e.g., 12.50" />
    </p>
    <p class="description">
        <?php _e('Enter the price for this menu item ($ symbol will be added automatically). The title will be the item name, and the content editor below will be the description (will display in italics).', 'huapai-menu'); ?>
    </p>
    <p>
        <label>
            <input type="checkbox" id="huapai_menu_gluten_free" name="huapai_menu_gluten_free" value="1" <?php checked($gluten_free, '1'); ?> />
            <?php _e('Gluten Free', 'huapai-menu'); ?>
        </label>
    </p>
    <p>
        <label>
            <input type="checkbox" id="huapai_menu_nut_free" name="huapai_menu_nut_free" value="1" <?php checked($nut_free, '1'); ?> />
            <?php _e('Nut Free', 'huapai-menu'); ?>
        </label>
    </p>
    <?php
}

/**
 * Save Meta Box Data
 */
function huapai_menu_save_meta_box($post_id) {
    // Check if nonce is set
    if (!isset($_POST['huapai_menu_price_nonce'])) {
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['huapai_menu_price_nonce'], 'huapai_menu_price_nonce')) {
        return;
    }

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save price
    if (isset($_POST['huapai_menu_price'])) {
        $price = sanitize_text_field($_POST['huapai_menu_price']);
        // Automatically add $ symbol if not present
        if (!empty($price) && strpos($price, '$') === false) {
            $price = '$' . $price;
        }
        update_post_meta($post_id, '_huapai_menu_price', $price);
    }
    
    // Save gluten free checkbox
    if (isset($_POST['huapai_menu_gluten_free'])) {
        update_post_meta($post_id, '_huapai_menu_gluten_free', '1');
    } else {
        delete_post_meta($post_id, '_huapai_menu_gluten_free');
    }
    
    // Save nut free checkbox
    if (isset($_POST['huapai_menu_nut_free'])) {
        update_post_meta($post_id, '_huapai_menu_nut_free', '1');
    } else {
        delete_post_meta($post_id, '_huapai_menu_nut_free');
    }
}
add_action('save_post_huapai_menu_item', 'huapai_menu_save_meta_box');

/**
 * Shortcode to Display Menu Group
 * Usage: [huapai_menu group="starters"]
 */
function huapai_menu_shortcode($atts) {
    $atts = shortcode_atts(array(
        'group' => '',
    ), $atts, 'huapai_menu');

    if (empty($atts['group'])) {
        return '<p>' . __('Please specify a menu group.', 'huapai-menu') . '</p>';
    }

    $args = array(
        'post_type'      => 'huapai_menu_item',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'tax_query'      => array(
            array(
                'taxonomy' => 'huapai_menu_group',
                'field'    => 'slug',
                'terms'    => $atts['group'],
            ),
        ),
    );

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p>' . __('No menu items found in this group.', 'huapai-menu') . '</p>';
    }

    ob_start();
    ?>
    <div class="huapai-menu-group">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <div class="huapai-menu-item">
                <div class="huapai-menu-item-content">
                    <div class="huapai-menu-item-left">
                        <h3 class="huapai-menu-item-title">
                            <?php the_title(); ?>
                            <?php 
                            $gluten_free = get_post_meta(get_the_ID(), '_huapai_menu_gluten_free', true);
                            $nut_free = get_post_meta(get_the_ID(), '_huapai_menu_nut_free', true);
                            
                            if ($gluten_free || $nut_free) : ?>
                                <span class="huapai-menu-dietary-icons">
                                    <?php if ($gluten_free) : ?>
                                        <span class="huapai-menu-icon huapai-menu-gluten-free" title="<?php esc_attr_e('Gluten Free', 'huapai-menu'); ?>">GF</span>
                                    <?php endif; ?>
                                    <?php if ($nut_free) : ?>
                                        <span class="huapai-menu-icon huapai-menu-nut-free" title="<?php esc_attr_e('Nut Free', 'huapai-menu'); ?>">NF</span>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        </h3>
                        <?php 
                        $content = apply_filters('the_content', get_the_content());
                        if ($content) : 
                        ?>
                            <div class="huapai-menu-item-description"><?php echo wp_kses_post($content); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="huapai-menu-item-right">
                        <span class="huapai-menu-item-price">
                            <?php 
                            $price = get_post_meta(get_the_ID(), '_huapai_menu_price', true);
                            echo esc_html($price);
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('huapai_menu', 'huapai_menu_shortcode');

/**
 * Enqueue Frontend Styles
 */
function huapai_menu_enqueue_styles() {
    wp_enqueue_style('huapai-menu-styles', HUAPAI_MENU_PLUGIN_URL . 'assets/css/huapai-menu.css', array(), HUAPAI_MENU_VERSION);
    
    // Add inline styles based on settings
    $title_color = get_option('huapai_menu_title_color', '#000000');
    $description_color = get_option('huapai_menu_description_color', '#666666');
    $price_color = get_option('huapai_menu_price_color', '#000000');
    $title_font_size = get_option('huapai_menu_title_font_size', '1.1em');
    $description_font_size = get_option('huapai_menu_description_font_size', '0.9em');
    $price_font_size = get_option('huapai_menu_price_font_size', '1em');
    
    $custom_css = '';
    
    // Only add styles if we have valid values
    if (!empty($title_color) || !empty($title_font_size)) {
        $custom_css .= '.huapai-menu-item-title {';
        if (!empty($title_color)) {
            $custom_css .= 'color: ' . esc_attr($title_color) . ';';
        }
        if (!empty($title_font_size)) {
            $custom_css .= 'font-size: ' . esc_attr($title_font_size) . ';';
        }
        $custom_css .= '}';
    }
    
    if (!empty($description_color) || !empty($description_font_size)) {
        $custom_css .= '.huapai-menu-item-description {';
        if (!empty($description_color)) {
            $custom_css .= 'color: ' . esc_attr($description_color) . ';';
        }
        if (!empty($description_font_size)) {
            $custom_css .= 'font-size: ' . esc_attr($description_font_size) . ';';
        }
        $custom_css .= '}';
    }
    
    if (!empty($price_color) || !empty($price_font_size)) {
        $custom_css .= '.huapai-menu-item-price {';
        if (!empty($price_color)) {
            $custom_css .= 'color: ' . esc_attr($price_color) . ';';
        }
        if (!empty($price_font_size)) {
            $custom_css .= 'font-size: ' . esc_attr($price_font_size) . ';';
        }
        $custom_css .= '}';
    }
    
    if (!empty($custom_css)) {
        wp_add_inline_style('huapai-menu-styles', $custom_css);
    }
}
add_action('wp_enqueue_scripts', 'huapai_menu_enqueue_styles');

/**
 * Add custom column to admin list view
 */
function huapai_menu_add_price_column($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['price'] = __('Price', 'huapai-menu');
        }
    }
    return $new_columns;
}
add_filter('manage_huapai_menu_item_posts_columns', 'huapai_menu_add_price_column');

/**
 * Display price in admin column
 */
function huapai_menu_display_price_column($column, $post_id) {
    if ($column === 'price') {
        $price = get_post_meta($post_id, '_huapai_menu_price', true);
        echo esc_html($price);
    }
}
add_action('manage_huapai_menu_item_posts_custom_column', 'huapai_menu_display_price_column', 10, 2);

/**
 * Make price column sortable
 */
function huapai_menu_sortable_columns($columns) {
    $columns['price'] = 'price';
    return $columns;
}
add_filter('manage_edit-huapai_menu_item_sortable_columns', 'huapai_menu_sortable_columns');

/**
 * Modify query to handle price column sorting and default ordering
 */
function huapai_menu_price_column_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    // Only apply to menu item post type
    if ($query->get('post_type') !== 'huapai_menu_item') {
        return;
    }

    $orderby = $query->get('orderby');

    if ('price' === $orderby) {
        $query->set('meta_key', '_huapai_menu_price');
        $query->set('orderby', 'meta_value_num');
    } elseif (empty($orderby)) {
        // Set default ordering to menu_order when no orderby is specified
        // This ensures drag-and-drop order persists on page refresh
        $query->set('orderby', 'menu_order');
        $query->set('order', 'ASC');
    }
}
add_action('pre_get_posts', 'huapai_menu_price_column_orderby');

/**
 * Add shortcode column to menu groups taxonomy
 */
function huapai_menu_add_shortcode_column($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'name') {
            $new_columns['shortcode'] = __('Shortcode', 'huapai-menu');
        }
    }
    return $new_columns;
}
add_filter('manage_edit-huapai_menu_group_columns', 'huapai_menu_add_shortcode_column');

/**
 * Display shortcode in menu groups taxonomy column
 */
function huapai_menu_display_shortcode_column($content, $column_name, $term_id) {
    if ($column_name === 'shortcode') {
        $term = get_term($term_id, 'huapai_menu_group');
        if ($term && !is_wp_error($term)) {
            $shortcode = '[huapai_menu group="' . esc_attr($term->slug) . '"]';
            $content = '<code>' . esc_html($shortcode) . '</code>';
        }
    }
    return $content;
}
add_filter('manage_huapai_menu_group_custom_column', 'huapai_menu_display_shortcode_column', 10, 3);

/**
 * Add menu group filter dropdown to admin list view
 */
function huapai_menu_add_group_filter() {
    global $typenow;
    
    if ($typenow === 'huapai_menu_item') {
        $taxonomy = 'huapai_menu_group';
        $selected = isset($_GET[$taxonomy]) ? sanitize_text_field($_GET[$taxonomy]) : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        
        wp_dropdown_categories(array(
            'show_option_all' => __('All Menu Groups', 'huapai-menu'),
            'taxonomy' => $taxonomy,
            'name' => $taxonomy,
            'orderby' => 'name',
            'selected' => $selected,
            'show_count' => true,
            'hide_empty' => true,
            'value_field' => 'slug',
            'hierarchical' => true,
        ));
    }
}
add_action('restrict_manage_posts', 'huapai_menu_add_group_filter');

/**
 * Filter menu items by selected menu group
 */
function huapai_menu_filter_by_group($query) {
    global $pagenow;
    $taxonomy = 'huapai_menu_group';
    
    if ($pagenow === 'edit.php' && 
        isset($_GET['post_type']) && 
        sanitize_text_field($_GET['post_type']) === 'huapai_menu_item' && 
        isset($_GET[$taxonomy]) && 
        $_GET[$taxonomy] !== '') {
        
        // Preserve existing tax_query if it exists
        $tax_query = isset($query->query_vars['tax_query']) ? $query->query_vars['tax_query'] : array();
        
        // Add our menu group filter
        $tax_query[] = array(
            'taxonomy' => $taxonomy,
            'field' => 'slug',
            'terms' => sanitize_text_field($_GET[$taxonomy]),
        );
        
        $query->query_vars['tax_query'] = $tax_query;
    }
}
add_filter('parse_query', 'huapai_menu_filter_by_group');

/**
 * AJAX handler to save menu item order
 */
function huapai_menu_save_order_ajax() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'huapai_menu_order_nonce')) {
        wp_send_json_error('Invalid nonce');
        return;
    }
    
    // Check user permissions
    if (!current_user_can('edit_others_posts')) {
        wp_send_json_error('Insufficient permissions');
        return;
    }
    
    // Get the order data
    if (!isset($_POST['order']) || !is_array($_POST['order'])) {
        wp_send_json_error('Invalid order data');
        return;
    }
    
    // Update menu_order for each post
    foreach ($_POST['order'] as $item) {
        // Sanitize and validate each item
        if (!is_array($item) || !isset($item['id']) || !isset($item['position'])) {
            continue;
        }
        
        $post_id = absint($item['id']);
        $position = absint($item['position']);
        
        // Skip if invalid data
        if ($post_id === 0) {
            continue;
        }
        
        // Verify this is a menu item post
        if (get_post_type($post_id) === 'huapai_menu_item') {
            wp_update_post(array(
                'ID' => $post_id,
                'menu_order' => $position,
            ));
        }
    }
    
    wp_send_json_success('Order updated successfully');
}
add_action('wp_ajax_huapai_menu_save_order', 'huapai_menu_save_order_ajax');
