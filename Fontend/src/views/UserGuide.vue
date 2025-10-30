<template>
  <NavComp @theme-changed="handleThemeChange"/>
  <br><br><br>
  <div class="user-guide-page" :class="{ 'dark-mode': isDarkMode }">
    <!-- Header -->
    <div class="guide-header-section">
      <div class="header-content">
        <h1 class="guide-title">Employee Attendance System Guide</h1>
        <p class="guide-subtitle">Learn how to effectively monitor and manage employee attendance in real-time</p>
      </div>
    </div>

    <!-- Main Content -->
    <div class="guide-content-section">
      <div class="content-grid">
        <!-- Dashboard Overview -->
        <div class="guide-section">
          <div class="section-header">
            <div class="section-icon">
              <i class="fa-solid fa-gauge-high"></i>
            </div>
            <h2>Dashboard Overview</h2>
          </div>
          <div class="section-content">
            <p>Your dashboard provides a comprehensive real-time view of employee attendance with key statistics and detailed employee information.</p>
            <div class="feature-list">
              <div class="feature-item">
                <i class="fa-solid fa-circle-check"></i>
                <span>Real-time attendance monitoring</span>
              </div>
              <div class="feature-item">
                <i class="fa-solid fa-circle-check"></i>
                <span>Live employee status updates</span>
              </div>
              <div class="feature-item">
                <i class="fa-solid fa-circle-check"></i>
                <span>Comprehensive analytics and reporting</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Attendance Statistics Cards -->
        <div class="guide-section">
          <div class="section-header">
            <div class="section-icon">
              <i class="fa-solid fa-chart-pie"></i>
            </div>
            <h2>Attendance Statistics Cards</h2>
          </div>
          <div class="section-content">
            <p>These four cards provide an at-a-glance view of your organization's attendance status:</p>
            <div class="card-examples">
              <div class="example-card total-employees">
                <div class="card-icon">
                  <i class="fa-solid fa-users"></i>
                </div>
                <div class="card-content">
                  <h4>Total Employees</h4>
                  <p class="card-value">120</p>
                  <p class="card-desc">All registered employees</p>
                  <p class="card-change positive">+5% from last month</p>
                </div>
              </div>
              <div class="example-card clock-in">
                <div class="card-icon">
                  <i class="fa-solid fa-door-open"></i>
                </div>
                <div class="card-content">
                  <h4>Clock In</h4>
                  <p class="card-value">95</p>
                  <p class="card-desc">Currently working</p>
                  <p class="card-change positive">+3% today</p>
                </div>
              </div>
              <div class="example-card clock-out">
                <div class="card-icon">
                  <i class="fa-solid fa-door-closed"></i>
                </div>
                <div class="card-content">
                  <h4>Clock Out</h4>
                  <p class="card-value">20</p>
                  <p class="card-desc">Ended shifts today</p>
                  <p class="card-change negative">-1% today</p>
                </div>
              </div>
              <div class="example-card absent">
                <div class="card-icon">
                  <i class="fa-solid fa-user-xmark"></i>
                </div>
                <div class="card-content">
                  <h4>Absent</h4>
                  <p class="card-value">5</p>
                  <p class="card-desc">Not present today</p>
                  <p class="card-change">No change</p>
                </div>
              </div>
            </div>
            <p class="section-tip">💡 Cards update automatically throughout the day as employees clock in and out.</p>
          </div>
        </div>

        <!-- Employee Table -->
        <div class="guide-section">
          <div class="section-header">
            <div class="section-icon">
              <i class="fa-solid fa-table"></i>
            </div>
            <h2>Employee Details Table</h2>
          </div>
          <div class="section-content">
            <p>The employee table provides comprehensive details for all staff members with the following information:</p>
            <div class="table-features">
              <div class="table-feature">
                <strong>Employee Identification:</strong> Names, employee IDs, and contact information
              </div>
              <div class="table-feature">
                <strong>Department & Role:</strong> Organizational structure and job positions
              </div>
              <div class="table-feature">
                <strong>Attendance Status:</strong> Real-time clock-in/clock-out/absent status with color coding
              </div>
              <div class="table-feature">
                <strong>Time Tracking:</strong> Exact clock-in times, clock-out times, and total hours worked
              </div>
              <div class="table-feature">
                <strong>Search & Filter:</strong> Find employees by name, department, or status
              </div>
              <div class="table-feature">
                <strong>Sorting:</strong> Organize data by any column for better analysis
              </div>
            </div>
          </div>
        </div>

        <!-- Attendance History Section -->
        <div class="guide-section">
          <div class="section-header">
            <div class="section-icon">
              <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <h2>Attendance History</h2>
          </div>
          <div class="section-content">
            <p>The Attendance History page allows you to review past attendance records with detailed breakdowns and filtering capabilities.</p>
            
            <div class="history-features">
              <div class="history-feature">
                <div class="feature-header">
                  <i class="fa-solid fa-filter"></i>
                  <h4>Advanced Filtering</h4>
                </div>
                <p>Filter records by specific dates, employee names, IDs, or status to find exactly what you need.</p>
              </div>
              
              <div class="history-feature">
                <div class="feature-header">
                  <i class="fa-solid fa-download"></i>
                  <h4>Data Export</h4>
                </div>
                <p>Download filtered attendance data as CSV files for further analysis in Excel or Google Sheets.</p>
              </div>
              
              <div class="history-feature">
                <div class="feature-header">
                  <i class="fa-solid fa-list-check"></i>
                  <h4>Detailed Break Tracking</h4>
                </div>
                <p>View comprehensive break records including tea breaks (out/in) and lunch breaks (out/in) for each employee.</p>
              </div>
              
              <div class="history-feature">
                <div class="feature-header">
                  <i class="fa-solid fa-user-check"></i>
                  <h4>Status Monitoring</h4>
                </div>
                <p>Track employee status changes over time including active, on-leave, inactive, and terminated statuses.</p>
              </div>
            </div>

            <div class="history-example">
              <h4>Sample History Record:</h4>
              <div class="record-example">
                <div class="record-item">
                  <strong>Employee:</strong> John Adams (EMP001)
                </div>
                <div class="record-item">
                  <strong>Date:</strong> 2025-10-23
                </div>
                <div class="record-item">
                  <strong>Schedule:</strong> 08:00 (In) → 10:00 (Tea Out) → 10:15 (Tea In) → 12:30 (Lunch Out) → 13:00 (Lunch In) → 17:00 (Out)
                </div>
                <div class="record-item">
                  <strong>Status:</strong> <span class="status-active">Active</span>
                </div>
              </div>
            </div>

            <p class="section-tip">💡 Use the download feature to export historical data for payroll processing or compliance reporting.</p>
          </div>
        </div>

        <!-- Navigation & Layout -->
        <div class="guide-section">
          <div class="section-header">
            <div class="section-icon">
              <i class="fa-solid fa-compass"></i>
            </div>
            <h2>Navigation & Layout</h2>
          </div>
          <div class="section-content">
            <p>The system is organized for easy navigation and quick access to information:</p>
            <div class="feature-list">
              <div class="feature-item">
                <i class="fa-solid fa-bars"></i>
                <span><strong>Navigation Bar:</strong> Access different sections of the application</span>
              </div>
              <div class="feature-item">
                <i class="fa-solid fa-chart-simple"></i>
                <span><strong>Dashboard:</strong> Real-time overview and current status</span>
              </div>
              <div class="feature-item">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span><strong>History:</strong> Past attendance records and reporting</span>
              </div>
              <div class="feature-item">
                <i class="fa-solid fa-circle-question"></i>
                <span><strong>Help Access:</strong> Use the Help Guide button for assistance</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Data Interpretation -->
        <div class="guide-section">
          <div class="section-header">
            <div class="section-icon">
              <i class="fa-solid fa-magnifying-glass-chart"></i>
            </div>
            <h2>Understanding Your Data</h2>
          </div>
          <div class="section-content">
            <div class="data-interpretation">
              <div class="data-point">
                <h4>Total Employees (120)</h4>
                <p>This represents your entire workforce. Compare this with clocked-in numbers to gauge daily participation.</p>
              </div>
              <div class="data-point">
                <h4>Clock In vs Clock Out</h4>
                <p>95 clocked-in + 20 clocked-out = 115 accounted for. The remaining 5 are shown as absent.</p>
              </div>
              <div class="data-point">
                <h4>Percentage Changes</h4>
                <p>Green (+) indicates improvement, Red (-) shows decrease. Monitor trends over time.</p>
              </div>
              <div class="data-point">
                <h4>Real-time Updates</h4>
                <p>All data updates automatically as employees clock in and out throughout the day.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Best Practices -->
        <div class="guide-section">
          <div class="section-header">
            <div class="section-icon">
              <i class="fa-solid fa-lightbulb"></i>
            </div>
            <h2>Best Practices</h2>
          </div>
          <div class="section-content">
            <div class="best-practices">
              <div class="practice-item">
                <div class="practice-icon">📊</div>
                <div class="practice-content">
                  <h4>Daily Monitoring</h4>
                  <p>Check the dashboard each morning to review attendance patterns and identify any issues early.</p>
                </div>
              </div>
              <div class="practice-item">
                <div class="practice-icon">🔍</div>
                <div class="practice-content">
                  <h4>Regular Analysis</h4>
                  <p>Use the table filters to analyze attendance by department, role, or time periods.</p>
                </div>
              </div>
              <div class="practice-item">
                <div class="practice-icon">⏰</div>
                <div class="practice-content">
                  <h4>Timely Action</h4>
                  <p>Address attendance issues promptly using the real-time data provided.</p>
                </div>
              </div>
              <div class="practice-item">
                <div class="practice-icon">📈</div>
                <div class="practice-content">
                  <h4>Trend Spotting</h4>
                  <p>Monitor percentage changes to identify positive or negative attendance trends.</p>
                </div>
              </div>
              <div class="practice-item">
                <div class="practice-icon">📋</div>
                <div class="practice-content">
                  <h4>Historical Review</h4>
                  <p>Regularly check the History page to spot patterns and generate reports for management.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="guide-sidebar">
        <div class="sidebar-section">
          <h3>Quick Actions</h3>
          <div class="help-options">
            <button class="help-option" @click="startInteractiveGuide">
              <i class="fa-solid fa-play-circle"></i>
              Interactive Tour
            </button>
            <button class="help-option" @click="viewPDFGuide">
              <i class="fa-solid fa-file-pdf"></i>
              Printable Guide
            </button>
            <button class="help-option" @click="contactSupport">
              <i class="fa-solid fa-phone"></i>
              Get Support
            </button>
          </div>
        </div>

        <div class="sidebar-section">
          <h3>System Features</h3>
          <div class="feature-highlights">
            <div class="feature-highlight">
              <i class="fa-solid fa-users"></i>
              <span>120 Employee Capacity</span>
            </div>
            <div class="feature-highlight">
              <i class="fa-solid fa-clock"></i>
              <span>Real-time Updates</span>
            </div>
            <div class="feature-highlight">
              <i class="fa-solid fa-chart-line"></i>
              <span>Trend Analytics</span>
            </div>
            <div class="feature-highlight">
              <i class="fa-solid fa-history"></i>
              <span>Historical Data</span>
            </div>
            <div class="feature-highlight">
              <i class="fa-solid fa-download"></i>
              <span>Data Export</span>
            </div>
            <div class="feature-highlight">
              <i class="fa-solid fa-mobile-screen"></i>
              <span>Mobile Responsive</span>
            </div>
          </div>
        </div>

        <div class="sidebar-section">
          <h3>Need Help?</h3>
          <div class="quick-links">
            <a href="#" class="quick-link">Attendance Policies</a>
            <a href="#" class="quick-link">Employee Handbook</a>
            <a href="#" class="quick-link">System FAQ</a>
            <a href="#" class="quick-link">Technical Support</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import NavComp from '@/components/NavComp.vue';

