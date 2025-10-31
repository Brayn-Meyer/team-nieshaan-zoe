-- Create the emp_classification table that stores employee classification information
CREATE TABLE IF NOT EXISTS emp_classification (
  classification_id INT PRIMARY KEY AUTO_INCREMENT,
  department VARCHAR(100) NOT NULL,
  position VARCHAR(100) NOT NULL,
  role VARCHAR(100) NOT NULL,
  employment_type VARCHAR(50) NOT NULL,
  employee_level VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY unique_classification (department, position, role, employment_type, employee_level)
);

-- Insert some default classifications (modify as needed)
INSERT INTO emp_classification (department, position, role, employment_type, employee_level) VALUES
('IT', 'Software Developer', 'Developer', 'Full-time', 'Junior'),
('IT', 'Software Developer', 'Developer', 'Full-time', 'Senior'),
('HR', 'HR Manager', 'Manager', 'Full-time', 'Senior'),
('Finance', 'Accountant', 'Accountant', 'Full-time', 'Mid-level'),
('Operations', 'Operations Manager', 'Manager', 'Full-time', 'Senior')
ON DUPLICATE KEY UPDATE classification_id=classification_id;
