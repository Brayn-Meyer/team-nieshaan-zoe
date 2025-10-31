<template>
  <div class="user-guide-page">
   
    <!-- Main Content -->
    <div class="guide-content-section">

      <!-- Dashboard Overview -->
      <div class="guide-section">
        <div class="section-header">
          <div class="section-icon"><i class="fa-solid fa-gauge-high"></i></div>
          <h2>Dashboard Overview</h2>
        </div>
        <p>Your dashboard provides a comprehensive real-time view of employee attendance with key statistics and detailed employee information.</p>
        <div class="feature-list">
          <div class="feature-item"><i class="fa-solid fa-circle-check"></i> Real-time attendance monitoring</div>
          <div class="feature-item"><i class="fa-solid fa-circle-check"></i> Live employee status updates</div>
          <div class="feature-item"><i class="fa-solid fa-circle-check"></i> Comprehensive analytics and reporting</div>
        </div>
      </div>

      <!-- Attendance Statistics -->
      <div class="guide-section">
        <div class="section-header">
          <div class="section-icon"><i class="fa-solid fa-chart-pie"></i></div>
          <h2>Attendance Statistics Cards</h2>
        </div>
        <ul>
          <li><strong>Total Employees:</strong> Shows the total number of registered employees in the system.</li>
          <li><strong>Clock In:</strong> Displays how many employees are currently clocked in and working.</li>
          <li><strong>Clock Out:</strong> Indicates the number of employees who have completed their shift for the day.</li>
          <li><strong>Absent:</strong> Lists the number of employees who haven’t clocked in for the day.</li>
        </ul>
        <p class="section-tip">💡 Cards update automatically throughout the day as employees clock in and out.</p>
      </div>

      <!-- Employee Table -->
      <div class="guide-section">
        <div class="section-header">
          <div class="section-icon"><i class="fa-solid fa-table"></i></div>
          <h2>Employee Details Table</h2>
        </div>
        <p>The employee table provides comprehensive details for all staff members:</p>
        <div class="table-features">
          <div class="table-feature"><strong>Employee Identification:</strong> Names, employee IDs, and contact information</div>
          <div class="table-feature"><strong>Department & Role:</strong> Organizational structure and job positions</div>
          <div class="table-feature"><strong>Attendance Status:</strong> Real-time clock-in/clock-out/absent status with color coding</div>
          <div class="table-feature"><strong>Time Tracking:</strong> Exact clock-in/out times and total hours worked</div>
          <div class="table-feature"><strong>Search & Filter:</strong> Find employees by name, department, or status</div>
          <div class="table-feature"><strong>Sorting:</strong> Organize data by any column for better analysis</div>
        </div>
      </div>

      <!-- Attendance History -->
      <div class="guide-section">
        <div class="section-header">
          <div class="section-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
          <h2>Attendance History</h2>
        </div>
        <p>Review past attendance records with detailed breakdowns and filtering capabilities.</p>
        <div class="history-features">
          <div class="history-feature">
            <div class="feature-header"><i class="fa-solid fa-filter"></i><h4>Advanced Filtering</h4></div>
            <p>Filter records by specific dates, employee names, IDs, or status.</p>
          </div>
          <div class="history-feature">
            <div class="feature-header"><i class="fa-solid fa-download"></i><h4>Data Export</h4></div>
            <p>Download filtered attendance data as CSV files.</p>
          </div>
          <div class="history-feature">
            <div class="feature-header"><i class="fa-solid fa-list-check"></i><h4>Detailed Break Tracking</h4></div>
            <p>View comprehensive break records for each employee.</p>
          </div>
          <div class="history-feature">
            <div class="feature-header"><i class="fa-solid fa-user-check"></i><h4>Status Monitoring</h4></div>
            <p>Track status changes including active, on-leave, inactive, and terminated.</p>
          </div>
        </div>

        <div class="history-example">
          <h4>Sample History Record:</h4>
          <div class="record-example">
            <div class="record-item"><strong>Employee:</strong> John Adams (EMP001)</div>
            <div class="record-item"><strong>Date:</strong> 2025-10-23</div>
            <div class="record-item"><strong>Schedule:</strong> 08:00 → 17:00 with breaks</div>
            <div class="record-item"><strong>Status:</strong> <span class="status-active">Active</span></div>
          </div>
        </div>
        <p class="section-tip">💡 Export historical data for payroll or compliance reporting.</p>
      </div>

      <!-- Navigation & Layout -->
      <div class="guide-section">
        <div class="section-header">
          <div class="section-icon"><i class="fa-solid fa-compass"></i></div>
          <h2>Navigation & Layout</h2>
        </div>
        <div class="feature-list">
          <div class="feature-item"><i class="fa-solid fa-bars"></i><strong>Navigation Bar:</strong> Access different sections</div>
          <div class="feature-item"><i class="fa-solid fa-chart-simple"></i><strong>Dashboard:</strong> Real-time overview</div>
          <div class="feature-item"><i class="fa-solid fa-clock-rotate-left"></i><strong>History:</strong> Past attendance records</div>
          <div class="feature-item"><i class="fa-solid fa-circle-question"></i><strong>Help Access:</strong> Use the Help Guide button</div>
        </div>
      </div>

      <!-- Help & Tips -->
      <div class="guide-section">
        <h2>🖱️ Help Button</h2>
        <p>The floating green “Help Guide” button allows quick access to this guide. Press <kbd>Ctrl + /</kbd> to open instantly.</p>
      </div>

      <div class="guide-section">
        <h2>🧰 Tips for Best Use</h2>
        <ul>
          <li>Refresh the dashboard regularly for the latest data.</li>
          <li>Use <kbd>Ctrl + /</kbd> to access guidance instantly.</li>
          <li>Log out after use to protect information.</li>
        </ul>
      </div>

