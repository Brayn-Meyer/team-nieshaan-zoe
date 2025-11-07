<?php
// TimeLog.php
$employees = [
    ['id' => 1, 'name' => 'John Doe', 'hoursWorked' => 40, 'hoursOwed' => 0, 'overtime' => 0, 'indicator' => 'green', 'is_saved' => true],
    ['id' => 2, 'name' => 'Jane Smith', 'hoursWorked' => 35, 'hoursOwed' => 5, 'overtime' => 0, 'indicator' => 'red', 'is_saved' => false],
    ['id' => 3, 'name' => 'Mike Johnson', 'hoursWorked' => 42, 'hoursOwed' => 0, 'overtime' => 2, 'indicator' => 'green', 'is_saved' => true],
    ['id' => 4, 'name' => 'Sarah Wilson', 'hoursWorked' => 38, 'hoursOwed' => 2, 'overtime' => 0, 'indicator' => 'red', 'is_saved' => false],
];

$filterData = [
    'search' => $_GET['search'] ?? '',
    'filter' => $_GET['filter'] ?? null,
    'week' => $_GET['week'] ?? null
];

$filteredEmployees = $employees;
if (!empty($filterData['search'])) {
    $query = strtolower($filterData['search']);
    $filteredEmployees = array_filter($employees, function($employee) use ($query) {
        return strpos(strtolower($employee['name']), $query) !== false || 
               strpos((string)$employee['id'], $query) !== false;
    });
}

