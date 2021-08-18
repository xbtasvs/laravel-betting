<template>
  <div class="modal-mask">
    <div class="modal-wrapper">
      <div class="modal-container success-modal-container">
        <div class="modal-header success-modal-header">
          <h3>{{ modalTitle }}</h3>
          <i class="fas fa-times modal-close" @click="closeSuccess" />
        </div>

        <div class="modal-body">
          <p>{{ modalText }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'success',
  props: ['modalTitle', 'modalText'],
  methods: {
    closeSuccess() {
      this.$emit('close-success')
      if (this.modalText === 'You have successfully reset your password.') {
        this.$root.$emit('remove-token')
        this.$router.push('/')
        this.$root.$emit('show-login')
      }
    }
  }
}
</script>
