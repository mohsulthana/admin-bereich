var path = require('path');
var webpack = require('webpack');

var BrowserSyncPlugin = require('browser-sync-webpack-plugin');

const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

module.exports = {
    entry: {
        JS: './assets/javascripts/loader.dev.js',
    },
    output: {
        devtoolLineToLine: true,
        sourceMapFilename: "./[name]-bundle.js.map",
        pathinfo: true,
        path: path.resolve(__dirname, '../public/js'),
        filename: '[name]-bundle.js'
    },
    module: {
        loaders: [
            {
                test: /\.js$/,
                loader: 'babel-loader',
                query: {
                    presets: ['es2015']
                }
            }
        ],
        rules: [{
            test: /\.(jpe|jpg|woff|woff2|eot|ttf|svg|scss)(\?.*$|$)/,
            use: [{
                loader: "style-loader"
            }, {
                loader: "css-loader"
            }, {
                loader: "sass-loader"
            }]
        }]
    },
    plugins: [
        new BrowserSyncPlugin(
            {
                host: 'localhost',
                port: 3000,
                proxy: 'http://localhost:8080/'
            },
            {
                reload: true
            }
        ),
    ],
    stats: {
        colors: true
    },
    devtool: 'source-map'
};