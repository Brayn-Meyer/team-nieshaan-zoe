<?php
/**
 * Simple front controller for the app.
 * Loads configuration and maps a ?page=... query to the corresponding view.
 * This keeps asset paths and includes consistent and avoids exposing raw file redirects.
 */

require_once __DIR__ . '/../includes/config.php';

// Determine requested page (default = home)
$page = $_GET['page'] ?? 'home';

// Allow a small whitelist of pages to avoid arbitrary file includes
switch ($page) {
	case 'timeLog':
		require_once __DIR__ . '/../app/views/timeLog.php';
		break;
	case 'history':
		require_once __DIR__ . '/../app/views/history.php';
		break;
	case 'userGuide':
		require_once __DIR__ . '/../app/views/userGuide.php';
		break;
	case 'home':
	default:
		require_once __DIR__ . '/../app/views/home.php';
		break;
}

// End of front controller
