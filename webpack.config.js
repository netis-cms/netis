const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const EventHooksPlugin = require('event-hooks-webpack-plugin');
const fs = require('fs');
const path = require("path");
const glob = require("glob");

const ASSETS_SRC_PATH = __dirname + "/public/";
const ASSETS_DEST_PATH = __dirname + "/www";

module.exports = {
	mode: "development",
	devtool: "eval-cheap-module-source-map",
	plugins: [
		
		// Extract css as separate file (default behaviour is that css is embedded in js)
		new MiniCssExtractPlugin({
			filename: "[name].css",
			chunkFilename: "[id].css"
		}),
		// Provide plugin exposes modules as globals"
		new EventHooksPlugin({
			"done": () => {
				for(let filename of glob.sync(ASSETS_DEST_PATH + "/styles/*.css")){
                    let toRemove = filename.substr(0, filename.length - 4);
                    if(fs.existsSync(toRemove))
                        fs.unlinkSync(filename.substr(0, filename.length - 4));
                }
			}
		})
	],
	// Every js file in assets/js (not in the subdirectories) will be separate bundle
	entry: Object.assign(
		glob.sync(ASSETS_SRC_PATH + "js/*.js").reduce(
			(entry, el) => {
				entry[path.relative(ASSETS_SRC_PATH, el)] = el;
				return entry;
			}, {}
		),
		glob.sync(ASSETS_SRC_PATH + "styles/*.scss").reduce(
			(entry, el) => {
				entry[path.relative(ASSETS_SRC_PATH, el.substring(0, el.lastIndexOf(".")))] = el;
				return entry;
			}, {}
		)
	),
	output: {
		filename: "[name]",
		path: ASSETS_DEST_PATH,
	},
	module: {
		rules: [
			{
				// Process sass files, loaders are executed from bottom up
				test: /\.s[ac]ss$/i,
				use: [
					{
						// Extract CSS as separate CSS files
						loader: MiniCssExtractPlugin.loader,
						options: {
							publicPath: "../"
						}
					},
					{
						loader: "css-loader",
						options: {
							importLoaders: 1
						}
					},
					// I think this fixes URLs in SASS
					"resolve-url-loader",
					{
						loader: "sass-loader",
						options: {
							sassOptions: {
								includePaths: ["node_modules", "assets/styles"]
							},
							sourceMap: true
						}
					}
				]
			},{
				// Process css files
				test: /\.css$/i,
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: "css-loader",
						options: {
							importLoaders: 1
						}
					}
				]
			},
 {
				test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
				use: [{
					loader: "file-loader",
					options: {
						name: "[name].[ext]",
						outputPath: "fonts"
					}
				}],
			}, {
				test: /\.(jpg|png|gif)$/,
				use: {
					loader: "file-loader",
					options: {
						name: "[name].[ext]",
						outputPath: "images"
					}
				}
			}
		]
	}
};