export default {
  components: {
    NavComp
  },
  name: "UserGuidePage",
  data() {
    return {
      isDarkMode: false
    }
  },
  methods: {
    handleThemeChange(isDarkMode) {
      this.isDarkMode = isDarkMode;
    },
    startInteractiveGuide() {
      this.$router.push('/?guide=true');
    },
    contactSupport() {
      alert('For technical support or questions:\n\nEmail: support@attendance-system.com\nPhone: +1 (555) 123-4567\nHours: Monday-Friday, 8:00 AM - 6:00 PM EST');
    },
    viewPDFGuide() {
      const pdfHTML = this.generatePDFHTML();
      const newWindow = window.open('', '_blank');
      newWindow.document.write(pdfHTML);
      newWindow.document.close();
      this.addPrintStyles(newWindow);
      newWindow.focus();
    },
    generatePDFHTML() {
      const currentDate = new Date().toLocaleDateString();
      return `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance System Guide</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 40px 20px; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 3px solid #10b981; padding-bottom: 20px; }
        .header h1 { color: #065f46; font-size: 2.5em; margin-bottom: 10px; }
        .section { margin-bottom: 30px; page-break-inside: avoid; }
        .section h2 { color: #065f46; border-left: 4px solid #10b981; padding-left: 15px; margin: 25px 0 15px 0; }
        .feature-item { margin: 8px 0; display: flex; align-items: center; }
        .feature-item:before { content: "✓"; color: #10b981; font-weight: bold; margin-right: 10px; }
        .card-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 20px 0; }
        .card { background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #10b981; padding: 15px; border-radius: 8px; }
        .contact-info { background: #f0fdf4; border: 1px solid #a7f3d0; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 2px solid #e2e8f0; color: #6b7280; }
        .history-feature { background: #f8fafc; padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 3px solid #10b981; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Employee Attendance System</h1>
        <div class="subtitle">Complete User Guide & Documentation</div>
    </div>

    <div class="section">
        <h2>Dashboard Overview</h2>
        <p>Real-time attendance monitoring system tracking 120 employees with live status updates and comprehensive analytics.</p>
    </div>

    <div class="section">
        <h2>Key Statistics</h2>
        <div class="card-grid">
            <div class="card"><strong>Total Employees:</strong> 120 (+5%)</div>
            <div class="card"><strong>Clocked In:</strong> 95 (+3%)</div>
            <div class="card"><strong>Clocked Out:</strong> 20 (-1%)</div>
            <div class="card"><strong>Absent:</strong> 5 (No change)</div>
        </div>
    </div>

    <div class="section">
        <h2>Attendance History Features</h2>
        <div class="history-feature">
            <strong>Advanced Filtering:</strong> Filter by date, employee name, ID, or status
        </div>
        <div class="history-feature">
            <strong>Data Export:</strong> Download CSV files for Excel or Google Sheets
        </div>
        <div class="history-feature">
            <strong>Break Tracking:</strong> Detailed tea and lunch break records
        </div>
        <div class="history-feature">
            <strong>Status Monitoring:</strong> Track active, on-leave, inactive, and terminated employees
        </div>
    </div>

    <div class="section">
        <h2>Features</h2>
        <div class="feature-item">Real-time employee status monitoring</div>
        <div class="feature-item">Comprehensive attendance analytics</div>
        <div class="feature-item">Detailed employee information table</div>
        <div class="feature-item">Historical attendance records</div>
        <div class="feature-item">Search and filter capabilities</div>
        <div class="feature-item">Data export functionality</div>
        <div class="feature-item">Mobile-responsive design</div>
    </div>

    <div class="section">
        <h2>Best Practices</h2>
        <div class="feature-item">Monitor dashboard daily for attendance patterns</div>
        <div class="feature-item">Use filters to analyze department-specific data</div>
        <div class="feature-item">Address attendance issues promptly</div>
        <div class="feature-item">Track trends using percentage change indicators</div>
        <div class="feature-item">Export historical data for reporting and analysis</div>
    </div>

    <div class="section">
        <h2>Support</h2>
        <div class="contact-info">
            <p><strong>Technical Support:</strong><br>
            Email: support@attendance-system.com<br>
            Phone: +1 (555) 123-4567<br>
            Hours: Mon-Fri, 8:00 AM - 6:00 PM EST</p>
        </div>
    </div>

    <div class="footer">
        <p>Employee Attendance System Guide | Version 2.0 | ${currentDate}</p>
    </div>
</body>
</html>`;
    },
    addPrintStyles(win) {
      const style = win.document.createElement('style');
      style.textContent = `@media print { body { margin: 0; padding: 20px; font-size: 12pt; } .header { margin-bottom: 20px; } .section { margin-bottom: 20px; } }`;
      win.document.head.appendChild(style);
    }
  },
  mounted() {
    // Check for saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
      this.isDarkMode = true;
    }
  }
};
</script>

