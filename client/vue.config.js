module.exports = {
    outputDir: './dist/',
    indexPath: '../dist/index.html',
    assetsDir: './assets/',
    lintOnSave: process.env.NODE_ENV !== 'production',
    devServer: {
        disableHostCheck: true,
        overlay: {
            warnings: false,
            errors: true
        }
    },
    runtimeCompiler: true,
    configureWebpack: {
        performance: {
            hints: process.env.NODE_ENV !== 'production' ? "warning" : false,
            maxEntrypointSize: 1024*15000,
            maxAssetSize: 1024*15000
        },
        module: {
            rules: [
                {
                    test: /\.less$/,
                    use: [
                        {
                            loader: "less-loader",
                            options: {
                                javascriptEnabled: true
                            }
                        },
                        {
                            loader: "js-to-styles-var-loader"
                        }
                    ]
                }
            ],
          },
    },
};
