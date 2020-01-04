module.exports = {
  root: true,
  env: {
    node: true,
  },
  extends: [
    'plugin:vue/essential',
  ],
  rules: {
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'max-len': 'off',
    'no-undef': 'off',
    'import/no-extraneous-dependencies': 'off',
    'global-require': 'off',
  },
  parserOptions: {
    parser: 'babel-eslint',
  },
};
