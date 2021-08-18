<template>
  <div class="modal-mask">
    <div class="modal-wrapper">
      <div class="modal-container">
        <div class="modal-header">
          <h3>Registration</h3>
          <i class="fas fa-times modal-close" @click="$emit('close-register')" />
        </div>

        <div class="modal-body">
          <div class="d-flex justify-content-between">
            <div class="d-flex flex-column-reverse w-100 position-relative">
              <input v-model="first_name" type="text" class="form-control" name="first_name" @blur="checker" @input="checker" @keypress="isAlpha">
              <label for="first_name">First name *</label>
              <i class="far fa-times-circle invalid-icon" />
              <i class="far fa-check-circle valid-icon" />
              <small class="invalid-text">First name is required.</small>
            </div>
            <div class="d-flex flex-column-reverse w-100 position-relative mx-3">
              <input v-model="middle_name" type="text" class="form-control" name="middle_name" @keypress="isAlpha">
              <label for="middle_name">Middle name</label>
            </div>
            <div class="d-flex flex-column-reverse w-100 position-relative">
              <input v-model="last_name" type="text" class="form-control" name="last_name" @blur="checker" @input="checker" @keypress="isAlpha">
              <label for="first_name">Last name *</label>
              <i class="far fa-times-circle invalid-icon" />
              <i class="far fa-check-circle valid-icon" />
              <small class="invalid-text">Last name is required.</small>
            </div>
          </div>
          <div class="d-flex justify-content-between mt-3">
            <div class="d-flex flex-column-reverse w-100 position-relative">
              <input v-model="email" type="text" class="form-control" name="email" autocomplete="off" @input="isEmail" @keypress="emailStop" @keyup="emailTyping">
              <label for="email">Email *</label>
              <i v-if="emailSpinner" class="fas fa-spinner fa-pulse spinner-icon" />
              <i v-if="!emailSpinner" class="far fa-times-circle invalid-icon" />
              <i v-if="!emailSpinner" class="far fa-check-circle valid-icon" />
              <small class="invalid-text">{{ emailMessage }}</small>
            </div>
            <div class="d-flex flex-column-reverse w-100 position-relative mx-3">
              <input v-model="phone" type="text" class="form-control" name="phone" @input="checker" @keypress="phoneStop" @keyup="phoneTyping">
              <label for="phone">Phone *</label>
              <i v-if="phoneSpinner" class="fas fa-spinner fa-pulse spinner-icon" />
              <i v-if="!phoneSpinner" class="far fa-times-circle invalid-icon" />
              <i v-if="!phoneSpinner" class="far fa-check-circle valid-icon" />
              <small class="invalid-text">{{ phoneMessage }}</small>
            </div>
            <div class="d-flex flex-column-reverse w-100 position-relative">
              <datepicker v-model="birthday" :calendar-button="true" :bootstrap-styling="true" calendar-button-icon="far fa-calendar" name="birthday" :typeable="true" @blur="birthdayChecker" @input="birthdayChecker" />
              <label for="birthday">Birthday *</label>
              <i class="far fa-times-circle invalid-icon" />
              <i class="far fa-check-circle valid-icon" />
              <small class="invalid-text">Birthday is required.</small>
            </div>
          </div>
          <div class="d-flex justify-content-between mt-3">
            <div class="d-flex flex-column-reverse w-100 position-relative">
              <input v-model="country" type="text" class="form-control" name="country" @blur="checker" @input="checker" @keypress="isAlpha">
              <label for="country">Country *</label>
              <i class="far fa-times-circle invalid-icon" />
              <i class="far fa-check-circle valid-icon" />
              <small class="invalid-text">Country is required.</small>
            </div>
            <div class="d-flex flex-column-reverse w-100 position-relative mx-3">
              <input v-model="province" type="text" class="form-control" name="province" @blur="checker" @input="checker" @keypress="isAlpha">
              <label for="province">State *</label>
              <i class="far fa-times-circle invalid-icon" />
              <i class="far fa-check-circle valid-icon" />
              <small class="invalid-text">State / Province is required.</small>
            </div>
            <div class="d-flex flex-column-reverse w-100 position-relative">
              <input v-model="city" type="text" class="form-control" name="city" @blur="checker" @input="checker" @keypress="isAlpha">
              <label for="city">City *</label>
              <i class="far fa-times-circle invalid-icon" />
              <i class="far fa-check-circle valid-icon" />
              <small class="invalid-text">City is required.</small>
            </div>
          </div>
          <div class="d-flex justify-content-between mt-3">
            <div class="d-flex flex-column-reverse w-100 position-relative mr-2">
              <input v-model="address" type="text" class="form-control" name="address" @blur="checker" @input="checker" @keypress="isAlpha">
              <label for="address">Address *</label>
              <i class="far fa-times-circle invalid-icon" />
              <i class="far fa-check-circle valid-icon" />
              <small class="invalid-text">Address is required.</small>
            </div>
            <div class="d-flex flex-column-reverse w-50 position-relative ml-2">
              <input v-model="zip_code" type="text" class="form-control" name="zip_code" @blur="checker" @input="checker">
              <label for="zip_code">ZIP *</label>
              <i class="far fa-times-circle invalid-icon" />
              <i class="far fa-check-circle valid-icon" />
              <small class="invalid-text">ZIP is required.</small>
            </div>
          </div>
          <div class="d-flex justify-content-between mt-3">
            <div class="d-flex flex-column-reverse w-100 position-relative mr-2">
              <input v-model="password" :type="passwordType" class="password form-control" name="password" autocomplete="off" @blur="passwordMatch" @input="passwordMatch">
              <label for="password">Password *</label>
              <i :class="passwordType === 'text' ? 'fas fa-unlock-alt lock-icon' : 'fas fa-key lock-icon'" @click="togglePass" />
              <small class="invalid-text">Passwords do not match.</small>
            </div>
            <div class="d-flex flex-column-reverse w-100 position-relative ml-2">
              <input v-model="password_confirmation" :type="passwordType" class="password form-control" name="password_confirmation" autocomplete="off" @blur="passwordMatch" @input="passwordMatch">
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
          <button class="default-button mr-3" @click="formReset">
            Reset
          </button>
          <button class="default-button" :disabled="errors" @click="register">
            Register
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker'

