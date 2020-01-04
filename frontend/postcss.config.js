const purgecss = require("@fullhuman/postcss-purgecss");


module.exports = {
  plugins: [
    require('tailwindcss')('tailwind.js'),
    require('autoprefixer')({
      add: true,
      grid: true
    }),

    process.env.NODE_ENV === "production"? purgecss({
      content: [
        "./src/**/*.html",
        "./src/**/*.vue"
      ]
    }): ""
  ],
};
