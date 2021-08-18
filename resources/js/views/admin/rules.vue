<template>
  <div class="main pages justify-content-start">
    <div class="accordion w-75 mx-auto">
      <div class="d-flex bg-primary p-3 font-bold justify-content-between align-items-center mt-3 w-100">
        <p class="w-25">
          Category order
        </p>
        <p class="text-center w-50 mx-auto">
          Category name
        </p>
        <p class="w-25 text-right">
          Actions
        </p>
      </div>
      <div v-for="category in rules" :id="category.id + 'margin'" :key="category.id" class="d-flex flex-column flex-nowrap w-100 border-bottom">
        <div class="d-flex bg-light text-dark p-1 justify-content-between align-items-center flex-nowrap position-relative">
          <p class="w-25 pl-3">
            {{ category.category_order }}
          </p>
          <p class="mx-auto w-50 text-center">
            {{ category.category_text }}
          </p>
          <div class="d-flex align-items-center justify-content-between w-25">
            <button type="button" name="button" class="btn btn-secondary icon-button ml-auto">
              <i class="far fa-edit" />
            </button>
            <button type="button" name="button" class="btn btn-secondary icon-button mx-2">
              <i class="fas fa-trash-alt" />
            </button>
            <button type="button" name="button" class="btn btn-secondary icon-button" @click="toggleRules(category.id)">
              <i class="far fa-eye" />
            </button>
          </div>

          <div :id="category.id" class="accordion-subitems position-absolute d-none">
            <div class="d-flex bg-primary-light text-light p-2 font-bold justify-content-between align-items-center w-100">
              <p class="w-25">
                Rule order
              </p>
              <p class="text-center w-50 mx-auto">
                Rule name
              </p>
              <p class="w-25 text-right">
                Actions
              </p>
            </div>
            <div class="d-flex flex-column flex-nowrap">
              <div v-for="rule in category.rules" :key="rule.id + 'x'" class="d-flex bg-light text-dark p-1 justify-content-between align-items-center flex-nowrap border-bottom">
                <p class="w-25 pl-3">
                  {{ rule.rule_order }}
                </p>
                <p class="text-left w-50 mx-auto">
                  {{ rule.rule_text }}
                </p>
                <div class="d-flex align-items-center justify-content-between w-25">
                  <button type="button" name="button" class="btn btn-secondary icon-button ml-auto">
                    <i class="far fa-edit" />
                  </button>
                  <button type="button" name="button" class="btn btn-secondary icon-button ml-2">
                    <i class="fas fa-trash-alt" />
                  </button>
                </div>
              </div>
            </div>
            <div class="d-flex bg-primary-light add-button text-light p-2 font-bold justify-content-end align-items-center text-right w-100 mb-3">
              Add rule<i class="fas fa-plus ml-2" />
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex bg-primary add-button p-2 font-bold justify-content-end align-items-center text-right w-100">
        Add category<i class="fas fa-plus ml-2" />
      </div>
    </div>
    <div v-show="loading" class="loading" />
  </div>
</template>

<script>
export default {
  name: 'admin-rules',
  data() {
    return {
      loading: true,
      rules: {}
    }
  },
  created() {
    axios.post('user').then((response) => {
      if (response.status == 200 && response.data.id == 1) {
        this.isAdmin = true
      } else {
        this.$router.push({ name: 'sports' })
      }
    })

    axios.post('getRules').then((response) => {
      if (response.status == 200) {
        this.rules = response.data
        this.loading = false
      }
    })
  },
  methods: {
    toggleRules (catId) {
      $('.accordion-subitems').each((key, el) => {
        if ($(el) !== $(catId)) {
          $(el).addClass('d-none')
        }
      })

      $('.margin-offset').each((key, el) => {
        if ($(el) !== $('#' + key + 'margin')) {
          $(el).removeClass('margin-offset')
        }
      })

      if ($('#' + catId).hasClass('d-none')) {
        $('#' + catId + 'margin').addClass('margin-offset')
        $('#' + catId).removeClass('d-none')
      } else {
        $('#' + catId).addClass('d-none')
        $('#' + catId + 'margin').removeClass('margin-offset')
      }
    }
  }
}
</script>