<!-- Weekly Time Log Section -->
<section class="guide-section">
  <div class="section-header">
    <div class="section-icon">
      <i class="fa-solid fa-clock-rotate-left"></i>
    </div>
    <h2>Weekly Time Log</h2>
  </div>

  <p>
    The <strong>Weekly Time Log</strong> section helps managers and employees track
    weekly work performance at a glance. It provides a detailed breakdown of each
    employee’s weekly activity — making it easier to identify attendance trends,
    monitor performance, and ensure accurate payroll processing.
  </p>

  <ul class="feature-list">
    <li class="feature-item">
      <i class="fa-solid fa-id-card feature-icon"></i>
      <span><strong>Employee Details:</strong> Displays the employee’s name and ID for clear identification.</span>
    </li>
    <li class="feature-item">
      <i class="fa-solid fa-user-check feature-icon"></i>
      <span><strong>Attendance Status:</strong> Shows if an employee is active, on leave, or absent during the week.</span>
    </li>
    <li class="feature-item">
      <i class="fa-solid fa-clock feature-icon"></i>
      <span><strong>Hours Worked:</strong> Tracks total hours completed by each employee for the week.</span>
    </li>
    <li class="feature-item">
      <i class="fa-solid fa-hourglass-half feature-icon"></i>
      <span><strong>Hours Owed:</strong> Highlights any missing or underworked hours that may affect payroll accuracy.</span>
    </li>
    <li class="feature-item">
      <i class="fa-solid fa-stopwatch feature-icon"></i>
      <span><strong>Overtime:</strong> Indicates any additional hours worked beyond the standard schedule.</span>
    </li>
    <li class="feature-item">
      <i class="fa-solid fa-circle feature-icon"></i>
      <span><strong>Performance Indicators:</strong> Uses color-coded dots for quick visual tracking — 
        <span style="color:#00C851;font-weight:600;">Green</span> for on track, 
        <span style="color:#FFBB33;font-weight:600;">Yellow</span> for pending overtime, and 
        <span style="color:#FF4444;font-weight:600;">Red</span> for attendance issues.
      </span>
    </li>
    <li class="feature-item">
      <i class="fa-solid fa-circle-question feature-icon"></i>
      <span><strong>Help Guide Access:</strong> Click the floating Help button or press <kbd>Ctrl + T</kbd> to open
        the Time Log Guide instantly.</span>
    </li>
    <li class="feature-item">
      <i class="fa-solid fa-rocket feature-icon"></i>
      <span><strong>Auto Popup:</strong> The guide opens automatically on first visit to ensure every user
        understands how to navigate the feature.</span>
    </li>
  </ul>

  <div class="section-tip">
    <i class="fa-solid fa-lightbulb"></i>
    <span>
      Tip: Encourage employees to review their Weekly Time Log every Friday to confirm their hours
      and avoid payroll discrepancies.
    </span>
  </div>
