# Clock It - Attendance Tracker (PHP Version)

## Migration from Vue 3 + Node.js to Pure PHP + MySQL

This is the PHP version of the Clock It attendance tracking system, migrated from the original Vue 3 + Node.js/Express stack to pure PHP for hosting compatibility with Xneelo.

---

## 📋 Table of Contents
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Project Structure](#project-structure)
- [API Endpoints](#api-endpoints)
- [Configuration](#configuration)
- [Real-time Updates](#real-time-updates)
- [Migration Notes](#migration-notes)

---

## ✨ Features

- **Dashboard**: KPI cards showing total employees, checked in, checked out, and absent
- **Employee Management**: Full CRUD operations for employees
- **Time Log**: Weekly hours tracking with hours owed and overtime calculations
- **Attendance History**: View all attendance records
- **Real-time Updates**: Live KPI card updates via polling
- **Responsive Design**: Mobile-friendly interface

---

## 🔧 Requirements

- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher
- **Web Server**: Apache with mod_rewrite enabled OR Nginx
- **Extensions**: mysqli, json

---

## 📦 Installation

### Step 1: Clone/Upload Files

Upload the `team-nieshaan-zoe-php` folder to your web server.

### Step 2: Configure Database

1. Edit `app/includes/config.php` and update database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'tracker_db');
```

2. Update `BASE_URL` to match your hosting path:

```php
define('BASE_URL', '/team-nieshaan-zoe-php/public');
// For root domain: define('BASE_URL', '');
```

### Step 3: Database Setup

Your existing MySQL database (`tracker_db`) should already have these tables:
- `employees`
- `emp_classification`
- `record_backups`
- `hours_management`

**No database migration needed** - the PHP version uses the same database schema.

### Step 4: Configure .htaccess (Apache)

Create `.htaccess` in the `public` folder:

```apache
RewriteEngine On
RewriteBase /team-nieshaan-zoe-php/public/

# API Routes
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^api/(.*)$ api/index.php [L,QSA]

# Default document
DirectoryIndex views/home.php
```

### Step 5: Set Permissions

```bash
chmod 755 public/
chmod 755 app/
chmod 644 app/includes/config.php
```

### Step 6: Test Installation

Visit: `http://yourdomain.com/team-nieshaan-zoe-php/public/views/home.php`

---

## 📁 Project Structure

```
team-nieshaan-zoe-php/
├── app/
│   ├── components/          # Reusable PHP components
│   ├── controllers/         # API controllers
│   │   ├── AdminCardsController.php
│   │   ├── EmployeeController.php
│   │   ├── ClockInOutController.php
│   │   └── HistoryController.php
│   ├── models/              # Database models
│   │   ├── AdminCardsModel.php
│   │   ├── EmployeeModel.php
│   │   └── ClockInOutModel.php
│   ├── views/               # Page views
│   │   ├── home.php
│   │   ├── timeLog.php
│   │   └── history.php
│   └── includes/            # Shared includes
│       ├── config.php       # Configuration
│       ├── db.php           # Database class
│       ├── header.php       # Header/nav
│       └── footer.php       # Footer
└── public/
    ├── api/
    │   └── index.php        # API router
    ├── assets/
    │   ├── css/             # Stylesheets
    │   ├── js/              # JavaScript files
    │   └── images/          # Images/logo
    └── .htaccess            # Apache config
```

---

## 🔌 API Endpoints

### Admin/KPI Cards
- `GET /api/admin/allKpiData` - Get all KPI card data
- `GET /api/admin/total` - Get total employees
- `GET /api/admin/checkedIn` - Get checked in count
- `GET /api/admin/checkedOut` - Get checked out count
- `GET /api/admin/absent` - Get absent count

### Employees
- `GET /api/employees/getEmployees` - Get all employees
- `POST /api/employees/addEmployee` - Add new employee
- `PUT /api/employees/updateEmployee` - Update employee
- `DELETE /api/employees/deleteEmployee` - Delete employee
- `GET /api/employees/getRoles` - Get all roles
- `GET /api/employees/getDepartments` - Get all departments

### Clock In/Out & Time Log
- `GET /api/clock-in-out/clockInOut` - Get clock in/out data
- `GET /api/clock-in-out/getTimeLogData?week=2025-10-27` - Get time log for week
- `POST /api/clock-in-out/createRecord` - Save hours record

### History
- `GET /api/history/getAllRecords` - Get all attendance records

---

## ⚙️ Configuration

### Database Connection

The `Database` class in `app/includes/db.php` provides:
- Singleton pattern for connection management
- Prepared statement helpers
- Auto-type detection
- Transaction support

Example usage:
```php
// Simple query
$results = db()->query("SELECT * FROM employees WHERE id = ?", [123], 'i');

// Insert with auto-generated ID
$newId = db()->execute("INSERT INTO employees (...) VALUES (...)", $params);
```

### API Configuration

API routes are defined in `public/api/index.php` and follow this pattern:
```
/api/{controller}/{action}
```

Examples:
- `/api/admin/allKpiData`
- `/api/employees/getEmployees`
- `/api/clock-in-out/getTimeLogData`

---

## 🔄 Real-time Updates

### Polling Implementation

Instead of Socket.io, the PHP version uses **JavaScript polling** for real-time updates.

**KPI Cards Update** (in `public/assets/js/dashboard.js`):
```javascript
// Poll KPI data every 10 seconds
setInterval(() => {
    fetchKpiData();
}, 10000);
```

**Configuration**:
- **Polling Interval**: 10 seconds (adjustable)
- **Auto-refresh**: KPI cards update automatically
- **Manual Refresh**: Click any area to force update

---

## 📝 Migration Notes

### Key Changes from Node.js Version

1. **Backend**:
   - Express routes → PHP controllers
   - mysql2 pool → MySQLi with prepared statements
   - Socket.io → Long-polling with `setInterval`
   - ES6 modules → PHP classes

2. **Frontend**:
   - Vue components → PHP views with vanilla JS
   - Vue reactivity → DOM manipulation
   - Vue Router → PHP multi-page application
   - Axios → Fetch API

3. **Database**:
   - **No changes** - Same MySQL schema
   - Same table structure
   - Same queries (adapted for PHP)

### Compatibility

✅ **Maintained**:
- All employee CRUD operations
- Time log calculations
- Hours owed/overtime logic
- KPI card functionality
- Attendance history

✅ **Improved**:
- Hosting compatibility (works on Xneelo)
- Simpler deployment (no Node.js required)
- Standard PHP hosting requirements

---

## 🚀 Deployment (Xneelo)

### Via FTP

1. Upload `team-nieshaan-zoe-php` folder to `public_html`
2. Edit `app/includes/config.php` with your database credentials
3. Update `BASE_URL` in `config.php`
4. Visit your domain

### Database Connection (Xneelo)

Xneelo provides MySQL credentials in cPanel:
```php
define('DB_HOST', 'localhost'); // Usually localhost
define('DB_USER', 'username_dbuser');
define('DB_PASS', 'your_password');
define('DB_NAME', 'username_tracker_db');
```

---

## 🐛 Troubleshooting

### "Page not found" errors
- Check `.htaccess` file exists in `public/`
- Verify `mod_rewrite` is enabled
- Update `BASE_URL` in `config.php`

### Database connection errors
- Verify credentials in `config.php`
- Check MySQL service is running
- Ensure database user has proper permissions

### API not working
- Check PHP error logs
- Verify `mysqli` extension is enabled
- Test API endpoint directly: `/public/api/admin/allKpiData`

### KPI cards not updating
- Check browser console for JavaScript errors
- Verify API endpoints are accessible
- Check polling interval in `dashboard.js`

---

## 📄 License

Same as original project.

---

## 👥 Credits

**Original Project**: [team-nieshaan-zoe](https://github.com/Brayn-Meyer/team-nieshaan-zoe)  
**Migration**: PHP conversion for Xneelo hosting compatibility

---

## 📞 Support

For issues related to the PHP migration, please check:
1. Error logs in `php_error.log`
2. Browser console for JavaScript errors
3. Database connection in `config.php`
4. API endpoint responses

---

**Last Updated**: November 3, 2025
