module.exports = {
  root: true,
  env: {
    es6: true,
    node: true,
  },
  extends: [
    "eslint:recommended",
    "google",
  ],
  rules: {
    "quotes": ["error", "double"],
    "max-len": ["error", { "code": 100 }], // Augmenter la limite à 100 caractères
  },
  parserOptions: {
    ecmaVersion: 2018,
  },
};
