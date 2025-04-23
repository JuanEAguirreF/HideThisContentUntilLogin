# HideThisContentUntilLogin

A WordPress plugin that provides a custom Gutenberg block to restrict content visibility based on user login status.

## Description

The HideThisContentUntilLogin plugin allows site administrators and editors to partially restrict the visibility of content within posts or pages using a custom Gutenberg block. Users can specify if the content will be shown only to logged-in users or only to anonymous visitors.

## Features

- Custom Gutenberg block "Restricted Content"
- Control content visibility based on user login status
- Support for nested blocks within the restricted content
- Configurable HTML headers and footers for different user types
- Clear visual indicators in the editor for restricted content

## Installation

1. Upload the `hide-this-content-until-login` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin settings under 'Settings > HideThisContentUntilLogin'

## Usage

### Adding Restricted Content

1. Edit a post or page
2. Add the "Restricted Content" block from the Widgets category
3. Select the audience (logged-in users or anonymous visitors) in the block settings sidebar
4. Add any content blocks inside the Restricted Content block
5. Save and publish

### Configuring Headers and Footers

1. Go to 'Settings > HideThisContentUntilLogin'
2. Configure the HTML headers and footers for both logged-in users and anonymous visitors
3. Save changes

## Development

### Prerequisites

- Node.js and npm

### Building the plugin

```bash
# Install dependencies
npm install

# Build the block
npm run build

# Start development mode
npm run start
```

## License

GPL-2.0-or-later