</section>


       <!-- Download Button -->
      <div class="download-section">
        <button @click="downloadGuide" class="download-btn">
          <i class="fa-solid fa-file-pdf"></i>
          Download This Guide (PDF)
        </button>
      </div>

    </div>
  </div>
</template>

<script>
import jsPDF from "jspdf";

export default {
  name: "UserGuide",
  methods: {
    downloadGuide() {
      const doc = new jsPDF();
      const lineHeight = 8;
      let y = 20; // starting y position

      const titleColor = [46, 178, 138];
      const textColor = [50, 50, 50];

      const addSection = (title, text) => {
        if (y > 270) {
          doc.addPage();
          y = 20;
        }
        doc.setFont("helvetica", "bold");
        doc.setTextColor(...titleColor);
        doc.setFontSize(14);
        doc.text(title, 15, y);
        y += 8;

        doc.setFont("helvetica", "normal");
        doc.setTextColor(...textColor);
        doc.setFontSize(12);

        const lines = doc.splitTextToSize(text, 180);
        lines.forEach(line => {
          if (y > 280) {
            doc.addPage();
            y = 20;
          }
          doc.text(line, 15, y);
          y += lineHeight;
        });

        y += 5;
      };

      // 🟢 Title
      doc.setFont("helvetica", "bold");
      doc.setFontSize(18);
      doc.setTextColor(...titleColor);
      doc.text("Employee Attendance Dashboard - User Guide", 15, y);
      y += 10;

      // 🩶 Intro
      doc.setFont("helvetica", "normal");
      doc.setFontSize(12);
      doc.setTextColor(...textColor);
      const intro = "This guide helps you understand and use the Attendance Tracker dashboard features.";
      const introLines = doc.splitTextToSize(intro, 180);
      doc.text(introLines, 15, y);
      y += introLines.length * lineHeight + 5;

      // 🧭 Sections
      addSection("Dashboard Overview",
        "- View real-time attendance data for all employees.\n" +
        "- Monitor who is clocked in, out, or absent.\n" +
        "- Access the guide anytime via the Help button."
      );

      addSection("Cards Explained",
        "• Total Employees: All employees in the system.\n" +
        "• Clock In: Currently active staff.\n" +
        "• Clock Out: Employees who ended their shift.\n" +
        "• Absent: Employees who didn’t clock in."
      );

      addSection("Navigation & Help",
        "- Use the navbar to switch between Dashboard and User Guide.\n" +
        "- Logout safely after use.\n" +
        "- Press Ctrl + / to open the Help pop-up."
      );

      // ⏰ New Section: Weekly Time Log
      addSection("Weekly Time Log",
        "The Weekly Time Log helps managers and employees monitor weekly work performance at a glance.\n\n" +
        "🔍 What it does:\n" +
        "- Displays a table showing each employee’s name, ID, attendance status, hours worked, hours owed (if under-time), and overtime hours.\n" +
        "- Includes a color indicator for quick visual performance tracking:\n" +
        "   • Green: Met or exceeded target hours\n" +
        "   • Yellow: Slightly below expected hours or pending overtime\n" +
        "   • Red: Below required hours or attendance issues\n\n" +
        "Additional features:\n" +
        "- Help Guide button for step-by-step instructions.\n" +
        "- Keyboard shortcut: Ctrl + T to open the guide instantly.\n" +
        "- Auto popup: Appears the first time a user visits this view.\n\n" +
        "This feature helps HR identify attendance trends and employees track their productivity — promoting fairness and accountability."
      );

      // 💾 Save
      doc.save("AttendanceDashboard_UserGuide.pdf");
    }
  }
};
</script>



