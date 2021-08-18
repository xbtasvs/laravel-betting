<template>
  <div class="modal-mask">
    <div class="modal-wrapper">
      <div class="modal-container error-modal-container">
        <div class="modal-header error-modal-header">
          <h3>{{ modalTitle }}</h3>
          <i class="fas fa-times modal-close" @click="closeError" />
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
  name: 'error',
  props: ['modalTitle', 'modalText'],
  methods: {
    closeError() {
      this.$emit('close-error')
      if (this.modalTitle === 'Login Error') {
        this.$root.$emit('show-login')
      }
    }
  }
}
</script>
