<?php
// Timelog.View.php - Complete Time Log System in One File
session_start();

// Dummy data for employees
$employees = [
    ['id' => 1, 'name' => 'John Doe', 'hoursWorked' => 40, 'hoursOwed' => 0, 'overtime' => 0, 'indicator' => 'green', 'is_saved' => true],
    ['id' => 2, 'name' => 'Jane Smith', 'hoursWorked' => 35, 'hoursOwed' => 5, 'overtime' => 0, 'indicator' => 'red', 'is_saved' => false],
    ['id' => 3, 'name' => 'Mike Johnson', 'hoursWorked' => 42, 'hoursOwed' => 0, 'overtime' => 2, 'indicator' => 'green', 'is_saved' => true],
    ['id' => 4, 'name' => 'Sarah Wilson', 'hoursWorked' => 38, 'hoursOwed' => 2, 'overtime' => 0, 'indicator' => 'red', 'is_saved' => false],
];

// Generate week options
function generateWeekOptions() {
    $options = [];
    $today = new DateTime();
    
    for ($i = 12; $i >= 0; $i--) {
        $weekStart = clone $today;
        $dayOfWeek = $today->format('N');
        $daysToSubtract = $dayOfWeek - 1 + ($i * 7);
        $weekStart->modify("-$daysToSubtract days");
        
        $weekEnd = clone $weekStart;
        $weekEnd->modify('+4 days');
        
        $value = $weekStart->format('Y-m-d');
        $startFormatted = $weekStart->format('M j, Y');
        $endFormatted = $weekEnd->format('M j, Y');
        $label = "Week of $startFormatted - $endFormatted";
        
        $options[] = ['value' => $value, 'label' => $label];
    }
    
    return $options;
}

$weekOptions = generateWeekOptions();

// Handle filters and data
$searchQuery = $_GET['search'] ?? '';
$activeFilter = $_GET['filter'] ?? null;
$selectedWeek = $_GET['week'] ?? ($weekOptions[count($weekOptions) - 1]['value'] ?? '');

// Filter employees
$filteredEmployees = $employees;
if (!empty($searchQuery)) {
    $query = strtolower($searchQuery);
    $filteredEmployees = array_filter($employees, function($employee) use ($query) {
        return strpos(strtolower($employee['name']), $query) !== false || 
               strpos((string)$employee['id'], $query) !== false;
    });
}

if (!empty($activeFilter)) {
    $filteredEmployees = array_filter($filteredEmployees, function($employee) use ($activeFilter) {
        return $employee['indicator'] === $activeFilter;
    });
}

// Handle popup
$showPopup = isset($_GET['popup']) && $_GET['popup'] === 'true';
$popupEmployeeId = $_GET['employee_id'] ?? null;
$popupEmployee = null;

if ($popupEmployeeId) {
    foreach ($employees as $employee) {
        if ($employee['id'] == $popupEmployeeId) {
            $popupEmployee = $employee;
            break;
        }
    }
}

