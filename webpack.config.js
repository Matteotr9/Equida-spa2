const Encore = require('@symfony/webpack-encore');
const HtmlWebpackPlugin = require('html-webpack-plugin');


Encore
    .setOutputPath('public/')
    .setPublicPath('/')
    .cleanupOutputBeforeBuild()
    .addEntry('app', './spa/src/app.js')
    .enablePreactPreset()
    .enableSingleRuntimeChunk()
    .enableSassLoader()
    .addPlugin(new HtmlWebpackPlugin({ template: 'spa/src/index.ejs', alwaysWriteToDisk: true }))
;

module.exports = Encore.getWebpackConfig();