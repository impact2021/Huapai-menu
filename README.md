# Huapai Menu - WordPress Plugin

A WordPress plugin to create and display restaurant menus with customizable menu groups (starters, mains, desserts, etc.)

## Features

- **Custom Menu Items**: Create menu items with name, description, and price
- **Menu Groups**: Organize items into groups (e.g., Starters, Mains, Desserts, Drinks)
- **Easy Ordering**: Drag and drop to reorder menu items within groups
- **Shortcode Support**: Display any menu group using simple shortcodes
- **Responsive Design**: Menu displays beautifully on all devices
- **Clean Admin Interface**: Intuitive WordPress admin area for managing menus

## Installation

1. Download or clone this repository
2. Upload the `huapai-menu` folder to your WordPress `wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. You'll see a new "Menu Items" section in your WordPress admin sidebar

## Usage

### Adding Menu Groups

1. Go to **Menu Items > Menu Groups** in your WordPress admin
2. Add new groups like "Starters", "Mains", "Desserts", etc.
3. Each group will have a slug (e.g., "starters", "mains") that you'll use in shortcodes

### Adding Menu Items

1. Go to **Menu Items > Add New** in your WordPress admin
2. Enter the **item name** in the title field (e.g., "Caesar Salad")
3. Enter the **description** in the content editor (this will display in italics)
4. Enter the **price** in the sidebar meta box (e.g., "$12.50")
5. Select the **Menu Group** (e.g., Starters)
6. Set the **Order** value to control the display order (lower numbers appear first)
7. Click **Publish**

### Reordering Menu Items

You can reorder menu items by:
- Setting different **Order** values in the Page Attributes box (lower numbers appear first)
- Or using the Quick Edit feature from the menu items list

### Displaying Menus on Your Site

Use the `[huapai_menu]` shortcode with the group slug:

```
[huapai_menu group="starters"]
[huapai_menu group="mains"]
[huapai_menu group="desserts"]
```

You can add these shortcodes to any page or post.

## Example

If you create menu items like:
- **Name**: Caesar Salad
- **Description**: Crisp romaine lettuce with parmesan cheese and croutons
- **Price**: $12.50
- **Group**: Starters

The shortcode `[huapai_menu group="starters"]` will display:

```
Caesar Salad                                    $12.50
Crisp romaine lettuce with parmesan cheese and croutons
────────────────────────────────────────────────────────
```

## Styling

The plugin includes default styling with:
- Item name and description on the left
- Price aligned to the right
- Description text in italics
- Light grey borders between items
- Responsive design for mobile devices

You can customize the appearance by adding custom CSS to your theme targeting the `.huapai-menu-group` class and its child elements.

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher

## License

GPL v2 or later

## Support

For issues, questions, or contributions, please visit the [GitHub repository](https://github.com/impact2021/Huapai-menu).