<style scoped>
/* Dark mode styles for UserGuide */
.user-guide-page.dark-mode {
  background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
  color: #e0e0e0;
}

.user-guide-page.dark-mode .guide-header-section,
.user-guide-page.dark-mode .guide-section,
.user-guide-page.dark-mode .sidebar-section {
  background: #2d2d2d;
  border-color: #404040;
  color: #e0e0e0;
}

.user-guide-page.dark-mode .guide-title,
.user-guide-page.dark-mode .guide-subtitle,
.user-guide-page.dark-mode .section-header h2,
.user-guide-page.dark-mode .sidebar-section h3 {
  color: #e0e0e0;
}

.user-guide-page.dark-mode .section-content p,
.user-guide-page.dark-mode .feature-item,
.user-guide-page.dark-mode .history-feature p,
.user-guide-page.dark-mode .record-item,
.user-guide-page.dark-mode .data-point p,
.user-guide-page.dark-mode .practice-content p {
  color: #b0b0b0;
}

.user-guide-page.dark-mode .example-card,
.user-guide-page.dark-mode .history-feature,
.user-guide-page.dark-mode .data-point,
.user-guide-page.dark-mode .practice-item,
.user-guide-page.dark-mode .table-feature,
.user-guide-page.dark-mode .help-option {
  background: #363636;
  border-color: #404040;
}

