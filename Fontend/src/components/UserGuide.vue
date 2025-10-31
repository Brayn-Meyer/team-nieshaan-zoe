<template>
  <div v-if="showGuide" class="user-guide-overlay">
    <!-- Highlight Overlay -->
    <div class="highlight-overlay" :style="highlightStyle"></div>
    
    <!-- Guide Content -->
    <div class="guide-content card" :style="guideContentStyle">
      <div class="guide-content-inner">
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
  </div>
</template>

<script>
export default {
  name: 'UserGuide',
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
          title: "Dashboard Overview",
          content: "Welcome to your Employee Attendance Dashboard! This is your main hub for tracking employee attendance in real-time.",
          highlight: { top: '120px', left: '60px', width: 'calc(100% - 120px)', height: 'calc(100% - 170px)' },
          position: { top: '200px', left: '50%', transform: 'translateX(-50%)' }
        },
        {
          title: "Total Employees",
          content: "This card shows the total number of registered employees in your system.",
          highlight: { top: '120px', left: '60px', width: 'calc(25% - 40px)', height: '140px' },
          position: { top: '280px', left: 'calc(12.5% + 60px)' }
        },
        {
          title: "Clock In Status",
          content: "Monitor how many employees are currently clocked in.",
          highlight: { top: '120px', left: 'calc(25% + 40px)', width: 'calc(25% - 40px)', height: '140px' },
          position: { top: '280px', left: '37.5%' }
        },
        {
          title: "Clock Out Status",
          content: "Track employees who have clocked out for the day.",
          highlight: { top: '120px', left: 'calc(50% + 20px)', width: 'calc(25% - 40px)', height: '140px' },
          position: { top: '280px', left: '62.5%' }
        },
        {
          title: "Absent Employees",
          content: "Keep an eye on absent employees.",
          highlight: { top: '120px', left: 'calc(75% + 0px)', width: 'calc(25% - 40px)', height: '140px' },
          position: { top: '280px', left: 'calc(87.5% + 20px)', transform: 'translateX(-100%)' }
        },
        {
          title: "Employee Details Table",
          content: "View detailed information about all employees including their clock-in/out times, status, and department information.",
          highlight: { top: '300px', left: '60px', width: 'calc(100% - 120px)', height: 'calc(100% - 350px)' },
          position: { top: '60%', left: '50%', transform: 'translateX(-50%)' }
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
  box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5); /* Reduced opacity for better visibility */
  pointer-events: none;
  transition: all 0.4s ease;
  animation: pulse 2s infinite;
  z-index: 10001;
}

@keyframes pulse {
  0% { border-color: #2EB28A; }
  50% { border-color: #34d399; }
  100% { border-color: #2EB28A; }
}

.guide-content.card {
  position: absolute;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  min-width: 350px;
  max-width: 450px;
  z-index: 10002; /* Higher than highlight */
  pointer-events: all;
  border-left: 4px solid #2EB28A;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.guide-content:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(46, 178, 138, 0.2);
}

.guide-content-inner {
  padding: 20px 25px;
}

.guide-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.guide-header h3 {
  margin: 0;
  color: #000000;
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
  transition: all 0.3s ease;
}

.close-btn:hover {
  background: #f0fdf4;
  color: #2EB28A;
}

.guide-body {
  margin-bottom: 15px;
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
  font-weight: 500;
  text-align: center;
  padding: 8px 0;
  border-top: 1px solid #e2e8f0;
}

.guide-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 15px;
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
  transform: translateY(-1px);
}

@media (max-width: 768px) {
  .guide-content {
    min-width: 300px;
    max-width: 350px;
    margin: 0 20px;
  }
  
  .guide-content-inner {
    padding: 15px 20px;
  }
}
</style>