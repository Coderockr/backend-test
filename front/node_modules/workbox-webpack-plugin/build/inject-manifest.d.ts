import webpack from 'webpack';
import { WebpackInjectManifestOptions } from 'workbox-build';
/**
 * This class supports compiling a service worker file provided via `swSrc`,
 * and injecting into that service worker a list of URLs and revision
 * information for precaching based on the webpack asset pipeline.
 *
 * Use an instance of `InjectManifest` in the
 * [`plugins` array](https://webpack.js.org/concepts/plugins/#usage) of a
 * webpack config.
 *
 * @memberof module:workbox-webpack-plugin
 */
declare class InjectManifest {
    private config;
    private alreadyCalled;
    /**
     * Creates an instance of InjectManifest.
     *
     * @param {Object} config The configuration to use.
     *
     * @param {string} config.swSrc An existing service worker file that will be
     * compiled and have a precache manifest injected into it.
     *
     * @param {Array<module:workbox-build.ManifestEntry>} [config.additionalManifestEntries]
     * A list of entries to be precached, in addition to any entries that are
     * generated as part of the build configuration.
     *
     * @param {Array<string>} [config.chunks] One or more chunk names whose corresponding
     * output files should be included in the precache manifest.
     *
     * @param {boolean} [config.compileSrc=true] When `true` (the default), the
     * `swSrc` file will be compiled by webpack. When `false`, compilation will
     * not occur (and `webpackCompilationPlugins` can't be used.) Set to `false`
     * if you want to inject the manifest into, e.g., a JSON file.
     *
     * @param {RegExp} [config.dontCacheBustURLsMatching] Assets that match this will be
     * assumed to be uniquely versioned via their URL, and exempted from the normal
     * HTTP cache-busting that's done when populating the precache. (As of Workbox
     * v6, this option is usually not needed, as each
     * [asset's metadata](https://github.com/webpack/webpack/issues/9038) is used
     * to determine whether it's immutable or not.)
     *
     * @param {Array<string|RegExp|Function>} [config.exclude=[/\.map$/, /^manifest.*\.js$]]
     * One or more specifiers used to exclude assets from the precache manifest.
     * This is interpreted following
     * [the same rules](https://webpack.js.org/configuration/module/#condition)
     * as `webpack`'s standard `exclude` option.
     *
     * @param {Array<string>} [config.excludeChunks] One or more chunk names whose
     * corresponding output files should be excluded from the precache manifest.
     *
     * @param {Array<string|RegExp|Function>} [config.include]
     * One or more specifiers used to include assets in the precache manifest.
     * This is interpreted following
     * [the same rules](https://webpack.js.org/configuration/module/#condition)
     * as `webpack`'s standard `include` option.
     *
     * @param  {string} [config.injectionPoint='self.__WB_MANIFEST'] The string to
     * find inside of the `swSrc` file. Once found, it will be replaced by the
     * generated precache manifest.
     *
     * @param {Array<module:workbox-build.ManifestTransform>} [config.manifestTransforms]
     * One or more functions which will be applied sequentially against the
     * generated manifest. If `modifyURLPrefix` or `dontCacheBustURLsMatching` are
     * also specified, their corresponding transformations will be applied first.
     *
     * @param {number} [config.maximumFileSizeToCacheInBytes=2097152] This value can be
     * used to determine the maximum size of files that will be precached. This
     * prevents you from inadvertently precaching very large files that might have
     * accidentally matched one of your patterns.
     *
     * @param {string} [config.mode] If set to 'production', then an optimized service
     * worker bundle that excludes debugging info will be produced. If not explicitly
     * configured here, the `mode` value configured in the current `webpack`
     * compilation will be used.
     *
     * @param {object<string, string>} [config.modifyURLPrefix] A mapping of prefixes
     * that, if present in an entry in the precache manifest, will be replaced with
     * the corresponding value. This can be used to, for example, remove or add a
     * path prefix from a manifest entry if your web hosting setup doesn't match
     * your local filesystem setup. As an alternative with more flexibility, you can
     * use the `manifestTransforms` option and provide a function that modifies the
     * entries in the manifest using whatever logic you provide.
     *
     * @param {string} [config.swDest] The asset name of the
     * service worker file that will be created by this plugin. If omitted, the
     * name will be based on the `swSrc` name.
     *
     * @param {Array<Object>} [config.webpackCompilationPlugins] Optional `webpack`
     * plugins that will be used when compiling the `swSrc` input file.
     */
    constructor(config: WebpackInjectManifestOptions);
    /**
     * @param {Object} [compiler] default compiler object passed from webpack
     *
     * @private
     */
    propagateWebpackConfig(compiler: webpack.Compiler): void;
    /**
     * @param {Object} [compiler] default compiler object passed from webpack
     *
     * @private
     */
    apply(compiler: webpack.Compiler): void;
    /**
     * @param {Object} compilation The webpack compilation.
     * @param {Object} parentCompiler The webpack parent compiler.
     *
     * @private
     */
    performChildCompilation(compilation: webpack.Compilation, parentCompiler: webpack.Compiler): Promise<void>;
    /**
     * @param {Object} compilation The webpack compilation.
     * @param {Object} parentCompiler The webpack parent compiler.
     *
     * @private
     */
    addSrcToAssets(compilation: webpack.Compilation, parentCompiler: webpack.Compiler): void;
    /**
     * @param {Object} compilation The webpack compilation.
     * @param {Object} parentCompiler The webpack parent compiler.
     *
     * @private
     */
    handleMake(compilation: webpack.Compilation, parentCompiler: webpack.Compiler): Promise<void>;
    /**
     * @param {Object} compilation The webpack compilation.
     *
     * @private
     */
    addAssets(compilation: webpack.Compilation): Promise<void>;
}
export { InjectManifest };
