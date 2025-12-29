# Settings Guide - Huapai Menu Plugin

## New Features Added

### 1. Settings Page
A new **Settings** submenu has been added under **Menu Items** in the WordPress admin area.

To access: **WordPress Admin → Menu Items → Settings**

### 2. Text Color Customization
You can now customize the colors for:
- **Menu Item Title Color** - Default: Black (#000000)
- **Description Color** - Default: Gray (#666666)
- **Price Color** - Default: Black (#000000)

Each color field includes a color picker for easy selection.

### 3. Font Size Customization
You can now customize the font sizes for:
- **Menu Item Title Font Size** - Default: 1.1em
- **Description Font Size** - Default: 0.9em (smaller than before)
- **Price Font Size** - Default: 1em

Font sizes can be entered in any CSS unit (em, px, rem, etc.).

### 4. Improved Spacing
- **Reduced space below item title**: The margin between the title and description has been reduced from 5px to 2px for a tighter, more compact layout.
- **Description now uses smaller font**: Default description font size is now 0.9em (90% of base font size) for better visual hierarchy.

### 5. Automatic $ Symbol
When entering prices in the menu item editor:
- Simply enter the numeric value (e.g., "12.50")
- The $ symbol will be automatically added when you save
- No need to manually type the $ symbol anymore!
- The placeholder now shows "e.g., 12.50" instead of "e.g., $12.50"

## Visual Comparison

### Before:
```
Menu Item Title (5px space below)
Description in regular size
```

### After:
```
Menu Item Title (2px space below - tighter)
Description in smaller font (0.9em - more compact)
```

## How to Use

1. **Access Settings**: Go to WordPress Admin → Menu Items → Settings

2. **Customize Colors**: Click on any color field to open the color picker and choose your desired color

3. **Adjust Font Sizes**: Enter your desired font size with units (examples: 1.2em, 18px, 1.1rem)

4. **Save**: Click "Save Settings" button to apply changes

5. **Add/Edit Menu Items**: When adding or editing menu items, just enter the numeric price (e.g., "12.50") - the $ will be added automatically

## Example Settings

For a modern, elegant look:
- Title Color: #2c3e50 (dark blue-gray)
- Description Color: #7f8c8d (medium gray)
- Price Color: #27ae60 (green)
- Title Font Size: 1.2em
- Description Font Size: 0.85em
- Price Font Size: 1.1em

For a traditional look:
- Title Color: #000000 (black)
- Description Color: #666666 (gray)
- Price Color: #000000 (black)
- Title Font Size: 1.1em
- Description Font Size: 0.9em
- Price Font Size: 1em

## Technical Details

The settings are stored in WordPress options and applied as inline CSS styles to the frontend menu display. This ensures:
- Fast loading times
- No additional HTTP requests
- Easy customization without editing theme files
- Changes are immediately visible on the frontend
