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
        this.highlightedTarget = null;
        this.cloneTarget = null;
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
    // Start hidden to avoid flashing / incorrect initial sizing
    this.highlightBox.style.visibility = 'hidden';
    this.highlightBox.style.opacity = '0';
    this.highlightBox.style.transition = 'none';
    this.highlightBox.style.animation = 'none';
        
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
    // Append highlight to overlay, but place the tooltip directly on body so it
    // is not affected by any stacking/overflow quirks of the overlay container.
    this.overlay.appendChild(this.highlightBox);
    document.body.appendChild(this.overlay);
    document.body.appendChild(this.tooltipBox);
    // Keep tooltip hidden until positioned to avoid flicker
    this.tooltipBox.style.visibility = 'hidden';
    this.tooltipBox.style.opacity = '0';
    this.tooltipBox.style.transition = 'none';
        
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

        // Remove previous temporary highlight class if applied
        if (this.highlightedTarget) {
            this.highlightedTarget.classList.remove('guide-target-highlighted');
            this.highlightedTarget = null;
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
            // If this is the last step, prefer centering the tooltip so it's fully visible
            const isLastStep = this.currentStepIndex === this.guideSteps.length - 1;
            if (isLastStep) {
                this.positionTooltip(target, 'center');
            } else {
                this.positionTooltip(target, step.position);
            }

            // Remove any previous clone
            if (this.cloneTarget) {
                this.cloneTarget.remove();
                this.cloneTarget = null;
            }

            // Create a visual clone of the target and place it above the highlight so
            // dark-mode global rules or stacking contexts can't obscure its content.
            try {
                const rect = target.getBoundingClientRect();
                const clone = target.cloneNode(true);
                clone.classList.add('guide-clone');
                // Reset ids to avoid duplicates
                if (clone.id) clone.removeAttribute('id');
                // Position the clone exactly over the original
                Object.assign(clone.style, {
                    position: 'fixed',
                    top: `${rect.top}px`,
                    left: `${rect.left}px`,
                    width: `${rect.width}px`,
                    height: `${rect.height}px`,
                    margin: '0',
                    zIndex: '1805',
                    pointerEvents: 'none',
                    overflow: 'hidden'
                });
                // copy border-radius from the target so the clone looks natural
                try {
                    const computed = window.getComputedStyle(target);
                    clone.style.borderRadius = computed.borderRadius;
                    // subtle shadow to lift the clone above the mask without forcing light colors
                    clone.style.boxShadow = '0 8px 24px rgba(0,0,0,0.45)';
                } catch (e) {}
                document.body.appendChild(clone);
                this.cloneTarget = clone;
            } catch (e) {
                // ignore clone errors
                this.cloneTarget = null;
            }

            // Apply temporary high-contrast class so the target is visible in dark mode
            try {
                target.classList.add('guide-target-highlighted');
                this.highlightedTarget = target;
            } catch (e) {
                // ignore if cannot add class
            }
        }, 300);
    }

    positionHighlight(target) {
        const rect = target.getBoundingClientRect();
        const padding = 10;
        
        this.highlightBox.style.top = `${rect.top - padding}px`;
        this.highlightBox.style.left = `${rect.left - padding}px`;
        this.highlightBox.style.width = `${rect.width + (padding * 2)}px`;
        this.highlightBox.style.height = `${rect.height + (padding * 2)}px`;
        // Reveal highlight smoothly after sizing to avoid seeing it at full-viewport size
        // Small timeout lets layout settle (use requestAnimationFrame for accuracy)
        requestAnimationFrame(() => {
            this.highlightBox.style.visibility = 'visible';
            // restore transition if previously disabled
            this.highlightBox.style.transition = this.highlightBox.style.transition || 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
            // fade/animate in
            this.highlightBox.style.opacity = '1';
        });
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
        
        // Apply position with smooth transition. To avoid rounding/animation issues
        // (which can leave the tooltip clipped at certain zoom levels), temporarily
        // disable animations/transitions, measure, clamp into viewport, then restore
        // and make visible.
        const prevTransition = this.tooltipBox.style.transition;
        const prevTransform = this.tooltipBox.style.transform;
        const prevAnimation = this.tooltipBox.style.animation;
        try {
            this.tooltipBox.style.transition = 'none';
            this.tooltipBox.style.animation = 'none';
            this.tooltipBox.style.transform = 'none';
            this.tooltipBox.style.top = `${top}px`;
            this.tooltipBox.style.left = `${left}px`;

            // Force reflow so measurements are accurate
            // eslint-disable-next-line no-unused-expressions
            this.tooltipBox.offsetHeight;

            const finalRect = this.tooltipBox.getBoundingClientRect();
            // clamp vertically
            if (finalRect.top < spacing) {
                top = spacing;
            }
            if (finalRect.bottom > viewport.height - bottomSafeZone) {
                top = Math.max(spacing, viewport.height - finalRect.height - bottomSafeZone);
            }
            // clamp horizontally
            let leftClamp = Math.min(Math.max(left, spacing), Math.max(spacing, viewport.width - finalRect.width - spacing));

            // If computed leftClamp still causes overflow due to shadows/antialiasing,
            // nudge it slightly left.
            if (leftClamp + finalRect.width > viewport.width - spacing) {
                leftClamp = Math.max(spacing, viewport.width - finalRect.width - spacing - 6);
            }

            this.tooltipBox.style.top = `${top}px`;
            this.tooltipBox.style.left = `${leftClamp}px`;
        } catch (e) {
            // ignore measurement errors
            this.tooltipBox.style.top = `${top}px`;
            this.tooltipBox.style.left = `${left}px`;
        } finally {
            // restore animation/transition (small timeout so entry animation still feels smooth)
            setTimeout(() => {
                this.tooltipBox.style.transition = prevTransition || '';
                this.tooltipBox.style.transform = prevTransform || '';
                this.tooltipBox.style.animation = prevAnimation || '';
                // Ensure tooltip is visible
                this.tooltipBox.style.visibility = 'visible';
                this.tooltipBox.style.opacity = '1';
            }, 20);
        }
    }

    positionClone(target) {
        if (!this.cloneTarget || !target) return;
        const rect = target.getBoundingClientRect();
        Object.assign(this.cloneTarget.style, {
            top: `${rect.top}px`,
            left: `${rect.left}px`,
            width: `${rect.width}px`,
            height: `${rect.height}px`
        });
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
        if (this.tooltipBox) {
            this.tooltipBox.remove();
            this.tooltipBox = null;
        }
        // remove temporary highlight class if present
        if (this.highlightedTarget) {
            this.highlightedTarget.classList.remove('guide-target-highlighted');
            this.highlightedTarget = null;
        }
        // remove clone if present
        if (this.cloneTarget) {
            this.cloneTarget.remove();
            this.cloneTarget = null;
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
            userGuide.positionClone(target);
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
            userGuide.positionClone(target);
        }
    }
});
