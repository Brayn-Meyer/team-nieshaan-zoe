// State
let allRecords = [];
let filteredRecords = [];
let currentPage = 1;
let pageSize = 10;
let activeFilters = {};

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updatePageSize();
    window.addEventListener('resize', updatePageSize);
    loadHistoryData();
});

// Update page size based on screen width
function updatePageSize() {
    pageSize = window.innerWidth < 768 ? 5 : 10;
    if (filteredRecords.length > 0) {
        renderHistory();
    }
}

// Load history data
async function loadHistoryData() {
    try {
        showLoadingState();
        const data = await HistoryAPI.getAllRecords();
        
        // Extract records array from response
        const records = data.records || [];
        
        // Filter out records with null work_date
        allRecords = records.filter(record => record.date !== null && record.date !== undefined);
        filteredRecords = [...allRecords];
        
        currentPage = 1;
        renderHistory();
    } catch (error) {
        console.error('Error loading history:', error);
        showToast('Failed to load attendance history', 'error');
        showErrorState();
    }
}

// Show loading state
function showLoadingState() {
    const mobileCards = document.getElementById('mobileCards');
    const tableBody = document.getElementById('historyTableBody');
    
    mobileCards.innerHTML = `
        <div class="text-center text-muted py-4">
            <i class="fa-solid fa-spinner fa-spin"></i> Loading records...
        </div>
    `;
    
    tableBody.innerHTML = `
        <tr>
            <td colspan="9" class="text-center text-muted">
                <i class="fa-solid fa-spinner fa-spin"></i> Loading records...
            </td>
        </tr>
    `;
}

// Show error state
function showErrorState() {
    const mobileCards = document.getElementById('mobileCards');
    const tableBody = document.getElementById('historyTableBody');
    
    mobileCards.innerHTML = `
        <div class="text-center text-muted py-4">
            Failed to load records. Please try again.
        </div>
    `;
    
    tableBody.innerHTML = `
        <tr>
            <td colspan="9" class="text-center text-muted">
                Failed to load records. Please try again.
            </td>
        </tr>
    `;
}

// Apply filters
function applyFilters() {
    const filterDate = document.getElementById('filterDate').value;
    const filterName = document.getElementById('filterName').value.toLowerCase().trim();
    
    // Build active filters object
    activeFilters = {};
    if (filterDate) {
        activeFilters.date = filterDate;
    }
    if (filterName) {
        activeFilters.name = filterName;
    }
    
    // Apply filters to data
    filteredRecords = allRecords.filter(record => {
        // Date filter
        if (filterDate && record.date !== filterDate) {
            return false;
        }
        
        // Name filter (first name or last name)
        if (filterName) {
            const firstName = (record.first_name || '').toLowerCase();
            const lastName = (record.last_name || '').toLowerCase();
            const fullName = `${firstName} ${lastName}`;
            
            if (!firstName.includes(filterName) && 
                !lastName.includes(filterName) && 
                !fullName.includes(filterName)) {
                return false;
            }
        }
        
        return true;
    });
    
    currentPage = 1;
    renderActiveFilters();
    renderHistory();
}

