/*eslint-env node*/
/**
 * External dependencies
 */
const fs = require( 'fs' );
const path = require( 'path' );
const rimraf = require( 'rimraf' );
const noop = () => {};

const rootPath = path.resolve( __dirname, '../' );

// This is a simple webpack plugin to merge the JS files generated by MiniCssExtractPlugin.
// Despited basically being noop files, they are required to get the real JS files to load,
// silently failing without them.
// See https://github.com/webpack-contrib/mini-css-extract-plugin/issues/147

function MergeExtractFilesPlugin( files = [], output = false ) {
	this.files = files;
	this.output = output;
}

MergeExtractFilesPlugin.prototype.apply = function( compiler ) {
	if ( ! this.output ) {
		return;
	}
	compiler.hooks.afterEmit.tap( 'afterEmit', () => {
		this.files.forEach( ( f ) => {
			// If we're watching, we might not have created all the file stubs.
			if ( ! fs.existsSync( path.resolve( rootPath, f ) ) ) {
				return;
			}
			const content = fs.readFileSync( path.resolve( rootPath, f ) );
			try {
				fs.appendFileSync(
					path.resolve( rootPath, this.output ),
					'\n\n' + content
				);
				// noop silently ignores errors with deleting the file.
				rimraf( f, noop );
			} catch ( error ) {
				console.log( /* eslint-disable-line no-console */
					` There was an error merging ${ f } into ${ this.output }`,
					error
				);
			}
		} );
	} );
};

module.exports = MergeExtractFilesPlugin;
