<?php
require_once __DIR__ . '/../../includes/config.php';

$pageTitle = 'Time Log - Clock It';
$currentPage = 'timeLog';
$additionalJS = [];
$additionalCSS = ['/assets/css/timeLog.css'];

require_once __DIR__ . '/../../includes/header.php';

?>

<h1>Weekly Time Log</h1>
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

<!-- Mobile Cards View -->
<div id="mobileCards" class="mobile-cards" style="display: none;">
    <div class="mobile-card">
        <div class="mobile-card-header">
            <div>
                <div class="mobile-card-title">Loading employees...</div>
            </div>
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

<script>
let timeLogData = [];
let currentFilters = {
    search: '',
    red: false,
    green: false
};

document.addEventListener('DOMContentLoaded', function() {
    loadWeekOptions();
    loadTimeLogData();
    checkViewport(); 
});


function loadWeekOptions() {
    const weekSelect = document.getElementById('weekSelect');
    
    
    while (weekSelect.options.length > 1) {
        weekSelect.remove(1);
    }
    
  
    for (let i = 0; i < 8; i++) {
        const date = new Date();
        date.setDate(date.getDate() - i * 7);
        
        const startOfWeek = new Date(date);
        startOfWeek.setDate(date.getDate() - date.getDay());
        
        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6);
        
        const option = document.createElement('option');
        option.value = startOfWeek.toISOString().split('T')[0];
        option.textContent = `Week of ${formatDate(startOfWeek)} to ${formatDate(endOfWeek)}`;
        
        if (i === 0) {
            option.selected = true;
        }
        
        weekSelect.appendChild(option);
    }
}

function formatDate(date) {
    return date.toLocaleDateString('en-US', {
        month: '2-digit',
        day: '2-digit',
        year: 'numeric'
    });
}

function loadTimeLogData() {
    const weekSelect = document.getElementById('weekSelect');
    const selectedWeek = weekSelect.value;
    
    document.getElementById('timeLogTableBody').innerHTML = `
        <tr>
            <td colspan="6" class="text-center text-muted">
                <i class="fa-solid fa-spinner fa-spin"></i> Loading time log...
            </td>
        </tr>
    `;
    
    document.getElementById('mobileCards').innerHTML = `
        <div class="mobile-card">
            <div class="mobile-card-header">
                <div>
                    <div class="mobile-card-title">Loading employees...</div>
                </div>
            </div>
        </div>
    `;
    
    setTimeout(() => {
        timeLogData = [
            { id: 1, name: 'John Smith', employeeId: 'EMP001', hoursWorked: 40, hoursOwed: 0, overtime: 2, status: 'green' },
            { id: 2, name: 'Jane Doe', employeeId: 'EMP002', hoursWorked: 35, hoursOwed: 5, overtime: 0, status: 'red' },
            { id: 3, name: 'Mike Johnson', employeeId: 'EMP003', hoursWorked: 42, hoursOwed: 0, overtime: 2, status: 'green' },
            { id: 4, name: 'Sarah Wilson', employeeId: 'EMP004', hoursWorked: 38, hoursOwed: 2, overtime: 0, status: 'red' },
            { id: 5, name: 'David Brown', employeeId: 'EMP005', hoursWorked: 40, hoursOwed: 0, overtime: 0, status: 'green' }
        ];
        
        updateTimeLogViews(timeLogData);
    }, 1000);
}

function updateTimeLogViews(data) {
    updateTableView(data);
    updateMobileCardsView(data);
    updateNoResultsMessage(data);
}

