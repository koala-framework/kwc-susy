const sass = require('node-sass');
const fs = require('fs');
const lookupSassIncludePaths = require('kwf-webpack/loader/lookup-sass-include-paths');

const scssContent = fs.readFileSync(__dirname+'/get-layouts.scss', 'utf-8');

var result = sass.renderSync({
    data: "@import \"config/global-settings\";\n" + scssContent,
    includePaths: lookupSassIncludePaths()
});

process.stdout.write(result.css.toString() + '\n');