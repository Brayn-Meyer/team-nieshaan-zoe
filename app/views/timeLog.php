<?php
require_once __DIR__ . '/../../includes/config.php';

$pageTitle = 'Time Log - Clock It';
$currentPage = 'timeLog';
$additionalJS = ['/assets/js/timeLog.js'];
$additionalCSS = ['/assets/css/timeLog.css'];

require_once __DIR__ . '/../../includes/header.php';
?>

<h1 style="text-align: center; font-weight: bold; font-family: 'Segoe UI', sans-serif; color: #333; margin-top: 80px;">Weekly Time Log</h1>
<br>

<!-- Filters -->
<div class="timelog-filters">
    <div class="filter-section">
        <div class="search-container">
            <input
                type="text"
                id="searchEmployee"
                placeholder="Search Employee..."
                class="search-input"
                oninput="filterTimeLog()"
            />
        </div>
        <div class="week-container">
            <select id="weekSelect" class="week-select" onchange="loadTimeLogData()">
                <option value="">Select Week</option>
            </select>
        </div>
        <div class="filter-buttons">
            <button
                class="filter-btn btn-red"
                id="filterRed"
                onclick="toggleFilter('red')"
                title="Hours Owed"
            >
                <span class="tooltip">Hours Owed</span>
            </button>
            <button
                class="filter-btn btn-green"
                id="filterGreen"
                onclick="toggleFilter('green')"
                title="Hours Worked"
            >
                <span class="tooltip">Hours Worked</span>
            </button>
        </div>
    </div>
</div>

<!-- Time Log Table -->
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
            <tbody id="timeLogTableBody">
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        <i class="fa-solid fa-spinner fa-spin"></i> Loading time log...
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div id="noResults" class="no-results" style="display: none;">
            <p>No employees found matching your criteria.</p>
        </div>
    </div>
</div>

<!-- Confirmation Popup -->
<div id="confirmPopup" class="popup-overlay" style="display: none;">
    <div class="popup-container">
        <div class="popup-content">
            <h3>Hours are balanced.</h3>
            <p id="popupMessage">Confirm changes for <span id="popupEmployeeName"></span>?</p>
            <div class="popup-buttons">
                <button class="popup-btn popup-btn-no" onclick="closePopup()">No</button>
                <button class="popup-btn popup-btn-yes" onclick="confirmChange()">Yes</button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
