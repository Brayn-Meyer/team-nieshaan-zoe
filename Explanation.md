# Complete Project Structure & Flow Explanation

Let me explain how this Pure PHP MVC project works, especially compared to Node.js/JS frameworks you're familiar with.

---

## :building_construction: **Project Architecture Overview**

```
team-nieshaan-zoe-php/
├── app/                    # Private application code (NOT web-accessible)
│   ├── controllers/        # Business logic handlers
│   ├── models/            # Database interaction layer
│   ├── includes/          # Shared PHP files (config, DB, header, footer)
│   └── views/             # HTML pages with PHP
└── public/                # Web-accessible files ONLY
    ├── api/               # API entry point
    ├── assets/            # Static files (CSS, JS, images)
    └── index.php          # Optional entry point
```

---

## :open_file_folder: **Folder Structure Explained**

### **1. app Folder - The Application Core**

This is where your **private** application logic lives. In a production server, this folder would NOT be accessible from the web browser directly.

#### **controllers** - Business Logic Layer
- Similar to controllers in Express.js or any MVC framework
- Handle incoming requests and coordinate between models and responses
- Example: EmployeeController.php
  ```php
  // Receives request → Calls model → Returns JSON response
  private static function getEmployees() {
      $employees = EmployeeModel::getAllEmployees(); // Call model
      echo json_encode(['employees' => $employees]); // Return JSON
  }
  ```

#### **models** - Data Access Layer
- Similar to models in Sequelize or Mongoose
- Handle ALL database queries (no SQL in controllers!)
- Example: EmployeeModel.php
  ```php
  // Pure database logic
  public static function getAllEmployees() {
      $query = "SELECT * FROM employees";
      return db()->query($query); // Returns array of data
  }
  ```

#### **includes** - Shared Components
Think of this like a `utils/` or `config/` folder in Node.js:

- **config.php** - Environment variables & settings
  ```php
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'tracker_db');
  define('BASE_URL', '/team-nieshaan-zoe-php/public');
  ```

- **db.php** - Database connection singleton (like Sequelize setup)
  ```php
  // Creates ONE database connection for the entire app
  $connection = Database::getInstance();
  ```

- **header.php & `footer.php`** - Reusable HTML components
  ```php
  // Like React components but for PHP
  // Include in every page to avoid repeating navbar/footer code
  ```

#### **views** - Frontend Pages (Server-Side Rendered)
- These are your actual HTML pages that users see
- PHP files that get processed on the server BEFORE being sent to browser
- Example: home.php
  ```php
  <?php 
  require_once __DIR__ . '/../includes/config.php';
  require_once __DIR__ . '/../includes/header.php'; // Load navbar
  ?>
  <div>Dashboard content here</div>
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
  ```

**Important**: Views access CSS/JS using the `BASE_URL` constant:
```php
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/main.css">
<script src="<?php echo BASE_URL; ?>/assets/js/dashboard.js"></script>
```

---

### **2. public Folder - Web-Accessible Files**

This is the ONLY folder that should be directly accessible from the web. In production, your web server (Apache) would point to THIS folder as the document root.

#### **assets** - Static Files
- **`css/`** - Stylesheets (like in any web project)
- **`js/`** - Client-side JavaScript (runs in browser)
- **`images/`** - Images, logos, etc.

Access: `http://localhost/team-nieshaan-zoe-php/public/assets/css/main.css`

#### **api** - API Router (Backend Entry Point)

This is **THE MOST IMPORTANT FILE** for understanding the backend flow!

**index.php** - The API Gateway
```php
// This file receives ALL API requests and routes them
// Think of it like Express.js routing:
// app.get('/api/employees/getEmployees', employeeController.getEmployees)

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// URL: /team-nieshaan-zoe-php/public/api/employees/getEmployees
// Extracts: ['employees', 'getEmployees']

switch ($controller) {
    case 'employees':
        require_once '../../app/controllers/EmployeeController.php';
        EmployeeController::handleRequest($action, $method);
        break;
}
```