.user-guide-page.dark-mode .section-tip,
.user-guide-page.dark-mode .history-example {
  background: #2a3a2e;
  border-color: #404040;
  color: #e0e0e0;
}

.user-guide-page.dark-mode .feature-highlight {
  background: #2a3a2e;
  color: #e0e0e0;
}

.user-guide-page.dark-mode .quick-link {
  color: #4ade80;
}

.user-guide-page.dark-mode .quick-link:hover {
  color: #86efac;
}

.user-guide-page.dark-mode .help-option:hover {
  background: #404040;
  border-color: #4ade80;
  color: #e0e0e0;
}

.user-guide-page.dark-mode .section-icon {
  background: #2a3a2e;
  color: #4ade80;
}

.user-guide-page.dark-mode .card-icon {
  background: #2a3a2e;
  color: #4ade80;
}

.user-guide-page.dark-mode .card-content h4,
.user-guide-page.dark-mode .card-value {
  color: #e0e0e0;
}

.user-guide-page.dark-mode .card-desc {
  color: #b0b0b0;
}

.user-guide-page.dark-mode .card-change.positive {
  color: #4ade80;
}

.user-guide-page.dark-mode .card-change.negative {
  color: #f87171;
}

.user-guide-page.dark-mode .status-active {
  color: #4ade80;
}

