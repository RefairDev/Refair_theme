// Gulp.js configuration
'use strict';

const

	domainName = "refair",
	themeName = "refair",
	serverPath = '[LOCAL_SERVER_PATH]',


  // source and build folders
  dir = {
    src         : 'src/',    
    node        : "node_modules/",
    build       : serverPath + domainName +'/wp-content/themes/'+ themeName + '/',
	  dist		: 'dist/' + themeName + '/',
	  distCustom	: 'dist/'	
  },

  // Gulp and plugins
  gulp          = require('gulp'),
  gutil         = require('gulp-util'),
  newer         = require('gulp-newer'),
  imagemin      = require('gulp-imagemin'),
  sass          = require('gulp-sass')(require('sass')),
  postcss       = require('gulp-postcss'),
  deporder      = require('gulp-deporder'),
  concat        = require('gulp-concat'),
  stripdebug    = require('gulp-strip-debug'),
  uglify        = require('gulp-uglify'),
  debug     	= require('gulp-debug'),
  del       	= require('del'),
  composer		= require('gulp-composer'),  
  babel       = require('gulp-babel'),
  webpack       = require('webpack-stream')
 ;



// Browser-sync
var mode = 'development';
var browsersync = false;
var destFolder = dir.build;

let /** @type {import("gulp-zip")} */ gulpzip;

function setDevEnv(cb){

	destFolder = dir.build;
	setEnv(destFolder);
	cb();
}

function setProdEnv(cb){
  mode = "production";
	destFolder = dir.dist;
	setEnv(destFolder);
	cb();
}

function setEnv(destFold){

	fonts.build = destFold + 'fonts';
	vendors.build = destFold + 'vendor';
	slick.dest = vendors.build + '/slick';
	php.build = destFold;
	images.build = destFold + 'images/';
	css.build = destFold;
	css.sassOpts.imagePath = images.build;
	js.build = destFold + 'js/';
	return;
}

function clean(){
  return del(destFolder, {force: true});
};

const startup = async () => {
  // @ts-ignore
  gulpzip = (await import("gulp-zip")).default;

};

// run this task before any that require imagemin
async function startupWrapper() {
  await startup();
};

//fonts settings
var fonts = {
  src           : dir.src + 'fonts/*',
  build         : destFolder + 'fonts',
};

//copy fonts files
function fontsCopy() {
  return gulp.src(fonts.src)
    .pipe(debug({title: "Fonts copy:"})) 
    .pipe(newer(fonts.build))
    .pipe(gulp.dest(fonts.build));
};

//vendors settings
var vendors = {
  srcNode			: 'node_modules/',
	src         : dir.src + 'vendor/',
  build       : destFolder + 'vendor',
};


var slick = {
	    src : vendors.srcNode + 'slick-carousel/slick/**/*',
	    dest  : vendors.build+ '/slick',
	};

//copy slick files
function slickCopy () {
  return gulp.src(slick.src)
      .pipe(debug({title: "slick_task:"}))
    .pipe(gulp.dest(slick.dest))
    .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());
};

var fabric = {
  src : vendors.srcNode + 'fabric/dist/**/*',
  dest  : vendors.build+ '/fabric',
};

var chart = {
  src : vendors.srcNode + 'chart.js/dist/**/*',
  src_exclude : '!'+ vendors.srcNode + 'chart.js/dist/docs/**/*',
  dest  : vendors.build+ '/chartjs',
};

var dataLabel = {
  src : vendors.srcNode + 'chartjs-plugin-datalabels/dist/**/*',
  src_exclude : '!'+vendors.srcNode + 'chartjs-plugin-datalabels/dist/docs/**/*',
  dest  : vendors.build+ '/chartjs-plugin-datalabels',
};

//copy Fabric files
function fabricCopy () {
  fabric.dest = vendors.build+ '/fabric';
  return gulp.src(fabric.src)
    .pipe(debug({title: "fabric_task:"}))
  .pipe(gulp.dest(fabric.dest))
  .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());
}
//copy chartjs files
function chartCopy () {
  chart.dest = vendors.build+ '/chartjs';
  return gulp.src([chart.src, chart.src_exclude])
    .pipe(debug({title: "chartjs_task:"}))
  .pipe(gulp.dest(chart.dest))
  .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());
}

//copy datalabel files
function dataLabelCopy () {
  dataLabel.dest = vendors.build+ '/chartjs-plugin-datalabels';
  return gulp.src([dataLabel.src,dataLabel.src_exclude])
    .pipe(debug({title: "chartjs-plugin-datalabels_task:"}))
  .pipe(gulp.dest(dataLabel.dest))
  .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());
}

// var leaflet = {
//   src : vendors.srcNode + 'leaflet/dist/**/*',
//   dest  : vendors.build+ '/leaflet',
// };

// //copy leaflet files
// function leafletCopy () {
// return gulp.src(leaflet.src)
//   .pipe(debug({title: "leaflet_task:"}))
// .pipe(gulp.dest(leaflet.dest))
// .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());
// };