// Render active filters badges
function renderActiveFilters() {
    const container = document.getElementById('activeFiltersContainer');
    const list = document.getElementById('activeFiltersList');
    
    if (Object.keys(activeFilters).length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    
    const badges = Object.entries(activeFilters).map(([key, value]) => {
        const label = key === 'date' ? 'Date' : 'Name';
        const displayValue = key === 'date' ? formatDate(value) : value;
        
        return `
            <span class="filter-badge">
                ${label}: ${escapeHtml(displayValue)}
                <button class="filter-badge-close" onclick="clearFilter('${key}')">×</button>
            </span>
        `;
    }).join('');
    
    list.innerHTML = badges;
}

// Clear single filter
function clearFilter(filterKey) {
    if (filterKey === 'date') {
        document.getElementById('filterDate').value = '';
    } else if (filterKey === 'name') {
        document.getElementById('filterName').value = '';
    }
    
    delete activeFilters[filterKey];
    applyFilters();
}

// Clear all filters
function clearAllFilters() {
    document.getElementById('filterDate').value = '';
    document.getElementById('filterName').value = '';
    activeFilters = {};
    
    filteredRecords = [...allRecords];
    currentPage = 1;
    
    renderActiveFilters();
    renderHistory();
}

// Render history (both mobile and desktop)
function renderHistory() {
    const start = (currentPage - 1) * pageSize;
    const end = start + pageSize;
    const paginatedRecords = filteredRecords.slice(start, end);
    
    renderMobileCards(paginatedRecords);
    renderDesktopTable(paginatedRecords);
    renderPagination();
    
    // Show/hide no results message
    const noResults = document.getElementById('noResults');
    if (filteredRecords.length === 0) {
        noResults.style.display = 'block';
    } else {
        noResults.style.display = 'none';
    }
}

// Render mobile cards
function renderMobileCards(records) {
    const container = document.getElementById('mobileCards');
    
    if (records.length === 0) {
        container.innerHTML = '';
        return;
    }
    
    container.innerHTML = records.map(record => {
        const fullName = `${record.first_name || ''} ${record.last_name || ''}`.trim();
        const employeeId = record.employee_id || 'N/A';
        const workDate = formatDate(record.date);
        
        return `
            <div class="history-card">
                <div class="card-header-row">
                    <div>
                        <h6 class="card-title">${escapeHtml(fullName)}</h6>
                        <small class="card-employee-id">${escapeHtml(employeeId)}</small>
                    </div>
                </div>
                <div class="card-date-section">
                    <small class="card-label">Date</small>
                    <div class="card-value">${workDate}</div>
                </div>
                <div class="card-times-grid">
                    <div>
                        <small class="card-label">Clock In</small>
                        <div class="card-value">${record.clockin_time || '-'}</div>
                    </div>
                    <div>
                        <small class="card-label">Clock Out</small>
                        <div class="card-value">${record.clockout_time || '-'}</div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// Render desktop table
function renderDesktopTable(records) {
    const tbody = document.getElementById('historyTableBody');
    
    if (records.length === 0) {
        tbody.innerHTML = '';
        return;
    }
    
    tbody.innerHTML = records.map(record => {
        const fullName = `${record.first_name || ''} ${record.last_name || ''}`.trim();
        const employeeId = record.employee_id || 'N/A';
        const workDate = formatDate(record.date);
        
        return `
            <tr>
                <td>${escapeHtml(fullName)}</td>
                <td>${escapeHtml(employeeId)}</td>
                <td>${workDate}</td>
                <td>${record.clockin_time || '-'}</td>
                <td>${record.clockout_time || '-'}</td>
            </tr>
        `;
    }).join('');
}

// Render pagination
function renderPagination() {
    const totalPages = Math.ceil(filteredRecords.length / pageSize);
    const nav = document.getElementById('paginationNav');
    const list = document.getElementById('paginationList');
    const info = document.getElementById('paginationInfo');
    
    if (totalPages <= 1) {
        nav.style.display = 'none';
        return;
    }
    
    nav.style.display = 'block';
    
    // Calculate visible pages
    const maxVisible = window.innerWidth < 576 ? 3 : 5;
    let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
    let end = Math.min(totalPages, start + maxVisible - 1);
    
    if (end - start + 1 < maxVisible) {
        start = Math.max(1, end - maxVisible + 1);
    }
    
    const pages = [];
    for (let i = start; i <= end; i++) {
        pages.push(i);
    }
    
    // Build pagination HTML
    let paginationHTML = `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <button class="page-link" onclick="changePage(${currentPage - 1})">
                <span class="d-none d-sm-inline">Previous</span>
                <span class="d-inline d-sm-none">←</span>
            </button>
        </li>
    `;
    
    pages.forEach(page => {
        paginationHTML += `
            <li class="page-item ${currentPage === page ? 'active' : ''}">
                <button class="page-link" onclick="changePage(${page})">${page}</button>
            </li>
        `;
    });
    
    paginationHTML += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <button class="page-link" onclick="changePage(${currentPage + 1})">
                <span class="d-none d-sm-inline">Next</span>
                <span class="d-inline d-sm-none">→</span>
            </button>
        </li>
    `;
    
    list.innerHTML = paginationHTML;
    
    // Pagination info
    const recordStart = (currentPage - 1) * pageSize + 1;
    const recordEnd = Math.min(currentPage * pageSize, filteredRecords.length);
    const isMobile = window.innerWidth < 768;
    const perPageText = isMobile ? '5 per page' : '10 per page';
    
    info.innerHTML = `
        Showing ${recordStart}-${recordEnd} of ${filteredRecords.length} records
        <span class="${isMobile ? 'd-md-none' : 'd-none d-md-inline'}">(${perPageText})</span>
    `;
}

// Change page
function changePage(page) {
    const totalPages = Math.ceil(filteredRecords.length / pageSize);
    
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        renderHistory();
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

// Download CSV
function downloadCSV() {
    try {
        const data = filteredRecords;
        
        if (data.length === 0) {
            showToast('No records to download', 'info');
            return;
        }
        
        // CSV header
        const headers = [
            'Employee ID',
            'Date',
            'First Name',
            'Last Name',
            'Full Name',
            'Clock In',
            'Clock Out'
        ];
        
        // CSV rows
        const rows = data.map(r => [
            r.employee_id || '',
            r.date || '',
            r.first_name || '',
            r.last_name || '',
            `${r.first_name || ''} ${r.last_name || ''}`.trim(),
            r.clockin_time || '-',
            r.clockout_time || '-'
        ]);
        
        // Build CSV content
        const csvContent = [
            headers.join(','),
            ...rows.map(row => row.map(cell => `"${cell}"`).join(','))
        ].join('\n');
        
        // Create download link
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', 'attendance_history.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showToast('Table exported to CSV successfully', 'success');
    } catch (error) {
        console.error('Export error:', error);
        showToast('Failed to export data', 'error');
    }
}

// Format date for display
function formatDate(dateString) {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
