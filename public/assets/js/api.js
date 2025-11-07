const API_BASE_URL = window.location.origin + '/team-nieshaan-zoe/public/api';

// Helper function to handle API responses
async function handleResponse(response) {
    const contentType = response.headers.get('content-type');
    if (contentType && contentType.includes('application/json')) {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.error || data.message || 'API request failed');
        }
        return data;
    }
    throw new Error('Invalid response format');
}

// Admin/KPI Cards API
const AdminAPI = {
    async getAllKpiData() {
        const response = await fetch(`${API_BASE_URL}/admin/allKpiData`);
        return handleResponse(response);
    },
    
    async getTotalEmployees() {
        const response = await fetch(`${API_BASE_URL}/admin/total`);
        return handleResponse(response);
    },
    
    async getTotalCheckedIn() {
        const response = await fetch(`${API_BASE_URL}/admin/checkedIn`);
        return handleResponse(response);
    },
    
    async getTotalCheckedOut() {
        const response = await fetch(`${API_BASE_URL}/admin/checkedOut`);
        return handleResponse(response);
    },
    
    async getTotalAbsent() {
        const response = await fetch(`${API_BASE_URL}/admin/absent`);
        return handleResponse(response);
    }
};

// Employee API
const EmployeeAPI = {
    async getEmployees() {
        const response = await fetch(`${API_BASE_URL}/employees/getEmployees`);
        return handleResponse(response);
    },
    
    async addEmployee(employeeData) {
        const response = await fetch(`${API_BASE_URL}/employees/addEmployee`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(employeeData)
        });
        return handleResponse(response);
    },
    
    async updateEmployee(employeeData) {
        const response = await fetch(`${API_BASE_URL}/employees/updateEmployee`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(employeeData)
        });
        return handleResponse(response);
    },
    
    async deleteEmployee(employeeId) {
        const response = await fetch(`${API_BASE_URL}/employees/deleteEmployee`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ employee_id: employeeId })
        });
        return handleResponse(response);
    },
    
    async getRoles() {
        const response = await fetch(`${API_BASE_URL}/employees/getRoles`);
        return handleResponse(response);
    },
    
    async getDepartments() {
        const response = await fetch(`${API_BASE_URL}/employees/getDepartments`);
        return handleResponse(response);
    }
};

// Clock In/Out & Time Log API
const ClockInOutAPI = {
    async getClockInOutData() {
        const response = await fetch(`${API_BASE_URL}/clock-in-out/clockInOut`);
        return handleResponse(response);
    },
    
    async getTimeLogData(week) {
        const url = week 
            ? `${API_BASE_URL}/clock-in-out/getTimeLogData?week=${week}`
            : `${API_BASE_URL}/clock-in-out/getTimeLogData`;
        const response = await fetch(url);
        return handleResponse(response);
    },
    
    async createHoursRecord(recordData) {
        const response = await fetch(`${API_BASE_URL}/clock-in-out/createRecord`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(recordData)
        });
        return handleResponse(response);
    }
};

// History API
const HistoryAPI = {
    async getAllRecords() {
        const response = await fetch(`${API_BASE_URL}/history/getAllRecords`);
        return handleResponse(response);
    }
};

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { AdminAPI, EmployeeAPI, ClockInOutAPI, HistoryAPI };
}
