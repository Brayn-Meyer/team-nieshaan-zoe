<template>
  <div class="container mt-4">
    <h2 class="mb-4 fw-bold text-dark">Admin Notifications</h2>

    <div v-if="notifications.length" class="list-group">
      <div
        v-for="notif in notifications"
        :key="notif.id"
        :id="'notif-' + notif.id"
        class="list-group-item d-flex justify-content-between align-items-center mb-2 shadow-sm rounded p-3 bg-white notification-item"
      >
        <div>
          <p class="mb-1 text-dark fw-semibold">{{ notif.message }}</p>
          <small class="text-muted">{{ notif.timestamp }}</small>
        </div>
        <div class="form-check">
          <input
            class="form-check-input"
            type="checkbox"
            :id="'check-' + notif.id"
            @change="openConfirmModal(notif)"
          />
        </div>
      </div>
    </div>

    <div v-else class="text-muted text-center mt-5">No notifications available 🎉</div>

    <!-- Confirmation Modal -->
    <div
      class="modal fade show"
      tabindex="-1"
      style="display: block;"
      v-if="showConfirmModal"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header bg-warning-subtle">
            <h5 class="modal-title fw-semibold">Confirm Action</h5>
            <button type="button" class="btn-close" @click="cancelClear"></button>
          </div>
          <div class="modal-body">
            <p class="fs-6 mb-0">
              Are you sure you want to clear this notification?
            </p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary rounded-pill px-4" @click="cancelClear">Cancel</button>
            <button class="btn btn-danger rounded-pill px-4" @click="confirmClear">Yes, Clear</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AdminNotificationsView',
  data() {
    return {
      notifications: [
        { id: 1, message: 'John Doe requested time off', timestamp: '2 mins ago' },
        { id: 2, message: 'Jane Smith submitted a report', timestamp: '10 mins ago' },
        { id: 3, message: 'Mark Lee reported a system issue', timestamp: 'Yesterday' },
      ],
      showConfirmModal: false,
      selectedNotification: null,
    }
  },
  methods: {
    openConfirmModal(notif) {
      this.selectedNotification = notif
      this.showConfirmModal = true
    },
    cancelClear() {
      this.showConfirmModal = false
      this.selectedNotification = null
    },
    confirmClear() {
      if (this.selectedNotification) {
        const id = this.selectedNotification.id
        setTimeout(() => {
          this.notifications = this.notifications.filter(n => n.id !== id)
        }, 1000)
        this.showConfirmModal = false
      }
    },
  },
}
</script>

<style scoped>
.notification-item {
  transition: all 0.3s ease;
}
.notification-item:hover {
  background-color: #f8f9fa;
  transform: scale(1.01);
}
</style>