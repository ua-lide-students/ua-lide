var Encore = require('@symfony/webpack-encore');

Encore
// directory where compiled assets will be stored
  .setOutputPath('web/build/')
  // public path used by the web server to access the output path
  .setPublicPath('/build')
  // only needed for CDN's or sub-directory deploy
  //.setManifestKeyPrefix('build/')

  /*
   * ENTRY CONFIG
   *
   * Add 1 entry for each "page" of your app
   * (including one that's included on every page - e.g. "app")
   *
   * Each entry will result in one JavaScript file (e.g. app.js)
   * and one CSS file (e.g. app.scss) if you JavaScript imports CSS.
   */
  .addEntry('ide', './assets/js/ide.js')
  .addEntry('home', './assets/js/homepage.js')
  //.addEntry('page1', './assets/js/page1.js')
  //.addEntry('page2', './assets/js/page2.js')
  .addStyleEntry('app', './assets/css/app.scss')
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  // enables hashed filenames (e.g. app.abc123.css)
  .enableVersioning(Encore.isProduction())

  .enableVueLoader()
  .enableSassLoader(function () {
  }, {
    resolveUrlLoader: false //Set this to false otherwise it cause problem with tailwind
  })
  .enablePostCssLoader()

  .addLoader({
    test: /\.js$/,
    enforce: 'pre',
    loader: 'prettier-loader',
    exclude: /node_modules/,
    options: {
      parser: 'babylon'
    }
  })
  .addLoader({
    test: /\.js$/,
    enforce: 'pre',
    loader: 'eslint-loader',
    exclude: /node_modules/,
    options: {
      fix: false
    }
  })
;

module.exports = Encore.getWebpackConfig();
