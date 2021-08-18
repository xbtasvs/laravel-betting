<template>
  <div class="main pages wrapper contact">
    <h1 class="text-center text-uppercase mb-5">
      Get In Touch!
    </h1>
    <form id="contact_form" class="w-100 d-flex flex-column">
      <div class="d-flex flex-column mb-4">
        <label class="text-white" for="name">Name *</label>
        <input v-model="name" class="form-control" type="text" name="name" required>
      </div>
      <div class="d-flex flex-column mb-4">
        <label class="text-white" for="email">Email *</label>
        <input v-model="email" class="form-control" type="email" name="email" required>
      </div>
      <div class="d-flex flex-column mb-4">
        <label class="text-white" for="message">Message *</label>
        <textarea v-model="message" rows="13" class="form-control" name="message" required />
      </div>
      <button class="ml-auto btn btn-primary" @click="sendMessage">
        SEND
      </button>
    </form>
    <div v-show="loading" class="loading" />
  </div>
</template>

<script>
export default {
  name: 'contact',
  data() {
    return {
      loading: true,
      name: '',
      email: '',
      message: ''
    }
  },
  mounted() {
    this.loading = false
  },
  methods: {
    sendMessage(e) {
      e.preventDefault()
      var form = document.getElementById('contact_form')
      var status = form.checkValidity()
      form.reportValidity()
      if (status) {
        axios.post('message', { params: {
          name: this.name,
          email: this.email,
          message: this.message
        }}).then((response) => {
          this.$router.push({
            name: 'sports'
          })
          this.$root.$emit('success', {
            modalTitle: 'Message sent',
            modalText: 'Thank you for reching us, we have received your message and we will get back to you as soon as possible.'
          })
        })
      }
    }
  }
}
</script>