.user-guide-page.dark-mode .feature-header i {
  color: #4ade80;
}

.user-guide-page.dark-mode .feature-header h4 {
  color: #e0e0e0;
}

.user-guide-page.dark-mode .record-example {
  background: #363636;
  border-color: #404040;
}

.user-guide-page.dark-mode .record-item strong {
  color: #4ade80;
}

/* Rest of your existing CSS remains the same */
.user-guide-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
  padding: 20px;
  transition: all 0.3s ease;
}

.guide-header-section {
  background: white;
  border-radius: 16px;
  padding: 40px;
  margin-bottom: 30px;
  box-shadow: 0 4px 20px rgba(16, 185, 129, 0.1);
  border: 1px solid #d1fae5;
  text-align: center;
  transition: all 0.3s ease;
}

.guide-title {
  color: #065f46;
  font-size: 2.5em;
  font-weight: 700;
  margin-bottom: 10px;
  transition: color 0.3s ease;
}

.guide-subtitle {
  color: #059669;
  font-size: 1.2em;
  margin-bottom: 0;
  transition: color 0.3s ease;
}

.guide-content-section {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 30px;
  max-width: 1400px;
  margin: 0 auto;
}

.content-grid {
  display: flex;
  flex-direction: column;
  gap: 25px;
}

.guide-section {
  background: white;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  border: 1px solid #e2e8f0;
  transition: all 0.3s ease;
}

.guide-section:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 20px rgba(16, 185, 129, 0.15);
}

.section-header {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 20px;
}

.section-icon {
  width: 50px;
  height: 50px;
  background: #d1fae5;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #065f46;
  font-size: 1.2em;
}

.section-header h2 {
  color: #065f46;
  font-size: 1.5em;
  font-weight: 600;
  margin: 0;
  transition: color 0.3s ease;
}

.section-content p {
  color: #374151;
  line-height: 1.6;
  margin-bottom: 15px;
  transition: color 0.3s ease;
}

.feature-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 15px;
}

.feature-item {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #059669;
  font-weight: 500;
  transition: color 0.3s ease;
}

.feature-item i {
  color: #10b981;
}

.card-examples {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin: 20px 0;
}

.example-card {
  background: #f8fafc;
  border-radius: 10px;
  padding: 20px;
  border-left: 4px solid #10b981;
  display: flex;
  align-items: center;
  gap: 15px;
}

.card-icon {
  width: 40px;
  height: 40px;
  background: #d1fae5;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #065f46;
}

.card-content h4 {
  margin: 0 0 5px 0;
  color: #065f46;
  font-size: 0.9em;
}

.card-value {
  font-size: 1.5em;
  font-weight: 700;
  color: #065f46;
  margin: 0;
}

.card-desc {
  font-size: 0.8em;
  color: #6b7280;
  margin: 0;
}

