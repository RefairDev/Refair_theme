const path = require('path');

module.exports = {
	output: {
		filename: 'bundle.js',
	  },
	devtool: "source-map",
	module: {		
        rules: [
			{
				test: /\.(jsx|js)$/,
                //exclude: /node_modules/,
                use: {
					loader: 'babel-loader',
					options: {
					  presets: [
						['@babel/preset-react', { targets: "defaults" }],
						['@babel/preset-env', { targets: "defaults" }]
					  ],
					  plugins: ["@babel/plugin-transform-nullish-coalescing-operator"]
					}
				  },
			},
			{
				test: /\.css$/i,
        		use: [
					"style-loader",
					"css-loader"
				]
			},
			{
				test: /\.(png|svg|jpg|gif)$/i,
				loader: 'file-loader',
				options: {
					publicPath: '/wp-content/themes/refair/images',
					name: '[name].[ext]',
				},
			}
		]
	},
	mode:"development"
};
