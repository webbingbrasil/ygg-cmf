const mix = require('laravel-mix');
const webpack = require('webpack');
const path = require('path');

mix.js('resources/assets/js/ygg.js', 'resources/assets/dist/ygg.js')
    .js('resources/assets/js/client-api.js', 'resources/assets/dist/client-api.js')
    .sass('resources/assets/sass/ygg.scss', 'resources/assets/dist/ygg.css')
    .copy('node_modules/font-awesome/fonts','resources/assets/dist/fonts')
    .copy('node_modules/leaflet/dist/images/*','resources/assets/dist/images')
    .options({
        processCssUrls: false
    })
    .version()
    .extract()
    .setPublicPath('resources/assets/dist')
    .webpackConfig({
        plugins: [
            new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
            // new (require('webpack-bundle-analyzer').BundleAnalyzerPlugin)
        ],
        // transpile vue-clip package
        module: {
            rules: [
                {
                    test: /\.js$/,
                    include: [
                        path.resolve(__dirname, 'node_modules/vue-clip'),
                        path.resolve(__dirname, 'node_modules/vue2-timepicker')
                    ],
                    use: [
                        {
                            loader: 'babel-loader',
                            options: Config.babel()
                        }
                    ]
                }
            ]
        },
        resolve: {
            alias: {
                // resolve core-js@2.0 polyfills (now 3.0)
                'core-js/fn': 'core-js/features'
            }
        }
    });