.card-change {
  font-size: 0.75em;
  margin: 5px 0 0 0;
  font-weight: 600;
}

.card-change.positive {
  color: #10b981;
}

.card-change.negative {
  color: #ef4444;
}

.section-tip {
  background: #f0fdf4;
  border: 1px solid #a7f3d0;
  border-radius: 8px;
  padding: 15px;
  color: #065f46;
  font-style: italic;
}

.table-features {
  display: grid;
  gap: 10px;
  margin-top: 15px;
}

.table-feature {
  padding: 10px 15px;
  background: #f8fafc;
  border-radius: 6px;
  border-left: 3px solid #10b981;
}

/* History Section Styles */
.history-features {
  display: flex;
  flex-direction: column;
  gap: 15px;
  margin: 20px 0;
}

.history-feature {
  background: #f8fafc;
  padding: 20px;
  border-radius: 10px;
  border-left: 4px solid #10b981;
  transition: transform 0.2s ease;
}

.history-feature:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
}

.feature-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 10px;
}

.feature-header i {
  color: #10b981;
  font-size: 1.2em;
}

.feature-header h4 {
  color: #065f46;
  margin: 0;
  font-size: 1.1em;
}

.history-feature p {
  margin: 0;
  color: #6b7280;
  line-height: 1.5;
}

.history-example {
  background: #f0fdf4;
  border: 1px solid #a7f3d0;
  border-radius: 10px;
  padding: 20px;
  margin: 25px 0;
}

.history-example h4 {
  color: #065f46;
  margin: 0 0 15px 0;
  font-size: 1.1em;
}

.record-example {
  background: white;
  border-radius: 8px;
  padding: 15px;
  border: 1px solid #d1fae5;
}

.record-item {
  margin: 8px 0;
  color: #374151;
}

.record-item strong {
  color: #065f46;
}

.status-active {
  color: #10b981;
  font-weight: 600;
}

.data-interpretation {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.data-point {
  background: #f8fafc;
  padding: 15px;
  border-radius: 8px;
  border-left: 3px solid #10b981;
}

.data-point h4 {
  color: #065f46;
  margin: 0 0 8px 0;
  font-size: 1em;
}

.data-point p {
  margin: 0;
  color: #6b7280;
  font-size: 0.9em;
}

.best-practices {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.practice-item {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  padding: 15px;
  background: #f8fafc;
  border-radius: 8px;
}

.practice-icon {
  font-size: 1.5em;
  min-width: 40px;
}

.practice-content h4 {
  color: #065f46;
  margin: 0 0 5px 0;
  font-size: 1em;
}

.practice-content p {
  margin: 0;
  color: #6b7280;
  font-size: 0.9em;
}

.guide-sidebar {
  display: flex;
  flex-direction: column;
  gap: 25px;
}

.sidebar-section {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  border: 1px solid #e2e8f0;
}

.sidebar-section h3 {
  color: #065f46;
  margin: 0 0 20px 0;
  font-size: 1.2em;
}

.help-options {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.help-option {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 12px 15px;
  color: #374151;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 10px;
  text-align: left;
  width: 100%;
  font-family: inherit;
  font-size: 0.9em;
}

.help-option:hover {
  background: #f0fdf4;
  border-color: #a7f3d0;
  color: #065f46;
}

.feature-highlights {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.feature-highlight {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px;
  background: #f0fdf4;
  border-radius: 6px;
  color: #065f46;
  font-weight: 500;
}

.feature-highlight i {
  color: #10b981;
}

.quick-links {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.quick-link {
  color: #059669;
  text-decoration: none;
  padding: 8px 0;
  border-bottom: 1px solid #e2e8f0;
  transition: color 0.3s ease;
}

.quick-link:hover {
  color: #065f46;
}

.quick-link:last-child {
  border-bottom: none;
}

@media (max-width: 1024px) {
  .guide-content-section {
    grid-template-columns: 1fr;
  }
  
  .guide-sidebar {
    order: -1;
  }
}

@media (max-width: 768px) {
  .user-guide-page {
    padding: 15px;
  }
  
  .guide-header-section {
    padding: 30px 20px;
  }
  
  .guide-title {
    font-size: 2em;
  }
  
  .guide-section {
    padding: 20px;
  }
  
  .card-examples {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 480px) {
  .guide-title {
    font-size: 1.5em;
  }
  
  .section-header {
    flex-direction: column;
    text-align: center;
    gap: 10px;
  }
}
</style>