module.exports = {
  env: {
    commonjs: true,
    es6: true,
    mocha: true,
  },
  extends: ['hbg'],
  globals: {
    Atomics: 'readonly',
    SharedArrayBuffer: 'readonly',
  },
  rules: {
    "prettier/prettier": [
      "error",
      {
        trailingComma: "es5",
        printWidth: 100,
        singleQuote: true,
        tabWidth: 2
      }
    ],
  },
}