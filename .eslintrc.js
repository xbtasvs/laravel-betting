module.exports = {
  extends: [
    'eslint:recommended',
    'plugin:vue/base',
    'plugin:vue/essential',
    'plugin:vue/recommended'
  ],
  parser: 'vue-eslint-parser',
  parserOptions: {
    parser: '@babel/eslint-parser',
    sourceType: 'module'
  },
  rules: {
    'no-console': process.env.APP_ENV === 'production' ? 'warn' : 'off',
    'no-debugger': process.env.APP_ENV == 'production' ? 'warn' : 'off',
    'quotes': ['warn', 'single'],
    'semi': ['warn', 'never'],
    'linebreak-style': ['error', 'unix'],
    'no-unused-vars': 'off',
    'no-undef': 'off',
    'vue/script-indent': 2,
    'vue/require-prop-types': 'off',
    'vue/component-name-in-template-casing': ['error', 'kebab-case'],
    'vue/component-definition-name-casing': ['error', 'kebab-case'],
    'vue/max-attributes-per-line': ['error', {
      'singleline': {
        'max': 12,
        'allowFirstLine': true
      },
      'multiline': {
        'max': 12,
        'allowFirstLine': true
      }
    }]
  }
}
