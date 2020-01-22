var path = require('path');
var webpack = require('webpack');

const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

const ExtractTextPlugin = require("extract-text-webpack-plugin");

const extractSass = new ExtractTextPlugin({
    filename: "../css/[name].min.css"
});

module.exports = {
    entry: {
        adminCSS: './assets/stylesheets/admin.scss',
        websiteCSS: './assets/stylesheets/website.scss',
        JS: './assets/javascripts/loader.prod.js',
    },
    output: {
        path: path.resolve(__dirname, '../public/js'),
        filename: '[name]-bundle.js'
    },
    module: {
        rules: [{
            test: /\.scss$/,
            use: extractSass.extract({
                use: [{
                    loader: "css-loader"
                }, {
                    loader: "sass-loader"
                }],
                fallback: "style-loader"
            })
        }]
    },
    plugins: [
        extractSass,
        // Minify CSS
        new webpack.LoaderOptionsPlugin({
            minimize: true,
        }),
        new UglifyJsPlugin({
            sourceMap: false,
            compress: true,
        }),
    ]
};