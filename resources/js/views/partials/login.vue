<template>
  <div class="modal-mask">
    <div class="modal-wrapper">
      <div class="modal-container">
        <div class="modal-header">
          <h3>Login</h3>
          <i class="fas fa-times modal-close" @click="$emit('close-login')" />
        </div>

        <div class="modal-body">
          <div class="d-flex justify-content-between">
            <div class="d-flex flex-column-reverse w-100 position-relative mr-2">
              <input v-model="email" type="text" class="form-control" name="email" autocomplete="off" @input="isEmail" @blur="isEmail">
              <label for="email">Email *</label>
              <i class="far fa-times-circle invalid-icon" />
              <i class="far fa-check-circle valid-icon" />
              <small class="invalid-text">{{ emailMessage }}</small>
            </div>
            <div class="d-flex flex-column-reverse w-100 position-relative ml-2">
              <input v-model="password" :type="passwordType" class="password form-control" name="password" autocomplete="off" @input="checker" @blur="checker">
              <label for="password">Password *</label>
              <i :class="passwordType === 'text' ? 'fas fa-unlock-alt lock-icon' : 'fas fa-key lock-icon'" @click="togglePass" />
              <small class="invalid-text">Password is required.</small>
            </div>
          </div>
          <p class="mt-2 w-100 text-right pr-1 hover-underline" @click="getPassword">
            Forgot your password?
          </p>
        </div>

        <div class="modal-footer">
          <button class="default-button" :disabled="errors" @click="login">
            Login
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'login',
  data() {
    return {
      email: '',
      password: '',
      passwordType: 'password',
      emailMessage: 'Email is required.',
      errors: true
    }
  },
  created() {
    this.formReset()
  },
  mounted() {
    this.formReset()
  },
  methods: {
    login() {
      axios.post('login', {
        params: {
          email: this.email,
          password: this.password
        }
      }).then((response) => {
        if (response.data === 'Error') {
          this.$root.$emit('error', {
            modalTitle: 'Login Error',
            modalText: 'Email or password not found, please try again.'
          })
        } else {
          this.$root.$emit('login', {
            user: response.data.user
          })
          $('meta[name="csrf-token"]').attr('content', response.data.token)
          window.axios.defaults.headers.common['X-CSRF-TOKEN'] = response.data.token
        }

        this.$emit('close-login')
      })
    },
    setErrors() {
      if (($('.invalid-input').length > 0) === true || this.dataEmpty() === true) {
        this.errors = true
      } else {
        this.errors = false
      }
    },
    checker(evt) {
      if (evt.target.value === '') {
        evt.target.className = 'form-control invalid-input'
        this.setErrors()
      } else {
        evt.target.className = 'form-control valid-input'
        this.setErrors()
      }
    },
    dataEmpty() {
      if (this.password === '' || this.email === '') {
        return true
      } else {
        return false
      }
    },
    togglePass() {
      if (this.passwordType === 'password') {
        this.passwordType = 'text'
      } else {
        this.passwordType = 'password'
      }
    },
    isEmail(evt) {
      if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(evt.target.value)) {
        evt.target.className = 'form-control valid-input'
        this.setErrors()
      } else {
        evt.target.className = 'form-control invalid-input'
        this.emailMessage = 'Email is required in valid format.'
        this.setErrors()
      }
    },
    formReset() {
      this.email = ''
      this.password = ''
      this.passwordType = 'password'
      this.emailMessage = 'Email is required.'
      this.errors = true

      $('.form-control').each((i, el) => {
        if ($(el).hasClass('invalid-input') || $(el).hasClass('valid-input')) {
          $(el).removeClass('invalid-input')
          $(el).removeClass('valid-input')
        }
      })
    },
    getPassword() {
      this.$emit('close-login')
      this.$root.$emit('send-password')
    }
  }
}
</script>