//copy others vendors files
function others (){
  return gulp.src([vendors.src+'**/*'])
    .pipe(gulp.dest(vendors.build))
    .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());
};



// PHP settings
var php = {
  src           : dir.src + 'php/**/*.php',
  build         : destFolder
};

// copy PHP files
function phpCopy () {
  return gulp.src(php.src)
    .pipe(debug({title: "Php_task:"}))
    .pipe(newer(php.build))
    .pipe(gulp.dest(php.build))
    // .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());
};

function jsonCopy () {
	  return gulp.src(dir.src + '**/*.json')
	    .pipe(debug({title: "json_task:"}))
	    .pipe(newer(destFolder))
	    .pipe(gulp.dest(destFolder))
	    .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop()); 
	}

function geojsonCopy () {
  return gulp.src(dir.src + 'geojson/**/*.geojson')
    .pipe(debug({title: "geojson_task:"}))
    .pipe(newer(destFolder+"geojson/"))
    .pipe(gulp.dest(destFolder+"geojson/"))
    .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop()); 
}

function vendors_lib(cb){
	composer({
		"working-dir": destFolder
  });
  cb();
};

//image settings
var images = {
  src         : dir.src + 'images/**/*',
  build       : destFolder + 'images/'
};

// image processing
 function imagesCopy() {
  return gulp.src(images.src)
    .pipe(debug({title: "images copy:"})) 
    .pipe(newer(images.build))
    .pipe(imagemin())
    .pipe(gulp.dest(images.build));
};

// image processing
function screenshot() {
  return gulp.src(dir.src + 'screenshot.png')
    .pipe(debug({title: "screenshot:"})) 
    .pipe(newer(destFolder))
    .pipe(imagemin())
    .pipe(gulp.dest(destFolder));
};

//CSS settings
var css = {
  src         : dir.src + 'scss/style.scss',
  srcAdmin    : dir.src + 'scss/admin.scss',
  watch       : dir.src + 'scss/**/*.scss',
  build       : destFolder,

  sassOpts: {
    outputStyle     : 'expanded',
    imagePath       : images.build,
    precision       : 3,
    errLogToConsole : true
  },

  processors: [
    require('postcss-assets')({
      loadPaths: ['images/'],
      basePath: destFolder,
      baseUrl: '/wp-content/themes/'+themeName +'/'
    }),
    require('postcss-sort-media-queries')(),
    require('autoprefixer')(),    
    require('cssnano')
  ]
};

// CSS processing
function cssTask() {
  return gulp.src(css.src)
    .pipe(debug({title: "CSS_task:"}))
    .pipe(sass(css.sassOpts))
    .pipe(postcss(css.processors))
    .pipe(gulp.dest(css.build))
    .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());
};

//CSS processing
function cssTaskAdmin() {
  return gulp.src(css.srcAdmin)
    .pipe(debug({title: "CSS_task admin:"}))
    .pipe(sass(css.sassOpts))
    .pipe(postcss(css.processors))
    .pipe(gulp.dest(css.build))
    .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());
};

//JavaScript settings
var js = {
  src_admin   : dir.src + 'js/admin/**/*.js',
  src_public  : dir.src + 'js/public/**/*.js',
  build     : destFolder + 'js/',
  filename_public   : 'scripts_public.js',
  filename_admin    : 'scripts_admin.js'
};

// JavaScript processing public files for dev
 function js_public() {

  return gulp.src(js.src_public, { sourcemaps: true })
    .pipe(debug({title: "js public:"})) 
    .pipe(deporder())
    .pipe(concat(js.filename_public))
    .pipe(newer(js.build))
    .pipe(gulp.dest(js.build, { sourcemaps: '.' }))
    .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());

};

// JavaScript processing private files for dev
function js_admin() {

  return gulp.src(js.src_admin, { sourcemaps: true })
    .pipe(debug({title: "js admin:"})) 
    .pipe(deporder())
    .pipe(concat(js.filename_admin))
    .pipe(newer(js.build))
    .pipe(gulp.dest(js.build, { sourcemaps: '.' }))
    .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());

};

//JavaScript processing public files for prod
function js_public_prod() {

 return gulp.src(js.src_public, { sourcemaps: true })
  .pipe(debug({title: "js public prod:"})) 
	 .pipe(deporder())
	 .pipe(concat(js.filename_public))
	 .pipe(stripdebug())
	 .pipe(uglify())
	 .pipe(newer(js.build))
	 .pipe(gulp.dest(js.build, { sourcemaps: '.' }))
	 .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());

};

//JavaScript processing admin files for prod
function js_admin_prod() {

 return gulp.src(js.src_admin, { sourcemaps: true })
  .pipe(debug({title: "js admin prod:"})) 
	 .pipe(deporder())
	 .pipe(concat(js.filename_admin))
	 .pipe(stripdebug())
	 .pipe(uglify())
	 .pipe(newer(js.build))
	 .pipe(gulp.dest(js.build, { sourcemaps: '.' }))
	 .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());

};

