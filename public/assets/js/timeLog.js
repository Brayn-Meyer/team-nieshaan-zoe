// State
let allTimeLogData = [];
let filteredTimeLogData = [];
let currentWeek = null;
let activeFilter = null; // 'red', 'green', or null
let currentEmployeeForConfirm = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    generateWeekOptions();
    setCurrentWeek();
});

// Generate week options (13 weeks: current + 12 previous)
function generateWeekOptions() {
    const weekSelect = document.getElementById('weekSelect');
    const weeks = [];
    
    // Generate 13 weeks
    for (let i = 0; i < 13; i++) {
        const date = new Date();
        date.setDate(date.getDate() - (i * 7));
        
        // Get Monday of that week
        const monday = getMonday(date);
        const friday = new Date(monday);
        friday.setDate(monday.getDate() + 4);
        
        weeks.push({
            value: formatDateForAPI(monday),
            label: formatWeekLabel(monday, friday)
        });
    }
    
    // Clear existing options except the first one
    weekSelect.innerHTML = '<option value="">Select Week</option>';
    
    // Add week options
    weeks.forEach(week => {
        const option = document.createElement('option');
        option.value = week.value;
        option.textContent = week.label;
        weekSelect.appendChild(option);
    });
}

// Get Monday of the week
function getMonday(date) {
    const d = new Date(date);
    const day = d.getDay();
    const diff = d.getDate() - day + (day === 0 ? -6 : 1); // Adjust when day is Sunday
    return new Date(d.setDate(diff));
}

// Set current week as selected
function setCurrentWeek() {
    const weekSelect = document.getElementById('weekSelect');
    const currentMonday = getMonday(new Date());
    const formattedDate = formatDateForAPI(currentMonday);
    
    weekSelect.value = formattedDate;
    currentWeek = formattedDate;
    
    loadTimeLogData();
}