if (!empty($filterData['filter'])) {
    $filteredEmployees = array_filter($filteredEmployees, function($employee) use ($filterData) {
        return $employee['indicator'] === $filterData['filter'];
    });
}

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Time Log</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            color: #1e293b;
            font-size: 28px;
            font-weight: 700;
        }

        .filters {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            min-width: 250px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 20px 12px 45px;
            border: 1px solid #cbd5e1;
            border-radius: 50px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: white;
        }

        .search-box input:focus {
            outline: none;
            border-color: #2EB28A;
            box-shadow: 0 0 0 3px rgba(46, 178, 138, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        .filter-select {
            padding: 12px 20px;
            border: 1px solid #cbd5e1;
            border-radius: 50px;
            font-size: 16px;
            background-color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: #2EB28A;
            box-shadow: 0 0 0 3px rgba(46, 178, 138, 0.1);
        }

        .table-container {
            background-color: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .time-log-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 600px;
        }

        .time-log-table thead {
            background-color: #2EB28A;
            color: white;
        }

        .time-log-table th {
            padding: 20px;
            text-align: left;
            font-weight: 600;
            font-size: 16px;
        }

        .time-log-table th:first-child {
            border-top-left-radius: 16px;
        }

        .time-log-table th:last-child {
            border-top-right-radius: 16px;
        }

        .time-log-table td {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .time-log-table tbody tr:last-child td {
            border-bottom: none;
        }

        .time-log-table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 16px;
        }

        .time-log-table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 16px;
        }

        .table-row:hover {
            background-color: #f8fafc;
        }

        .indicator {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            cursor: pointer;
            transition: transform 0.2s;
            border: 2px solid transparent;
        }

        .indicator:hover {
            transform: scale(1.2);
        }

        .indicator.green {
            background-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        .indicator.red {
            background-color: #ef4444;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .no-results i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #cbd5e1;
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
            padding: 20px;
        }

        .popup-container {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .popup-content h3 {
            margin: 0 0 15px 0;
            font-size: 22px;
            font-weight: 600;
            color: #1e293b;
        }

        .popup-content p {
            margin: 0 0 25px 0;
            font-size: 16px;
            color: #64748b;
        }

        .popup-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .popup-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 100px;
            text-decoration: none;
            display: inline-block;
        }

        .popup-btn-no {
            background-color: #f8fafc;
            color: #475569;
            border: 2px solid #cbd5e1;
        }

        .popup-btn-no:hover {
            background-color: #e2e8f0;
            border-color: #94a3b8;
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
            bottom: 30px;
            right: 30px;
            background: #10b981;
            color: white;
            border: none;
            padding: 14px 22px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            z-index: 100;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .help-btn:hover {
            background: #059669;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        }

        /* Mobile Responsive Styles */
        @media (max-width: 1024px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .filters {
                width: 100%;
            }
            
            .search-box {
                flex-grow: 1;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .search-box {
                min-width: 200px;
            }
            
            .time-log-table {
                min-width: 100%;
            }
            
            .time-log-table thead {
                display: none;
            }
            
            .time-log-table tbody tr {
                display: block;
                margin-bottom: 20px;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 0;
                background: white;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            }
            
            .time-log-table tbody tr td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 15px;
                border-bottom: 1px solid #f1f5f9;
                text-align: right;
            }
            
            .time-log-table tbody tr td:last-child {
                border-bottom: none;
            }
            
            .time-log-table tbody tr td::before {
                content: attr(data-label);
                font-weight: 600;
                text-align: left;
                color: #475569;
            }
            
            .time-log-table tbody tr td:last-child {
                justify-content: flex-end;
                gap: 10px;
            }
            
            .time-log-table tbody tr td:last-child::before {
                content: "Indicator";
            }
            
            .popup-container {
                padding: 25px;
            }
            
            .popup-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .popup-btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .header h1 {
                font-size: 22px;
            }
            
            .filters {
                flex-direction: column;
                width: 100%;
            }
            
            .search-box, .filter-select {
                width: 100%;
            }
            
            .table-container {
                border-radius: 12px;
            }
            
            .time-log-table tbody tr td {
                padding: 12px;
                font-size: 15px;
            }
            
            .time-log-table tbody tr {
                margin-bottom: 15px;
            }
            
            .help-btn {
                bottom: 20px;
                right: 20px;
                padding: 12px 18px;
                font-size: 14px;
            }
        }

        @media (max-width: 360px) {
            .time-log-table tbody tr td {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .time-log-table tbody tr td::before {
                font-size: 14px;
            }
            
            .time-log-table tbody tr td:last-child {
                flex-direction: row;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Employee Time Log</h1>
            <div class="filters">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search employees..." value="<?php echo htmlspecialchars($filterData['search']); ?>">
                </div>
                <select class="filter-select">
                    <option value="">All Indicators</option>
                    <option value="green" <?php echo $filterData['filter'] === 'green' ? 'selected' : ''; ?>>Green</option>
                    <option value="red" <?php echo $filterData['filter'] === 'red' ? 'selected' : ''; ?>>Red</option>
                </select>
            </div>
        </div>

        <div class="table-container">
            <div class="table-wrapper">
                <table class="time-log-table">
                    <thead>
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
                                    <i class="fas fa-search"></i>
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

    <button onclick="showUserGuide()" class="help-btn">
        <i class="fa-solid fa-circle-question"></i>
        Help Guide
    </button>

    <script>
        function handleIndicatorClick(employeeId, employeeName, indicator, isSaved) {
            if (indicator === 'red' && !isSaved) {
                window.location.href = `?popup=true&employee_id=${employeeId}<?php echo !empty($filterData['search']) ? '&search=' . urlencode($filterData['search']) : ''; ?><?php echo !empty($filterData['filter']) ? '&filter=' . urlencode($filterData['filter']) : ''; ?><?php echo !empty($filterData['week']) ? '&week=' . urlencode($filterData['week']) : ''; ?>`;
            }
        }

        function showUserGuide() {
            alert('User Guide:\n\n• Green indicators show employees with balanced hours\n• Red indicators show employees who owe hours\n• Click red indicators to balance hours\n• Use filters to search and filter employees');
        }

        document.addEventListener('click', function(event) {
            const popup = document.querySelector('.popup-overlay');
            if (event.target === popup) {
                window.location.href = '?<?php echo !empty($filterData['search']) ? 'search=' . urlencode($filterData['search']) . '&' : ''; ?><?php echo !empty($filterData['filter']) ? 'filter=' . urlencode($filterData['filter']) . '&' : ''; ?><?php echo !empty($filterData['week']) ? 'week=' . urlencode($filterData['week']) : ''; ?>';
            }
        });

        // Add interactivity to search and filter
        document.querySelector('.search-box input').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                const searchValue = this.value;
                const filterValue = document.querySelector('.filter-select').value;
                let url = '?';
                
                if (searchValue) {
                    url += `search=${encodeURIComponent(searchValue)}&`;
                }
                
                if (filterValue) {
                    url += `filter=${encodeURIComponent(filterValue)}&`;
                }
                
                window.location.href = url.slice(0, -1); // Remove trailing & or ?
            }
        });

        document.querySelector('.filter-select').addEventListener('change', function() {
            const searchValue = document.querySelector('.search-box input').value;
            const filterValue = this.value;
            let url = '?';
            
            if (searchValue) {
                url += `search=${encodeURIComponent(searchValue)}&`;
            }
            
            if (filterValue) {
                url += `filter=${encodeURIComponent(filterValue)}&`;
            }
            
            window.location.href = url.slice(0, -1); // Remove trailing & or ?
        });
    </script>
</body>
</html>