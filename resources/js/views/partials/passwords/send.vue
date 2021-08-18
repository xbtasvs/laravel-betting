<template>
  <div class="modal-mask">
    <div class="modal-wrapper">
      <div class="modal-container">
        <div class="modal-header">
          <h3>Send Reset Password Link</h3>
          <i class="fas fa-times modal-close" @click="$emit('close-send-password')" />
        </div>

        <div class="modal-body">
          <div class="d-flex justify-content-between">
            <div class="d-flex flex-column-reverse w-100 position-relative">
              <input v-model="email" type="text" class="form-control" name="email" @input="isEmail" @blur="isEmail">
              <label for="email">Email *</label>
              <i class="far fa-times-circle invalid-icon" />
              <i class="far fa-check-circle valid-icon" />
              <small class="invalid-text">Email is required in valid format.</small>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button class="default-button" :disabled="errors" @click="sendLink">
            Send reset link
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'send-password',
  data() {
    return {
      email: '',
      errors: true
    }
  },
  created() {
    this.formReset()
  },
  methods: {
    sendLink() {
      axios.post('forgot-password', {
        params: {
          email: this.email
        }
      }).then((response) => {
        this.$emit('close-send-password')
        this.$root.$emit('success', {
          modalTitle: 'Password Reset',
          modalText: 'You have successfully sent password reset link to your email.'
        })
      })
    },
    setErrors() {
      if (($('.invalid-input').length > 0) === true || this.email === '') {
        this.errors = true
      } else {
        this.errors = false
      }
    },
    isEmail(evt) {
      if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(evt.target.value)) {
        evt.target.className = 'form-control valid-input'
        this.setErrors()
      } else {
        evt.target.className = 'form-control invalid-input'
        this.setErrors()
      }
    },
    formReset() {
      this.email = ''
      this.errors = true

      $('.form-control').each((i, el) => {
        if ($(el).hasClass('invalid-input') || $(el).hasClass('valid-input')) {
          $(el).removeClass('invalid-input')
          $(el).removeClass('valid-input')
        }
      })
    }
  }
}
</script>
