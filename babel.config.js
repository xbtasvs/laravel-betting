module.exports = {
  presets: [
    '@babel/preset-env'
  ],
  plugins: [
    '@babel/plugin-syntax-jsx',
    ['@babel/plugin-proposal-decorators', { 'legacy': true }]
  ]
}
