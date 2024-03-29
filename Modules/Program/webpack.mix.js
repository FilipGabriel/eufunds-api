let mix = require('laravel-mix');

mix.js(`${__dirname}/Resources/assets/admin/js/main.js`, `${__dirname}/Assets/admin/js/program.js`)
    .scripts(`${__dirname}/node_modules/jstree/dist/jstree.js`, `${__dirname}/Assets/admin/js/jstree.js`)
    .sass(`${__dirname}/Resources/assets/admin/sass/main.scss`, `${__dirname}/Assets/admin/css/program.css`)
    .copy(`${__dirname}/node_modules/jstree/dist/themes/default/32px.png`, `${__dirname}/Assets/admin/css`)
    .copy(`${__dirname}/node_modules/jstree/dist/themes/default/40px.png`, `${__dirname}/Assets/admin/css`)
    .copy(`${__dirname}/node_modules/jstree/dist/themes/default/throbber.gif`, `${__dirname}/Assets/admin/css`);
