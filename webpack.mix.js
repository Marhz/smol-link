let mix = require('laravel-mix');
let webpack = require('webpack');
let fs = require('fs')

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
	.sourceMaps();


if (process.env.NODE_ENV === 'production') {
	mix.then((stats) => {
	   let data = JSON.stringify(
		   stats.toJson()
	   );
	   fs.writeFile('./stats.json', data)
	});
}
mix.webpackConfig({
	plugins: [
		new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/)
	],
	resolve: {
		alias: {
			moment: path.resolve(__dirname, 'node_modules/moment/'),
		}
	}
});
