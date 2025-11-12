<!-- Help Button -->
<button onclick="showUserGuide()" class="help-btn" title="Help Guide">
    <i class="fa-solid fa-circle-question"></i>
    <span>Help Guide</span>
</button>

<!-- User Guide Overlay -->
<div id="userGuideOverlay" class="user-guide-overlay" style="display: none;">
    <div id="highlightOverlay" class="highlight-overlay" style="box-shadow: 0 0 0 4px rgba(46,178,138,0.3), 0 0 0 9999px rgba(0,0,0,0.6); border:3px solid #2EB28A; border-radius:12px; background:transparent;"></div>
    
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
    /* keep overlay below modals (modal.css uses z-index:2000) */
    /* raise overlay stacking base to avoid page stacking context issues */
    z-index: 99980;
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
    /* ensure highlight sits below the guide content but above page content */
    z-index: 99990 !important;
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
    /* sit above overlay but below modals */
    /* make guide content the top-most element for the guide */
    z-index: 99999 !important;
    pointer-events: all;
    border: 1px solid #e2e8f0;
    transition: all 0.4s ease;
}

/* Ensure guide content always has a solid background */
body:not(.dark-mode) .guide-content {
    background: #ffffff !important;
}

body.dark-mode .guide-content {
    background: #1f2937 !important;
    border-color: #374151 !important;
}

.guide-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px 0 25px;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 15px;
    background: transparent !important;
}

.guide-header h3 {
    margin: 0;
    color: #000000;
    font-size: 1.3em;
    font-weight: 700;
}

/* Ensure header text is visible in both themes */
body:not(.dark-mode) .guide-header h3 {
    color: #000000 !important;
}

body.dark-mode .guide-header h3 {
    color: #f9fafb !important;
}

body.dark-mode .guide-header {
    border-bottom-color: #374151 !important;
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
    transition: all 0.2s ease;
}

.close-btn:hover {
    background: #f0fdf4;
}

.guide-body {
    padding: 20px 25px;
    background: transparent !important;
}

.guide-body p {
    margin: 0 0 15px 0;
    line-height: 1.6;
    color: #374151;
    font-size: 1em;
    background: transparent !important;
}

/* Ensure body text is visible in both themes */
body:not(.dark-mode) .guide-body p {
    color: #374151 !important;
}

body.dark-mode .guide-body p {
    color: #d1d5db !important;
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
    /* Match the dashboard tooltip back button color and remove hover effect */
    background: #22543D;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: none; /* disable hover transition */
    box-shadow: 0 4px 10px rgba(34,84,61,0.18);
}

.btn-secondary:hover {
    /* No color change on hover; keep same appearance */
    background: #22543D;
    color: #ffffff;
    transform: none;
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

/* Clone styling used to render a visible copy of the target above the mask */
.history-guide-clone {
    box-sizing: border-box;
    transform-origin: top left;
    pointer-events: none;
    /* ensure clone visually sits above overlay mask but below modal/dialog layers */
    z-index: 99990 !important;
}
.history-guide-clone img,
.history-guide-clone svg {
    filter: none !important;
}
/* Scoped overrides to remove the large dark mask for History guide when active */
body[data-guide-active="history"].dark-mode #userGuideOverlay {
    background: transparent !important;
}

body[data-guide-active="history"].dark-mode .highlight-overlay {
    /* show green outline and dim the rest of the page */
    border: 3px solid #2EB28A !important;
    border-radius: 12px !important;
    box-shadow: 0 0 0 4px rgba(46, 178, 138, 0.3) !important, 0 0 0 9999px rgba(0,0,0,0.6) !important;
    background: transparent !important;
    animation: none !important;
    z-index: 99990 !important;
}
/* Ensure clones render normally even when site-wide dark-mode rules apply
   and place them above any overlay mask. */
.history-guide-clone,
.history-guide-clone * {
    filter: none !important;
    mix-blend-mode: normal !important;
    background: transparent !important;
    color: inherit !important;
}
.history-guide-clone {
    z-index: 99990 !important;
}

/* Scoped dark-mode styles while the history guide is active so modal text
   remains readable. This only applies when the guide is open via the
   data-guide-active="history" attribute on <body>. */
body[data-guide-active="history"]:not(.dark-mode) .guide-content {
    background: #ffffff !important;
    border-color: #e2e8f0 !important;
}

body[data-guide-active="history"].dark-mode .guide-content,
body[data-guide-active="history"].dark-mode .guide-header h3,
body[data-guide-active="history"].dark-mode .guide-body p,
body[data-guide-active="history"].dark-mode .step-indicator,
body[data-guide-active="history"].dark-mode .guide-footer,
body[data-guide-active="history"].dark-mode .guide-header {
    color: #e5e7eb !important;
}
body[data-guide-active="history"].dark-mode .guide-content {
    background: #0b1220 !important;
}

