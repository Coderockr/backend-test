"use strict";
/*
  Copyright 2018 Google LLC

  Use of this source code is governed by an MIT-style
  license that can be found in the LICENSE file or at
  https://opensource.org/licenses/MIT.
*/
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.injectManifest = void 0;
const assert_1 = __importDefault(require("assert"));
const fs_extra_1 = __importDefault(require("fs-extra"));
const fast_json_stable_stringify_1 = __importDefault(require("fast-json-stable-stringify"));
const upath_1 = __importDefault(require("upath"));
const errors_1 = require("./lib/errors");
const escape_regexp_1 = require("./lib/escape-regexp");
const get_file_manifest_entries_1 = require("./lib/get-file-manifest-entries");
const get_source_map_url_1 = require("./lib/get-source-map-url");
const rebase_path_1 = require("./lib/rebase-path");
const replace_and_update_source_map_1 = require("./lib/replace-and-update-source-map");
const translate_url_to_sourcemap_paths_1 = require("./lib/translate-url-to-sourcemap-paths");
const validate_options_1 = require("./lib/validate-options");
// eslint-disable-next-line jsdoc/newline-after-description
/**
 * This method creates a list of URLs to precache, referred to as a "precache
 * manifest", based on the options you provide.
 *
 * The manifest is injected into the `swSrc` file, and the placeholder string
 * `injectionPoint` determines where in the file the manifest should go.
 *
 * The final service worker file, with the manifest injected, is written to
 * disk at `swDest`.
 *
 * @param {Object} config The configuration to use.
 *
 * @param {string} config.globDirectory The local directory you wish to match
 * `globPatterns` against. The path is relative to the current directory.
 *
 * @param {string} config.swDest The path and filename of the service worker file
 * that will be created by the build process, relative to the current working
 * directory. It must end in '.js'.
 *
 * @param {string} config.swSrc The path and filename of the service worker file
 * that will be read during the build process, relative to the current working
 * directory.
 *
 * @param {Array<workbox-build.ManifestEntry>} [config.additionalManifestEntries]
 * A list of entries to be precached, in addition to any entries that are
 * generated as part of the build configuration.
 *
 * @param {RegExp} [config.dontCacheBustURLsMatching] Assets that match this will be
 * assumed to be uniquely versioned via their URL, and exempted from the normal
 * HTTP cache-busting that's done when populating the precache. While not
 * required, it's recommended that if your existing build process already
 * inserts a `[hash]` value into each filename, you provide a RegExp that will
 * detect that, as it will reduce the bandwidth consumed when precaching.
 *
 * @param {boolean} [config.globFollow=true] Determines whether or not symlinks
 * are followed when generating the precache manifest. For more information, see
 * the definition of `follow` in the `glob`
 * [documentation](https://github.com/isaacs/node-glob#options).
 *
 * @param {Array<string>} [config.globIgnores=['node_modules/**']]
 * A set of patterns matching files to always exclude when generating the
 * precache manifest. For more information, see the definition of `ignore` in the `glob`
 * [documentation](https://github.com/isaacs/node-glob#options).
 *
 * @param {Array<string>} [config.globPatterns=['**.{js,css,html}']]
 * Files matching any of these patterns will be included in the precache
 * manifest. For more information, see the
 * [`glob` primer](https://github.com/isaacs/node-glob#glob-primer).
 *
 * @param {boolean} [config.globStrict=true] If true, an error reading a directory when
 * generating a precache manifest will cause the build to fail. If false, the
 * problematic directory will be skipped. For more information, see the
 * definition of `strict` in the `glob`
 * [documentation](https://github.com/isaacs/node-glob#options).
 *
 * @param  {string} [config.injectionPoint='self.__WB_MANIFEST'] The string to
 * find inside of the `swSrc` file. Once found, it will be replaced by the
 * generated precache manifest.
 *
 * @param {Array<workbox-build.ManifestTransform>} [config.manifestTransforms] One or more
 * functions which will be applied sequentially against the generated manifest.
 * If `modifyURLPrefix` or `dontCacheBustURLsMatching` are also specified, their
 * corresponding transformations will be applied first.
 *
 * @param {number} [config.maximumFileSizeToCacheInBytes=2097152] This value can be
 * used to determine the maximum size of files that will be precached. This
 * prevents you from inadvertently precaching very large files that might have
 * accidentally matched one of your patterns.
 *
 * @param {object<string, string>} [config.modifyURLPrefix] A mapping of prefixes
 * that, if present in an entry in the precache manifest, will be replaced with
 * the corresponding value. This can be used to, for example, remove or add a
 * path prefix from a manifest entry if your web hosting setup doesn't match
 * your local filesystem setup. As an alternative with more flexibility, you can
 * use the `manifestTransforms` option and provide a function that modifies the
 * entries in the manifest using whatever logic you provide.
 *
 * @param {Object} [config.templatedURLs] If a URL is rendered based on some
 * server-side logic, its contents may depend on multiple files or on some other
 * unique string value. The keys in this object are server-rendered URLs. If the
 * values are an array of strings, they will be interpreted as `glob` patterns,
 * and the contents of any files matching the patterns will be used to uniquely
 * version the URL. If used with a single string, it will be interpreted as
 * unique versioning information that you've generated for a given URL.
 *
 * @return {Promise<{count: number, filePaths: Array<string>, size: number, warnings: Array<string>}>}
 * A promise that resolves once the service worker and related files
 * (indicated by `filePaths`) has been written to `swDest`. The `size` property
 * contains the aggregate size of all the precached entries, in bytes, and the
 * `count` property contains the total number of precached entries. Any
 * non-fatal warning messages will be returned via `warnings`.
 *
 * @memberof workbox-build
 */
