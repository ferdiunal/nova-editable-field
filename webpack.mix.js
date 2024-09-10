let mix = require('laravel-mix')

require('./nova.mix')

mix
  .setPublicPath('dist')
  .js('resources/js/field.js', 'js')
  .vue({ version: 3 })
  // .css('resources/css/field.css', 'css')
  .postCss("resources/css/field.css", "css", [
      require("tailwindcss")("tailwind.config.js"),
  ])
  .nova('ferdiunal/nova-editable-field')
