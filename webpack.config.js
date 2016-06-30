/**
 * Created by pgotthardt on 14/01/16.
 */
var path = require('path');
var webpack = require('webpack');
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var OptimizeCssAssetsPlugin = require("optimize-css-assets-webpack-plugin");

var isProduction = (process.env.NODE_ENV === 'production');

var plugins = [
    new ExtractTextPlugin("app.css")
];

if (isProduction) {
    plugins.push(new webpack.optimize.UglifyJsPlugin({
        compress: {warnings: false}
    }));
    plugins.push(new OptimizeCssAssetsPlugin({
        assetNameRegExp: /\.min\.css$/,
        cssProcessorOptions: {discardComments: {removeAll: true}}
    }));
}

module.exports = {
    entry: ['whatwg-fetch', 'babel-polyfill', path.normalize(__dirname + '/web/src/js/main')],
    devtool: 'cheap-module-source-map',
    output: {
        filename: 'bundle.js',
        path: path.join(__dirname, 'web/assets/dist')
    },
    module: {
        loaders: [
            {
                test: /\.js$/,
                loader: 'babel',
                include: [path.resolve(__dirname, 'web/src', 'js')],
                query: {
                    plugins: ['transform-runtime'],
                    presets: ['es2015']
                }
            },
            {
                test: /\.css$/,
                loader: ExtractTextPlugin.extract("style-loader", "css-loader")
            },
            {
                test: /\.jsx?$/,
                loaders: ['babel?cacheDirectory'],
                include: [path.resolve(__dirname, 'web/src', 'js')]
            }
        ]
    },
    plugins: plugins
};