async function injectManifest(config) {
    const options = (0, validate_options_1.validateInjectManifestOptions)(config);
    // Make sure we leave swSrc and swDest out of the precache manifest.
    for (const file of [options.swSrc, options.swDest]) {
        options.globIgnores.push((0, rebase_path_1.rebasePath)({
            file,
            baseDirectory: options.globDirectory,
        }));
    }
    const globalRegexp = new RegExp((0, escape_regexp_1.escapeRegExp)(options.injectionPoint), 'g');
    const { count, size, manifestEntries, warnings } = await (0, get_file_manifest_entries_1.getFileManifestEntries)(options);
    let swFileContents;
    try {
        swFileContents = await fs_extra_1.default.readFile(options.swSrc, 'utf8');
    }
    catch (error) {
        throw new Error(`${errors_1.errors['invalid-sw-src']} ${error instanceof Error && error.message ? error.message : ''}`);
    }
    const injectionResults = swFileContents.match(globalRegexp);
    // See https://github.com/GoogleChrome/workbox/issues/2230
    const injectionPoint = options.injectionPoint ? options.injectionPoint : '';
    if (!injectionResults) {
        if (upath_1.default.resolve(options.swSrc) === upath_1.default.resolve(options.swDest)) {
            throw new Error(`${errors_1.errors['same-src-and-dest']} ${injectionPoint}`);
        }
        throw new Error(`${errors_1.errors['injection-point-not-found']} ${injectionPoint}`);
    }
    (0, assert_1.default)(injectionResults.length === 1, `${errors_1.errors['multiple-injection-points']} ${injectionPoint}`);
    const manifestString = (0, fast_json_stable_stringify_1.default)(manifestEntries);
    const filesToWrite = {};
    const url = (0, get_source_map_url_1.getSourceMapURL)(swFileContents);
    // See https://github.com/GoogleChrome/workbox/issues/2957
    const { destPath, srcPath, warning } = (0, translate_url_to_sourcemap_paths_1.translateURLToSourcemapPaths)(url, options.swSrc, options.swDest);
    if (warning) {
        warnings.push(warning);
    }
    // If our swSrc file contains a sourcemap, we would invalidate that
    // mapping if we just replaced injectionPoint with the stringified manifest.
    // Instead, we need to update the swDest contents as well as the sourcemap
    // (assuming it's a real file, not a data: URL) at the same time.
    // See https://github.com/GoogleChrome/workbox/issues/2235
    // and https://github.com/GoogleChrome/workbox/issues/2648
    if (srcPath && destPath) {
        const originalMap = (await fs_extra_1.default.readJSON(srcPath, {
            encoding: 'utf8',
        }));
        const { map, source } = await (0, replace_and_update_source_map_1.replaceAndUpdateSourceMap)({
            originalMap,
            jsFilename: upath_1.default.basename(options.swDest),
            originalSource: swFileContents,
            replaceString: manifestString,
            searchString: options.injectionPoint,
        });
        filesToWrite[options.swDest] = source;
        filesToWrite[destPath] = map;
    }
    else {
        // If there's no sourcemap associated with swSrc, a simple string
        // replacement will suffice.
        filesToWrite[options.swDest] = swFileContents.replace(globalRegexp, manifestString);
    }
    for (const [file, contents] of Object.entries(filesToWrite)) {
        try {
            await fs_extra_1.default.mkdirp(upath_1.default.dirname(file));
        }
        catch (error) {
            throw new Error(errors_1.errors['unable-to-make-sw-directory'] +
                ` '${error instanceof Error && error.message ? error.message : ''}'`);
        }
        await fs_extra_1.default.writeFile(file, contents);
    }
    return {
        count,
        size,
        warnings,
        // Use upath.resolve() to make all the paths absolute.
        filePaths: Object.keys(filesToWrite).map((f) => upath_1.default.resolve(f)),
    };
}
exports.injectManifest = injectManifest;
