const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );
const path = require( 'path' );
const { globSync } = require( 'glob' );

/**
 * Auto-discovery entry points: każdy "blocks/<dir>/index.js" i "blocks/<dir>/view.js".
 * Wynik: "build/blocks/<dir>/<index|view>.js".
 */
const blockEntries = [
	...globSync( './blocks/*/index.js' ),
	...globSync( './blocks/*/view.js' ),
].reduce( ( entries, file ) => {
	const blockName = path.basename( path.dirname( file ) );
	const entryName = path.basename( file, '.js' );
	entries[ `blocks/${ blockName }/${ entryName }` ] = path.resolve( __dirname, file );
	return entries;
}, {} );

/**
 * Fallback entry — przy zero bloków webpack padłby na pustym `entry`.
 * assets/src/index.js (pusty) gwarantuje, że `npm run build` przechodzi
 * od razu po `npm install`. Po dodaniu pierwszego bloku można go zignorować.
 */
const entry = Object.keys( blockEntries ).length
	? blockEntries
	: { 'theme': path.resolve( __dirname, 'assets/src/index.js' ) };

module.exports = {
	...defaultConfig,
	entry,
	output: {
		path: path.resolve( __dirname, 'build' ),
		filename: '[name].js',
	},
	plugins: [
		...( defaultConfig.plugins || [] ),
		new CopyWebpackPlugin( {
			patterns: [
				{ from: 'blocks/*/block.json', to: '[path][name][ext]', noErrorOnMissing: true },
				{ from: 'blocks/**/*.php', to: '[path][name][ext]', noErrorOnMissing: true },
			],
		} ),
	],
};
