/**
 * User Guide System
 * Interactive step-by-step guide that highlights and explains dashboard components
 */

class UserGuide {
    constructor() {
        this.currentStepIndex = 0;
        this.isActive = false;
        this.guideSteps = [
            {
                title: "Dashboard Overview",
                content: "Welcome to your Employee Attendance Dashboard! This is your main hub for tracking employee attendance in real-time.",
                target: ".dashboard-main",
                position: "center"
            },
            {
                title: "Total Employees",
                content: "This card shows the total number of registered employees in your system.",
                target: ".dashboard-main .card:nth-child(1)",
                position: "bottom"
            },
            {
                title: "Clock In Status",
                content: "Monitor how many employees are currently clocked in for the day.",
                target: ".dashboard-main .card:nth-child(2)",
                position: "bottom"
            },
            {
                title: "Clock Out Status",
                content: "Track employees who have clocked out for the day.",
                target: ".dashboard-main .card:nth-child(3)",
                position: "bottom"
            },
            {
                title: "Absent Employees",
                content: "Keep an eye on employees who are marked as absent today.",
                target: ".dashboard-main .card:nth-child(4)",
                position: "bottom"
            },
            {
                title: "Quick Actions",
                content: "Use these buttons to quickly navigate to History and Time Log pages.",
                target: ".search-add-container .col-auto:first-child",
                position: "bottom"
            },
            {
                title: "Search Employees",
                content: "Search for employees by name, ID, department, or role using this search bar.",
                target: ".search-container",
                position: "bottom"
            },
            {
                title: "Add New Employee",
                content: "Click this button to add a new employee to your system.",
                target: ".search-add-container .col-auto:last-child",
                position: "bottom"
            },
            {
                title: "Employee Details Table",
                content: "View detailed information about all employees including their name, ID, department, role, and status. Click the action menu to edit or delete employees.",
                target: ".employee-table, .mobile-employees-container",
                position: "top"
            }
        ];
        
        this.overlay = null;
        this.highlightBox = null;
        this.tooltipBox = null;
    }

    start() {
        if (this.isActive) return;
        
        this.isActive = true;
        this.currentStepIndex = 0;
        this.hideHelpButton();
        this.createOverlay();
        this.showStep();
    }

    hideHelpButton() {
        const helpBtn = document.querySelector('.help-btn');
        if (helpBtn) {
            helpBtn.style.display = 'none';
        }
    }

    showHelpButton() {
        const helpBtn = document.querySelector('.help-btn');
        if (helpBtn) {
            helpBtn.style.display = 'flex';
        }
    }

