<template>
  <div class="modal-mask">
    <div class="modal-wrapper">
      <div class="modal-container">
        <div class="modal-header">
          <h3>Deposit</h3>
          <i class="fas fa-times modal-close" @click="$emit('close-deposit')" />
        </div>

        <div class="modal-body">
          <div class="d-flex flex-column w-100">
            <div class="d-flex justify-content-between mt-3">
              <div class="d-flex flex-column-reverse w-100 position-relative">
                <div id="card-element" class="bg-white" @input="checker" />
                <label for="card-element">Credit Card *</label>
                <small id="card-error" class="invalid-text" />
              </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
              <div class="d-flex flex-column-reverse w-100 position-relative">
                <small>Minimum deposit amount is £10.</small>
                <input id="amount-element" v-model="amount" type="number" class="form-control" @blur="checker" @input="checker">
                <label for="amount">Amount *</label>
                <small id="amount-error" class="invalid-text" />
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button :disabled="disabled" class="btn btn-primary btn-block mt-3" @click="processPayment">
            <i class="far fa-credit-card mr-2" />Pay<i v-if="paymentProcessing" class="fas fa-spinner fa-pulse spinner-icon ml-2 text-white position-static" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { loadStripe } from '@stripe/stripe-js'

export default {
  name: 'deposit',
  data() {
    return {
      stripe: {},
      cardElement: {},
      paymentProcessing: false,
      paymentMethodId: undefined,
      loading: true,
      first_name: '',
      last_name: '',
      email: '',
      phone: '',
      country: '',
      province: '',
      city: '',
      address: '',
      zip_code: '',
      amount: '',
      disabled: true
    }
  },
  async mounted() {
    this.getUser()

    this.stripe = await loadStripe(process.env.MIX_STRIPE_KEY)

    const elements = this.stripe.elements()
    this.cardElement = elements.create('card', {
      classes: {
        base: 'form-control special-line-height'
      }
    })

    this.cardElement.mount('#card-element')
  },
  methods: {
    getUser() {
      axios.post('user').then((response) => {
        if (Object.keys(response.data).length !== 0) {
          this.user = response.data
          this.first_name = response.data.first_name
          this.last_name = response.data.last_name
          this.email = response.data.email
          this.phone = response.data.phone
          this.country = response.data.country
          this.province = response.data.state
          this.city = response.data.city
          this.address = response.data.address
          this.zip_code = response.data.zip_code
        } else {
          // console.error(response)
        }

        this.loading = false
      })
    },
    checker(evt) {
      $('#card-error').html('')
      $('#card-element').removeClass('invalid-input')

      if (this.amount < 10) {
        this.disabled = true
        $('#amount-error').html('Please deposit minimum £10.')
        $('#amount-element').addClass('invalid-input')
      } else if (this.amount > 100) {
        this.disabled = false
        $('#amount-error').html('')
        $('#amount-element').removeClass('invalid-input')
      } else {
        this.disabled = false
        $('#amount-error').html('')
        $('#amount-element').addClass('valid-input')
      }
    },
    async processPayment() {
      this.paymentProcessing = true

      const { paymentMethod, error } = await this.stripe.createPaymentMethod('card', this.cardElement, {
        billing_details: {
          name: this.first_name + ' ' + this.last_name,
          email: this.email,
          address: {
            line1: this.address,
            city: this.city,
            state: this.province,
            postal_code: this.zip_code
          }
        }
      })

      if (error) {
        $('#card-error').html(error.message)
        $('#card-element').addClass('invalid-input')

        setTimeout(() => {
          this.paymentProcessing = false
        }, 1500)
      } else {
        axios.post('/purchase', {
          params: {
            amount: this.amount * 100,
            payment_method_id: paymentMethod.id,
            customer: {
              name: this.first_name + ' ' + this.last_name,
              email: this.email,
              address: {
                line1: this.address,
                city: this.city,
                country: this.country,
                state: this.province,
                postal_code: this.zip_code
              }
            }
          }
        }).then((response) => {
          if (response.status === 201) {
            this.paymentProcessing = false
            $('#card-error').html(response.data)
            $('#card-element').addClass('invalid-input')
          } else if (response.status === 200) {
            this.$emit('close-deposit')
            this.paymentProcessing = false
            this.$root.$emit('success', {
              modalTitle: 'Success',
              modalText: 'Your deposit was successfull!'
            })
          }
        }).catch((error) => {
          this.$emit('close-deposit')
          this.paymentProcessing = false
          this.$root.$emit('error', {
            modalTitle: 'Deposit error',
            modalText: error.message
          })
        })
      }
    }
  }
}
</script>
