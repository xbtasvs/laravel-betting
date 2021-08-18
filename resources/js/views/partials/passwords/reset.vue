<template>
  <div class="modal-mask">
    <div class="modal-wrapper">
      <div class="modal-container">
        <div class="modal-header">
          <h3>Reset Password</h3>
          <i class="fas fa-times modal-close" @click="$emit('close-reset-password')" />
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
          <div class="d-flex justify-content-between mt-3">
            <div class="d-flex flex-column-reverse w-100 position-relative mr-2">
              <input v-model="password" :type="passwordType" class="password form-control" name="password" @blur="passwordMatch" @input="passwordMatch">
              <label for="password">Password *</label>
              <i :class="passwordType === 'text' ? 'fas fa-unlock-alt lock-icon' : 'fas fa-key lock-icon'" @click="togglePass" />
              <small class="invalid-text">Passwords do not match.</small>
            </div>
            <div class="d-flex flex-column-reverse w-100 position-relative ml-2">
              <input v-model="password_confirmation" :type="passwordType" class="password form-control" name="password_confirmation" @blur="passwordMatch" @input="passwordMatch">
              <label for="password_confirmation">Password Confirmation *</label>
              <i :class="passwordType === 'text' ? 'fas fa-unlock-alt lock-icon' : 'fas fa-key lock-icon'" @click="togglePass" />
              <small class="invalid-text">Passwords do not match.</small>
            </div>
          </div>
          <p class="mt-3 w-75 pl-1">
            * Please fill in all the required fields.
          </p>
        </div>

        <div class="modal-footer">
          <button class="default-button" :disabled="errors" @click="resetPassword">
            Reset Password
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'reset-password',
  props: ['passwordToken'],
  data() {
    return {
      email: '',
      password: '',
      password_confirmation: '',
      passwordType: 'password',
      errors: true
    }
  },
  created() {
    this.formReset()
  },
  methods: {
    resetPassword() {
      axios.post('reset-password', {
        params: {
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
          token: this.passwordToken
        }
      }).then((response) => {
        this.$emit('close-reset-password')
        this.$root.$emit('success', {
          modalTitle: 'Password Reset',
          modalText: 'You have successfully reset your password.'
        })
      })
    },
    setErrors() {
      if (($('.invalid-input').length > 0) === true || this.email === '' || this.passwordsEmpty()) {
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
    passwordsEmpty() {
      if (this.password === '' || this.password_confirmation === '') {
        return true
      } else {
        return false
      }
    },
    passwordMatch(evt) {
      if (this.password_confirmation !== this.password || this.passwordsEmpty() === true) {
        $('.password.form-control').each((i, el) => {
          $(el).attr('class', 'password form-control invalid-input')
        })

        this.setErrors()
      } else {
        $('.password.form-control').each((i, el) => {
          $(el).attr('class', 'password form-control valid-input')
        })

        this.setErrors()
      }
    },
    togglePass() {
      if (this.passwordType === 'password') {
        this.passwordType = 'text'
      } else {
        this.passwordType = 'password'
      }
    },
    formReset() {
      this.email = ''
      this.password = ''
      this.password_confirmation = ''
      this.passwordType = 'password'
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