export default {
  name: 'register',
  components: {
    Datepicker
  },
  data() {
    return {
      first_name: '',
      middle_name: '',
      last_name: '',
      email: '',
      phone: '',
      birthday: null,
      country: '',
      province: '',
      city: '',
      address: '',
      zip_code: '',
      password: '',
      password_confirmation: '',
      passwordType: 'password',
      emailMessage: '',
      phoneMessage: '',
      typingEmail: undefined,
      typingPhone: undefined,
      phoneSpinner: false,
      emailSpinner: false,
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
    register() {
      axios.post('register', {
        params: {
          first_name: this.first_name,
          middle_name: this.middle_name,
          last_name: this.last_name,
          email: this.email,
          phone: this.phone,
          birthday: this.birthday,
          country: this.country,
          province: this.province,
          city: this.city,
          address: this.address,
          zip_code: this.zip_code,
          password: this.password
        }
      }).then((response) => {
        this.$root.$emit('login', {
          user: response.data.user
        })
        $('meta[name="csrf-token"]').attr('content', response.data.token)
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = response.data.token
      })

      this.$emit('close-register')
    },
    setErrors() {
      if (($('.invalid-input').length > 0) === true || this.dataEmpty() === true || this.passwordsEmpty() === true) {
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
    birthdayChecker(evt) {
      if (evt === null) {
        $('.vdp-datepicker').attr('class', 'vdp-datepicker invalid-input')
        this.setErrors()
      } else {
        $('.vdp-datepicker').attr('class', 'vdp-datepicker valid-input')
        this.setErrors()
      }
    },
    dataEmpty() {
      if (this.first_name === '' || this.last_name === '' || this.birthday === null || this.email === '' || this.phone === '' || this.country === '' || this.province === '' || this.city === '' || this.address === '' || this.zip_code === '') {
        return true
      } else {
        return false
      }
    },
    passwordsEmpty() {
      if (this.password === '' || this.password_confirmation === '') {
        return true
      } else {
        return false
      }
    },
    isNumber(evt) {
      evt = (evt) ? evt : window.event
      var charCode = (evt.which) ? evt.which : evt.keyCode
      if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        evt.preventDefault()
      } else {
        return true
      }
    },
    isAlpha(evt) {
      evt = (evt) ? evt : window.event
      var charCode = (evt.which) ? evt.which : evt.keyCode
      if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123)) {
        return true
      } else {
        evt.preventDefault()
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
      this.first_name = ''
      this.middle_name = ''
      this.last_name = ''
      this.email = ''
      this.phone = ''
      this.birthday = null
      this.country = ''
      this.province = ''
      this.city = ''
      this.address = ''
      this.zip_code = ''
      this.password = ''
      this.password_confirmation = ''
      this.passwordType = 'password'
      this.emailMessage = ''
      this.phoneMessage = ''
      this.typingEmail = undefined
      this.typingPhone = undefined
      this.phoneSpinner = false
      this.emailSpinner = false
      this.errors = true

      $('.form-control').each((i, el) => {
        if ($(el).hasClass('invalid-input') || $(el).hasClass('valid-input')) {
          $(el).removeClass('invalid-input')
          $(el).removeClass('valid-input')
        }
      })
    },
    phoneStop(evt) {
      clearTimeout(this.typingPhone)
      this.phoneSpinner = true
    },
    emailStop(evt) {
      clearTimeout(this.typingEmail)
      this.emailSpinner = true
    },
    phoneTyping(evt) {
      clearTimeout(this.typingPhone)
      this.typingPhone = setTimeout(() => {
        this.phoneSpinner = false
        if (evt.target.value === '') {
          this.phoneMessage = 'Phone number is required.'
          evt.target.className = 'form-control invalid-input'
          this.setErrors()
        } else {
          axios.post('phonecheck', {
            params: {
              phone: evt.target.value
            }
          }).then((response) => {
            if (response.data === 'OK') {
              evt.target.className = 'form-control valid-input'
              this.setErrors()
            } else {
              evt.target.className = 'form-control invalid-input'
              this.phoneMessage = 'Phone number already exists.'
              this.setErrors()
            }
          })
        }
      }, 1500)
    },
    emailTyping(evt) {
      clearTimeout(this.typingEmail)
      this.typingEmail = setTimeout(() => {
        this.emailSpinner = false
        if (evt.target.value === '' || !(/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(evt.target.value))) {
          this.emailMessage = 'Email is required in valid format.'
          evt.target.className = 'form-control invalid-input'
          this.setErrors()
        } else {
          axios.post('emailcheck', {
            params: {
              email: evt.target.value
            }
          }).then((response) => {
            if (response.data === 'OK') {
              evt.target.className = 'form-control valid-input'
              this.setErrors()
            } else {
              evt.target.className = 'form-control invalid-input'
              this.emailMessage = 'Email already exists.'
              this.setErrors()
            }
          })
        }
      }, 1500)
    }
  }
}
</script>
