<template>
 <!-- NAVBAR -->
<nav class="app-navbar" :class="{ 'dark-mode': isDarkMode }">
  <div class="app-navbar-inner">
    <div class="nav-left">
      <img src="@/assets/Your paragraph text_PhotoGrid.png" alt="Clock It Logo" class="logo" />
    </div>

    <div class="nav-right">
      <router-link to="/" class="nav-link" exact-active-class="active">Dashboard</router-link>
      <router-link to="/user-guide" class="nav-link" exact-active-class="active">User guide</router-link>
      
      <!-- Theme Toggle Button -->
      <button class="theme-toggle-btn" @click="toggleTheme">
        <i :class="themeIcon"></i>
        {{ isDarkMode ? 'Light Mode' : 'Dark Mode' }}
      </button>
      
      <button class="logout-btn">
        <i class="fa-solid fa-right-from-bracket"></i>
        Logout
      </button>
    </div>
  </div>
</nav>
</template>

<script>
export default {
  name: 'NavComp',
  data() {
    return {
      isDarkMode: false
    }
  },
  computed: {
    themeIcon() {
      return this.isDarkMode ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
    }
  },
  methods: {
    toggleTheme() {
      this.isDarkMode = !this.isDarkMode;
      
      // Update body class for global styling
      if (this.isDarkMode) {
        document.body.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
      } else {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
      }
      
      // Emit event for other components to listen to
      this.$emit('theme-changed', this.isDarkMode);
    },
    loadTheme() {
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') {
        this.isDarkMode = true;
        document.body.classList.add('dark-mode');
      }
    }
  },
  mounted() {
    this.loadTheme();
  }
}
</script>

<style>
.app-navbar {
  width: 100%;
  background: #2EB28A;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  border-bottom: 1px solid #e6e9ee;
  transition: all 0.3s ease;
}

.app-navbar.dark-mode {
  background: #1a1a1a;
  border-bottom: 1px solid #333;
}

.app-navbar-inner {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 18px 36px;
  width: 100%;
  box-sizing: border-box;
}

.nav-left {
  display: flex;
  align-items: center;
  gap: 10px;
}

.logo {
  height: 90px; 
  width: 200px; 
  object-fit: cover;
  margin-top: -8px; 
  margin-bottom: -8px; 
}

.nav-right {
  display: flex;
  align-items: center;
  gap: 25px;
}

.nav-link {
  color: #ffffff;
  text-decoration: none;
  font-weight: 500;
  transition: 0.3s;
}

.app-navbar.dark-mode .nav-link {
  color: #e0e0e0;
}

.nav-link:hover {
  color: #d9f5ec;
}

.app-navbar.dark-mode .nav-link:hover {
  color: #ffffff;
}

.active {
  border-bottom: 2px solid #ffffff;
}

.app-navbar.dark-mode .active {
  border-bottom: 2px solid #2EB28A;
}

.theme-toggle-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 18px;
  border-radius: 25px;
  border: none;
  background: rgba(255, 255, 255, 0.2);
  color: #ffffff;
  cursor: pointer;
  font-weight: 600;
  transition: 0.3s;
}

.theme-toggle-btn:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: translateY(-2px);
}

.app-navbar.dark-mode .theme-toggle-btn {
  background: rgba(46, 178, 138, 0.2);
  color: #2EB28A;
}

.app-navbar.dark-mode .theme-toggle-btn:hover {
  background: rgba(46, 178, 138, 0.3);
}

.logout-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 18px;
  border-radius: 25px;
  border: none;
  background: white;
  color: #2EB28A;
  cursor: pointer;
  font-weight: 600;
  transition: 0.3s;
}

.logout-btn:hover {
  background: #249a77;
  color: #ffffff;
}

.app-navbar.dark-mode .logout-btn {
  background: #2EB28A;
  color: #ffffff;
}

.app-navbar.dark-mode .logout-btn:hover {
  background: #249a77;
}

/* Global dark mode styles for the entire app */
body.dark-mode {
  background-color: #121212;
  color: #e0e0e0;
}

body.dark-mode .app-content {
  background-color: #121212;
  color: #e0e0e0;
}
</style>