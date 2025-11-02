<template>
  <div class="d-inline-block me-2">
    <button class="btn btn-light position-relative" @click="openNotificationsModal" aria-label="Open notifications"
      title="View notifications">
      <i class="fa-regular fa-bell fs-5"></i>
      <span v-if="unreadCount > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
        :style="badgeStyle">
        {{ unreadCount }}
      </span>
    </button>
  </div>

  <!-- Admin Notifications Modal -->
  <div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel"
    aria-hidden="true" ref="notificationsModal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content rounded-3">
        <div class="modal-header d-flex justify-content-between align-items-center"
          :style="{ borderBottom: '1px solid var(--light-grey)' }">
          <h5 class="modal-title mb-0 fw-semibold" id="notificationsModalLabel">Notifications</h5>

          <div class="d-flex align-items-center gap-2 ms-auto">
            <button class="btn btn-sm d-flex align-items-center justify-content-center" :style="iconBtnStyle"
              @click="openGroupChatModal" aria-label="Open group chat" title="Open group chat">
              <i class="fa-solid fa-comments"></i>
            </button>

            <button type="button" class="btn d-flex align-items-center justify-content-center"
              style="border: none; background: transparent; color: #000;" @click="closeNotificationsModal"
              aria-label="Close" title="Close modal">
              <i class="fa-solid fa-xmark fs-5"></i>
            </button>
          </div>
        </div>

        <div class="modal-body p-0">
          <!-- Section: Today -->
          <div class="px-4 py-3" :style="{ borderBottom: '1px solid var(--light-grey)' }">
            <div class="small text-muted">Today</div>
          </div>

          <div>
            <div class="list-group list-group-flush">
              <div v-for="(note, idx) in displayedToday" :key="'t-' + idx"
                class="list-group-item d-flex align-items-center justify-content-between"
                :class="['py-3', note.read ? '' : 'bg-light-unread unread-hover']">
                <div class="d-flex align-items-start gap-3">
                  <div class="avatar-bell flex-shrink-0">
                    <i class="fa-solid fa-bell"></i>
                  </div>
                  <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2">
                      <div class="fw-bold text-success">{{ note.employee }}:</div>
                      <div class="text-muted small">{{ note.message }}</div>
                    </div>
                  </div>
                </div>

                <div class="d-flex flex-column align-items-end gap-2">
                  <div class="d-flex gap-2 align-items-center">
                    <button class="btn btn-sm" :class="note.read ? 'btn-outline-secondary' : 'btn-outline-success'"
                      @click="toggleRead(note)" :title="note.read ? 'Mark as unread' : 'Mark as read'">
                      <i class="fa-solid fa-check"></i>
                    </button>

                    <button class="btn btn-sm btn-outline-secondary" @click="openReplyModal(note)"
                      title="Reply to employee">
                      <i class="fa-solid fa-message"></i>
                    </button>
                  </div>
                  <small class="text-muted">{{ note.time }}</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Divider -->
          <div class="px-4 py-3"
            :style="{ borderTop: '1px solid var(--light-grey)', borderBottom: '1px solid var(--light-grey)' }">
            <div class="small text-muted">Earlier</div>
          </div>

          <!-- Earlier list -->
          <div>
            <div class="list-group list-group-flush">
              <div v-for="(note, idx) in displayedEarlier" :key="'e-' + idx"
                class="list-group-item d-flex align-items-center justify-content-between"
                :class="['py-3', note.read ? '' : 'bg-light-unread unread-hover']">
                <div class="d-flex align-items-start gap-3">
                  <div class="avatar-bell flex-shrink-0">
                    <i class="fa-solid fa-bell"></i>
                  </div>
                  <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center gap-2">
                      <div class="fw-bold text-success">{{ note.employee }}:</div>
                      <div class="text-muted small">{{ note.message }}</div>
                    </div>
                  </div>
                </div>

                <div class="d-flex flex-column align-items-end gap-2">
                  <div class="d-flex gap-2 align-items-center">
                    <button class="btn btn-sm" :class="note.read ? 'btn-outline-secondary' : 'btn-outline-success'"
                      @click="toggleRead(note)" :title="note.read ? 'Mark as unread' : 'Mark as read'">
                      <i class="fa-solid fa-check"></i>
                    </button>

                    <button class="btn btn-sm btn-outline-secondary" @click="openReplyModal(note)"
                      title="Reply to employee">
                      <i class="fa-solid fa-message"></i>
                    </button>
                  </div>
                  <small class="text-muted">{{ note.time }}</small>
                </div>
              </div>
            </div>
          </div>

          <div class="p-3 text-center">
            <button class="btn btn-success btn-sm px-4" @click="toggleShowAll">
              {{ showAll ? 'Show Less' : 'Show All' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Group Chat Modal -->
  <div class="modal fade" id="groupChatModal" tabindex="-1" aria-labelledby="groupChatModalLabel" aria-hidden="true"
    ref="groupChatModal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content rounded-3">
        <div class="modal-header">
          <h5 class="modal-title" id="groupChatModalLabel">Group Chat</h5>
          <button type="button" class="btn btn-close" @click="closeGroupChatModal" aria-label="Close"
            title="Close group chat">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="chat-box mb-3">
            <div v-for="(m, i) in groupMessages" :key="i" class="mb-2">
              <strong>{{ m.sender }}:</strong> <span>{{ m.text }}</span>
            </div>
          </div>

          <div class="input-group">
            <input v-model="newGroupMessage" type="text" class="form-control" placeholder="Type an announcement..."
              @keyup.enter="sendGroupMessage">
            <button class="btn btn-success" @click="sendGroupMessage">Send</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Reply Modal -->
  <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true"
    ref="replyModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-3">
        <div class="modal-header">
          <h5 class="modal-title" id="replyModalLabel">Reply to {{ selectedNotification?.employee || 'Employee' }}</h5>
          <button type="button" class="btn btn-close" @click="closeReplyModal" aria-label="Close"
            title="Close reply modal">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <div class="modal-body">
          <p class="small"><strong>Original:</strong> {{ selectedNotification?.message }}</p>
          <textarea v-model="replyMessage" class="form-control" rows="4" placeholder="Type your reply..."></textarea>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" @click="closeReplyModal">Cancel</button>
          <button class="btn btn-success" @click="sendReply">Send Reply</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AdminNotifications',
  data() {
    return {
      brand: '#2eb28a',
      lightGrey: '#f1f2f3',
      showAll: false,
      notifications: [
        { employee: 'Employee Name', message: 'Request for clock-in adjustment.', time: '2 mins ago', read: false, section: 'today' },
        { employee: 'Employee Name', message: 'Request for clock-in adjustment.', time: '10 mins ago', read: false, section: 'today' },
        { employee: 'Employee Name', message: 'Request for clock-in adjustment.', time: '45 mins ago', read: true, section: 'today' },
        { employee: 'Employee Name', message: 'Request for clock-in adjustment.', time: 'Yesterday', read: true, section: 'earlier' },
        { employee: 'Employee Name', message: 'Request for clock-in adjustment.', time: '2 days ago', read: false, section: 'earlier' },
        { employee: 'Employee Name', message: 'Request for clock-in adjustment.', time: '2 days ago', read: false, section: 'earlier' },
        { employee: 'Employee Name', message: 'Request for clock-in adjustment.', time: '2 days ago', read: false, section: 'earlier' },
        { employee: 'Employee Name', message: 'Request for clock-in adjustment.', time: '2 days ago', read: false, section: 'earlier' },
      ],
      selectedNotification: null,
      replyMessage: '',
      groupMessages: [
        { sender: 'Admin', text: 'Morning team! Please review the schedule.' },
        { sender: 'Employee Name', text: 'Will do!' }
      ],
      newGroupMessage: '',
      // Modal instances and state
      notificationsModal: null,
      groupChatModal: null,
      replyModal: null,
      activeModals: new Set() // Track which modals are currently open
    }
  },
  computed: {
    todayNotifications() { return this.notifications.filter(n => n.section === 'today') },
    earlierNotifications() { return this.notifications.filter(n => n.section === 'earlier') },
    displayedToday() { return this.showAll ? this.todayNotifications : this.todayNotifications.slice(0, 3) },
    displayedEarlier() { return this.showAll ? this.earlierNotifications : this.earlierNotifications.slice(0, 2) },
    unreadCount() { return this.notifications.filter(n => !n.read).length },
    badgeStyle() { return { backgroundColor: this.brand, color: '#fff', padding: '0.3em 0.45em', fontSize: '0.75rem' } },
    iconBtnStyle() { return { border: `1px solid var(--light-grey)`, background: 'transparent' } }
  },
  methods: {
    toggleShowAll() { this.showAll = !this.showAll },
    toggleRead(note) { note.read = !note.read },

    // Modal Management Methods
    initializeModals() {
      if (window.bootstrap) {
        const notificationsModalEl = this.$refs.notificationsModal;
        const groupChatModalEl = this.$refs.groupChatModal;
        const replyModalEl = this.$refs.replyModal;

        if (notificationsModalEl) {
          this.notificationsModal = new window.bootstrap.Modal(notificationsModalEl, {
            backdrop: true,
            keyboard: true
          });

          // Add event listeners for modal show/hide
          notificationsModalEl.addEventListener('show.bs.modal', () => {
            this.activeModals.add('notifications');
            this.ensureBackdrop();
          });

          notificationsModalEl.addEventListener('hide.bs.modal', () => {
            this.activeModals.delete('notifications');
            this.cleanupIfNoModals();
          });
        }

        if (groupChatModalEl) {
          this.groupChatModal = new window.bootstrap.Modal(groupChatModalEl, {
            backdrop: true,
            keyboard: true
          });

          groupChatModalEl.addEventListener('show.bs.modal', () => {
            this.activeModals.add('groupChat');
            this.ensureBackdrop();
          });

          groupChatModalEl.addEventListener('hide.bs.modal', () => {
            this.activeModals.delete('groupChat');
            this.cleanupIfNoModals();
          });
        }

        if (replyModalEl) {
          this.replyModal = new window.bootstrap.Modal(replyModalEl, {
            backdrop: true,
            keyboard: true
          });

          replyModalEl.addEventListener('show.bs.modal', () => {
            this.activeModals.add('reply');
            this.ensureBackdrop();
          });

          replyModalEl.addEventListener('hide.bs.modal', () => {
            this.activeModals.delete('reply');
            this.cleanupIfNoModals();
          });
        }
      }
    },

    // Ensure backdrop exists when any modal is open
    ensureBackdrop() {
      if (this.activeModals.size > 0) {
        document.body.classList.add('modal-open');
        // Ensure backdrop exists
        if (!document.querySelector('.modal-backdrop')) {
          const backdrop = document.createElement('div');
          backdrop.className = 'modal-backdrop fade show';
          document.body.appendChild(backdrop);
        }
      }
    },

    // Clean up only when no modals are open
    cleanupIfNoModals() {
      if (this.activeModals.size === 0) {
        setTimeout(() => {
          // Only cleanup if still no active modals
          if (this.activeModals.size === 0) {
            this.cleanupModals();
          }
        }, 10);
      }
    },

    openNotificationsModal() {
      if (this.notificationsModal) {
        this.notificationsModal.show();
      }
    },

    closeNotificationsModal() {
      if (this.notificationsModal) {
        this.notificationsModal.hide();
      }
    },

    openGroupChatModal() {
      // Close current modal and open group chat
      this.closeNotificationsModal();
      setTimeout(() => {
        if (this.groupChatModal) {
          this.groupChatModal.show();
        }
      }, 150); // Small delay to allow smooth transition
    },

    closeGroupChatModal() {
      if (this.groupChatModal) {
        this.groupChatModal.hide();
      }
    },

    openReplyModal(note) {
      this.selectedNotification = note;
      this.replyMessage = '';
      // Close current modal and open reply
      this.closeNotificationsModal();
      setTimeout(() => {
        if (this.replyModal) {
          this.replyModal.show();
        }
      }, 150); // Small delay to allow smooth transition
    },

    closeReplyModal() {
      if (this.replyModal) {
        this.replyModal.hide();
      }
    },

    cleanupModals() {
      // Remove all modal backdrops
      const backdrops = document.querySelectorAll('.modal-backdrop');
      backdrops.forEach(backdrop => {
        backdrop.remove();
      });

      // Reset body styles only if no modals are active
      if (this.activeModals.size === 0) {
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
      }
    },

    sendReply() {
      if (!this.replyMessage.trim()) return;
      this.groupMessages.push({
        sender: 'Admin (reply)',
        text: `Reply to ${this.selectedNotification.employee}: ${this.replyMessage}`
      });
      alert(`Reply sent to ${this.selectedNotification.employee}`);
      this.replyMessage = '';
      this.closeReplyModal();
    },

    sendGroupMessage() {
      if (!this.newGroupMessage.trim()) return;
      this.groupMessages.push({ sender: 'Admin', text: this.newGroupMessage });
      this.newGroupMessage = '';
    },

    // Handle ESC key to close all modals
    handleKeydown(event) {
      if (event.key === 'Escape') {
        this.closeAllModals();
      }
    },

    closeAllModals() {
      if (this.notificationsModal) this.notificationsModal.hide();
      if (this.groupChatModal) this.groupChatModal.hide();
      if (this.replyModal) this.replyModal.hide();
      this.activeModals.clear();
      this.cleanupModals();
    }
  },
  mounted() {
    document.documentElement.style.setProperty('--brand-color', this.brand);
    document.documentElement.style.setProperty('--light-grey', this.lightGrey);

    // Initialize modals after DOM is mounted
    this.$nextTick(() => {
      this.initializeModals();
    });

    // Add event listener for ESC key
    document.addEventListener('keydown', this.handleKeydown);
  },
  beforeUnmount() {
    // Clean up when component is destroyed
    this.closeAllModals();
    document.removeEventListener('keydown', this.handleKeydown);
  }
}
</script>

<style scoped>
.modal-content {
  border-radius: 12px;
  background: #ffffff;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.bg-light-unread {
  background-color: var(--light-grey) !important;
}

.unread-hover:hover {
  background-color: #dedede !important;
  transition: 0.2s;
}

.avatar-bell {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background-color: var(--brand-color);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 18px;
}

.btn.btn-sm {
  border-radius: 10px;
  padding: 0.50rem 0.65rem;
}

.list-group-item {
  border: none;
  border-bottom: 1px solid var(--light-grey);
}

.btn-close {
  background: transparent;
  border: none;
  cursor: pointer;
}

.modal-lg {
  max-width: 720px;
}

.modal-header {
  background-color: #fff;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid var(--light-grey);
}

.modal-header .btn i {
  transition: 0.2s ease;
}

.modal-header .btn:hover i {
  color: var(--brand-color);
  transform: scale(1.1);
}

.chat-box {
  border: 1px solid var(--light-grey);
  border-radius: 8px;
  padding: 1rem;
  background-color: #fafafa;
  max-height: 300px;
  overflow: auto;
}

/* Smooth transitions for modals */
.modal.fade .modal-dialog {
  transition: transform 0.3s ease-out;
  transform: translate(0, -50px);
}

.modal.show .modal-dialog {
  transform: none;
}

/* Button hover effects */
.btn {
  transition: all 0.2s ease-in-out;
}

.btn:hover {
  transform: translateY(-1px);
}

.btn-success {
  background-color: var(--brand-color);
  border-color: var(--brand-color);
}

.btn-success:hover {
  background-color: #28a17d;
  border-color: #28a17d;
  transform: translateY(-1px);
}

:root {
  --brand-color: #2eb28a;
  --light-grey: #f1f2f3;
}

.bg-light-unread {
  background-color: var(--light-grey) !important;
  transition: box-shadow 0.3s ease, transform 0.2s ease;
}

.avatar-bell {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background-color: var(--brand-color);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 18px;
  transition: transform 0.2s ease, background-color 0.2s ease;
}

.avatar-bell:hover {
  transform: scale(1.05);
  background-color: #28a17d;
}

.btn.btn-sm {
  border-radius: 10px;
  padding: 0.50rem 0.65rem;
  transition: transform 0.1s ease;
}

.btn.btn-sm:hover {
  transform: scale(1.05);
}

.list-group-item {
  border: none;
  border-bottom: 1px solid var(--light-grey);
  transition: background-color 0.2s ease;
}

.list-group-item:hover {
  background-color: #f8f9fa;
}

.btn-close {
  background: transparent;
  border: none;
  transition: transform 0.2s ease;
  cursor: pointer;
}

.btn-close:hover i {
  color: var(--brand-color);
  transform: scale(1.2);
}

.modal-lg {
  max-width: 720px;
}

.modal-header .btn i {
  transition: 0.2s ease;
}

.modal-header .btn:hover i {
  color: var(--brand-color);
  transform: scale(1.1);
}

.chat-box {
  border: 1px solid var(--light-grey);
  border-radius: 8px;
  padding: 1rem;
  background-color: #fafafa;
  max-height: 300px;
  overflow-y: auto;
}

/* Focus styles for accessibility */
.btn:focus,
.form-control:focus {
  box-shadow: 0 0 0 0.2rem rgba(46, 178, 138, 0.25);
  border-color: var(--brand-color);
}

/* Ensure modal backdrop stays visible */
.modal-backdrop {
  opacity: 0.5;
}

.modal-backdrop.show {
  opacity: 0.5;
}
</style>