/* Ensure buttons are visible in dark mode */
body[data-guide-active="history"].dark-mode .btn-primary,
body[data-guide-active="history"]:not(.dark-mode) .btn-primary {
    background: #2EB28A !important;
    color: #ffffff !important;
    border: none !important;
}

body[data-guide-active="history"].dark-mode .btn-secondary,
body[data-guide-active="history"]:not(.dark-mode) .btn-secondary {
    /* Force same green back button for history guide in both themes */
    background: #22543D !important;
    color: #ffffff !important;
    border: none !important;
    box-shadow: 0 4px 10px rgba(34,84,61,0.18) !important;
}

body[data-guide-active="history"].dark-mode .btn-primary:hover {
    background: #259673 !important;
}

body[data-guide-active="history"].dark-mode .btn-secondary:hover {
    /* Keep hover identical to base to remove hover effect */
    background: #22543D !important;
    color: #ffffff !important;
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
        // place the tooltip at the left-bottom of the viewport for better visibility
        position: 'left-bottom'
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
let guideClone = null;

function showUserGuide() {
    currentStepIndex = 0;
    document.getElementById('userGuideOverlay').style.display = 'block';
    // mark body so we can apply any guide-specific CSS overrides if needed
    try { document.body.setAttribute('data-guide-active', 'history'); } catch (e) {}
    updateGuide();
}

function closeGuide() {
    document.getElementById('userGuideOverlay').style.display = 'none';
    if (guideClone) {
        guideClone.remove();
        guideClone = null;
    }
    // Clean up any orphaned clones (safety measure)
    document.querySelectorAll('.history-guide-clone').forEach(clone => clone.remove());
    try { document.body.removeAttribute('data-guide-active'); } catch (e) {}
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
        // For mobile, always center horizontally
        position.left = '50%';
        position.transform = 'translateX(-50%)';
        
        const elementMiddle = elementRect.top + (elementRect.height / 2);
        const spaceAbove = elementRect.top;
        const spaceBelow = viewportHeight - elementRect.bottom;
        
        // Check if guide content would overlap with the highlighted element
        const guideBottomFromTop = elementRect.top - guideContentHeight - padding;
        const guideTopFromBottom = elementRect.bottom + padding + guideContentHeight;
        
        // If placing below would cause overlap and there's more space above, place above
        if (elementRect.bottom + padding + guideContentHeight > viewportHeight - padding && spaceAbove > guideContentHeight + padding) {
            position.top = (elementRect.top - guideContentHeight - padding) + 'px';
        }
        // If placing above would cause overlap and there's more space below, place below
        else if (elementRect.top - guideContentHeight - padding < padding && spaceBelow > guideContentHeight + padding) {
            position.top = (elementRect.bottom + padding) + 'px';
        }
        // If both placements would cause overlap, find the best position
        else {
            const overlapAbove = Math.max(0, padding - (elementRect.top - guideContentHeight - padding));
            const overlapBelow = Math.max(0, (elementRect.bottom + padding + guideContentHeight) - (viewportHeight - padding));
            
            if (overlapAbove <= overlapBelow && spaceAbove > spaceBelow) {
                position.top = (elementRect.top - guideContentHeight - padding) + 'px';
            } else {
                position.top = (elementRect.bottom + padding) + 'px';
            }
        }
        
        // Ensure the guide stays within viewport bounds
        const guideTop = parseInt(position.top);
        if (guideTop < padding) {
            position.top = padding + 'px';
        } else if (guideTop + guideContentHeight > viewportHeight - padding) {
            position.top = (viewportHeight - guideContentHeight - padding) + 'px';
        }
    } else {
        // Desktop positioning logic (unchanged)
        const elementMiddle = elementRect.top + (elementRect.height / 2);
        const spaceAbove = elementRect.top;
        const spaceBelow = viewportHeight - elementRect.bottom;
        const spaceLeft = elementRect.left;
        const spaceRight = viewportWidth - elementRect.right;
        
        if (spaceRight > 500) {
            position.left = (elementRect.right + padding) + 'px';
            position.top = Math.max(padding, elementMiddle - (guideContentHeight / 2)) + 'px';
            position.transform = 'none';
        } else if (spaceLeft > 500) {
            position.right = (viewportWidth - elementRect.left + padding) + 'px';
            position.left = 'auto';
            position.top = Math.max(padding, elementMiddle - (guideContentHeight / 2)) + 'px';
            position.transform = 'none';
        }
        // Prefer placing tooltip above the highlight when possible so it "rises"
        // above the highlighted area instead of overlapping it.
        else if (spaceAbove > guideContentHeight + padding) {
            position.top = (elementRect.top - guideContentHeight - padding) + 'px';
            position.left = '50%';
            position.transform = 'translateX(-50%)';
        } else if (spaceBelow > guideContentHeight + padding) {
            position.top = (elementRect.bottom + padding) + 'px';
            position.left = '50%';
            position.transform = 'translateX(-50%)';
        } else {
            position.top = '50%';
            position.left = '50%';
            position.transform = 'translate(-50%, -50%)';
        }
    }
    
    return position;
}

