# WP Playwright POC

A simple WordPress plugin built as a proof of concept for testing automated QA workflows against pull requests using Playwright.

## Purpose

This plugin serves as a practical example for:
- Demonstrating automated end-to-end testing with Playwright on WordPress
- Testing PR-based QA automation workflows
- Providing a simple, testable WordPress plugin with predictable behavior
- Showcasing best practices for test-friendly WordPress development

## Features

The plugin provides a testimonials/reviews display system with the following capabilities:

### Admin Settings
- Configurable layout (Grid or List view)
- Adjustable grid columns (1-6)
- Maximum items display control (1-8)
- Toggle star ratings visibility
- Toggle review date visibility
- Customizable colors (background, text, star)

### Frontend Display
- Shortcode-based rendering: `[wppoc_reviews]`
- Responsive grid or list layouts
- Avatar images for reviewers
- Star rating display (1-5 stars)
- Review dates and text
- CSS custom properties for easy styling

## Installation

1. Clone this repository into your WordPress plugins directory:
   ```bash
   cd wp-content/plugins/
   git clone <repository-url> wp-playwright-poc
   ```

2. Activate the plugin through the WordPress admin:
   - Navigate to **Plugins** in the WordPress admin
   - Find "WP Playwright POC"
   - Click **Activate**

## Usage

### Settings Page
Navigate to **Settings → WP Playwright POC** in the WordPress admin to configure:
- Layout type
- Number of columns (for grid layout)
- Maximum items to display
- Star and date visibility
- Color scheme

### Displaying Reviews
Add the shortcode to any page or post:
```
[wppoc_reviews]
```

The plugin includes 8 sample testimonials with varying ratings (3-5 stars) for testing purposes.

## Testing Considerations

This plugin is specifically designed with automated testing in mind:

### Test Attributes
The plugin includes `data-testid` attributes on key elements for reliable selector targeting:
- `wppoc-container` - Main container element
- `wppoc-card` - Individual review cards
- `wppoc-name` - Reviewer names
- `wppoc-date` - Review dates
- `wppoc-stars` - Star rating containers
- `wppoc-text` - Review text content

### Testable Features
- Settings form submission and validation
- Layout switching (grid/list)
- Conditional rendering (stars, dates)
- Shortcode output rendering
- Color customization
- Success notifications

### Example Test Scenarios
1. Activate plugin and verify settings page exists
2. Change layout from grid to list and verify frontend update
3. Toggle star visibility and confirm display change
4. Modify max items and check correct number renders
5. Update colors and verify CSS custom properties
6. Save settings and verify success notice appears

## File Structure

```
wp-playwright-poc/
├── wp-playwright-poc.php    # Main plugin file
├── assets/
│   └── css/
│       └── frontend.css     # Frontend styles
└── README.md                # This file
```

## Technical Details

- **Version:** 1.0.0
- **Requires WordPress:** 5.0+
- **Text Domain:** wp-playwright-poc
- **Settings API:** WordPress Options API
- **Rendering:** Shortcode-based with output buffering

## Development

This plugin uses WordPress coding standards and best practices:
- Proper sanitization and escaping
- Settings API for options storage
- Nonce verification (WordPress core)
- Accessible markup with ARIA labels
- Semantic HTML structure

## License

This is a proof of concept plugin for testing purposes.

## Author

Zach Tirrell
