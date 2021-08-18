<template>
  <div class="main pages rules">
    <h1 class="text-center my-3 mx-auto">
      Game Rules
    </h1>

    <ol>
      <li v-for="category in rules" :key="category.id" class="rule-header mt-4">
        {{ category.category_text }}
        <ol :id="category.id" class="rule-subitems">
          <li v-for="rule in category.rules" :key="rule.id" class="rule-subitem-text mt-1">
            {{ rule.rule_text }}
          </li>
        </ol>
      </li>
    </ol>

    <div v-show="loading" class="loading" />
  </div>
</template>

<script>
export default {
  name: 'rules',
  data() {
    return {
      loading: true,
      rules: {}
    }
  },
  mounted() {
    axios.post('getRules').then((response) => {
      if (response.status == 200) {
        this.rules = response.data
        this.loading = false
      }
    })
  }
}
</script>
