<template>
  <div class="main pages profile wrapper">
    <div v-if="!loading" class="d-flex flex-column align-items-center">
      <div class="d-flex align-items-center mb-3 mt-2">
        <transition name="component" mode="out-in">
          <img v-if="!editing" :key="notEditingKey" class="profile-image rounded-circle mr-2" :src="user.avatar" :alt="user.first_name + ' ' + user.last_name">
          <slim-cropper v-else :key="editingKey" class="slim-wrapper profile-image rounded-circle mr-2" :options="slimOptions" />
        </transition>
        <div class="d-flex flex-column profile-data ml-4">
          <transition name="component" mode="out-in">
            <span v-if="!editing" :key="notEditingKey" class="profile-data">
              <h1>{{ user.first_name + ' ' + user.last_name }}</h1>
              <h5><i class="fas fa-birthday-cake mr-2" />{{ user.birthday | moment("dddd, MMMM Do YYYY") }}</h5>
              <h5><i class="far fa-envelope mr-2" />{{ user.email }}</h5>
              <h5><i class="fas fa-mobile-alt mr-2" />{{ user.phone }}</h5>
              <h5><i class="fas fa-city mr-2" />{{ user.city }}, {{ user.country }}</h5>
              <h5><i class="fas fa-map-marked-alt mr-2" />{{ user.address }}</h5>
            </span>
            <span v-else :key="editingKey">
              <div class="modal-body">
                <div class="d-flex justify-content-between">
                  <div class="d-flex flex-column-reverse w-100 position-relative">
                    <input v-model="first_name" type="text" class="form-control" name="first_name" :placeholder="user.first_name" @blur="checker" @input="checker" @keypress="isAlpha">
                    <label class="profile-label" for="first_name">First name *</label>
                    <i class="far fa-times-circle invalid-icon" />
                    <i class="far fa-check-circle valid-icon" />
                    <small class="invalid-text profile-input-invalid">First name is required.</small>
                  </div>
                  <div class="d-flex flex-column-reverse w-100 position-relative mx-3">
                    <input v-model="middle_name" type="text" class="form-control" name="middle_name" :placeholder="user.middle_name" @keypress="isAlpha">
                    <label class="profile-label" for="middle_name">Middle name</label>
                  </div>
                  <div class="d-flex flex-column-reverse w-100 position-relative">
                    <input v-model="last_name" type="text" class="form-control" name="last_name" :placeholder="user.last_name" @blur="checker" @input="checker" @keypress="isAlpha">
                    <label class="profile-label" for="first_name">Last name *</label>
                    <i class="far fa-times-circle invalid-icon" />
                    <i class="far fa-check-circle valid-icon" />
                    <small class="invalid-text profile-input-invalid">Last name is required.</small>
                  </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                  <div class="d-flex flex-column-reverse w-100 position-relative">
                    <input v-model="email" type="text" class="form-control" name="email" :placeholder="user.email" readonly>
                    <label class="profile-label" for="email">Email **</label>
                  </div>
                  <div class="d-flex flex-column-reverse w-100 position-relative mx-3">
                    <input v-model="phone" type="text" class="form-control" name="phone" :placeholder="user.phone" readonly>
                    <label class="profile-label" for="phone">Phone **</label>
                  </div>
                  <div class="d-flex flex-column-reverse w-100 position-relative">
                    <datepicker v-model="birthday" :calendar-button="true" :bootstrap-styling="true" calendar-button-icon="far fa-calendar" name="birthday" :placeholder="user.birthday" :typeable="true" @blur="birthdayChecker" @input="birthdayChecker" />
                    <label class="profile-label" for="birthday">Birthday *</label>
                    <i class="far fa-times-circle invalid-icon" />
                    <i class="far fa-check-circle valid-icon" />
                    <small class="invalid-text profile-input-invalid">Birthday is required.</small>
                  </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                  <div class="d-flex flex-column-reverse w-100 position-relative">
                    <input v-model="country" type="text" class="form-control" name="country" :placeholder="user.province" @blur="checker" @input="checker" @keypress="isAlpha">
                    <label class="profile-label" for="country">Country *</label>
                    <i class="far fa-times-circle invalid-icon" />
                    <i class="far fa-check-circle valid-icon" />
                    <small class="invalid-text profile-input-invalid">Country is required.</small>
                  </div>
                  <div class="d-flex flex-column-reverse w-100 position-relative mx-3">
                    <input v-model="province" type="text" class="form-control" name="province" :placeholder="user.province" @blur="checker" @input="checker" @keypress="isAlpha">
                    <label class="profile-label" for="province">State *</label>
                    <i class="far fa-times-circle invalid-icon" />
                    <i class="far fa-check-circle valid-icon" />
                    <small class="invalid-text profile-input-invalid">State is required.</small>
                  </div>
                  <div class="d-flex flex-column-reverse w-100 position-relative">
                    <input v-model="city" type="text" class="form-control" name="city" :placeholder="user.city" @blur="checker" @input="checker" @keypress="isAlpha">
                    <label class="profile-label" for="city">City *</label>
                    <i class="far fa-times-circle invalid-icon" />
                    <i class="far fa-check-circle valid-icon" />
                    <small class="invalid-text profile-input-invalid">City is required.</small>
                  </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                  <div class="d-flex flex-column-reverse w-100 position-relative mr-2">
                    <input v-model="address" type="text" class="form-control" name="address" :placeholder="user.address" @blur="checker" @input="checker" @keypress="isAlpha">
                    <label class="profile-label" for="address">Address *</label>
                    <i class="far fa-times-circle invalid-icon" />
                    <i class="far fa-check-circle valid-icon" />
                    <small class="invalid-text profile-input-invalid">Address is required.</small>
                  </div>
                  <div class="d-flex flex-column-reverse w-50 position-relative ml-2">
                    <input v-model="zip_code" type="text" class="form-control" name="zip_code" :placeholder="user.zip_code" @blur="checker" @input="checker">
                    <label class="profile-label" for="zip_code">ZIP *</label>
                    <i class="far fa-times-circle invalid-icon" />
                    <i class="far fa-check-circle valid-icon" />
                    <small class="invalid-text profile-input-invalid">ZIP is required.</small>
                  </div>
                </div>
                <div class="mt-4 d-flex w-100">
                  <div class="d-flex flex-column pl-1">
                    <p class="text-white">* Please fill in all the required fields.</p>
                    <p class="text-white">** Please contact us to change phone or email.</p>
                  </div>
                  <button type="button" class="btn btn-primary ml-auto text-white" :disabled="errors" @click="saveEdits"><i class="fas fa-save text-white mr-2" />Save</button>
                </div>
              </div>
            </span>
          </transition>
        </div>
      </div>
      <div class="d-flex align-items-center mt-2">
        <button v-if="!editing" type="button" class="btn btn-secondary mx-2" @click="toggleEdit">
          <i class="fas fa-user-edit mr-2" />Edit Profile
        </button>
        <button v-else type="button" class="btn btn-secondary mx-2" @click="toggleEdit">
          <i class="fas fa-ban mr-2" />Cancel
        </button>
        <router-link class="btn btn-secondary mx-2" :to="{ name: 'active', params: {} }">
          <i class="fas fa-stream mr-2" />Active Bets
        </router-link>
        <router-link class="btn btn-secondary mx-2" :to="{ name: 'history', params: {} }">
          <i class="fas fa-history mr-2" />History
        </router-link>
        <li class="btn btn-secondary mx-2" @click="$root.$emit('send-password')">
          <i class="fas fa-unlock-alt mr-2" />Reset Password
        </li>
      </div>
    </div>
    <div v-show="loading" class="loading" />
  </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker'
