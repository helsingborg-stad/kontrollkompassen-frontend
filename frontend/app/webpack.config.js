require('dotenv').config();

const path = require('path');

const webpack = require('webpack');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const WebpackNotifierPlugin = require('webpack-notifier');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const RemoveEmptyScripts = require('webpack-remove-empty-scripts');
const CssMinimizerWebpackPlugin = require('css-minimizer-webpack-plugin');
const autoprefixer = require('autoprefixer');

const { getIfUtils, removeEmpty } = require('webpack-config-utils');

const { ifProduction, ifNotProduction } = getIfUtils(process.env.NODE_ENV);

module.exports = {
    mode: ifProduction('production', 'development'),
    /**
     * Add your entry files here
     */
    entry: {
        'css/styleguide': './source/sass/styleguide.scss',
        'css/custom': './source/sass/custom.scss',
        'js/kokop': './source/js/app.js'
    },
    /**
     * Output settings  
     */
    output: {
        filename: ifProduction('[name].[contenthash].js', '[name].js'),
        path: path.resolve(__dirname, 'assets', 'dist'),
        publicPath: '',
    },
    /**
     * Define external dependencies here
     */
    externals: {
        jquery: 'jQuery',
        tinymce: 'tinymce'
    },
    module: {
        rules: [
            /**
             * Scripts
             */
            {
                test: /\.js$/,
                exclude: /(node_modules)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        // Babel config goes here
                        presets: ['@babel/preset-env'],
                        plugins: [
                            '@babel/plugin-syntax-dynamic-import',
                            '@babel/plugin-proposal-export-default-from',
                            '@babel/plugin-proposal-class-properties',
                        ],
                    }
                }
            },
            /**
             * TypeScript
             */
            {
                test: /\.ts?$/,
                loader: 'ts-loader',
                options: { allowTsInNodeModules: true }
            },
            /**
             * Styles
             */
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {}
                    },
                    {
                        loader: 'css-loader',
                        options: {
                            importLoaders: 2
                        },
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: {
                                plugins: [autoprefixer],
                            }
                        },
                    },
                    {
                        loader: 'sass-loader',
                        options: {}
                    }
                ],
            },

            /**
             * Images
             */
            {
                test: /\.(png|svg|jpg|gif)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: ifProduction('[name].[contenthash:8].[ext]', '[name].[ext]'),
                            outputPath: 'images',
                            publicPath: '../images',
                        },
                    },
                ],
            },

            /**
             * Fonts
             */
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: ifProduction('[name].[contenthash:8].[ext]', '[name].[ext]'),
                            outputPath: 'fonts',
                            publicPath: '../fonts',
                        },
                    },
                ],
            }
        ],
    },
    resolve: {
        extensions: ['.tsx', '.ts', '.js'],
    },
    plugins: removeEmpty([
        /**
         * Fix CSS entry chunks generating js file
         */
        new RemoveEmptyScripts(),

        /**
         * Clean dist folder
         */
        new CleanWebpackPlugin(),

        /**
         * Output CSS files
         */
        new MiniCssExtractPlugin({
            filename: ifProduction('[name].[contenthash:8].css', '[name].css')
        }),

        /**
         * Output manifest.json for cache busting
         */
        new WebpackManifestPlugin({
            // Filter manifest items
            filter(file) {
                // Don't include source maps
                if (file.path.match(/\.(map)$/)) {
                    return false;
                }
                return true;
            },
            // Custom mapping of manifest item goes here
            map(file) {
                // Fix incorrect key for fonts
                if (
                    file.isAsset &&
                    file.isModuleAsset &&
                    file.path.match(/\.(woff|woff2|eot|ttf|otf)$/)
                ) {
                    const pathParts = file.path.split('.');
                    const nameParts = file.name.split('.');

                    // Compare extensions
                    if (pathParts[pathParts.length - 1] !== nameParts[nameParts.length - 1]) {
                        file.name = pathParts[0].concat('.', pathParts[pathParts.length - 1]);
                    }
                }
                return file;
            },
        }),
        new webpack.ProvidePlugin({
            process: 'process/browser',
        }),
        /**
         * Enable build OS notifications (when using watch command)
         */
        new WebpackNotifierPlugin({alwaysNotify: true, skipFirstNotification: true}),

        /**
         * Minimize CSS assets
         */
        ifProduction(new CssMinimizerWebpackPlugin({
            minimizerOptions: {
                preset: [
                    "default",
                    {
                        discardComments: { removeAll: true },
                    },
                ],
            },
        }))
    ]).filter(Boolean),
    devtool: 'source-map',
    stats: { children: false, loggingDebug: ifNotProduction(['sass-loader'], []), }
};