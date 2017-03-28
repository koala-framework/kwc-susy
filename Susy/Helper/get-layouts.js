const sass = require('node-sass');
const fs = require('fs');
const sassConfigImporter = require('kwf-webpack/loader-kwc/sass-config-importer');
const lookupSassIncludePaths = require('kwf-webpack/loader/lookup-sass-include-paths');

const scssContent = fs.readFileSync(__dirname+'/get-layouts.scss', 'utf-8');

var result = sass.renderSync({
    data: "@import \"config/global-settings\";\n" + scssContent,
    includePaths: lookupSassIncludePaths(),
    importer: [sassConfigImporter]
});

process.stdout.write(result.css.toString() + '\n');