var jsx = {
  src_admin   : dir.src + 'js/admin/**/*.jsx',
  src_public  : dir.src + 'js/public/**/*.jsx',
  build     : destFolder + 'js/',
  filename_public   : 'app.js',
  filename_admin    : 'app_admin.js'

}

// JavaScript processing public files for dev
function jsx_public() {

  return gulp.src(jsx.src_public, { sourcemaps: true })
    .pipe(debug({title: "jsx public:"})) 
    .pipe(deporder())
    .pipe(babel({
      presets: ["@babel/preset-env","@babel/react"]}))
    .pipe(concat(js.filename_public))
    .pipe(newer(js.build))
    .pipe(gulp.dest(js.build, { sourcemaps: '.' }))
    .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());

};

// JavaScript processing public files for dev
function jsx_public_prod() {

  return gulp.src(jsx.src_public, { sourcemaps: true })
    .pipe(debug({title: "jsx public prod:"})) 
    .pipe(deporder())
    .pipe(babel({
      presets: ["@babel/preset-env","@babel/react"]}))
    .pipe(concat(js.filename_public))    
	  .pipe(uglify())
    .pipe(newer(js.build))
    .pipe(gulp.dest(js.build, { sourcemaps: '.' }))
    .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());

};

function webpackging(){
  jsx.build = destFolder + 'js/';
  let reactLeaflet = dir.node+ "react-leaflet/umd/";
  let fileName = './webpack.dev.config.js';
  switch (mode){
    case "production":
      fileName = './webpack.prod.config.js';
      break;
    default:
      fileName = './webpack.dev.config.js';
  }
  return gulp.src([jsx.src_public,reactLeaflet], { sourcemaps: true })
  .pipe(webpack( require(fileName) ))
  .pipe(gulp.dest(jsx.build, { sourcemaps: '.' }))
}


// text domain files
var languages={
  src   : dir.src + 'languages/**/*.mo',
  dist  : destFolder + 'languages/'
}

function languagesCopy () {
  languages.dist = destFolder + 'languages/';
  return gulp.src(languages.src)
    .pipe(debug({title: "languages_task:"}))
    .pipe(newer(languages.dist))
    .pipe(gulp.dest(languages.dist))
    .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop()); 
}


//Browsersync options
var syncOpts = {
  proxy       : domainName,
  files       : dir.build + '**/*',
  open        : false,
  notify      : false,
  ghostMode   : false,
  ui: {
    port: 8001
  }
};


// browser-sync
function browsersyncManagement(cb) {
  if (browsersync === false) {
    browsersync = require('browser-sync').create();
    browsersync.init(syncOpts);
  }
  cb();
};

//watch for file changes
function watch(cb){

  // page changes
  gulp.watch(php.src, gulp.series(browsersyncManagement,phpCopy));
  gulp.watch(dir.src + 'geojson/**/*.json', gulp.series(browsersyncManagement, geojsonCopy));
  gulp.watch(PCLMt.src, gulp.series(browsersyncManagement, PCLMtCopy));  

  gulp.watch(vendors.src, gulp.series(browsersyncManagement, fabricCopy, chartCopy, dataLabelCopy, others ));
  
  gulp.watch(dir.src + '**/*.json', gulp.series(browsersyncManagement, languagesCopy, jsonCopy, vendors_lib ));

  // image changes
  gulp.watch(images.src, gulp.series(browsersyncManagement, imagesCopy, screenshot));

    // CSS changes
  gulp.watch(css.watch, gulp.series(fontsCopy,gulp.series(browsersyncManagement, cssTask, cssTaskAdmin)));

  // JavaScript main changes
  gulp.watch([js.src_admin,js.src_public,jsx.src_public], gulp.series(browsersyncManagement, js_admin, js_public, webpackging));

  cb();

};

function zipAll(){
  return gulp.src(dir.dist)
		.pipe(gulpzip( themeName + '.zip' ) )
		.pipe(gulp.dest('dist'))
}

exports.cleanDev = gulp.series(
		  setDevEnv,
      startupWrapper,
		  clean
		);

//run distrib tasks
exports.dist = gulp.series(
	setProdEnv,
  startupWrapper,
  gulp.parallel(fontsCopy, phpCopy, geojsonCopy),
  gulp.parallel(imagesCopy,screenshot,languagesCopy),
  gulp.parallel(cssTask, cssTaskAdmin),
  gulp.parallel(jsonCopy, vendors_lib, fabricCopy, chartCopy, dataLabelCopy, others),
  gulp.parallel(js_admin_prod, js_public_prod, webpackging),
  zipAll
  );

//default task
exports.default = gulp.series(
  setDevEnv,
  startupWrapper,
  gulp.parallel(fontsCopy, phpCopy, geojsonCopy),
  gulp.parallel(imagesCopy,screenshot,languagesCopy),
  gulp.parallel(cssTask, cssTaskAdmin),
  gulp.parallel(jsonCopy, vendors_lib, fabricCopy, chartCopy, dataLabelCopy, others),
  gulp.parallel(js_admin, js_public, webpackging),
  gulp.parallel(watch, browsersyncManagement)
  );
