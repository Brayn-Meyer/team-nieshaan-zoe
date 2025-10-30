<template>
  <div v-if="showGuide" class="history-guide-overlay">
    <!-- Highlight Overlay -->
    <div class="highlight-overlay" :style="highlightStyle"></div>
    
    <!-- Guide Content -->
    <div class="guide-content" :style="guideContentStyle">
      <div class="guide-header">
        <h3>{{ currentStep.title }}</h3>
        <button @click="closeGuide" class="close-btn">&times;</button>
      </div>
      <div class="guide-body">
        <p>{{ currentStep.content }}</p>
        <div class="step-indicator">
          Step {{ currentStepIndex + 1 }} of {{ guideSteps.length }}
        </div>
      </div>
      <div class="guide-footer">
        <button 
          v-if="currentStepIndex > 0" 
          @click="prevStep" 
          class="btn-secondary"
        >
          Back
        </button>
        <button @click="nextStep" class="btn-primary">
          {{ currentStepIndex === guideSteps.length - 1 ? 'Finish' : 'Next' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HistoryGuide',
  props: {
    showGuide: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      currentStepIndex: 0,
      guideSteps: [
        {
          title: "Attendance History Overview",
          content: "Welcome to the Attendance History page! Here you can view, filter, and export comprehensive attendance records for all employees.",
          highlight: { top: '120px', left: '1rem', width: 'calc(100% - 2rem)', height: 'calc(100% - 140px)' },
          position: { top: '200px', left: '50%', transform: 'translateX(-50%)' }
        },
        {
          title: "Page Header & Title",
          content: "This section shows you're on the Attendance History page with a clear description of its purpose - filtering employee history.",
          highlight: { top: '120px', left: '1rem', width: 'calc(100% - 2rem)', height: '80px' },
          position: { top: '220px', left: '50%', transform: 'translateX(-50%)' }
        },
        {
          title: "Filter Controls",
          content: "Use these filters to narrow down attendance records by date, employee name, ID, or status. The table will update automatically.",
          highlight: { top: '220px', left: '1rem', width: 'calc(100% - 2rem)', height: '80px' },
          position: { top: '320px', left: '50%', transform: 'translateX(-50%)' }
        },
        {
          title: "Download Button",
          content: "Export filtered attendance data to CSV format. The downloaded file can be opened in Excel or Google Sheets for further analysis.",
          highlight: { top: '320px', left: 'calc(100% - 120px)', width: '110px', height: '40px' },
          position: { top: '380px', left: 'calc(100% - 200px)', transform: 'translateX(-100%)' }
        },
        {
          title: "Attendance Records Table",
          content: "View detailed attendance information including clock-in/out times, tea breaks, lunch breaks, and employee status for each day.",
          highlight: { top: '380px', left: '1rem', width: 'calc(100% - 2rem)', height: 'calc(100% - 420px)' },
          position: { top: '60%', left: '50%', transform: 'translateX(-50%)' }
        },
        {
          title: "Understanding Status Types",
          content: "Employees can have different statuses: 'active' (working), 'on-leave' (absent with permission), 'inactive' (not working), or 'terminated'.",
          highlight: { top: '400px', left: '1rem', width: 'calc(100% - 2rem)', height: '200px' },
          position: { top: '65%', left: '50%', transform: 'translateX(-50%)' }
        }
      ]
    };
  },
  computed: {
    currentStep() {
      return this.guideSteps[this.currentStepIndex];
    },
    highlightStyle() {
      return {
        top: this.currentStep.highlight.top,
        left: this.currentStep.highlight.left,
        width: this.currentStep.highlight.width,
        height: this.currentStep.highlight.height,
      };
    },
    guideContentStyle() {
      return {
        top: this.currentStep.position.top,
        left: this.currentStep.position.left,
        transform: this.currentStep.position.transform
      };
    }
  },
  methods: {
    nextStep() {
      if (this.currentStepIndex < this.guideSteps.length - 1) {
        this.currentStepIndex++;
      } else {
        this.finishGuide();
      }
    },
    prevStep() {
      if (this.currentStepIndex > 0) {
        this.currentStepIndex--;
      }
    },
    closeGuide() {
      this.$emit('close-guide');
      this.resetGuide();
    },
    finishGuide() {
      this.$emit('finish-guide');
      this.resetGuide();
    },
    resetGuide() {
      this.currentStepIndex = 0;
    }
  },
  watch: {
    showGuide(newVal) {
      if (newVal) {
        this.resetGuide();
      }
    }
  }
};
</script>

<style scoped>
.history-guide-overlay {
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
  border: 3px solid #10b981;
  border-radius: 12px;
  box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.7);
  pointer-events: none;
  transition: all 0.4s ease;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% { border-color: #10b981; }
  50% { border-color: #34d399; }
  100% { border-color: #10b981; }
}

.guide-content {
  position: absolute;
  background: white;
  border-radius: 12px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
  min-width: 350px;
  max-width: 450px;
  z-index: 10001;
  pointer-events: all;
  border: 1px solid #e2e8f0;
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
  color: #065f46;
  font-size: 1.3em;
  font-weight: 600;
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
  color: #059669;
  font-weight: 500;
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
  background: #10b981;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background: #059669;
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
    min-width: 300px;
    max-width: 350px;
    margin: 0 20px;
  }
  
  .guide-header,
  .guide-body,
  .guide-footer {
    padding: 15px 20px;
  }
}
</style>