// Format date for API (YYYY-MM-DD)
function formatDateForAPI(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// Format week label (e.g., "Week of Oct 27 - Oct 31")
function formatWeekLabel(monday, friday) {
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    const mondayMonth = monthNames[monday.getMonth()];
    const mondayDay = monday.getDate();
    const fridayMonth = monthNames[friday.getMonth()];
    const fridayDay = friday.getDate();
    
    if (monday.getMonth() === friday.getMonth()) {
        return `Week of ${mondayMonth} ${mondayDay} - ${fridayDay}`;
    } else {
        return `Week of ${mondayMonth} ${mondayDay} - ${fridayMonth} ${fridayDay}`;
    }
}

// Load time log data
async function loadTimeLogData() {
    const weekSelect = document.getElementById('weekSelect');
    const selectedWeek = weekSelect.value;
    
    if (!selectedWeek) {
        showToast('Please select a week', 'error');
        return;
    }
    
    currentWeek = selectedWeek;
    
    try {
        showLoadingInTable();
        const data = await ClockInOutAPI.getTimeLogData(selectedWeek);
        console.log('API Response:', data); // Debug log
        allTimeLogData = data.timeLogData || [];
        console.log('allTimeLogData after assignment:', allTimeLogData); // Debug log
        filterTimeLog();
    } catch (error) {
        console.error('Error loading time log:', error);
        showToast('Failed to load time log data', 'error');
        showErrorInTable();
    }
}

// Show loading state in table
function showLoadingInTable() {
    const tbody = document.getElementById('timeLogTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="6" class="text-center text-muted">
                <i class="fa-solid fa-spinner fa-spin"></i> Loading time log...
            </td>
        </tr>
    `;
}

// Show error state in table
function showErrorInTable() {
    const tbody = document.getElementById('timeLogTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="6" class="text-center text-muted">
                Failed to load time log data. Please try again.
            </td>
        </tr>
    `;
}

// Filter time log based on search and color filter
function filterTimeLog() {
    const searchInput = document.getElementById('searchEmployee');
    const searchTerm = searchInput.value.toLowerCase().trim();
    
    // Safety check: ensure allTimeLogData is an array
    if (!Array.isArray(allTimeLogData)) {
        console.error('allTimeLogData is not an array:', allTimeLogData);
        allTimeLogData = [];
    }
    
    let filtered = [...allTimeLogData];
    
    // Apply search filter
    if (searchTerm) {
        filtered = filtered.filter(employee => {
            const fullName = (employee.name || '').toLowerCase();
            const employeeId = employee.id ? employee.id.toString() : '';
            return fullName.includes(searchTerm) || employeeId.includes(searchTerm);
        });
    }
    
    // Apply color filter
    if (activeFilter === 'red') {
        filtered = filtered.filter(employee => employee.indicator === 'red');
    } else if (activeFilter === 'green') {
        filtered = filtered.filter(employee => employee.indicator === 'green');
    }
    
    filteredTimeLogData = filtered;
    renderTimeLog();
}

// Render time log table
function renderTimeLog() {
    const tbody = document.getElementById('timeLogTableBody');
    const noResults = document.getElementById('noResults');
    const mobileCards = document.getElementById('mobileCards'); // 👈 added for mobile

    if (filteredTimeLogData.length === 0) {
        tbody.innerHTML = '';
        noResults.style.display = 'block';
        if (mobileCards) {
            mobileCards.innerHTML = `
                <div class="mobile-card text-center text-muted p-4">
                    No employees found for this week.
                </div>
            `;
        }
        return;
    }

    noResults.style.display = 'none';

    // ✅ DESKTOP TABLE RENDER
    tbody.innerHTML = filteredTimeLogData.map(employee => {
        const fullName = employee.name || 'Unknown';
        const hoursWorked = parseFloat(employee.hoursWorked || 0).toFixed(2);
        const hoursOwed = parseFloat(employee.hoursOwed || 0).toFixed(2);
        const overtime = parseFloat(employee.overtime || 0).toFixed(2);
        const indicator = employee.indicator || 'green';
        const indicatorClass = indicator === 'red' ? 'indicator-red' : 'indicator-green';
        
        const isBalanced = Math.abs(hoursOwed) < 0.01 && overtime > 0;
        const rowStyle = isBalanced ? 'cursor: pointer;' : '';
        const rowOnClick = isBalanced ? `onclick="showConfirmPopup('${escapeHtml(fullName)}', ${employee.id})"` : '';
        
        return `
            <tr style="${rowStyle}" ${rowOnClick}>
                <td>${escapeHtml(fullName)}</td>
                <td>${escapeHtml(employee.id || 'N/A')}</td>
                <td>${hoursOwed}</td>
                <td>${hoursWorked}</td>
                <td>${overtime}</td>
                <td><span class="indicator-circle ${indicatorClass}"></span></td>
            </tr>
        `;
    }).join('');

    // ✅ MOBILE CARDS RENDER
    if (mobileCards) {
        mobileCards.innerHTML = filteredTimeLogData.map(employee => {
            const fullName = employee.name || 'Unknown';
            const hoursWorked = parseFloat(employee.hoursWorked || 0).toFixed(2);
            const hoursOwed = parseFloat(employee.hoursOwed || 0).toFixed(2);
            const overtime = parseFloat(employee.overtime || 0).toFixed(2);
            const indicator = employee.indicator || 'green';
            const indicatorClass = indicator === 'red' ? 'indicator-red' : 'indicator-green';
            
            return `
                <div class="mobile-card">
                    <div class="mobile-card-header">
                        <div>
                            <div class="mobile-card-title">${escapeHtml(fullName)}</div>
                            <div class="mobile-card-subtitle">ID: ${escapeHtml(employee.id || 'N/A')}</div>
                        </div>
                        <span class="indicator-circle ${indicatorClass}"></span>
                    </div>
                    <div class="mobile-card-body">
                        <div><strong>Hours Owed:</strong> ${hoursOwed}</div>
                        <div><strong>Hours Worked:</strong> ${hoursWorked}</div>
                        <div><strong>Overtime:</strong> ${overtime}</div>
                    </div>
                </div>
            `;
        }).join('');
    }
}


// Toggle filter
function toggleFilter(color) {
    const redBtn = document.getElementById('filterRed');
    const greenBtn = document.getElementById('filterGreen');
    
    if (activeFilter === color) {
        // Deactivate filter
        activeFilter = null;
        redBtn.classList.remove('active');
        greenBtn.classList.remove('active');
    } else {
        // Activate filter
        activeFilter = color;
        redBtn.classList.remove('active');
        greenBtn.classList.remove('active');
        
        if (color === 'red') {
            redBtn.classList.add('active');
        } else if (color === 'green') {
            greenBtn.classList.add('active');
        }
    }
    
    filterTimeLog();
}

// Show confirmation popup
function showConfirmPopup(employeeName, employeeId) {
    const popup = document.getElementById('confirmPopup');
    const employeeNameSpan = document.getElementById('popupEmployeeName');
    
    employeeNameSpan.textContent = employeeName;
    currentEmployeeForConfirm = employeeId;
    popup.style.display = 'flex';
}

// Close popup
function closePopup() {
    const popup = document.getElementById('confirmPopup');
    popup.style.display = 'none';
    currentEmployeeForConfirm = null;
}

// Confirm change
async function confirmChange() {
    if (!currentEmployeeForConfirm || !currentWeek) {
        showToast('Missing required information', 'error');
        closePopup();
        return;
    }
    
    try {
        showLoading();
        await ClockInOutAPI.createHoursRecord(currentEmployeeForConfirm, currentWeek);
        showToast('Hours confirmed successfully', 'success');
        closePopup();
        
        // Reload data
        await loadTimeLogData();
    } catch (error) {
        console.error('Error confirming hours:', error);
        showToast('Failed to confirm hours', 'error');
    } finally {
        hideLoading();
    }
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