<style scoped>
/* ================================
   Base & Layout
================================ */
.user-guide-page {
  min-height: 100vh;
  background: linear-gradient(135deg,#f0fdf4 0%,#ecfdf5 100%);
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  font-family: 'Segoe UI', sans-serif;
  color: #374151;
  text-align: left; /* left align base text */
}

.guide-header-section {
  background: white;
  border-radius: 16px;
  padding: 40px;
  margin-bottom: 30px;
  box-shadow: 0 4px 20px rgba(16,185,129,0.1);
  border: 1px solid #d1fae5;
  text-align: left; /* header left alignment */
}

.guide-title { color: #065f46; font-size: 2.5em; font-weight: 700; margin-bottom: 10px; }
.guide-subtitle { color: #059669; font-size: 1.2em; margin-bottom: 0; }

.guide-content-section {
  display: flex;
  flex-direction: column;
  width: 100%;
  max-width: 1000px;
  gap: 25px;
  align-items: stretch; /* stretch sections full width */
}

/* ================================
   Sections
================================ */
.guide-section {
  background: white;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  border: 1px solid #e2e8f0;
  transition: all 0.2s ease;
  width: 100%;
  display: flex;
  flex-direction: column;
}

.guide-section:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 20px rgba(16,185,129,0.15);
}

.section-header {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 20px;
}

.section-icon {
  width: 50px; height: 50px;
  background: #d1fae5;
  border-radius: 12px;
  display: flex;
  align-items: center; justify-content: center;
  color: #065f46; font-size: 1.2em;
  flex-shrink: 0;
}

.section-header h2 {
  color: #065f46;
  font-size: 1.5em;
  font-weight: 600;
  margin: 0;
}

/* ================================
   Lists & Features
================================ */
.feature-list, .table-features, .history-features, .best-practices {
  display: flex;
  flex-direction: column;
  gap: 12px;
  width: 100%;
}

.feature-item, .table-feature, .history-feature, .practice-item {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  background: #f8fafc;
  border-radius: 8px;
  padding: 15px;
  border-left: 4px solid #10b981;
  transition: all 0.2s ease;
  width: 100%;
  max-width: 600px;
  text-align: left;
}

.feature-item i, .practice-icon, .history-feature i { color: #10b981; font-size: 1.2em; margin-top:3px; }

.feature-item:hover, .history-feature:hover, .practice-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(16,185,129,0.1);
}

.feature-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 8px;
}

/* ================================
   History & Records
================================ */
.history-example, .record-example {
  background: #f0fdf4;
  border: 1px solid #a7f3d0;
  border-radius: 10px;
  padding: 20px;
  margin: 15px 0;
}

.record-item { margin: 5px 0; color: #374151; }
.record-item strong { color: #065f46; }
.status-active { color: #10b981; font-weight: 600; }

/* ================================
   Buttons & Downloads
================================ */
.download-section { display:flex; justify-content:flex-start; width:100%; padding:20px 0; }
.download-btn {
  display: inline-flex; align-items: center; gap:10px;
  background: #10b981; color:white; border:none;
  padding:12px 24px; border-radius:8px; font-size:1em; font-weight:600;
  cursor:pointer; transition: all 0.3s ease;
}
.download-btn:hover {
  background: #059669;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(16,185,129,0.2);
}

/* ================================
   Miscellaneous
================================ */
.section-tip {
  background: #f0fdf4; border:1px solid #a7f3d0; border-radius:8px;
  padding:15px; color:#065f46; font-style:italic;
}

ul { list-style-position: inside; padding-left:20px; }
kbd {
  background: #065f46; color: white; padding: 5px 10px; border-radius:6px;
  font-family: monospace; font-weight:600;
}

/* ================================
   Responsive
================================ */
@media (max-width:1024px){.guide-content-section{grid-template-columns:1fr;}}
@media (max-width:768px){
  .user-guide-page{padding:15px;}
  .guide-header-section{padding:30px 20px;}
  .guide-title{font-size:2em;}
  .guide-section{padding:20px;}
}
@media (max-width:480px){
  .guide-title{font-size:1.5em;}
  .section-header{flex-direction:column; gap:10px;}
}

/* Weekly Time Log Styling */
.guide-section {
  background: #ffffff;
  border-radius: 12px;
  padding: 25px;
  margin-bottom: 30px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.section-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 15px;
}

.section-icon {
  background: #d1fae5;
  color: #047857;
  padding: 10px;
  border-radius: 50%;
  font-size: 18px;
}

.feature-list {
  list-style: none;
  padding: 0;
  margin: 0 0 15px 0;
}

.feature-item {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  background: #f9fafb;
  border-left: 4px solid #10b981;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 10px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.feature-item .feature-icon {
  color: #10b981;
  font-size: 16px;
  margin-top: 2px;
}

.section-tip {
  background: #ecfdf5;
  color: #065f46;
  border-left: 4px solid #34d399;
  border-radius: 8px;
  padding: 12px 14px;
  font-size: 14px;
  display: flex;
  align-items: flex-start;
  gap: 8px;
}

.section-tip i {
  color: #10b981;
}


</style>