<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Clock It - Attendance Tracker'; ?></title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cards.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/table.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/modal.css">
    
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo BASE_URL . $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="app-navbar">
      <div class="app-navbar-inner">
    <div class="nav-left">
        <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="Clock It Logo" class="logo" />
    </div>

    <div class="nav-right">
        <a href="<?php echo BASE_URL; ?>?page=home" class="nav-link <?php echo ($page ?? '') === 'home' ? 'active' : ''; ?>">
            Dashboard
        </a>
        <a href="<?php echo BASE_URL; ?>?page=timeLog" class="nav-link <?php echo ($page ?? '') === 'timeLog' ? 'active' : ''; ?>">
            Time Log
        </a>
        <a href="<?php echo BASE_URL; ?>?page=history" class="nav-link <?php echo ($page ?? '') === 'history' ? 'active' : ''; ?>">
            History
        </a>

        <!-- 🌙 Moved dark mode button here -->
        <button id="theme-toggle" class="theme-toggle" aria-label="Toggle dark mode" title="Toggle dark mode">
            <i class="fa-solid fa-moon"></i>
            <i class="fa-solid fa-sun"></i>
        </button>

        <button class="logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </button>
    </div>
</div>

        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
       <script src="<?php echo BASE_URL; ?>/assets/js/theme-toggle.js" defer></script>


