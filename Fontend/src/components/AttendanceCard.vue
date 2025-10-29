<template>
  <div class="card">
    <div class="card-content">
      <i :class="icon" class="card-icon"></i>
      <div>
        <div class="card-title">{{ title }}</div>
        <div class="card-value">{{ value }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "AttendanceCard",
  props: {
    title: String,
    icon: String,
    value: [String, Number],
  },
};
</script>

<style scoped>
.card {
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  padding: 20px 25px;
  padding-left: 70px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-left: 4px solid #2EB28A;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(46, 178, 138, 0.2);
}

.card-content {
  display: flex;
  align-items: center;
  gap: 15px;
}

.card-icon {
  font-size: 1.8rem;
  color: #2EB28A;
}

.card-title {
  font-size: 1rem;
  color: #000000;
  font-weight: 600;
}

.card-value {
  font-size: 1.8rem;
  font-weight: 700;
  color: #000000;
}
</style>