import Slim from './partials/slim/slim.vue'

export default {
  name: 'profile',
  components: {
    'datepicker': Datepicker,
    'slim-cropper': Slim
  },
  data() {
    return {
      loading: true,
      user: undefined,
      editing: false,
      editingKey: 0,
      notEditingKey: 1,
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
      errors: false,
      slimOptions: {
        ratio: '1:1',
        instantEdit: true,
        buttonEditLabel: '',
        buttonRemoveLabel: '',
        buttonRotateLabel: '',
        buttonUploadLabel: '',
        serviceFormat: 'file',
        initialImage: this.user === undefined ? '' : this.user.avatar,
        service: this.slimService
      }
    }
  },
  created() {
    this.formReset()
    this.getUser()
  },
  mounted() {
    this.formReset()
  },
  beforeDestroy() {
    this.formReset()
  },
  methods: {
    toggleEdit() {
      this.editing = !this.editing
      this.editingKey = this.editingKey + 1
      this.notEditingKey = this.notEditingKey + 1
    },
    saveEdits() {
      axios.post('profile-update', {
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
        this.getUser()
        this.$root.$emit('success', {
          modalTitle: 'Profile update',
          modalText: 'Your profile is successfuly updated.'
        })
        this.toggleEdit()
      })
    },
    slimService(formdata, progress, success, failure, slim) {
      var data = new FormData()
      data.append('id', this.user_id)
      data.append('avatar', formdata[0])
      axios.post('avatar-update', data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then((response) => {
        this.getUser()
        this.$root.$emit('success', {
          modalTitle: 'Profile image update',
          modalText: 'Your profile image is successfuly updated.'
        })
      })
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
      this.errors = false

      $('.form-control').each((i, el) => {
        if ($(el).hasClass('invalid-input') || $(el).hasClass('valid-input')) {
          $(el).removeClass('invalid-input')
          $(el).removeClass('valid-input')
        }
      })
    },
    getUser() {
      axios.post('user').then((response) => {
        if (Object.keys(response.data).length !== 0) {
          this.user = response.data
          this.first_name = response.data.first_name
          this.middle_name = response.data.middle_name
          this.last_name = response.data.last_name
          this.email = response.data.email
          this.phone = response.data.phone
          this.birthday = response.data.birthday
          this.country = response.data.country
          this.province = response.data.state
          this.city = response.data.city
          this.address = response.data.address
          this.zip_code = response.data.zip_code
          this.loading = false
        } else {
          this.$router.push({
            name: 'sports'
          })
        }
      })
    }
  }
}
</script>