    createOverlay() {
        // Create main overlay
        this.overlay = document.createElement('div');
        this.overlay.className = 'user-guide-overlay';
        
        // Create highlight box (creates backdrop via box-shadow)
        this.highlightBox = document.createElement('div');
        this.highlightBox.className = 'guide-highlight';
        this.overlay.appendChild(this.highlightBox);
        
        // Create tooltip
        this.tooltipBox = document.createElement('div');
        this.tooltipBox.className = 'guide-tooltip';
        this.tooltipBox.innerHTML = `
            <div class="guide-tooltip-inner">
                <div class="guide-header">
                    <h3 class="guide-title"></h3>
                    <button class="guide-close" onclick="userGuide.close()">&times;</button>
                </div>
                <div class="guide-body">
                    <p class="guide-content"></p>
                </div>
                <div class="guide-footer">
                    <div class="guide-step-indicator">
                        <span class="current-step"></span> of <span class="total-steps"></span>
                    </div>
                    <div class="guide-buttons">
                        <button class="guide-btn guide-btn-secondary" onclick="userGuide.prevStep()">
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </button>
                        <button class="guide-btn guide-btn-primary" onclick="userGuide.nextStep()">
                            <span class="next-text">Next</span>
                            <span class="finish-text" style="display: none;">Finish</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        this.overlay.appendChild(this.tooltipBox);
        
        document.body.appendChild(this.overlay);
        
        // Prevent scrolling while guide is active
        document.body.style.overflow = 'hidden';
    }

    showStep() {
        const step = this.guideSteps[this.currentStepIndex];
        
        // Handle multiple selectors - choose based on visibility/screen size
        let target;
        if (step.target.includes(',')) {
            const selectors = step.target.split(',').map(s => s.trim());
            // For mobile (< 768px), prefer mobile-specific containers
            const isMobile = window.innerWidth < 768;
            
            for (const selector of selectors) {
                const element = document.querySelector(selector);
                if (!element) continue;
                
                // On mobile, prefer mobile containers
                if (isMobile && selector.includes('mobile')) {
                    target = element;
                    break;
                }
                
                // On desktop, skip mobile containers
                if (!isMobile && selector.includes('mobile')) {
                    continue;
                }
                
                // Use first valid non-mobile element on desktop
                if (!isMobile && !selector.includes('mobile')) {
                    target = element;
                    break;
                }
            }
            
            // Fallback to first existing element
            if (!target) {
                target = selectors.map(s => document.querySelector(s))
                    .find(el => el !== null);
            }
        } else {
            target = document.querySelector(step.target);
        }
        
        if (!target) {
            console.warn('Target not found for step:', step.target);
            return;
        }

        // Update tooltip content
        this.tooltipBox.querySelector('.guide-title').textContent = step.title;
        this.tooltipBox.querySelector('.guide-content').textContent = step.content;
        this.tooltipBox.querySelector('.current-step').textContent = this.currentStepIndex + 1;
        this.tooltipBox.querySelector('.total-steps').textContent = this.guideSteps.length;
        
        // Show/hide back button
        const backBtn = this.tooltipBox.querySelector('.guide-btn-secondary');
        backBtn.style.display = this.currentStepIndex === 0 ? 'none' : 'inline-flex';
        
        // Show/hide next/finish text
        const nextText = this.tooltipBox.querySelector('.next-text');
        const finishText = this.tooltipBox.querySelector('.finish-text');
        const isLastStep = this.currentStepIndex === this.guideSteps.length - 1;
        nextText.style.display = isLastStep ? 'none' : 'inline';
        finishText.style.display = isLastStep ? 'inline' : 'none';
        
        // Scroll target into view with offset for navbar
        const navbarHeight = 80; // Approximate navbar height
        const targetRect = target.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const extraPadding = 40; // Extra space above component
        const targetTop = targetRect.top + scrollTop - navbarHeight - extraPadding;
        
        window.scrollTo({
            top: Math.max(0, targetTop), // Don't scroll negative
            behavior: 'smooth'
        });
        
        // Wait for scroll to complete before positioning
        setTimeout(() => {
            this.positionHighlight(target);
            this.positionTooltip(target, step.position);
        }, 300);
    }

    positionHighlight(target) {
        const rect = target.getBoundingClientRect();
        const padding = 10;
        
        this.highlightBox.style.top = `${rect.top - padding}px`;
        this.highlightBox.style.left = `${rect.left - padding}px`;
        this.highlightBox.style.width = `${rect.width + (padding * 2)}px`;
        this.highlightBox.style.height = `${rect.height + (padding * 2)}px`;
    }

    positionTooltip(target, position) {
        const rect = target.getBoundingClientRect();
        const tooltipRect = this.tooltipBox.getBoundingClientRect();
        const viewport = {
            width: window.innerWidth,
            height: window.innerHeight
        };
        
        let top, left;
        const spacing = 20;
        const bottomSafeZone = 80; // Extra space at bottom for mobile
        
        // Remove previous position classes
        this.tooltipBox.classList.remove('position-top', 'position-bottom', 'position-left', 'position-right', 'position-center');
        
        if (position === 'center') {
            // Center on screen
            top = (viewport.height - tooltipRect.height) / 2;
            left = (viewport.width - tooltipRect.width) / 2;
            this.tooltipBox.classList.add('position-center');
        } else {
            // Calculate based on position preference
            
            if (position === 'bottom' || position === 'top') {
                // Horizontal centering
                left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
                
                // Ensure tooltip stays within viewport horizontally
                if (left < spacing) left = spacing;
                if (left + tooltipRect.width > viewport.width - spacing) {
                    left = viewport.width - tooltipRect.width - spacing;
                }
                
                if (position === 'bottom') {
                    top = rect.bottom + spacing;
                    this.tooltipBox.classList.add('position-bottom');
                    
                    // If tooltip goes below viewport, show above instead
                    if (top + tooltipRect.height > viewport.height - bottomSafeZone) {
                        top = rect.top - tooltipRect.height - spacing;
                        this.tooltipBox.classList.remove('position-bottom');
                        this.tooltipBox.classList.add('position-top');
                        
                        // If still doesn't fit above, position at top with scroll
                        if (top < spacing) {
                            top = spacing;
                        }
                    }
                } else {
                    top = rect.top - tooltipRect.height - spacing;
                    this.tooltipBox.classList.add('position-top');
                    
                    // If tooltip goes above viewport, show below instead
                    if (top < spacing) {
                        top = rect.bottom + spacing;
                        this.tooltipBox.classList.remove('position-top');
                        this.tooltipBox.classList.add('position-bottom');
                        
                        // If still doesn't fit below, position at bottom minus safe zone
                        if (top + tooltipRect.height > viewport.height - bottomSafeZone) {
                            top = viewport.height - tooltipRect.height - bottomSafeZone;
                        }
                    }
                }
            }
        }
        
        // Final boundary check - ensure tooltip is always visible
        if (top < spacing) top = spacing;
        if (top + tooltipRect.height > viewport.height - bottomSafeZone) {
            top = viewport.height - tooltipRect.height - bottomSafeZone;
        }
        if (left < spacing) left = spacing;
        if (left + tooltipRect.width > viewport.width - spacing) {
            left = viewport.width - tooltipRect.width - spacing;
        }
        
        // Apply position with smooth transition
        this.tooltipBox.style.top = `${top}px`;
        this.tooltipBox.style.left = `${left}px`;
    }

    nextStep() {
        if (this.currentStepIndex < this.guideSteps.length - 1) {
            this.currentStepIndex++;
            this.showStep();
        } else {
            this.finish();
        }
    }

    prevStep() {
        if (this.currentStepIndex > 0) {
            this.currentStepIndex--;
            this.showStep();
        }
    }

    close() {
        this.finish();
    }

    finish() {
        if (this.overlay) {
            this.overlay.remove();
        }
        document.body.style.overflow = '';
        this.isActive = false;
        this.currentStepIndex = 0;
        this.showHelpButton();
    }
}

// Initialize user guide
let userGuide = null;

function showUserGuide() {
    if (!userGuide) {
        userGuide = new UserGuide();
    }
    userGuide.start();
}

// Handle window resize
window.addEventListener('resize', () => {
    if (userGuide && userGuide.isActive) {
        const step = userGuide.guideSteps[userGuide.currentStepIndex];
        const target = document.querySelector(step.target);
        if (target) {
            userGuide.positionHighlight(target);
            userGuide.positionTooltip(target, step.position);
        }
    }
});

// Handle scroll (reposition on scroll)
window.addEventListener('scroll', () => {
    if (userGuide && userGuide.isActive) {
        const step = userGuide.guideSteps[userGuide.currentStepIndex];
        const target = document.querySelector(step.target);
        if (target) {
            userGuide.positionHighlight(target);
            userGuide.positionTooltip(target, step.position);
        }
    }
});
