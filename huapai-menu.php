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
 * Register Custom Post Type for Menu Items
 */
function huapai_menu_register_post_type() {
    $labels = array(
        'name'                  => _x('Menu Items', 'Post Type General Name', 'huapai-menu'),
        'singular_name'         => _x('Menu Item', 'Post Type Singular Name', 'huapai-menu'),
        'menu_name'             => __('Menu Items', 'huapai-menu'),
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
        'menu_position'         => 25,
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
    ?>
    <p>
        <label for="huapai_menu_price"><?php _e('Price:', 'huapai-menu'); ?></label><br>
        <input type="text" id="huapai_menu_price" name="huapai_menu_price" value="<?php echo esc_attr($price); ?>" style="width: 100%;" placeholder="e.g., $12.50" />
    </p>
    <p class="description">
        <?php _e('Enter the price for this menu item. The title will be the item name, and the content editor below will be the description (will display in italics).', 'huapai-menu'); ?>
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
        update_post_meta($post_id, '_huapai_menu_price', sanitize_text_field($_POST['huapai_menu_price']));
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
                        <h3 class="huapai-menu-item-title"><?php the_title(); ?></h3>
                        <?php if (get_the_content()) : ?>
                            <div class="huapai-menu-item-description"><?php the_content(); ?></div>
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
