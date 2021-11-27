const path = require('path');

module.exports = {
	mode: 'development',
	// entry: path.join(__dirname, 'client', 'src', 'index.jsx'),
	entry: path.join(__dirname, 'assets', 'js', 'app', 'src', 'index.js'),
	output: {
		path: path.resolve(__dirname, 'assets', 'js', 'app', 'build'),
		filename: 'index.js',
	},
	module: {
		rules: [
			{
				test: /\.(js|jsx)$/,
				include: path.resolve(__dirname, 'assets', 'js', 'app', 'src'),
				exclude: [
					/node_modules/,
				],
				use: [
					{
						loader: 'babel-loader',
						options: {
							presets: ["@babel/preset-env", "@babel/preset-react"],
						},
					}
				],
			},
			{
				test: /\.(scss|css)$/,
				exclude: /node_modules/,
				use: [ 'style-loader', 'css-loader', 'sass-loader' ],
			},
		],
	},
	resolve: {
		extensions: ['.js', '.jsx', '.json', '.scss', '.css'],
	},
};