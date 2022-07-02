let mix = require('laravel-mix');
const path = require('path');
require('./nova.mix');

mix.setPublicPath('dist')
    .js('resources/js/tool.js', 'js')
    .vue({ version: 3 })
    .css('resources/css/tool.css', 'css')
    .nova('mrw/report-page-generator')
    .alias({
        '@': path.join(__dirname, 'resources/js'),
    });