function updateGuide() {
    const step = guideSteps[currentStepIndex];
    // remove any existing clone from previous step
    if (guideClone) {
        guideClone.remove();
        guideClone = null;
    }
    
    document.getElementById('guideTitle').textContent = step.title;
    document.getElementById('guideDescription').textContent = step.content;
    document.getElementById('currentStep').textContent = currentStepIndex + 1;
    document.getElementById('totalSteps').textContent = guideSteps.length;
    
    document.getElementById('backBtn').style.display = currentStepIndex > 0 ? 'block' : 'none';
    
    const nextBtn = document.querySelector('.guide-footer .btn-primary');
    nextBtn.textContent = currentStepIndex === guideSteps.length - 1 ? 'Finish' : 'Next';
    
    const guideContent = document.getElementById('guideContent');
    const highlightOverlay = document.getElementById('highlightOverlay');
    
    const activeFiltersContainer = document.getElementById('activeFiltersContainer');
    if (step.element === 'activeFiltersContainer' && activeFiltersContainer) {
        activeFiltersContainer.style.display = 'block';
        activeFiltersContainer.style.visibility = 'visible';
    }
    
    if (step.element) {
        const element = document.getElementById(step.element);
        if (element) {
            const rect = element.getBoundingClientRect();
            highlightOverlay.style.top = rect.top + 'px';
            highlightOverlay.style.left = rect.left + 'px';
            highlightOverlay.style.width = rect.width + 'px';
            highlightOverlay.style.height = rect.height + 'px';
            highlightOverlay.style.display = 'block';

            // create a visual clone positioned above the overlay so its content remains visible
            try {
                const clone = element.cloneNode(true);
                if (clone.id) clone.removeAttribute('id');
                clone.classList.add('history-guide-clone');
                    Object.assign(clone.style, {
                    position: 'fixed',
                    top: rect.top + 'px',
                    left: rect.left + 'px',
                    width: rect.width + 'px',
                    height: rect.height + 'px',
                    margin: '0',
                    // make sure clone sits above the overlay mask
                    zIndex: '99990',
                    pointerEvents: 'none',
                    overflow: 'hidden'
                });
                // copy border radius and add subtle shadow to blend with page
                try {
                    const computed = window.getComputedStyle(element);
                    clone.style.borderRadius = computed.borderRadius;
                    clone.style.boxShadow = '0 8px 24px rgba(0,0,0,0.45)';
                } catch (e) {}
                document.body.appendChild(clone);
                guideClone = clone;
            } catch (e) {
                guideClone = null;
            }
            
            if (step.position === 'auto') {
                const guideHeight = guideContent.offsetHeight || 250;
                const smartPosition = calculateSmartPosition(rect, guideHeight);
                
                guideContent.style.top = smartPosition.top || 'auto';
                guideContent.style.left = smartPosition.left || 'auto';
                guideContent.style.right = smartPosition.right || 'auto';
                guideContent.style.bottom = smartPosition.bottom || 'auto';
                guideContent.style.transform = smartPosition.transform || 'none';
            } else if (step.position) {
                // Allow string positions like 'left-bottom' for screen-anchored placements
                if (typeof step.position === 'string') {
                    if (step.position === 'left-bottom' || step.position === 'bottom-left') {
                        // anchor to bottom-left of the viewport with a small margin
                        guideContent.style.left = '20px';
                        guideContent.style.bottom = '20px';
                        guideContent.style.top = 'auto';
                        guideContent.style.right = 'auto';
                        guideContent.style.transform = 'none';
                    } else if (step.position === 'center') {
                        guideContent.style.top = '50%';
                        guideContent.style.left = '50%';
                        guideContent.style.right = 'auto';
                        guideContent.style.bottom = 'auto';
                        guideContent.style.transform = 'translate(-50%, -50%)';
                    } else {
                        // unknown string — fall back to auto
                        guideContent.style.top = 'auto';
                        guideContent.style.left = 'auto';
                        guideContent.style.right = 'auto';
                        guideContent.style.bottom = 'auto';
                        guideContent.style.transform = 'none';
                    }
                } else {
                    // existing object-based positioning
                    guideContent.style.top = step.position.top || 'auto';
                    guideContent.style.left = step.position.left || 'auto';
                    guideContent.style.right = step.position.right || 'auto';
                    guideContent.style.bottom = step.position.bottom || 'auto';
                    guideContent.style.transform = step.position.transform || 'none';
                }
            }
        } else {
            if (step.highlight) {
                highlightOverlay.style.top = step.highlight.top;
                highlightOverlay.style.left = step.highlight.left;
                highlightOverlay.style.width = step.highlight.width;
                highlightOverlay.style.height = step.highlight.height;
                highlightOverlay.style.display = 'block';

                    // Create a visual clone for the highlighted area (when no specific element is present)
                    // This grabs the element near the center of the highlight and clones it so the region
                    // remains visible above the overlay mask in dark-mode.
                    try {
                        const rect = highlightOverlay.getBoundingClientRect();
                        const cx = rect.left + (rect.width / 2);
                        const cy = rect.top + (rect.height / 2);
                        const centerEl = document.elementFromPoint(cx, cy);
                        if (centerEl && centerEl !== document.body && centerEl !== document.documentElement) {
                            const clone = centerEl.cloneNode(true);
                            if (clone.id) clone.removeAttribute('id');
                            clone.classList.add('history-guide-clone');
                            Object.assign(clone.style, {
                                position: 'fixed',
                                top: rect.top + 'px',
                                left: rect.left + 'px',
                                width: rect.width + 'px',
                                height: rect.height + 'px',
                                margin: '0',
                                zIndex: '99990',
                                pointerEvents: 'none',
                                overflow: 'hidden'
                            });
                            try {
                                const computed = window.getComputedStyle(centerEl);
                                clone.style.borderRadius = computed.borderRadius;
                                clone.style.boxShadow = '0 8px 24px rgba(0,0,0,0.45)';
                            } catch (e) {}
                            document.body.appendChild(clone);
                            guideClone = clone;
                        }
                    } catch (e) {
                        guideClone = null;
                    }
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

document.addEventListener('DOMContentLoaded', function() {
    const applyBtn = document.querySelector('.btn-apply');
    if (applyBtn) {
        applyBtn.id = 'applyFiltersBtn';
    }
    
    const downloadBtn = document.querySelector('.btn-download');
    if (downloadBtn) {
        downloadBtn.id = 'downloadBtn';
    }

    const desktopTable = document.querySelector('.desktop-table-container');
    if (desktopTable) {
        desktopTable.id = 'desktopTableContainer';
    }
    
    const mobileCards = document.getElementById('mobileCards');
    if (mobileCards) {
    
    }
    
    const pagination = document.getElementById('paginationNav');
    if (pagination) {

    }
});

// Keep the clone positioned during scroll/resize while the guide is open
window.addEventListener('scroll', () => {
    if (!guideClone || document.getElementById('userGuideOverlay').style.display === 'none') return;
    const step = guideSteps[currentStepIndex];
    if (!step) return;
    // Determine the rect to follow: prefer a specific element, otherwise use the highlight overlay rect
    let rect = null;
    if (step.element) {
        const element = document.getElementById(step.element);
        if (element) rect = element.getBoundingClientRect();
    }
    if (!rect) {
        const highlightOverlay = document.getElementById('highlightOverlay');
        if (highlightOverlay && highlightOverlay.style.display !== 'none') {
            rect = highlightOverlay.getBoundingClientRect();
        }
    }
    if (!rect) return;
    Object.assign(guideClone.style, { top: rect.top + 'px', left: rect.left + 'px' });
});

window.addEventListener('resize', () => {
    if (!guideClone || document.getElementById('userGuideOverlay').style.display === 'none') return;
    const step = guideSteps[currentStepIndex];
    if (!step) return;
    let rect = null;
    if (step.element) {
        const element = document.getElementById(step.element);
        if (element) rect = element.getBoundingClientRect();
    }
    if (!rect) {
        const highlightOverlay = document.getElementById('highlightOverlay');
        if (highlightOverlay && highlightOverlay.style.display !== 'none') {
            rect = highlightOverlay.getBoundingClientRect();
        }
    }
    if (!rect) return;
    Object.assign(guideClone.style, { top: rect.top + 'px', left: rect.left + 'px', width: rect.width + 'px', height: rect.height + 'px' });
});
</script>