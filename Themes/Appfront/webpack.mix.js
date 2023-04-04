let mix = require('laravel-mix');
let execSync = require('child_process').execSync;

mix.js(`${__dirname}/resources/assets/admin/js/main.js`, `${__dirname}/assets/admin/js/appfront.js`)
    .then(() => {
        execSync(`npm run rtlcss ${__dirname}/assets/admin/css/appfront.css ${__dirname}/assets/admin/css/appfront.rtl.css`);
    });