// Handle user guide
$showUserGuide = $_GET['show_guide'] ?? false;
$currentStepIndex = intval($_GET['step'] ?? 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Log System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
        }

        .navbar {
            background: #2EB28A;
            padding: 1rem 2rem;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-content {
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .main-content {
            margin-top: 80px;
            padding: 20px;
        }

        /* Filters Styles */
        h1 {
            text-align: center;
            font-style: bold;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        .timelog-filters {
            margin-bottom: 20px;
            display: flex;
            justify-content: center; 
        }

        .filter-section {
            display: flex;
            width: 50%;
            gap: 15px;
            align-items: center;
            padding: 15px;
            background-color: #F8F9FA;
            border-radius: 8px;
            border: 1px solid #E9ECEF;
        }

        .search-container {
            flex: 1;
            min-width: 0;
        }

        .search-input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            height: 40px;
            font-family: 'Poppins', sans-serif;
            background-color: white;
            color: #333;
        }

        .search-input:focus {
            outline: none;
            border-color: #4A90E2;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
        }

        .week-container {
            flex-shrink: 0;
            min-width: 250px;
        }

        .week-select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            height: 40px;
            font-family: 'Poppins', sans-serif;
            background-color: white;
            color: #333;
            cursor: pointer;
        }

        .week-select:focus {
            outline: none;
            border-color: #4A90E2;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
        }

        .filter-buttons {
            display: flex;
            gap: 12px;
            flex-shrink: 0;
        }

        .filter-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .filter-btn.active {
            transform: translateY(0);
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.2);
            border: 2px solid #000000;
        }

        .btn-red {
            background-color: #E74C3C;
        }

        .btn-green {
            background-color: #2ECC71;
        }

        .tooltip {
            position: absolute;
            bottom: -40px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s;
            z-index: 10;
        }

        .tooltip::after {
            content: '';
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            border-width: 0 5px 5px 5px;
            border-style: solid;
            border-color: transparent transparent #333 transparent;
        }

        .filter-btn:hover .tooltip {
            opacity: 1;
            visibility: visible;
        }

        /* Table Styles */
        .table-container {
            margin: 1rem auto;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            max-width: 1200px;
            width: 95%;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 8px;
        }

        .time-log-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Poppins', sans-serif;
            background-color: white;
            min-width: 600px;
        }

        .time-log-table thead {
            background-color: #2EB28A;
            color: white;
        }

        .time-log-table th, .time-log-table td {
            padding: 30px 20px;
            border-bottom: 1px solid #E0E0E0;
        }

        .table-row:hover {
            background-color: #f8f9fa;
        }

        .indicator {
            display: inline-block;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            cursor: pointer;
            transition: transform 0.2s;
            border: 2px solid transparent;
        }

        .indicator:hover {
            transform: scale(1.2);
        }

        .indicator.green {
            background-color: #00C851;
        }

        .indicator.red {
            background-color: #FF4444;
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: #666;
            font-family: 'Poppins', sans-serif;
        }

        /* Popup Styles */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            min-width: 300px;
            max-width: 90%;
            text-align: center;
        }

        .popup-content h3 {
            margin: 0 0 10px 0;
            font-size: 20px;
            font-weight: 600;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }

        .popup-content p {
            margin: 0 0 25px 0;
            font-size: 16px;
            color: #666;
            font-family: 'Poppins', sans-serif;
        }

        .popup-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .popup-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            min-width: 100px;
        }

        .popup-btn-no {
            background-color: #f8f9fa;
            color: #333;
            border: 2px solid #dee2e6;
        }

        .popup-btn-no:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }

        .popup-btn-yes {
            background-color: #2EB28A;
            color: white;
            border: 2px solid #2EB28A;
        }

        .popup-btn-yes:hover {
            background-color: #26997a;
            border-color: #26997a;
        }

        /* Help Button Styles */
        .help-btn {
            position: fixed;
            top: 100px;
            right: 30px;
            background: #10b981;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .help-btn:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }

        .help-btn i {
            font-size: 16px;
        }

        /* User Guide Styles */
        .time-log-guide-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            pointer-events: none;
        }

        .highlight-overlay {
            position: absolute;
            border: 3px solid #10b981;
            border-radius: 12px;
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.7);
            pointer-events: none;
            transition: all 0.4s ease;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { border-color: #10b981; }
            50% { border-color: #34d399; }
            100% { border-color: #10b981; }
        }

        .guide-content {
            position: absolute;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            min-width: 350px;
            max-width: 450px;
            z-index: 10001;
            pointer-events: all;
            border: 1px solid #e2e8f0;
        }

        .guide-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 25px 0 25px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 15px;
        }

        .guide-header h3 {
            margin: 0;
            color: #065f46;
            font-size: 1.3em;
            font-weight: 600;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5em;
            cursor: pointer;
            color: #718096;
            padding: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            background: #f0fdf4;
        }

        .guide-body {
            padding: 20px 25px;
        }

        .guide-body p {
            margin: 0 0 15px 0;
            line-height: 1.6;
            color: #374151;
            font-size: 1em;
        }

        .step-indicator {
            font-size: 0.9em;
            color: #059669;
            font-weight: 500;
            text-align: center;
            padding: 8px 0;
            border-top: 1px solid #e2e8f0;
        }

        .guide-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 25px;
            border-top: 1px solid #e2e8f0;
        }

        .btn-primary {
            background: #10b981;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #d1fae5;
            color: #065f46;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #a7f3d0;
            color: #064e3b;
        }

        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .filter-section {
                width: 95%;
                gap: 12px;
            }
            
            .week-container {
                min-width: 220px;
            }
        }

        @media (max-width: 768px) {
            .filter-section {
                width: 95%;
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }
            
            .search-container {
                width: 100%;
                min-width: auto;
            }
            
            .week-container {
                width: 100%;
                min-width: auto;
            }
            
            .week-select {
                width: 100%;
            }
            
            .filter-buttons {
                justify-content: center;
                width: 100%;
            }

            .table-container {
                margin: 1rem;
                margin-bottom: 1rem;
            }
            
            .time-log-table {
                min-width: 100%;
            }
            
            .time-log-table thead {
                display: none;
            }
            
            .time-log-table tbody tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid #E0E0E0;
                border-radius: 8px;
                padding: 10px;
                background: white;
            }
            
            .time-log-table tbody tr td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 15px;
                border-bottom: 1px solid #f0f0f0;
                text-align: right;
            }
            
            .time-log-table tbody tr td:last-child {
                border-bottom: none;
            }
            
            .time-log-table tbody tr td::before {
                content: attr(data-label);
                font-weight: 600;
                text-align: left;
                color: #333;
            }
            
            .time-log-table tbody tr td:last-child {
                justify-content: flex-end;
                gap: 10px;
            }
            
            .time-log-table tbody tr td:last-child::before {
                content: "Indicator";
            }

            .help-btn {
                top: 80px;
                right: 20px;
                padding: 10px 16px;
                font-size: 0.9em;
            }
        }

        @media (max-width: 480px) {
            .filter-section {
                width: 100%;
                margin: 0 10px;
                padding: 12px;
            }
            
            h1 {
                font-size: 1.5rem;
                padding: 0 10px;
            }
            
            .search-input,
            .week-select {
                font-size: 16px;
                height: 44px;
            }
            
            .week-container {
                min-width: auto;
            }
            
            .table-container {
                margin: 0.5rem;
                margin-bottom: 1rem;
            }
            
            .time-log-table tbody tr td {
                padding: 8px 12px;
                font-size: 14px;
            }
            
            .time-log-table tbody tr {
                margin-bottom: 10px;
                padding: 8px;
            }
            
            .popup-container {
                padding: 20px;
                min-width: 250px;
            }
            
            .popup-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .popup-btn {
                width: 100%;
            }

            .guide-content {
                min-width: 300px;
                max-width: 350px;
                margin: 0 20px;
            }
            
            .guide-header,
            .guide-body,
            .guide-footer {
                padding: 15px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-content">
            ⏱️ Time Log System
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Filters Section -->
        <div class="timelog-filters">
            <h1>Weekly Time Log</h1>
            <br>
            <form method="GET" class="filter-section">
                <div class="search-container">
                    <input
                        type="text"
                        name="search"
                        value="<?php echo htmlspecialchars($searchQuery); ?>"
                        placeholder="Search Employee..."
                        class="search-input"
                        onchange="this.form.submit()"
                    />
                </div>
                <div class="week-container">
                    <select
                        name="week"
                        class="week-select"
                        onchange="this.form.submit()"
                    >
                        <option value="">Select Week</option>
                        <?php foreach ($weekOptions as $week): ?>
                            <option value="<?php echo $week['value']; ?>" 
                                <?php echo $selectedWeek === $week['value'] ? 'selected' : ''; ?>>
                                <?php echo $week['label']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-buttons">
                    <button
                        type="submit"
                        name="filter"
                        value="<?php echo $activeFilter === 'red' ? '' : 'red'; ?>"
                        class="filter-btn btn-red <?php echo $activeFilter === 'red' ? 'active' : ''; ?>"
                    >
                        <span class="tooltip">Hours Owed</span>
                    </button>
                    <button
                        type="submit"
                        name="filter"
                        value="<?php echo $activeFilter === 'green' ? '' : 'green'; ?>"
                        class="filter-btn btn-green <?php echo $activeFilter === 'green' ? 'active' : ''; ?>"
                    >
                        <span class="tooltip">Hours Worked</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Button -->
        <button onclick="showUserGuide()" class="help-btn">
            <i class="fa-solid fa-circle-question"></i>
            Help Guide
        </button>
        
        <!-- Table Section -->
        <div class="table-container">
            <div class="table-wrapper">
                <table class="time-log-table">
                    <thead class="th">
                        <tr>
                            <th>Employee Name</th>
                            <th>Employee ID</th>
                            <th>Hours Worked</th>
                            <th>Hours Owed</th>
                            <th>Overtime</th>
                            <th>Indicator</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($filteredEmployees)): ?>
                            <tr>
                                <td colspan="6" class="no-results">
                                    <p>No employees found matching your criteria.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($filteredEmployees as $employee): ?>
                                <tr class="table-row">
                                    <td data-label="Employee Name"><?php echo htmlspecialchars($employee['name']); ?></td>
                                    <td data-label="Employee ID"><?php echo htmlspecialchars($employee['id']); ?></td>
                                    <td data-label="Hours Worked"><?php echo htmlspecialchars($employee['hoursWorked']); ?></td>
                                    <td data-label="Hours Owed"><?php echo $employee['hoursOwed'] > 0 ? $employee['hoursOwed'] . 'h' : '-'; ?></td>
                                    <td data-label="Overtime"><?php echo $employee['overtime'] > 0 ? $employee['overtime'] . 'h' : '-'; ?></td>
                                    <td data-label="Indicator">
                                        <span
                                            class="indicator <?php echo $employee['indicator']; ?>"
                                            onclick="handleIndicatorClick(<?php echo $employee['id']; ?>, '<?php echo $employee['name']; ?>', '<?php echo $employee['indicator']; ?>', <?php echo $employee['is_saved'] ? 'true' : 'false'; ?>)"
                                        ></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($showPopup && $popupEmployee): ?>
                <div class="popup-overlay">
                    <div class="popup-container">
                        <div class="popup-content">
                            <h3>Hours are balanced.</h3>
                            <p>Confirm changes for <?php echo htmlspecialchars($popupEmployee['name']); ?>?</p>
                            <div class="popup-buttons">
                                <a href="?" class="popup-btn popup-btn-no">No</a>
                                <a href="?employee_id=<?php echo $popupEmployee['id']; ?>&action=confirm" class="popup-btn popup-btn-yes">Yes</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- User Guide -->
        <?php if ($showUserGuide): ?>
            <?php
            $guideSteps = [
                [
                    'title' => "Time Log Table Overview",
                    'content' => "Welcome to the Time Log Table! This page provides a comprehensive view of employee work hours, overtime, and performance indicators.",
                    'highlight' => ['top' => '0', 'left' => '0', 'width' => '100%', 'height' => '100%'],
                    'position' => ['top' => '50%', 'left' => '50%', 'transform' => 'translate(-50%, -50%)']
                ],
                [
                    'title' => "Understanding Indicators",
                    'content' => "Green: Normal hours (45h), Yellow: Some overtime (42h + 3h OT), Red: Hours owed (40h + 5h owed) - monitor closely.",
                    'highlight' => ['top' => '120px', 'left' => '1rem', 'width' => 'calc(100% - 2rem)', 'height' => '200px'],
                    'position' => ['top' => '350px', 'left' => '50%', 'transform' => 'translateX(-50%)']
                ]
            ];

            $currentStep = $guideSteps[$currentStepIndex] ?? $guideSteps[0];
            ?>
            <div class="time-log-guide-overlay">
                <!-- Highlight Overlay -->
                <div class="highlight-overlay" style="
                    top: <?php echo $currentStep['highlight']['top']; ?>;
                    left: <?php echo $currentStep['highlight']['left']; ?>;
                    width: <?php echo $currentStep['highlight']['width']; ?>;
                    height: <?php echo $currentStep['highlight']['height']; ?>;
                "></div>
                
                <!-- Guide Content -->
                <div class="guide-content" style="
                    top: <?php echo $currentStep['position']['top']; ?>;
                    left: <?php echo $currentStep['position']['left']; ?>;
                    transform: <?php echo $currentStep['position']['transform']; ?>;
                ">
                    <div class="guide-header">
                        <h3><?php echo $currentStep['title']; ?></h3>
                        <button onclick="closeGuide()" class="close-btn">&times;</button>
                    </div>
                    <div class="guide-body">
                        <p><?php echo $currentStep['content']; ?></p>
                        <div class="step-indicator">
                            Step <?php echo $currentStepIndex + 1; ?> of <?php echo count($guideSteps); ?>
                        </div>
                    </div>
                    <div class="guide-footer">
                        <?php if ($currentStepIndex > 0): ?>
                            <a href="?show_guide=1&step=<?php echo $currentStepIndex - 1; ?>" class="btn-secondary">Back</a>
                        <?php endif; ?>
                        <?php if ($currentStepIndex < count($guideSteps) - 1): ?>
                            <a href="?show_guide=1&step=<?php echo $currentStepIndex + 1; ?>" class="btn-primary">Next</a>
                        <?php else: ?>
                            <a href="?show_guide=0" class="btn-primary">Finish</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function handleIndicatorClick(employeeId, employeeName, indicator, isSaved) {
            if (indicator === 'red' && !isSaved) {
                window.location.href = `?popup=true&employee_id=${employeeId}<?php echo !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : ''; ?><?php echo !empty($activeFilter) ? '&filter=' . urlencode($activeFilter) : ''; ?><?php echo !empty($selectedWeek) ? '&week=' . urlencode($selectedWeek) : ''; ?>`;
            }
        }

        function showUserGuide() {
            window.location.href = '?show_guide=1<?php echo !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : ''; ?><?php echo !empty($activeFilter) ? '&filter=' . urlencode($activeFilter) : ''; ?><?php echo !empty($selectedWeek) ? '&week=' . urlencode($selectedWeek) : ''; ?>';
        }

        function closeGuide() {
            window.location.href = '?show_guide=0<?php echo !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : ''; ?><?php echo !empty($activeFilter) ? '&filter=' . urlencode($activeFilter) : ''; ?><?php echo !empty($selectedWeek) ? '&week=' . urlencode($selectedWeek) : ''; ?>';
        }

        document.addEventListener('click', function(event) {
            const popup = document.querySelector('.popup-overlay');
            if (event.target === popup) {
                window.location.href = '?<?php echo !empty($searchQuery) ? 'search=' . urlencode($searchQuery) . '&' : ''; ?><?php echo !empty($activeFilter) ? 'filter=' . urlencode($activeFilter) . '&' : ''; ?><?php echo !empty($selectedWeek) ? 'week=' . urlencode($selectedWeek) : ''; ?>';
            }
        });
    </script>
</body>
</html>