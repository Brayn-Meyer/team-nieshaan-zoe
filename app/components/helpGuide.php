<?php
/**
 * Help Guide Component
 * Renders a floating help button that triggers the interactive user guide
 * 
 * Usage:
 * Include this component in any view where you want the help guide available
 * Make sure to include the required CSS and JS files in the page's header:
 * - CSS: /assets/css/userGuide.css
 * - JS: /assets/js/userGuide.js
 * 
 * Example:
 * $additionalCSS = ['/assets/css/userGuide.css'];
 * $additionalJS = ['/assets/js/userGuide.js'];
 * require_once __DIR__ . '/../components/helpGuide.php';
 */
?>

<!-- Help Button -->
<button onclick="showUserGuide()" class="help-btn" aria-label="Open help guide" title="Help Guide">
    <i class="fa-solid fa-circle-question"></i>
    <span>Help Guide</span>
</button>
