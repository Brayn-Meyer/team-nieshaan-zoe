<!-- Help Button -->
<button onclick="showUserGuide()" class="help-btn">
    <i class="fa-solid fa-circle-question"></i>
    Help Guide
</button>

<!-- User Guide Overlay -->
<div id="userGuideOverlay" class="user-guide-overlay" style="display: none;">
    <!-- Highlight Overlay -->
    <div id="highlightOverlay" class="highlight-overlay"></div>
    
    <!-- Guide Content -->
    <div id="guideContent" class="guide-content">
        <div class="guide-header">
            <h3 id="guideTitle">Guide Title</h3>
            <button onclick="closeGuide()" class="close-btn">&times;</button>
        </div>
        <div class="guide-body">
            <p id="guideDescription">Guide description will appear here.</p>
            <div class="step-indicator">
                Step <span id="currentStep">1</span> of <span id="totalSteps">5</span>
            </div>
        </div>
        <div class="guide-footer">
            <button id="backBtn" onclick="prevStep()" class="btn-secondary" style="display: none;">
                Back
            </button>
            <button onclick="nextStep()" class="btn-primary">
                Next
            </button>
        </div>
    </div>
</div>

<style>
.user-guide-overlay {
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
    border: 3px solid #2EB28A;
    border-radius: 12px;
    box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.7);
    pointer-events: none;
    transition: all 0.4s ease;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { border-color: #2EB28A; }
    50% { border-color: #34d399; }
    100% { border-color: #2EB28A; }
}

.guide-content {
    position: fixed;
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    min-width: 350px;
    max-width: 450px;
    z-index: 10001;
    pointer-events: all;
    border: 1px solid #e2e8f0;
    transition: all 0.4s ease;
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
    color: #000000;
    font-size: 1.3em;
    font-weight: 700;
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
    color: #2EB28A;
    font-weight: 600;
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
    background: #2EB28A;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #259673;
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

@media (max-width: 768px) {
    .guide-content {
        min-width: 280px;
        max-width: calc(100vw - 40px);
        width: calc(100% - 40px);
        margin: 0 20px;
        left: 50% !important;
        right: auto !important;
        transform: translateX(-50%) !important;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
    }
    
    .guide-header {
        padding: 15px 15px 0 15px;
        padding-bottom: 12px;
    }
    
    .guide-header h3 {
        font-size: 1.1em;
    }
    
    .guide-body {
        padding: 15px;
    }
    
    .guide-body p {
        font-size: 0.95em;
        margin: 0 0 12px 0;
    }
    
    .guide-footer {
        padding: 12px 15px;
    }
    
    .btn-primary,
    .btn-secondary {
        padding: 8px 16px;
        font-size: 0.9em;
    }
    
    .step-indicator {
        font-size: 0.85em;
        padding: 6px 0;
    }
}
</style>

<script>
// Guide steps configuration
const guideSteps = [
    {
        title: "Attendance History Overview",
        content: "Welcome to the Attendance History page! Here you can view, filter, and export comprehensive attendance records for all employees.",
        highlight: { top: '120px', left: '1rem', width: 'calc(100% - 2rem)', height: 'calc(100% - 140px)' },
        position: { top: '200px', left: '50%', transform: 'translateX(-50%)' }
    },
    {
        title: "Date Filter",
        content: "Use this date picker to filter attendance records by a specific date. Select any date to view only the records from that day.",
        element: "filterDate",
        position: 'auto'
    },
    {
        title: "Name Filter",
        content: "Search for specific employees by typing their first or last name here. The system will filter records matching your search.",
        element: "filterName",
        position: 'auto'
    },
    {
        title: "Apply Filters",
        content: "Click this button to apply your selected filters. The attendance records will update to show only the data matching your criteria.",
        element: "applyFiltersBtn",
        position: 'auto'
    },
    {
        title: "Active Filters Display",
        content: "This area shows which filters are currently active. You can clear individual filters or use the 'Clear All' button to remove all filters at once.",
        element: "activeFiltersContainer",
        position: 'auto'
    },
    {
        title: "Download Records",
        content: "Export your filtered attendance records to a CSV file by clicking this button. The file will include all visible records in the table.",
        element: "downloadBtn",
        position: 'auto'
    },
    {
        title: "Attendance Records Table",
        content: "View detailed attendance information including employee names, IDs, dates, clock-in and clock-out times. The table shows all records matching your current filters.",
        element: "desktopTableContainer",
        position: 'auto'
    }
];

let currentStepIndex = 0;

function showUserGuide() {
    currentStepIndex = 0;
    document.getElementById('userGuideOverlay').style.display = 'block';
    updateGuide();
}

function closeGuide() {
    document.getElementById('userGuideOverlay').style.display = 'none';
}

function nextStep() {
    if (currentStepIndex < guideSteps.length - 1) {
        currentStepIndex++;
        updateGuide();
    } else {
        finishGuide();
    }
}

function prevStep() {
    if (currentStepIndex > 0) {
        currentStepIndex--;
        updateGuide();
    }
}

function finishGuide() {
    closeGuide();
    currentStepIndex = 0;
}

function calculateSmartPosition(elementRect, guideContentHeight) {
    const isMobile = window.innerWidth <= 768;
    const padding = 20;
    const viewportHeight = window.innerHeight;
    const viewportWidth = window.innerWidth;
    
    let position = {};
    
    if (isMobile) {
        // Mobile positioning logic
        const elementMiddle = elementRect.top + (elementRect.height / 2);
        const spaceAbove = elementRect.top;
        const spaceBelow = viewportHeight - elementRect.bottom;
        
        // Check if there's more space above or below
        if (spaceBelow > guideContentHeight + padding && spaceBelow > spaceAbove) {
            // Position below the element
            position.top = (elementRect.bottom + padding) + 'px';
        } else if (spaceAbove > guideContentHeight + padding) {
            // Position above the element
            position.top = (elementRect.top - guideContentHeight - padding) + 'px';
        } else {
            // Not enough space, position in center but offset from element
            if (elementMiddle < viewportHeight / 2) {
                // Element is in top half, position tooltip in bottom half
                position.top = '60%';
            } else {
                // Element is in bottom half, position tooltip in top half
                position.top = '20%';
            }
        }
        
        position.left = '50%';
        position.transform = 'translateX(-50%)';
    } else {
        // Desktop positioning logic
        const elementMiddle = elementRect.top + (elementRect.height / 2);
        const spaceAbove = elementRect.top;
        const spaceBelow = viewportHeight - elementRect.bottom;
        const spaceLeft = elementRect.left;
        const spaceRight = viewportWidth - elementRect.right;
        
        // Try to position beside the element first (left or right)
        if (spaceRight > 500) {
            // Position to the right
            position.left = (elementRect.right + padding) + 'px';
            position.top = Math.max(padding, elementMiddle - (guideContentHeight / 2)) + 'px';
            position.transform = 'none';
        } else if (spaceLeft > 500) {
            // Position to the left
            position.right = (viewportWidth - elementRect.left + padding) + 'px';
            position.left = 'auto';
            position.top = Math.max(padding, elementMiddle - (guideContentHeight / 2)) + 'px';
            position.transform = 'none';
        } else if (spaceBelow > guideContentHeight + padding && spaceBelow > spaceAbove) {
            // Position below
            position.top = (elementRect.bottom + padding) + 'px';
            position.left = '50%';
            position.transform = 'translateX(-50%)';
        } else if (spaceAbove > guideContentHeight + padding) {
            // Position above
            position.top = (elementRect.top - guideContentHeight - padding) + 'px';
            position.left = '50%';
            position.transform = 'translateX(-50%)';
        } else {
            // Fallback to center
            position.top = '50%';
            position.left = '50%';
            position.transform = 'translate(-50%, -50%)';
        }
    }
    
    return position;
}

function updateGuide() {
    const step = guideSteps[currentStepIndex];
    
    // Update text content
    document.getElementById('guideTitle').textContent = step.title;
    document.getElementById('guideDescription').textContent = step.content;
    document.getElementById('currentStep').textContent = currentStepIndex + 1;
    document.getElementById('totalSteps').textContent = guideSteps.length;
    
    // Update button visibility
    document.getElementById('backBtn').style.display = currentStepIndex > 0 ? 'block' : 'none';
    
    // Update button text
    const nextBtn = document.querySelector('.guide-footer .btn-primary');
    nextBtn.textContent = currentStepIndex === guideSteps.length - 1 ? 'Finish' : 'Next';
    
    const guideContent = document.getElementById('guideContent');
    const highlightOverlay = document.getElementById('highlightOverlay');
    
    // Show active filters container when on that step
    const activeFiltersContainer = document.getElementById('activeFiltersContainer');
    if (step.element === 'activeFiltersContainer' && activeFiltersContainer) {
        activeFiltersContainer.style.display = 'block';
        activeFiltersContainer.style.visibility = 'visible';
    }
    
    // Update highlight overlay and position guide content
    if (step.element) {
        // Highlight specific element
        const element = document.getElementById(step.element);
        if (element) {
            const rect = element.getBoundingClientRect();
            highlightOverlay.style.top = rect.top + 'px';
            highlightOverlay.style.left = rect.left + 'px';
            highlightOverlay.style.width = rect.width + 'px';
            highlightOverlay.style.height = rect.height + 'px';
            highlightOverlay.style.display = 'block';
            
            // Calculate smart position for guide content
            if (step.position === 'auto') {
                // Get approximate height of guide content
                const guideHeight = guideContent.offsetHeight || 250;
                const smartPosition = calculateSmartPosition(rect, guideHeight);
                
                guideContent.style.top = smartPosition.top || 'auto';
                guideContent.style.left = smartPosition.left || 'auto';
                guideContent.style.right = smartPosition.right || 'auto';
                guideContent.style.bottom = smartPosition.bottom || 'auto';
                guideContent.style.transform = smartPosition.transform || 'none';
            } else if (step.position) {
                guideContent.style.top = step.position.top || 'auto';
                guideContent.style.left = step.position.left || 'auto';
                guideContent.style.right = step.position.right || 'auto';
                guideContent.style.bottom = step.position.bottom || 'auto';
                guideContent.style.transform = step.position.transform || 'none';
            }
        } else {
            // If element not found, use the predefined highlight
            if (step.highlight) {
                highlightOverlay.style.top = step.highlight.top;
                highlightOverlay.style.left = step.highlight.left;
                highlightOverlay.style.width = step.highlight.width;
                highlightOverlay.style.height = step.highlight.height;
                highlightOverlay.style.display = 'block';
            } else {
                highlightOverlay.style.display = 'none';
            }
            
            if (step.position && step.position !== 'auto') {
                guideContent.style.top = step.position.top || 'auto';
                guideContent.style.left = step.position.left || 'auto';
                guideContent.style.right = step.position.right || 'auto';
                guideContent.style.bottom = step.position.bottom || 'auto';
                guideContent.style.transform = step.position.transform || 'none';
            }
        }
    } else if (step.highlight) {
        // Use predefined highlight area
        highlightOverlay.style.top = step.highlight.top;
        highlightOverlay.style.left = step.highlight.left;
        highlightOverlay.style.width = step.highlight.width;
        highlightOverlay.style.height = step.highlight.height;
        highlightOverlay.style.display = 'block';
        
        if (step.position && step.position !== 'auto') {
            guideContent.style.top = step.position.top || 'auto';
            guideContent.style.left = step.position.left || 'auto';
            guideContent.style.right = step.position.right || 'auto';
            guideContent.style.bottom = step.position.bottom || 'auto';
            guideContent.style.transform = step.position.transform || 'none';
        }
    } else {
        highlightOverlay.style.display = 'none';
        
        if (step.position && step.position !== 'auto') {
            guideContent.style.top = step.position.top || 'auto';
            guideContent.style.left = step.position.left || 'auto';
            guideContent.style.right = step.position.right || 'auto';
            guideContent.style.bottom = step.position.bottom || 'auto';
            guideContent.style.transform = step.position.transform || 'none';
        }
    }
}

// Add IDs to elements that need to be highlighted
document.addEventListener('DOMContentLoaded', function() {
    // Add ID to Apply Filters button
    const applyBtn = document.querySelector('.btn-apply');
    if (applyBtn) {
        applyBtn.id = 'applyFiltersBtn';
    }
    
    // Add ID to Download button
    const downloadBtn = document.querySelector('.btn-download');
    if (downloadBtn) {
        downloadBtn.id = 'downloadBtn';
    }
    
    // Add ID to Desktop Table Container
    const desktopTable = document.querySelector('.desktop-table-container');
    if (desktopTable) {
        desktopTable.id = 'desktopTableContainer';
    }
    
    // Add ID to Mobile Cards
    const mobileCards = document.getElementById('mobileCards');
    if (mobileCards) {
        // Already has ID
    }
    
    // Add ID to Pagination
    const pagination = document.getElementById('paginationNav');
    if (pagination) {
        // Already has ID
    }
});
</script>