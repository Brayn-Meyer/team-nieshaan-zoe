class HistoryUserGuide {
    constructor() {
        this.currentStepIndex = 0;
        this.isActive = false;
        this.guideSteps = [
            {
                title: "History Overview",
                content: "Welcome to the History page! Here you can view and manage all attendance records.",
                target: ".history-header",
                position: "auto"  // Will auto-position based on space
            },
            {
                title: "Employee Name",
                content: "View the employee's full name in this column.",
                target: ".history-card .card-title",
                position: "auto"
            },
            // Add other steps here
        ];
    }

    positionTooltip(tooltip, target) {
        // Get dimensions and positions
        const tooltipRect = tooltip.getBoundingClientRect();
        const targetRect = target.getBoundingClientRect();
        const viewportHeight = window.innerHeight;
        const viewportWidth = window.innerWidth;
        
        // Calculate space above and below the target
        const spaceAbove = targetRect.top;
        const spaceBelow = viewportHeight - targetRect.bottom;
        
        // Default margins
        const margin = 20;
        const mobileMargin = 10;
        
        // Reset any previous positioning
        tooltip.style.top = '';
        tooltip.style.bottom = '';
        tooltip.style.left = '';
        tooltip.style.transform = '';
        
        // Remove previous position classes
        tooltip.classList.remove('position-top', 'position-bottom');
        
        // Center horizontally
        const left = targetRect.left + (targetRect.width - tooltipRect.width) / 2;
        tooltip.style.left = Math.max(margin, Math.min(left, window.innerWidth - tooltipRect.width - margin)) + 'px';
        
        // Handle mobile view differently
        if (viewportWidth < 768) {
            // For mobile, force the tooltip to the opposite side of where the content is
            if (targetRect.top < viewportHeight / 2) {
                // If target is in upper half, put tooltip below
                tooltip.style.top = (targetRect.bottom + margin) + 'px';
                tooltip.style.bottom = 'auto';
                tooltip.classList.add('position-bottom');
                tooltip.classList.remove('position-top');
            } else {
                // If target is in lower half, put tooltip above
                tooltip.style.bottom = (viewportHeight - targetRect.top + margin) + 'px';
                tooltip.style.top = 'auto';
                tooltip.classList.add('position-top');
                tooltip.classList.remove('position-bottom');
            }
        } else {
            // Desktop positioning
            if (spaceBelow >= tooltipRect.height + margin || spaceBelow > spaceAbove) {
                // Position below
                tooltip.style.top = (targetRect.bottom + margin) + 'px';
                tooltip.style.bottom = 'auto';
                tooltip.classList.add('position-bottom');
                tooltip.classList.remove('position-top');
            } else {
                // Position above
                tooltip.style.bottom = (viewportHeight - targetRect.top + margin) + 'px';
                tooltip.style.top = 'auto';
                tooltip.classList.add('position-top');
                tooltip.classList.remove('position-bottom');
            }
        }
        
        // For mobile, ensure the tooltip is fully visible
        if (window.innerWidth < 768) {
            const tooltipNewRect = tooltip.getBoundingClientRect();
            if (tooltipNewRect.top < 0) {
                tooltip.style.top = margin + 'px';
            } else if (tooltipNewRect.bottom > viewportHeight) {
                tooltip.style.bottom = margin + 'px';
            }
        }
    }

    showStep() {
        const step = this.guideSteps[this.currentStepIndex];
        const target = document.querySelector(step.target);
        if (!target || !this.tooltipBox) return;

        // Update tooltip content first
        this.updateTooltipContent(step);
        
        // Position the highlight box around the target
        this.positionHighlight(target);
        
        // Position the tooltip relative to the target
        this.positionTooltip(this.tooltipBox, target);
    }

    // ... rest of the guide implementation
}

// Initialize
const historyGuide = new HistoryUserGuide();