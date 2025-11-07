<button onclick="showUserGuide()" class="help-btn">
    <i class="fa-solid fa-circle-question"></i>
    Help Guide
</button>

<div id="userGuideOverlay" class="user-guide-overlay" style="display: none;">
    <div id="highlightOverlay" class="highlight-overlay" style="box-shadow: 0 0 0 4px rgba(46,178,138,0.3), 0 0 0 9999px rgba(0,0,0,0.6); border:3px solid #2EB28A; border-radius:12px; background:transparent;"></div>
    <div id="guideContent" class="guide-content">
        <div class="guide-header">
            <h3 id="guideTitle">Guide Title</h3>
            <button onclick="closeGuide()" class="close-btn">&times;</button>
        </div>
        <div class="guide-body">
            <p id="guideDescription">Guide description will appear here.</p>
        </div>
        <div class="guide-footer">
            <div class="step-indicator" style="border: none; padding: 0; margin-right: auto;">Step <span id="currentStep">1</span> of <span id="totalSteps">5</span></div>
            <div class="guide-buttons">
                <button id="backBtn" onclick="prevStep()" class="btn-secondary">← Back</button>
                <button onclick="nextStep()" class="btn-primary">Next →</button>
            </div>
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
    z-index: 1800;
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
    background: var(--guide-bg, #ffffff);
    border-radius: 12px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    min-width: 350px;
    max-width: 450px;
    /* sit above the overlay but below modals */
    z-index: 1811;
    pointer-events: all;
    border: 1px solid var(--guide-border, #e2e8f0);
    transition: all 0.4s ease;
}

.guide-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px 0 25px;
    border-bottom: 1px solid var(--guide-border, #e2e8f0);
    padding-bottom: 15px;
}

.guide-header h3 {
    margin: 0;
    color: var(--guide-title, #1a202c);
    font-size: 1.3em;
    font-weight: 700;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5em;
    cursor: pointer;
    color: var(--guide-text-secondary, #718096);
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
    background: var(--guide-hover, #f0fdf4);
    color: var(--guide-text, #2d3748);
}

.guide-body {
    padding: 20px 25px;
}

.guide-body p {
    margin: 0 0 15px 0;
    line-height: 1.6;
    color: var(--guide-text, #374151);
    font-size: 1em;
}

.step-indicator {
    font-size: 0.9em;
    color: #2EB28A;
    font-weight: 600;
    text-align: center;
    padding: 8px 0;
    border-top: 1px solid var(--guide-border, #e2e8f0);
}

.guide-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-top: 2px solid #f3f4f6;
    gap: 16px;
    flex-wrap: wrap;
}

.guide-step-indicator {
    font-size: 0.875rem;
    color: #2EB28A;
    font-weight: 600;
}

.guide-buttons {
    display: flex;
    gap: 12px;
    align-items: center;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.938rem;
    transition: all 0.2s ease;
    white-space: nowrap;
    background: linear-gradient(135deg, #2EB28A 0%, #259673 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(46, 178, 138, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #259673 0%, #1e7a5c 100%);
    box-shadow: 0 6px 16px rgba(46, 178, 138, 0.4);
    transform: translateY(-2px);
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.938rem;
    transition: all 0.2s ease;
    white-space: nowrap;
    background: #f3f4f6;
    color: #4b5563;
}

.btn-secondary:hover {
    background: #e5e7eb;
    color: #1f2937;
    transform: translateY(-2px);
}
}

.help-btn {
    position: fixed;
    top: 100px;
    right: 30px;
    background: #2EB28A;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 25px;
    cursor: pointer;
    font-weight: 600;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(46, 178, 138, 0.3);
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    font-family: inherit;
}

.help-btn:hover {
    background: #259673;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(46, 178, 138, 0.4);
}

.help-btn i {
    font-size: 16px;
}

/* Dark mode variables and scoped rules — only apply when site-level
   dark mode is active (body.dark-mode). Avoid relying on system
   preference so the site's toggle controls appearance consistently. */
body.dark-mode {
    --guide-bg: #1a202c;
    --guide-border: #2d3748;
    --guide-title: #f7fafc;
    --guide-text: #e2e8f0;
    --guide-text-secondary: #a0aec0;
    --guide-hover: #2d3748;
    --guide-secondary-bg: #22543d;
    --guide-secondary-text: #c6f6d5;
    --guide-secondary-hover: #276749;
    --guide-secondary-text-hover: #ffffff;
}

body.dark-mode .guide-content {
    background: #1a202c;
    border-color: #2d3748;
}

body.dark-mode .guide-header h3 {
    color: #f7fafc;
}

body.dark-mode .guide-body p {
    color: #e2e8f0;
}

body.dark-mode .close-btn {
    color: #a0aec0;
}

body.dark-mode .close-btn:hover {
    background: #2d3748;
    color: #f7fafc;
}

body.dark-mode .btn-secondary {
    background: #22543d;
    color: #c6f6d5;
}

body.dark-mode .btn-secondary:hover {
    background: #276749;
    color: #ffffff;
}

body.dark-mode .step-indicator {
    border-top-color: #2d3748;
}

body.dark-mode .guide-header,
body.dark-mode .guide-footer {
    border-color: #2d3748;
}

/* Light mode fallback */
.guide-content {
    background: #ffffff;
    border-color: #e2e8f0;
}

.guide-header h3 {
    color: #1a202c;
}

.guide-body p {
    color: #374151;
}

.close-btn {
    color: #718096;
}

.close-btn:hover {
    background: #f0fdf4;
    color: #2d3748;
}

.btn-secondary {
    background: #d1fae5;
    color: #065f46;
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

    .help-btn {
        top: 80px;
        right: 20px;
        padding: 10px 16px;
        font-size: 0.9em;
    }
}
</style>
<script>
const guideSteps = [
    {
        title: "Welcome to Time Log!",
        content: "This page lets you view and track employees' weekly hours, overtime, and owed time. Manage your team's time efficiently with these tools.",
        highlight: { top: '120px', left: '1rem', width: 'calc(100% - 2rem)', height: 'calc(100% - 140px)' },
        position: { top: '200px', left: '50%', transform: 'translateX(-50%)' }
    },
    {
        title: "Search Employee",
        content: "Use this search bar to quickly find an employee by name or ID. The results will update automatically as you type.",
        element: "searchEmployee",
        position: 'auto'
    },
    {
        title: "Select Week",
        content: "Use the week dropdown to select which week's data you want to view. The system will load time log information for the selected week.",
        element: "weekSelect",
        position: 'auto'
    },
    {
        title: "Status Indicators",
        content: "Green indicators mean hours are balanced. Red indicators mean hours are owed - click them to confirm adjustments and balance the time.",
        element: "filterButtons",
        position: 'auto'
    },
    {
        title: "Time Log Table",
        content: "This table displays each employee's hours worked, owed hours, overtime, and status indicators. Click on indicators to manage employee time balances.",
        element: "timeLogTable",
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
        const elementMiddle = elementRect.top + (elementRect.height / 2);
        const spaceAbove = elementRect.top;
        const spaceBelow = viewportHeight - elementRect.bottom;
        
        if (spaceBelow > guideContentHeight + padding && spaceBelow > spaceAbove) {
            position.top = (elementRect.bottom + padding) + 'px';
        } else if (spaceAbove > guideContentHeight + padding) {
            position.top = (elementRect.top - guideContentHeight - padding) + 'px';
        } else {
            if (elementMiddle < viewportHeight / 2) {
                position.top = '60%';
            } else {
                position.top = '20%';
            }
        }
        
        position.left = '50%';
        position.transform = 'translateX(-50%)';
    } else {
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
        } else if (spaceBelow > guideContentHeight + padding && spaceBelow > spaceAbove) {
            position.top = (elementRect.bottom + padding) + 'px';
            position.left = '50%';
            position.transform = 'translateX(-50%)';
        } else if (spaceAbove > guideContentHeight + padding) {
            position.top = (elementRect.top - guideContentHeight - padding) + 'px';
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
    
    document.getElementById('guideTitle').textContent = step.title;
    document.getElementById('guideDescription').textContent = step.content;
    document.getElementById('currentStep').textContent = currentStepIndex + 1;
    document.getElementById('totalSteps').textContent = guideSteps.length;
    
    document.getElementById('backBtn').style.display = currentStepIndex > 0 ? 'block' : 'none';
    
    const nextBtn = document.querySelector('.guide-footer .btn-primary');
    nextBtn.textContent = currentStepIndex === guideSteps.length - 1 ? 'Finish' : 'Next';
    
    const guideContent = document.getElementById('guideContent');
    const highlightOverlay = document.getElementById('highlightOverlay');
    
    if (step.element) {
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

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[placeholder="Search Employee..."]');
    if (searchInput) searchInput.id = 'searchEmployee';
    
    const weekSelect = document.querySelector('.week-select');
    if (weekSelect) weekSelect.id = 'weekSelect';
    
    const filterButtons = document.querySelector('.filter-buttons');
    if (filterButtons) filterButtons.id = 'filterButtons';
    
    const timeLogTable = document.querySelector('.time-log-table');
    if (timeLogTable) timeLogTable.id = 'timeLogTable';
    
    const firstIndicator = document.querySelector('.indicator');
    if (firstIndicator) firstIndicator.id = 'indicatorExample';
});
</script>