const path = require("path");
const webpack = require('webpack')
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const WebpackAssetsManifest = require('webpack-assets-manifest');
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const SpriteLoaderPlugin = require('svg-sprite-loader/plugin');
const CopyPlugin = require("copy-webpack-plugin");
require('dotenv').config()

const isProduction = process.env.NODE_ENV === "production";

const stylesHandler = () => {
    return isProduction ? MiniCssExtractPlugin.loader : 'style-loader';
};

const themeName = path.basename(__dirname);

const params = {
    port: +process.env.PORT ?? 3000,
    publicPath: process.env.PUBLIC_PATH + themeName + '/' + process.env.ASSETS_DIR + '/',
    serverAddress: process.env.DOMAIN,
    siteURL: `http://${process.env.DOMAIN}`
}

const configureDevServer = () => {
    return {
        compress: true,
        host: 'localhost',
        open: false,
        // hot: true,
        port: params.port,
        allowedHosts: 'all',
        devMiddleware: {
            publicPath: params.publicPath,
        },
        client: {
            progress: true,
            overlay: true,
            logging: 'info',
        },
        watchFiles: ['**/*.php'],
        proxy: {
            '/': {
                target: params.siteURL,
                secure: false,
                changeOrigin: true,
            }
        },
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, PATCH, OPTIONS',
            'Access-Control-Allow-Headers': 'X-Requested-With, content-type, Authorization',
        },
    };
};

const config = {
    optimization: {
        splitChunks: {
            cacheGroups: {
                vendor: {
                    name: 'vendors',
                    test: /[\\/]node_modules[\\/]/,
                    chunks: 'all',
                    enforce: true
                }
            }
        },
        minimizer: [
            new CssMinimizerPlugin(),
        ],
    },
    output: {
        filename: isProduction ? "js/[name].[contenthash].js" : "js/[name].js",
        publicPath: params.siteURL + ':' + params.port + params.publicPath,
        clean: true,
        assetModuleFilename: 'img/[hash][ext][query]'
    },
    devServer: configureDevServer(),
    resolve: {
        extensions: [
            '.js',
            '.jsx',
            '.css',
            '.scss',
            '.jpg',
            '.jpeg',
            '.png',
            '.svg',
            '.vue'
        ],
        alias: {
            'icons': path.resolve(__dirname, 'src', 'icons')
        }
    },
    plugins: [
        new webpack.DefinePlugin({
            __VUE_OPTIONS_API__: true,
            __VUE_PROD_DEVTOOLS__: false
        }),

        new MiniCssExtractPlugin({
            filename: isProduction ? "css/[name].[contenthash].css" : "css/[name].css",
            chunkFilename: isProduction ? "css/[id].[contenthash].css" : "css/[id].css",
        }),

        new WebpackAssetsManifest({
            output: 'assets.json',
            space: 2,
            writeToDisk: true,
            assets: {},
        }),

        new SpriteLoaderPlugin(),

        new CopyPlugin({
            patterns: [
                {from: path.resolve(__dirname, "src", "img"), to: 'img'}
            ],
        }),

        // new VueLoaderPlugin(),

        // Add your plugins here
        // Learn more about plugins from https://webpack.js.org/configuration/plugins/
    ],
    module: {
        rules: [
            {
                test: /\.(js|jsx)$/i,
                loader: "babel-loader",
            },
            {
                test: /\.s[ac]ss$/i,
                use: [stylesHandler(), "css-loader", "postcss-loader", "sass-loader"],
            },
            {
                test: /\.css$/i,
                use: [stylesHandler(), "css-loader", "postcss-loader"],
            },
            {
                test: /\.(png|jpg|gif)$/i,
                type: "asset/resource",
                generator: {
                    filename: 'img/[name][ext][query]'
                }
            },
            {
                test: /\.(eot|ttf|woff|woff2)$/i,
                type: "asset/resource",
                generator: {
                    filename: 'fonts/[name][ext][query]'
                }
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.svg$/,
                use: [
                    {
                        loader: 'svg-sprite-loader',
                        options: {
                            extract: true,
                            outputPath: '/',
                            publicPath: process.env.ASSETS_DIR
                        }
                    },
                    'svg-transform-loader',
                    'svgo-loader',
                ]
            }
        ],
    },
};

module.exports = () => {
    if (isProduction) {
        config.mode = "production";
        config.entry = {
            app: './src/_main/_index.js',
            critical: './src/_critical/_index.js',
        }

    } else {
        config.mode = "development";
        config.entry = {dev: './src/dev.js'}

        config.plugins.push(new webpack.HotModuleReplacementPlugin())
    }
    return config;
};