function updateTableView(data) {
    const tableBody = document.getElementById('timeLogTableBody');
    
    if (data.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted">
                    No employees found matching your criteria.
                </td>
            </tr>
        `;
        return;
    }
    
    tableBody.innerHTML = data.map(employee => `
        <tr>
            <td>${employee.name}</td>
            <td>${employee.employeeId}</td>
            <td>${employee.hoursWorked}</td>
            <td>${employee.hoursOwed}</td>
            <td>${employee.overtime}</td>
            <td>
                <span class="indicator-circle ${employee.status === 'red' ? 'indicator-red' : 'indicator-green'}"></span>
            </td>
        </tr>
    `).join('');
}

function updateMobileCardsView(data) {
    const mobileCards = document.getElementById('mobileCards');
    
    if (data.length === 0) {
        mobileCards.innerHTML = `
            <div class="no-results">
                <p>No employees found matching your criteria.</p>
            </div>
        `;
        return;
    }
    
    mobileCards.innerHTML = data.map(employee => `
        <div class="mobile-card">
            <div class="mobile-card-header">
                <div>
                    <div class="mobile-card-title">${employee.name}</div>
                    <div class="mobile-card-id">ID: ${employee.employeeId}</div>
                </div>
                <div class="mobile-card-status">
                    <span class="indicator-circle ${employee.status === 'red' ? 'indicator-red' : 'indicator-green'}"></span>
                    <span>${employee.status === 'red' ? 'Hours Owed' : 'Hours Worked'}</span>
                </div>
            </div>
            <div class="mobile-card-details">
                <div class="mobile-card-detail">
                    <span class="mobile-card-label">Hours Worked</span>
                    <span class="mobile-card-value">${employee.hoursWorked}</span>
                </div>
                <div class="mobile-card-detail">
                    <span class="mobile-card-label">Hours Owed</span>
                    <span class="mobile-card-value">${employee.hoursOwed}</span>
                </div>
                <div class="mobile-card-detail">
                    <span class="mobile-card-label">Overtime</span>
                    <span class="mobile-card-value">${employee.overtime}</span>
                </div>
                <div class="mobile-card-detail">
                    <span class="mobile-card-label">Status</span>
                    <div class="mobile-indicator">
                        <span class="indicator-circle ${employee.status === 'red' ? 'indicator-red' : 'indicator-green'}"></span>
                        <span class="mobile-card-value">${employee.status === 'red' ? 'Owed' : 'Complete'}</span>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function updateNoResultsMessage(data) {
    const noResults = document.getElementById('noResults');
    if (data.length === 0) {
        noResults.style.display = 'block';
    } else {
        noResults.style.display = 'none';
    }
}

function filterTimeLog() {
    currentFilters.search = document.getElementById('searchEmployee').value.toLowerCase();
    applyFilters();
}

function toggleFilter(color) {
    const button = document.getElementById(`filter${color.charAt(0).toUpperCase() + color.slice(1)}`);
    
    if (color === 'red') {
        currentFilters.red = !currentFilters.red;
        currentFilters.green = currentFilters.red ? false : currentFilters.green;
    } else {
        currentFilters.green = !currentFilters.green;
        currentFilters.red = currentFilters.green ? false : currentFilters.red;
    }
    
    button.classList.toggle('active');
    
    const otherButton = document.getElementById(`filter${color === 'red' ? 'Green' : 'Red'}`);
    otherButton.classList.remove('active');
    
    applyFilters();
}

function applyFilters() {
    let filteredData = timeLogData;
    
    if (currentFilters.search) {
        filteredData = filteredData.filter(employee => 
            employee.name.toLowerCase().includes(currentFilters.search) ||
            employee.employeeId.toLowerCase().includes(currentFilters.search)
        );
    }

    if (currentFilters.red) {
        filteredData = filteredData.filter(employee => employee.status === 'red');
    } else if (currentFilters.green) {
        filteredData = filteredData.filter(employee => employee.status === 'green');
    }
    
    updateTimeLogViews(filteredData);
}

function checkViewport() {
    const tableContainer = document.querySelector('.table-container');
    const mobileCards = document.getElementById('mobileCards');
    
    if (window.innerWidth <= 768) {
        tableContainer.style.display = 'none';
        mobileCards.style.display = 'block';
    } else {
        tableContainer.style.display = 'block';
        mobileCards.style.display = 'none';
    }
}

window.addEventListener('resize', checkViewport);

function showPopup(employeeName) {
    document.getElementById('popupEmployeeName').textContent = employeeName;
    document.getElementById('confirmPopup').style.display = 'flex';
}

function closePopup() {
    document.getElementById('confirmPopup').style.display = 'none';
}

function confirmChange() {
    console.log('Changes confirmed');
    closePopup();
}
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>