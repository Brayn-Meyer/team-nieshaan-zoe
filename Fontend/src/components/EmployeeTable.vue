<template>
  <div>
    <div class="search-container mb-3">
      <input 
        type="text" 
        class="form-control" 
        placeholder="Search employees..." 
        v-model="searchQuery"
      >
      </div>
    </div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Surname</th>
          <th scope="col">Employee ID</th>
          <th scope="col">Date</th>
          <th scope="col">Status</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="employee in filteredList" :key="employee.id">
          <td>{{ employee.name }}</td>
          <td>{{ employee.surname }}</td>
          <td>{{ employee.employeeId }}</td>
          <td>{{ formatDate(employee.date) }}</td>
          <td><i class="fa-solid fa-circle-dot" style="color: #00ffb3;"></i></td>
<td>
  <button class="menu">
  <i class="fa-solid fa-ellipsis-vertical"></i>
      </button>
      </td>

      </tr>
      <tr v-if="filteredList.length === 0">
          <td colspan="5" class="text-center text-muted">No employees found</td>
        </tr>
      </tbody>
    </table>
</template>

<script>
export default {
  data() {
    return {
      searchQuery: '',
      dateQuery: '',
      employees: [
        {
          id: 1,
          name: 'John',
          surname: 'Doe',
          employeeId: 'EMP001',
          date: '2025-10-21',
          status: 'Active',

        },
        {
          id: 2,
          name: 'Jane',
          surname: 'Smith',
          employeeId: 'EMP002',
          date: '2025-10-22',
          status: 'Active',

        },
        {
          id: 3,
          name: 'Mike',
          surname: 'Johnson',
          employeeId: 'EMP003',
          date: '2025-10-23',
          status: 'Active',

        },
        {
          id: 4,
          name: 'Sarah',
          surname: 'Wilson',
          employeeId: 'EMP004',
          date: '2025-10-24',
          status: 'Active',

        },
        {
          id: 5,
          name: 'David',
          surname: 'Brown',
          employeeId: 'EMP005',
          date: '2025-10-25',
          status: 'Active',

        },
        {
          id: 6,
          name: 'Emily',
          surname: 'Davis',
          employeeId: 'EMP006',
          date: '2025-10-26',
          status: 'Active',
        }
      ]
    }
  },
  computed: {
    filteredList() {
      return this.employees.filter(item => {
        const matchesText = item.name?.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                           item.surname?.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                           item.employeeId?.toLowerCase().includes(this.searchQuery.toLowerCase());
        
        if (!item.date || isNaN(new Date(item.date))) {
          console.warn("Skipping invalid date:", item.date, item);
          return false;
        }
        
        const itemDate = new Date(item.date).toISOString().split('T')[0];
        const queryDate = this.dateQuery ? new Date(this.dateQuery).toISOString().split('T')[0] : null;
        const matchesDate = queryDate ? itemDate === queryDate : true;
        
        return matchesText && matchesDate;
      });
    }
  },
  methods: {
    clearFilters() {
      this.dateQuery = '';
      this.searchQuery = '';
    },
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    }
  }
}
</script>

<style scoped>
.search-container {
  max-width: 500px;
  align-items: center;
  justify-content: center;
  margin: auto;
  border: 1px solid #00C0AA;
  padding: 20px;
  border-radius: 8px;
  background-color: #f8f9fa;
}


.menu{
  border: none;
  background-color:white
}
.menu:hover{
  background-color: #6c757d;
  border-radius: 50px;
}

table {
  margin: auto;
  width: 90%;
  align-items: center;
  justify-content: center;
  border: 1px solid #00C0AA;
}

.text-muted {
  color: #6c757d !important;
}

.form-control {
  margin-bottom: 10px;
  border: 1px solid #00C0AA;
}

.form-control:focus {
  border-color: #00C0AA;
  box-shadow: 0 0 0 0.2rem rgba(0, 192, 170, 0.25);
}
</style>