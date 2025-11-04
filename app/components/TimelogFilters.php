<?php
// TimelogFilters.php
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
$searchQuery = $_GET['search'] ?? '';
$activeFilter = $_GET['filter'] ?? null;
$selectedWeek = $_GET['week'] ?? ($weekOptions[count($weekOptions) - 1]['value'] ?? '');
?>

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

<style>
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
}
</style>