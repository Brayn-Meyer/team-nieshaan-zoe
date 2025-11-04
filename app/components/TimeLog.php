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

<div>
    <button onclick="showUserGuide()" class="help-btn">
        <i class="fa-solid fa-circle-question"></i>
        Help Guide
    </button>
    
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
</div>

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
</script>

<style>
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

/* Mobile Responsive Styles */
@media (max-width: 768px) {
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
}

@media (max-width: 480px) {
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

@media (max-width: 576px) {
    .help-btn {
        top: 80px;
        right: 20px;
        padding: 10px 16px;
        font-size: 0.9em;
    }
}
</style>