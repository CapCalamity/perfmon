process.env.DISABLE_NOTIFIER = true;

var elixir = require('laravel-elixir');

var bowerDir = './resources/assets/vendor/';
var assetDir = './resources/assets/';

var lessPaths = [
    bowerDir + "bootstrap/less",
    bowerDir + "font-awesome/less",
    bowerDir + "bootstrap-select/less",
    assetDir + "less/"
];

elixir(function (mix) {
    mix.less('app.less', 'public/css', {paths: lessPaths})
        .scripts([
            'jquery/dist/jquery.min.js',
            'bootstrap/dist/js/bootstrap.min.js',
            'bootstrap-select/dist/js/bootstrap-select.min.js',
            'highcharts/highstock.js',
            'highcharts/highcharts-more.js',
            'datejs/build/production/date.min.js'
        ], 'public/js/vendor.js', bowerDir)
        .scripts([
            'resources/assets/js/app.js'
        ], 'public/js/app.js')
        .scripts([
            'resources/assets/js/system.js'
        ], 'public/js/system.js')
        .copy(bowerDir + 'font-awesome/fonts', 'public/fonts');
});