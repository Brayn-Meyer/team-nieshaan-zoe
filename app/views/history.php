<?php
require_once __DIR__ . '/../../includes/config.php';

$pageTitle = 'History - Clock It';
$currentPage = 'history';
$additionalJS = ['/assets/js/history.js'];
$additionalCSS = ['/assets/css/history.css'];

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="history-container">
    <div class="history-header">
        <h2 class="history-title">Attendance History</h2>
        <p class="history-subtitle">Filter Employees History</p>
    </div>

    <!-- Filters Card -->
    <div class="filters-card">
        <div class="filters-row">
            <div class="filter-group">
                <label class="filter-label">Select Date</label>
                <input 
                    type="date" 
                    id="filterDate" 
                    class="filter-input"
                />
            </div>
            <div class="filter-group">
                <label class="filter-label">Name (First or Last)</label>
                <input 
                    type="text" 
                    id="filterName" 
                    class="filter-input" 
                    placeholder="Enter name"
                />
            </div>
            <div class="filter-group apply-group">
                <button class="btn-apply" onclick="applyFilters()">
                    <i class="fa-solid fa-filter"></i> Apply Filters
                </button>
            </div>
        </div>

        <!-- Active Filters Display -->
        <div id="activeFiltersContainer" class="active-filters" style="display: none;">
            <div class="active-filters-content">
                <small class="active-filters-label">Active Filters:</small>
                <div id="activeFiltersList" class="active-filters-list"></div>
                <button class="btn-clear-all" onclick="clearAllFilters()">Clear All</button>
            </div>
        </div>
    </div>

    <!-- Download Button -->
    <div class="download-section">
        <button class="btn-download" onclick="downloadCSV()">
            <i class="fa-solid fa-download"></i> Download
        </button>
    </div>

    <!-- Mobile View (Cards) -->
    <div class="mobile-cards d-md-none" id="mobileCards">
        <div class="text-center text-muted py-4">
            <i class="fa-solid fa-spinner fa-spin"></i> Loading records...
        </div>
    </div>

    <!-- Desktop View (Table) -->
    <div class="desktop-table-container d-none d-md-block">
        <div class="table-wrapper">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Employee ID</th>
                        <th>Date</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody">
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            <i class="fa-solid fa-spinner fa-spin"></i> Loading records...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="no-results" style="display: none;">
        <p>No records found</p>
    </div>

    <!-- Pagination -->
    <nav id="paginationNav" class="pagination-nav" style="display: none;">
        <ul class="pagination" id="paginationList"></ul>
        <div class="pagination-info" id="paginationInfo"></div>
    </nav>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