---

## :arrows_counterclockwise: **Complete Request Flow (Backend to Frontend)**

### **Example: Loading the Dashboard with Employees**

#### **Step 1: User Visits the Page**
```
Browser → http://localhost/team-nieshaan-zoe-php/app/views/home.php
```

#### **Step 2: Server Processes PHP (Server-Side)**
```php
// home.php runs on the server
<?php
require_once __DIR__ . '/../includes/config.php';  // Load settings
require_once __DIR__ . '/../includes/header.php';  // Load navbar
?>
<!-- HTML sent to browser -->
<link rel="stylesheet" href="/team-nieshaan-zoe-php/public/assets/css/main.css">
<script src="/team-nieshaan-zoe-php/public/assets/js/dashboard.js"></script>
```

Server sends **final HTML** (no PHP code visible in browser!)

#### **Step 3: Browser Loads Assets**
```
Browser downloads:
- /team-nieshaan-zoe-php/public/assets/css/main.css
- /team-nieshaan-zoe-php/public/assets/js/dashboard.js
```

#### **Step 4: JavaScript Makes API Call**
```javascript
// dashboard.js (runs in browser)
const data = await EmployeeAPI.getEmployees();
// ↓
// Calls: http://localhost/team-nieshaan-zoe-php/public/api/employees/getEmployees
```

#### **Step 5: API Router Receives Request**
```php
// public/api/index.php receives the request
$uri = '/employees/getEmployees'
$controller = 'employees'
$action = 'getEmployees'
$method = 'GET'

// Routes to controller
require_once '../../app/controllers/EmployeeController.php';
EmployeeController::handleRequest('getEmployees', 'GET');
```

#### **Step 6: Controller Processes Request**
```php
// EmployeeController.php
public static function handleRequest($action, $method) {
    if ($action === 'getEmployees' && $method === 'GET') {
        self::getEmployees();
    }
}

private static function getEmployees() {
    $employees = EmployeeModel::getAllEmployees(); // ← Calls model
    echo json_encode(['employees' => $employees]); // ← Returns JSON
}
```

#### **Step 7: Model Queries Database**
```php
// EmployeeModel.php
public static function getAllEmployees() {
    $query = "SELECT * FROM employees";
    return db()->query($query); // ← Returns array from DB
}
```

#### **Step 8: JSON Response to Frontend**
```json
{
  "employees": [
    {"id": 1, "name": "John Doe", "email": "john@example.com"},
    {"id": 2, "name": "Jane Smith", "email": "jane@example.com"}
  ]
}
```

#### **Step 9: JavaScript Updates DOM**
```javascript
// dashboard.js receives the data
const data = await EmployeeAPI.getEmployees();
allEmployees = data.employees; // Extract array
renderEmployees(allEmployees); // Update HTML
```

---

## :closed_lock_with_key: **What is .htaccess?**

.htaccess is Apache web server configuration. Think of it like middleware in Express.js.

### **Your .htaccess File:**
```apache
RewriteEngine On
RewriteBase /team-nieshaan-zoe-php/public/

# API Routes - This is URL rewriting (like Express routing)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^api/(.*)$ api/index.php [L,QSA]

# Security
Options -Indexes  # Prevent directory listing
```

### **What it does:**

1. **URL Rewriting** - Makes URLs clean
   ```
   Without htaccess:
   /api/index.php?controller=employees&action=getEmployees
   
   With htaccess:
   /api/employees/getEmployees  ← Clean URL
   ```

2. **Security** - Prevents users from browsing folders
   ```
   Options -Indexes means:
   /public/assets/  → Shows files (allowed)
   /public/assets/css/  → 403 Forbidden (no directory listing)
   ```

