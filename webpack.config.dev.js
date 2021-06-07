const path = require('path');
const webpack = require('webpack');

module.exports = {
    mode: "development",
    entry: {
        entry: './resources/assets/app.entry.js',
    },
    output: {
        filename: 'app.bundle.js',
        path: path.resolve(__dirname, 'public/js')
    },
    module: {
        rules: [
            {
                test: /\.js?$/,
                exclude: file => (/node_modules/).test(file),
                include: [
                    path.resolve(__dirname, '../fence/source/scripts/modules/'),
                ],
                use: {
                    loader: 'babel-loader',
                    options: {
                        // presets are coming from babel config in package.json
                        sourceType: 'module',
                    },
                },
            },
        ],
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
            Popper: ['popper.js', 'default'],
        }),
    ],
    resolve: {
        alias: { //https://webpack.js.org/configuration/resolve/#resolve-alias
            FenceModules: path.resolve(__dirname, '/vendor/glance-project/fence/source/scripts/modules/'),
        }
    }
};
