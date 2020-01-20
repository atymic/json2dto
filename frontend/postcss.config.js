const purgecss = require('@fullhuman/postcss-purgecss')

class TailwindExtractor {
  static extract (content) {
    return content.match(/[A-Za-z0-9-_:\/]+/g) || []
  }
}

module.exports = {
  plugins: [
    require('tailwindcss'),
    require('autoprefixer'),

    process.env.NODE_ENV === 'production' ? purgecss({
      extractors: [
        {
          extractor: TailwindExtractor,
          extensions: ['vue'],
        },
      ],
      content: [
        './src/**/*.vue',
      ],
      whitelistPatternsChildren: [
        /codemirror/i,
        /CodeMirror/i,
        /cm-/i,
        /markdown/i,
      ],
    }) : '',
  ],
}