3. **API Routing** - All `/api/*` requests go to index.php
   ```
   /api/employees/getEmployees  →  api/index.php
   /api/admin/allKpiData        →  api/index.php
   ```

---

## :vs: **Public vs App Folder**

| Aspect | public | app |
|--------|-----------|--------|
| **Web Access** | :white_check_mark: Direct browser access | :x: No direct access |
| **Purpose** | Static files & API entry | Application logic |
| **Contains** | CSS, JS, images, index.php | Controllers, Models, Views |
| **Security** | Public-facing | Private/Protected |
| **Example** | `main.css`, `logo.png` | EmployeeModel.php |

### **Why this separation?**

**Security!** You don't want users accessing:
- Database credentials in config.php
- Raw model files with SQL queries
- Controller logic

**Only** CSS, JS, images, and the API router should be web-accessible.

---

## :link: **How Views Access CSS & JS**

Views use the `BASE_URL` constant to build absolute paths:

```php
// In app/views/home.php
<?php 
define('BASE_URL', '/team-nieshaan-zoe-php/public');
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/main.css">
<!--                           ↑
     Becomes: /team-nieshaan-zoe-php/public/assets/css/main.css
-->
```

**Path Resolution:**
```
View location:    /team-nieshaan-zoe-php/app/views/home.php
CSS file:         /team-nieshaan-zoe-php/public/assets/css/main.css

Browser request:  http://localhost/team-nieshaan-zoe-php/public/assets/css/main.css
```

---

## :bar_chart: **MVC Pattern in This Project**

Compared to Node.js frameworks:

| Component | This Project | Express.js Equivalent |
|-----------|--------------|----------------------|
| **Routes** | index.php | `app.get('/api/...')` |
| **Controllers** | EmployeeController.php | `employeeController.js` |
| **Models** | EmployeeModel.php | Sequelize/Mongoose models |
| **Views** | home.php | React/Vue components OR EJS templates |
| **Config** | config.php | `.env` + `config.js` |
| **Database** | db.php | Sequelize/Mongoose connection |
| **Static Files** | assets | public in Express |

---

## :dart: **Key Differences from Node.js MVC**

### **1. Server-Side Rendering (SSR)**
```php
// PHP processes BEFORE sending to browser
<?php echo $userName; ?>  // Runs on server
↓
<p>John Doe</p>  // Browser receives this
```

### **2. No Build Step**
- No webpack, no npm build
- PHP files run directly on server
- Changes take effect immediately (refresh browser)

### **3. Synchronous by Default**
```php
// PHP is synchronous (no async/await needed for DB)
$data = $model->getData();  // Waits for DB
echo json_encode($data);    // Then responds
```

### **4. Include Instead of Import**
```php
require_once 'file.php';  // Like import in JS
```

---

## :fire: **Common Gotchas**

### **1. Path Resolution**
```php
// Relative paths are from the CURRENT file location
__DIR__              // Current directory
__DIR__ . '/../'     // Go up one level
```

### **2. Browser Caching**
- CSS/JS changes not showing? Hard refresh: `Ctrl+Shift+R`
- PHP changes show immediately (no cache)

### **3. Error Visibility**
```php
// In config.php
error_reporting(E_ALL);      // Show all errors
ini_set('display_errors', 1); // Display in browser
```

---

## :memo: **Summary for Your Team**

**"This is a traditional PHP MVC application with a modern API architecture"**

1. **Frontend**: HTML pages (views) with client-side JavaScript (js)
2. **Backend API**: RESTful JSON API (index.php routes to controllers)
3. **Database**: MySQL accessed through model layer (models)
4. **MVC Pattern**: 
   - Models talk to database
   - Controllers handle business logic
   - Views render HTML
5. **Security**: Only public folder is web-accessible
6. **No Build Tools**: Pure PHP, no compilation needed

**Flow**: Browser → View (PHP+HTML) → JavaScript → API → Controller → Model → Database → JSON Response → JavaScript updates DOM