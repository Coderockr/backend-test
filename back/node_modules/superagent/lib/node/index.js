"use strict";

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

/**
 * Module dependencies.
 */
// eslint-disable-next-line node/no-deprecated-api
var _require = require('url'),
    parse = _require.parse,
    format = _require.format,
    resolve = _require.resolve;

var Stream = require('stream');

var https = require('https');

var http = require('http');

var fs = require('fs');

var zlib = require('zlib');

var util = require('util');

var qs = require('qs');

var mime = require('mime');

var methods = require('methods');

var FormData = require('form-data');

var formidable = require('formidable');

var debug = require('debug')('superagent');

var CookieJar = require('cookiejar');

var semverGte = require('semver/functions/gte');

var safeStringify = require('fast-safe-stringify');

var utils = require('../utils');

var RequestBase = require('../request-base');

var _require2 = require('./unzip'),
    unzip = _require2.unzip;

var Response = require('./response');

var mixin = utils.mixin,
    hasOwn = utils.hasOwn;
var http2;
if (semverGte(process.version, 'v10.10.0')) http2 = require('./http2wrapper');

function request(method, url) {
  // callback
  if (typeof url === 'function') {
    return new exports.Request('GET', method).end(url);
  } // url first


  if (arguments.length === 1) {
    return new exports.Request('GET', method);
  }

  return new exports.Request(method, url);
}

module.exports = request;
exports = module.exports;
/**
 * Expose `Request`.
 */

exports.Request = Request;
/**
 * Expose the agent function
 */

exports.agent = require('./agent');
/**
 * Noop.
 */

function noop() {}
/**
 * Expose `Response`.
 */


exports.Response = Response;
/**
 * Define "form" mime type.
 */

mime.define({
  'application/x-www-form-urlencoded': ['form', 'urlencoded', 'form-data']
}, true);
/**
 * Protocol map.
 */

exports.protocols = {
  'http:': http,
  'https:': https,
  'http2:': http2
};
/**
 * Default serialization map.
 *
 *     superagent.serialize['application/xml'] = function(obj){
 *       return 'generated xml here';
 *     };
 *
 */

exports.serialize = {
  'application/x-www-form-urlencoded': qs.stringify,
  'application/json': safeStringify
};
/**
 * Default parsers.
 *
 *     superagent.parse['application/xml'] = function(res, fn){
 *       fn(null, res);
 *     };
 *
 */

exports.parse = require('./parsers');
/**
 * Default buffering map. Can be used to set certain
 * response types to buffer/not buffer.
 *
 *     superagent.buffer['application/xml'] = true;
 */

exports.buffer = {};
/**
 * Initialize internal header tracking properties on a request instance.
 *
 * @param {Object} req the instance
 * @api private
 */

function _initHeaders(request_) {
  request_._header = {// coerces header names to lowercase
  };
  request_.header = {// preserves header name case
  };
}
/**
 * Initialize a new `Request` with the given `method` and `url`.
 *
 * @param {String} method
 * @param {String|Object} url
 * @api public
 */


function Request(method, url) {
  Stream.call(this);
  if (typeof url !== 'string') url = format(url);
  this._enableHttp2 = Boolean(process.env.HTTP2_TEST); // internal only

  this._agent = false;
  this._formData = null;
  this.method = method;
  this.url = url;

  _initHeaders(this);

  this.writable = true;
  this._redirects = 0;
  this.redirects(method === 'HEAD' ? 0 : 5);
  this.cookies = '';
  this.qs = {};
  this._query = [];
  this.qsRaw = this._query; // Unused, for backwards compatibility only

  this._redirectList = [];
  this._streamRequest = false;
  this.once('end', this.clearTimeout.bind(this));
}
/**
 * Inherit from `Stream` (which inherits from `EventEmitter`).
 * Mixin `RequestBase`.
 */


util.inherits(Request, Stream);
mixin(Request.prototype, RequestBase.prototype);
/**
 * Enable or Disable http2.
 *
 * Enable http2.
 *
 * ``` js
 * request.get('http://localhost/')
 *   .http2()
 *   .end(callback);
 *
 * request.get('http://localhost/')
 *   .http2(true)
 *   .end(callback);
 * ```
 *
 * Disable http2.
 *
 * ``` js
 * request = request.http2();
 * request.get('http://localhost/')
 *   .http2(false)
 *   .end(callback);
 * ```
 *
 * @param {Boolean} enable
 * @return {Request} for chaining
 * @api public
 */

Request.prototype.http2 = function (bool) {
  if (exports.protocols['http2:'] === undefined) {
    throw new Error('superagent: this version of Node.js does not support http2');
  }

  this._enableHttp2 = bool === undefined ? true : bool;
  return this;
};
/**
 * Queue the given `file` as an attachment to the specified `field`,
 * with optional `options` (or filename).
 *
 * ``` js
 * request.post('http://localhost/upload')
 *   .attach('field', Buffer.from('<b>Hello world</b>'), 'hello.html')
 *   .end(callback);
 * ```
 *
 * A filename may also be used:
 *
 * ``` js
 * request.post('http://localhost/upload')
 *   .attach('files', 'image.jpg')
 *   .end(callback);
 * ```
 *
 * @param {String} field
 * @param {String|fs.ReadStream|Buffer} file
 * @param {String|Object} options
 * @return {Request} for chaining
 * @api public
 */


Request.prototype.attach = function (field, file, options) {
  var _this = this;

  if (file) {
    if (this._data) {
      throw new Error("superagent can't mix .send() and .attach()");
    }

    var o = options || {};

    if (typeof options === 'string') {
      o = {
        filename: options
      };
    }

    if (typeof file === 'string') {
      if (!o.filename) o.filename = file;
      debug('creating `fs.ReadStream` instance for file: %s', file);
      file = fs.createReadStream(file);
      file.on('error', function (error) {
        var formData = _this._getFormData();

        formData.emit('error', error);
      });
    } else if (!o.filename && file.path) {
      o.filename = file.path;
    }

    this._getFormData().append(field, file, o);
  }

  return this;
};

Request.prototype._getFormData = function () {
  var _this2 = this;

  if (!this._formData) {
    this._formData = new FormData();

    this._formData.on('error', function (error) {
      debug('FormData error', error);

      if (_this2.called) {
        // The request has already finished and the callback was called.
        // Silently ignore the error.
        return;
      }

      _this2.callback(error);

      _this2.abort();
    });
  }

  return this._formData;
};
/**
 * Gets/sets the `Agent` to use for this HTTP request. The default (if this
 * function is not called) is to opt out of connection pooling (`agent: false`).
 *
 * @param {http.Agent} agent
 * @return {http.Agent}
 * @api public
 */


Request.prototype.agent = function (agent) {
  if (arguments.length === 0) return this._agent;
  this._agent = agent;
  return this;
};
/**
 * Set _Content-Type_ response header passed through `mime.getType()`.
 *
 * Examples:
 *
 *      request.post('/')
 *        .type('xml')
 *        .send(xmlstring)
 *        .end(callback);
 *
 *      request.post('/')
 *        .type('json')
 *        .send(jsonstring)
 *        .end(callback);
 *
 *      request.post('/')
 *        .type('application/json')
 *        .send(jsonstring)
 *        .end(callback);
 *
 * @param {String} type
 * @return {Request} for chaining
 * @api public
 */


Request.prototype.type = function (type) {
  return this.set('Content-Type', type.includes('/') ? type : mime.getType(type));
};
/**
 * Set _Accept_ response header passed through `mime.getType()`.
 *
 * Examples:
 *
 *      superagent.types.json = 'application/json';
 *
 *      request.get('/agent')
 *        .accept('json')
 *        .end(callback);
 *
 *      request.get('/agent')
 *        .accept('application/json')
 *        .end(callback);
 *
 * @param {String} accept
 * @return {Request} for chaining
 * @api public
 */


Request.prototype.accept = function (type) {
  return this.set('Accept', type.includes('/') ? type : mime.getType(type));
};
/**
 * Add query-string `val`.
 *
 * Examples:
 *
 *   request.get('/shoes')
 *     .query('size=10')
 *     .query({ color: 'blue' })
 *
 * @param {Object|String} val
 * @return {Request} for chaining
 * @api public
 */


Request.prototype.query = function (value) {
  if (typeof value === 'string') {
    this._query.push(value);
  } else {
    Object.assign(this.qs, value);
  }

  return this;
};
/**
 * Write raw `data` / `encoding` to the socket.
 *
 * @param {Buffer|String} data
 * @param {String} encoding
 * @return {Boolean}
 * @api public
 */


Request.prototype.write = function (data, encoding) {
  var request_ = this.request();

  if (!this._streamRequest) {
    this._streamRequest = true;
  }

  return request_.write(data, encoding);
};
/**
 * Pipe the request body to `stream`.
 *
 * @param {Stream} stream
 * @param {Object} options
 * @return {Stream}
 * @api public
 */


Request.prototype.pipe = function (stream, options) {
  this.piped = true; // HACK...

  this.buffer(false);
  this.end();
  return this._pipeContinue(stream, options);
};

Request.prototype._pipeContinue = function (stream, options) {
  var _this3 = this;

  this.req.once('response', function (res) {
    // redirect
    if (isRedirect(res.statusCode) && _this3._redirects++ !== _this3._maxRedirects) {
      return _this3._redirect(res) === _this3 ? _this3._pipeContinue(stream, options) : undefined;
    }

    _this3.res = res;

    _this3._emitResponse();

    if (_this3._aborted) return;

    if (_this3._shouldUnzip(res)) {
      var unzipObject = zlib.createUnzip();
      unzipObject.on('error', function (error) {
        if (error && error.code === 'Z_BUF_ERROR') {
          // unexpected end of file is ignored by browsers and curl
          stream.emit('end');
          return;
        }

        stream.emit('error', error);
      });
      res.pipe(unzipObject).pipe(stream, options);
    } else {
      res.pipe(stream, options);
    }

    res.once('end', function () {
      _this3.emit('end');
    });
  });
  return stream;
};
/**
 * Enable / disable buffering.
 *
 * @return {Boolean} [val]
 * @return {Request} for chaining
 * @api public
 */


Request.prototype.buffer = function (value) {
  this._buffer = value !== false;
  return this;
};
/**
 * Redirect to `url
 *
 * @param {IncomingMessage} res
 * @return {Request} for chaining
 * @api private
 */


Request.prototype._redirect = function (res) {
  var url = res.headers.location;

  if (!url) {
    return this.callback(new Error('No location header for redirect'), res);
  }

  debug('redirect %s -> %s', this.url, url); // location

  url = resolve(this.url, url); // ensure the response is being consumed
  // this is required for Node v0.10+

  res.resume();
  var headers = this.req.getHeaders ? this.req.getHeaders() : this.req._headers;
  var changesOrigin = parse(url).host !== parse(this.url).host; // implementation of 302 following defacto standard

  if (res.statusCode === 301 || res.statusCode === 302) {
    // strip Content-* related fields
    // in case of POST etc
    headers = utils.cleanHeader(headers, changesOrigin); // force GET

    this.method = this.method === 'HEAD' ? 'HEAD' : 'GET'; // clear data

    this._data = null;
  } // 303 is always GET


  if (res.statusCode === 303) {
    // strip Content-* related fields
    // in case of POST etc
    headers = utils.cleanHeader(headers, changesOrigin); // force method

    this.method = 'GET'; // clear data

    this._data = null;
  } // 307 preserves method
  // 308 preserves method


  delete headers.host;
  delete this.req;
  delete this._formData; // remove all add header except User-Agent

  _initHeaders(this); // redirect


  this._endCalled = false;
  this.url = url;
  this.qs = {};
  this._query.length = 0;
  this.set(headers);
  this.emit('redirect', res);

  this._redirectList.push(this.url);

  this.end(this._callback);
  return this;
};
/**
 * Set Authorization field value with `user` and `pass`.
 *
 * Examples:
 *
 *   .auth('tobi', 'learnboost')
 *   .auth('tobi:learnboost')
 *   .auth('tobi')
 *   .auth(accessToken, { type: 'bearer' })
 *
 * @param {String} user
 * @param {String} [pass]
 * @param {Object} [options] options with authorization type 'basic' or 'bearer' ('basic' is default)
 * @return {Request} for chaining
 * @api public
 */


Request.prototype.auth = function (user, pass, options) {
  if (arguments.length === 1) pass = '';

  if (_typeof(pass) === 'object' && pass !== null) {
    // pass is optional and can be replaced with options
    options = pass;
    pass = '';
  }

  if (!options) {
    options = {
      type: 'basic'
    };
  }

  var encoder = function encoder(string) {
    return Buffer.from(string).toString('base64');
  };

  return this._auth(user, pass, options, encoder);
};
/**
 * Set the certificate authority option for https request.
 *
 * @param {Buffer | Array} cert
 * @return {Request} for chaining
 * @api public
 */


Request.prototype.ca = function (cert) {
  this._ca = cert;
  return this;
};
/**
 * Set the client certificate key option for https request.
 *
 * @param {Buffer | String} cert
 * @return {Request} for chaining
 * @api public
 */


Request.prototype.key = function (cert) {
  this._key = cert;
  return this;
};
/**
 * Set the key, certificate, and CA certs of the client in PFX or PKCS12 format.
 *
 * @param {Buffer | String} cert
 * @return {Request} for chaining
 * @api public
 */


Request.prototype.pfx = function (cert) {
  if (_typeof(cert) === 'object' && !Buffer.isBuffer(cert)) {
    this._pfx = cert.pfx;
    this._passphrase = cert.passphrase;
  } else {
    this._pfx = cert;
  }

  return this;
};
/**
 * Set the client certificate option for https request.
 *
 * @param {Buffer | String} cert
 * @return {Request} for chaining
 * @api public
 */


Request.prototype.cert = function (cert) {
  this._cert = cert;
  return this;
};
/**
 * Do not reject expired or invalid TLS certs.
 * sets `rejectUnauthorized=true`. Be warned that this allows MITM attacks.
 *
 * @return {Request} for chaining
 * @api public
 */


Request.prototype.disableTLSCerts = function () {
  this._disableTLSCerts = true;
  return this;
};
/**
 * Return an http[s] request.
 *
 * @return {OutgoingMessage}
 * @api private
 */
// eslint-disable-next-line complexity


Request.prototype.request = function () {
  var _this4 = this;

  if (this.req) return this.req;
  var options = {};

  try {
    var query = qs.stringify(this.qs, {
      indices: false,
      strictNullHandling: true
    });

    if (query) {
      this.qs = {};

      this._query.push(query);
    }

    this._finalizeQueryString();
  } catch (err) {
    return this.emit('error', err);
  }

  var url = this.url;
  var retries = this._retries; // Capture backticks as-is from the final query string built above.
  // Note: this'll only find backticks entered in req.query(String)
  // calls, because qs.stringify unconditionally encodes backticks.

  var queryStringBackticks;

  if (url.includes('`')) {
    var queryStartIndex = url.indexOf('?');

    if (queryStartIndex !== -1) {
      var queryString = url.slice(queryStartIndex + 1);
      queryStringBackticks = queryString.match(/`|%60/g);
    }
  } // default to http://


  if (url.indexOf('http') !== 0) url = "http://".concat(url);
  url = parse(url); // See https://github.com/visionmedia/superagent/issues/1367

  if (queryStringBackticks) {
    var i = 0;
    url.query = url.query.replace(/%60/g, function () {
      return queryStringBackticks[i++];
    });
    url.search = "?".concat(url.query);
    url.path = url.pathname + url.search;
  } // support unix sockets


  if (/^https?\+unix:/.test(url.protocol) === true) {
    // get the protocol
    url.protocol = "".concat(url.protocol.split('+')[0], ":"); // get the socket, path

    var unixParts = url.path.match(/^([^/]+)(.+)$/);
    options.socketPath = unixParts[1].replace(/%2F/g, '/');
    url.path = unixParts[2];
  } // Override IP address of a hostname


  if (this._connectOverride) {
    var _url = url,
        hostname = _url.hostname;
    var match = hostname in this._connectOverride ? this._connectOverride[hostname] : this._connectOverride['*'];

    if (match) {
      // backup the real host
      if (!this._header.host) {
        this.set('host', url.host);
      }

      var newHost;
      var newPort;

      if (_typeof(match) === 'object') {
        newHost = match.host;
        newPort = match.port;
      } else {
        newHost = match;
        newPort = url.port;
      } // wrap [ipv6]


      url.host = /:/.test(newHost) ? "[".concat(newHost, "]") : newHost;

      if (newPort) {
        url.host += ":".concat(newPort);
        url.port = newPort;
      }

      url.hostname = newHost;
    }
  } // options


  options.method = this.method;
  options.port = url.port;
  options.path = url.path;
  options.host = url.hostname;
  options.ca = this._ca;
  options.key = this._key;
  options.pfx = this._pfx;
  options.cert = this._cert;
  options.passphrase = this._passphrase;
  options.agent = this._agent;
  options.rejectUnauthorized = typeof this._disableTLSCerts === 'boolean' ? !this._disableTLSCerts : process.env.NODE_TLS_REJECT_UNAUTHORIZED !== '0'; // Allows request.get('https://1.2.3.4/').set('Host', 'example.com')

  if (this._header.host) {
    options.servername = this._header.host.replace(/:\d+$/, '');
  }

  if (this._trustLocalhost && /^(?:localhost|127\.0\.0\.\d+|(0*:)+:0*1)$/.test(url.hostname)) {
    options.rejectUnauthorized = false;
  } // initiate request


  var mod = this._enableHttp2 ? exports.protocols['http2:'].setProtocol(url.protocol) : exports.protocols[url.protocol]; // request

  this.req = mod.request(options);
  var req = this.req; // set tcp no delay

  req.setNoDelay(true);

  if (options.method !== 'HEAD') {
    req.setHeader('Accept-Encoding', 'gzip, deflate');
  }

  this.protocol = url.protocol;
  this.host = url.host; // expose events

  req.once('drain', function () {
    _this4.emit('drain');
  });
  req.on('error', function (error) {
    // flag abortion here for out timeouts
    // because node will emit a faux-error "socket hang up"
    // when request is aborted before a connection is made
    if (_this4._aborted) return; // if not the same, we are in the **old** (cancelled) request,
    // so need to continue (same as for above)

    if (_this4._retries !== retries) return; // if we've received a response then we don't want to let
    // an error in the request blow up the response

    if (_this4.response) return;

    _this4.callback(error);
  }); // auth

  if (url.auth) {
    var auth = url.auth.split(':');
    this.auth(auth[0], auth[1]);
  }

  if (this.username && this.password) {
    this.auth(this.username, this.password);
  }

  for (var key in this.header) {
    if (hasOwn(this.header, key)) req.setHeader(key, this.header[key]);
  } // add cookies


  if (this.cookies) {
    if (hasOwn(this._header, 'cookie')) {
      // merge
      var temporaryJar = new CookieJar.CookieJar();
      temporaryJar.setCookies(this._header.cookie.split(';'));
      temporaryJar.setCookies(this.cookies.split(';'));
      req.setHeader('Cookie', temporaryJar.getCookies(CookieJar.CookieAccessInfo.All).toValueString());
    } else {
      req.setHeader('Cookie', this.cookies);
    }
  }

  return req;
};
/**
 * Invoke the callback with `err` and `res`
 * and handle arity check.
 *
 * @param {Error} err
 * @param {Response} res
 * @api private
 */


Request.prototype.callback = function (error, res) {
  if (this._shouldRetry(error, res)) {
    return this._retry();
  } // Avoid the error which is emitted from 'socket hang up' to cause the fn undefined error on JS runtime.


  var fn = this._callback || noop;
  this.clearTimeout();
  if (this.called) return console.warn('superagent: double callback bug');
  this.called = true;

  if (!error) {
    try {
      if (!this._isResponseOK(res)) {
        var message = 'Unsuccessful HTTP response';

        if (res) {
          message = http.STATUS_CODES[res.status] || message;
        }

        error = new Error(message);
        error.status = res ? res.status : undefined;
      }
    } catch (error_) {
      error = error_;
    }
  } // It's important that the callback is called outside try/catch
  // to avoid double callback


  if (!error) {
    return fn(null, res);
  }

  error.response = res;
  if (this._maxRetries) error.retries = this._retries - 1; // only emit error event if there is a listener
  // otherwise we assume the callback to `.end()` will get the error

  if (error && this.listeners('error').length > 0) {
    this.emit('error', error);
  }

  fn(error, res);
};
/**
 * Check if `obj` is a host object,
 *
 * @param {Object} obj host object
 * @return {Boolean} is a host object
 * @api private
 */


Request.prototype._isHost = function (object) {
  return Buffer.isBuffer(object) || object instanceof Stream || object instanceof FormData;
};
/**
 * Initiate request, invoking callback `fn(err, res)`
 * with an instanceof `Response`.
 *
 * @param {Function} fn
 * @return {Request} for chaining
 * @api public
 */


Request.prototype._emitResponse = function (body, files) {
  var response = new Response(this);
  this.response = response;
  response.redirects = this._redirectList;

  if (undefined !== body) {
    response.body = body;
  }

  response.files = files;

  if (this._endCalled) {
    response.pipe = function () {
      throw new Error("end() has already been called, so it's too late to start piping");
    };
  }

  this.emit('response', response);
  return response;
};

Request.prototype.end = function (fn) {
  this.request();
  debug('%s %s', this.method, this.url);

  if (this._endCalled) {
    throw new Error('.end() was called twice. This is not supported in superagent');
  }

  this._endCalled = true; // store callback

  this._callback = fn || noop;

  this._end();
};

Request.prototype._end = function () {
  var _this5 = this;

  if (this._aborted) return this.callback(new Error('The request has been aborted even before .end() was called'));
  var data = this._data;
  var req = this.req;
  var method = this.method;

  this._setTimeouts(); // body


  if (method !== 'HEAD' && !req._headerSent) {
    // serialize stuff
    if (typeof data !== 'string') {
      var contentType = req.getHeader('Content-Type'); // Parse out just the content type from the header (ignore the charset)

      if (contentType) contentType = contentType.split(';')[0];
      var serialize = this._serializer || exports.serialize[contentType];

      if (!serialize && isJSON(contentType)) {
        serialize = exports.serialize['application/json'];
      }

      if (serialize) data = serialize(data);
    } // content-length


    if (data && !req.getHeader('Content-Length')) {
      req.setHeader('Content-Length', Buffer.isBuffer(data) ? data.length : Buffer.byteLength(data));
    }
  } // response
  // eslint-disable-next-line complexity


  req.once('response', function (res) {
    debug('%s %s -> %s', _this5.method, _this5.url, res.statusCode);

    if (_this5._responseTimeoutTimer) {
      clearTimeout(_this5._responseTimeoutTimer);
    }

    if (_this5.piped) {
      return;
    }

    var max = _this5._maxRedirects;
    var mime = utils.type(res.headers['content-type'] || '') || 'text/plain';
    var type = mime.split('/')[0];
    if (type) type = type.toLowerCase().trim();
    var multipart = type === 'multipart';
    var redirect = isRedirect(res.statusCode);
    var responseType = _this5._responseType;
    _this5.res = res; // redirect

    if (redirect && _this5._redirects++ !== max) {
      return _this5._redirect(res);
    }

    if (_this5.method === 'HEAD') {
      _this5.emit('end');

      _this5.callback(null, _this5._emitResponse());

      return;
    } // zlib support


    if (_this5._shouldUnzip(res)) {
      unzip(req, res);
    }

    var buffer = _this5._buffer;

    if (buffer === undefined && mime in exports.buffer) {
      buffer = Boolean(exports.buffer[mime]);
    }

    var parser = _this5._parser;

    if (undefined === buffer && parser) {
      console.warn("A custom superagent parser has been set, but buffering strategy for the parser hasn't been configured. Call `req.buffer(true or false)` or set `superagent.buffer[mime] = true or false`");
      buffer = true;
    }

    if (!parser) {
      if (responseType) {
        parser = exports.parse.image; // It's actually a generic Buffer

        buffer = true;
      } else if (multipart) {
        var form = formidable();
        parser = form.parse.bind(form);
        buffer = true;
      } else if (isImageOrVideo(mime)) {
        parser = exports.parse.image;
        buffer = true; // For backwards-compatibility buffering default is ad-hoc MIME-dependent
      } else if (exports.parse[mime]) {
        parser = exports.parse[mime];
      } else if (type === 'text') {
        parser = exports.parse.text;
        buffer = buffer !== false; // everyone wants their own white-labeled json
      } else if (isJSON(mime)) {
        parser = exports.parse['application/json'];
        buffer = buffer !== false;
      } else if (buffer) {
        parser = exports.parse.text;
      } else if (undefined === buffer) {
        parser = exports.parse.image; // It's actually a generic Buffer

        buffer = true;
      }
    } // by default only buffer text/*, json and messed up thing from hell


    if (undefined === buffer && isText(mime) || isJSON(mime)) {
      buffer = true;
    }

    _this5._resBuffered = buffer;
    var parserHandlesEnd = false;

    if (buffer) {
      // Protectiona against zip bombs and other nuisance
      var responseBytesLeft = _this5._maxResponseSize || 200000000;
      res.on('data', function (buf) {
        responseBytesLeft -= buf.byteLength || buf.length > 0 ? buf.length : 0;

        if (responseBytesLeft < 0) {
          // This will propagate through error event
          var error = new Error('Maximum response size reached');
          error.code = 'ETOOLARGE'; // Parsers aren't required to observe error event,
          // so would incorrectly report success

          parserHandlesEnd = false; // Will not emit error event

          res.destroy(error); // so we do callback now

          _this5.callback(error, null);
        }
      });
    }

    if (parser) {
      try {
        // Unbuffered parsers are supposed to emit response early,
        // which is weird BTW, because response.body won't be there.
        parserHandlesEnd = buffer;
        parser(res, function (error, object, files) {
          if (_this5.timedout) {
            // Timeout has already handled all callbacks
            return;
          } // Intentional (non-timeout) abort is supposed to preserve partial response,
          // even if it doesn't parse.


          if (error && !_this5._aborted) {
            return _this5.callback(error);
          }

          if (parserHandlesEnd) {
            _this5.emit('end');

            _this5.callback(null, _this5._emitResponse(object, files));
          }
        });
      } catch (err) {
        _this5.callback(err);

        return;
      }
    }

    _this5.res = res; // unbuffered

    if (!buffer) {
      debug('unbuffered %s %s', _this5.method, _this5.url);

      _this5.callback(null, _this5._emitResponse());

      if (multipart) return; // allow multipart to handle end event

      res.once('end', function () {
        debug('end %s %s', _this5.method, _this5.url);

        _this5.emit('end');
      });
      return;
    } // terminating events


    res.once('error', function (error) {
      parserHandlesEnd = false;

      _this5.callback(error, null);
    });
    if (!parserHandlesEnd) res.once('end', function () {
      debug('end %s %s', _this5.method, _this5.url); // TODO: unless buffering emit earlier to stream

      _this5.emit('end');

      _this5.callback(null, _this5._emitResponse());
    });
  });
  this.emit('request', this);

  var getProgressMonitor = function getProgressMonitor() {
    var lengthComputable = true;
    var total = req.getHeader('Content-Length');
    var loaded = 0;
    var progress = new Stream.Transform();

    progress._transform = function (chunk, encoding, cb) {
      loaded += chunk.length;

      _this5.emit('progress', {
        direction: 'upload',
        lengthComputable: lengthComputable,
        loaded: loaded,
        total: total
      });

      cb(null, chunk);
    };

    return progress;
  };

  var bufferToChunks = function bufferToChunks(buffer) {
    var chunkSize = 16 * 1024; // default highWaterMark value

    var chunking = new Stream.Readable();
    var totalLength = buffer.length;
    var remainder = totalLength % chunkSize;
    var cutoff = totalLength - remainder;

    for (var i = 0; i < cutoff; i += chunkSize) {
      var chunk = buffer.slice(i, i + chunkSize);
      chunking.push(chunk);
    }

    if (remainder > 0) {
      var remainderBuffer = buffer.slice(-remainder);
      chunking.push(remainderBuffer);
    }

    chunking.push(null); // no more data

    return chunking;
  }; // if a FormData instance got created, then we send that as the request body


  var formData = this._formData;

  if (formData) {
    // set headers
    var headers = formData.getHeaders();

    for (var i in headers) {
      if (hasOwn(headers, i)) {
        debug('setting FormData header: "%s: %s"', i, headers[i]);
        req.setHeader(i, headers[i]);
      }
    } // attempt to get "Content-Length" header


    formData.getLength(function (error, length) {
      // TODO: Add chunked encoding when no length (if err)
      if (error) debug('formData.getLength had error', error, length);
      debug('got FormData Content-Length: %s', length);

      if (typeof length === 'number') {
        req.setHeader('Content-Length', length);
      }

      formData.pipe(getProgressMonitor()).pipe(req);
    });
  } else if (Buffer.isBuffer(data)) {
    bufferToChunks(data).pipe(getProgressMonitor()).pipe(req);
  } else {
    req.end(data);
  }
}; // Check whether response has a non-0-sized gzip-encoded body


Request.prototype._shouldUnzip = function (res) {
  if (res.statusCode === 204 || res.statusCode === 304) {
    // These aren't supposed to have any body
    return false;
  } // header content is a string, and distinction between 0 and no information is crucial


  if (res.headers['content-length'] === '0') {
    // We know that the body is empty (unfortunately, this check does not cover chunked encoding)
    return false;
  } // console.log(res);


  return /^\s*(?:deflate|gzip)\s*$/.test(res.headers['content-encoding']);
};
/**
 * Overrides DNS for selected hostnames. Takes object mapping hostnames to IP addresses.
 *
 * When making a request to a URL with a hostname exactly matching a key in the object,
 * use the given IP address to connect, instead of using DNS to resolve the hostname.
 *
 * A special host `*` matches every hostname (keep redirects in mind!)
 *
 *      request.connect({
 *        'test.example.com': '127.0.0.1',
 *        'ipv6.example.com': '::1',
 *      })
 */


Request.prototype.connect = function (connectOverride) {
  if (typeof connectOverride === 'string') {
    this._connectOverride = {
      '*': connectOverride
    };
  } else if (_typeof(connectOverride) === 'object') {
    this._connectOverride = connectOverride;
  } else {
    this._connectOverride = undefined;
  }

  return this;
};

Request.prototype.trustLocalhost = function (toggle) {
  this._trustLocalhost = toggle === undefined ? true : toggle;
  return this;
}; // generate HTTP verb methods


if (!methods.includes('del')) {
  // create a copy so we don't cause conflicts with
  // other packages using the methods package and
  // npm 3.x
  methods = _toConsumableArray(methods);
  methods.push('del');
}

var _iterator = _createForOfIteratorHelper(methods),
    _step;

try {
  var _loop = function _loop() {
    var method = _step.value;
    var name = method;
    method = method === 'del' ? 'delete' : method;
    method = method.toUpperCase();

    request[name] = function (url, data, fn) {
      var request_ = request(method, url);

      if (typeof data === 'function') {
        fn = data;
        data = null;
      }

      if (data) {
        if (method === 'GET' || method === 'HEAD') {
          request_.query(data);
        } else {
          request_.send(data);
        }
      }

      if (fn) request_.end(fn);
      return request_;
    };
  };

  for (_iterator.s(); !(_step = _iterator.n()).done;) {
    _loop();
  }
  /**
   * Check if `mime` is text and should be buffered.
   *
   * @param {String} mime
   * @return {Boolean}
   * @api public
   */

} catch (err) {
  _iterator.e(err);
} finally {
  _iterator.f();
}

function isText(mime) {
  var parts = mime.split('/');
  var type = parts[0];
  if (type) type = type.toLowerCase().trim();
  var subtype = parts[1];
  if (subtype) subtype = subtype.toLowerCase().trim();
  return type === 'text' || subtype === 'x-www-form-urlencoded';
}

function isImageOrVideo(mime) {
  var type = mime.split('/')[0];
  if (type) type = type.toLowerCase().trim();
  return type === 'image' || type === 'video';
}
/**
 * Check if `mime` is json or has +json structured syntax suffix.
 *
 * @param {String} mime
 * @return {Boolean}
 * @api private
 */


function isJSON(mime) {
  // should match /json or +json
  // but not /json-seq
  return /[/+]json($|[^-\w])/i.test(mime);
}
/**
 * Check if we should follow the redirect `code`.
 *
 * @param {Number} code
 * @return {Boolean}
 * @api private
 */


function isRedirect(code) {
  return [301, 302, 303, 305, 307, 308].includes(code);
}
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIi4uLy4uL3NyYy9ub2RlL2luZGV4LmpzIl0sIm5hbWVzIjpbInJlcXVpcmUiLCJwYXJzZSIsImZvcm1hdCIsInJlc29sdmUiLCJTdHJlYW0iLCJodHRwcyIsImh0dHAiLCJmcyIsInpsaWIiLCJ1dGlsIiwicXMiLCJtaW1lIiwibWV0aG9kcyIsIkZvcm1EYXRhIiwiZm9ybWlkYWJsZSIsImRlYnVnIiwiQ29va2llSmFyIiwic2VtdmVyR3RlIiwic2FmZVN0cmluZ2lmeSIsInV0aWxzIiwiUmVxdWVzdEJhc2UiLCJ1bnppcCIsIlJlc3BvbnNlIiwibWl4aW4iLCJoYXNPd24iLCJodHRwMiIsInByb2Nlc3MiLCJ2ZXJzaW9uIiwicmVxdWVzdCIsIm1ldGhvZCIsInVybCIsImV4cG9ydHMiLCJSZXF1ZXN0IiwiZW5kIiwiYXJndW1lbnRzIiwibGVuZ3RoIiwibW9kdWxlIiwiYWdlbnQiLCJub29wIiwiZGVmaW5lIiwicHJvdG9jb2xzIiwic2VyaWFsaXplIiwic3RyaW5naWZ5IiwiYnVmZmVyIiwiX2luaXRIZWFkZXJzIiwicmVxdWVzdF8iLCJfaGVhZGVyIiwiaGVhZGVyIiwiY2FsbCIsIl9lbmFibGVIdHRwMiIsIkJvb2xlYW4iLCJlbnYiLCJIVFRQMl9URVNUIiwiX2FnZW50IiwiX2Zvcm1EYXRhIiwid3JpdGFibGUiLCJfcmVkaXJlY3RzIiwicmVkaXJlY3RzIiwiY29va2llcyIsIl9xdWVyeSIsInFzUmF3IiwiX3JlZGlyZWN0TGlzdCIsIl9zdHJlYW1SZXF1ZXN0Iiwib25jZSIsImNsZWFyVGltZW91dCIsImJpbmQiLCJpbmhlcml0cyIsInByb3RvdHlwZSIsImJvb2wiLCJ1bmRlZmluZWQiLCJFcnJvciIsImF0dGFjaCIsImZpZWxkIiwiZmlsZSIsIm9wdGlvbnMiLCJfZGF0YSIsIm8iLCJmaWxlbmFtZSIsImNyZWF0ZVJlYWRTdHJlYW0iLCJvbiIsImVycm9yIiwiZm9ybURhdGEiLCJfZ2V0Rm9ybURhdGEiLCJlbWl0IiwicGF0aCIsImFwcGVuZCIsImNhbGxlZCIsImNhbGxiYWNrIiwiYWJvcnQiLCJ0eXBlIiwic2V0IiwiaW5jbHVkZXMiLCJnZXRUeXBlIiwiYWNjZXB0IiwicXVlcnkiLCJ2YWx1ZSIsInB1c2giLCJPYmplY3QiLCJhc3NpZ24iLCJ3cml0ZSIsImRhdGEiLCJlbmNvZGluZyIsInBpcGUiLCJzdHJlYW0iLCJwaXBlZCIsIl9waXBlQ29udGludWUiLCJyZXEiLCJyZXMiLCJpc1JlZGlyZWN0Iiwic3RhdHVzQ29kZSIsIl9tYXhSZWRpcmVjdHMiLCJfcmVkaXJlY3QiLCJfZW1pdFJlc3BvbnNlIiwiX2Fib3J0ZWQiLCJfc2hvdWxkVW56aXAiLCJ1bnppcE9iamVjdCIsImNyZWF0ZVVuemlwIiwiY29kZSIsIl9idWZmZXIiLCJoZWFkZXJzIiwibG9jYXRpb24iLCJyZXN1bWUiLCJnZXRIZWFkZXJzIiwiX2hlYWRlcnMiLCJjaGFuZ2VzT3JpZ2luIiwiaG9zdCIsImNsZWFuSGVhZGVyIiwiX2VuZENhbGxlZCIsIl9jYWxsYmFjayIsImF1dGgiLCJ1c2VyIiwicGFzcyIsImVuY29kZXIiLCJzdHJpbmciLCJCdWZmZXIiLCJmcm9tIiwidG9TdHJpbmciLCJfYXV0aCIsImNhIiwiY2VydCIsIl9jYSIsImtleSIsIl9rZXkiLCJwZngiLCJpc0J1ZmZlciIsIl9wZngiLCJfcGFzc3BocmFzZSIsInBhc3NwaHJhc2UiLCJfY2VydCIsImRpc2FibGVUTFNDZXJ0cyIsIl9kaXNhYmxlVExTQ2VydHMiLCJpbmRpY2VzIiwic3RyaWN0TnVsbEhhbmRsaW5nIiwiX2ZpbmFsaXplUXVlcnlTdHJpbmciLCJlcnIiLCJyZXRyaWVzIiwiX3JldHJpZXMiLCJxdWVyeVN0cmluZ0JhY2t0aWNrcyIsInF1ZXJ5U3RhcnRJbmRleCIsImluZGV4T2YiLCJxdWVyeVN0cmluZyIsInNsaWNlIiwibWF0Y2giLCJpIiwicmVwbGFjZSIsInNlYXJjaCIsInBhdGhuYW1lIiwidGVzdCIsInByb3RvY29sIiwic3BsaXQiLCJ1bml4UGFydHMiLCJzb2NrZXRQYXRoIiwiX2Nvbm5lY3RPdmVycmlkZSIsImhvc3RuYW1lIiwibmV3SG9zdCIsIm5ld1BvcnQiLCJwb3J0IiwicmVqZWN0VW5hdXRob3JpemVkIiwiTk9ERV9UTFNfUkVKRUNUX1VOQVVUSE9SSVpFRCIsInNlcnZlcm5hbWUiLCJfdHJ1c3RMb2NhbGhvc3QiLCJtb2QiLCJzZXRQcm90b2NvbCIsInNldE5vRGVsYXkiLCJzZXRIZWFkZXIiLCJyZXNwb25zZSIsInVzZXJuYW1lIiwicGFzc3dvcmQiLCJ0ZW1wb3JhcnlKYXIiLCJzZXRDb29raWVzIiwiY29va2llIiwiZ2V0Q29va2llcyIsIkNvb2tpZUFjY2Vzc0luZm8iLCJBbGwiLCJ0b1ZhbHVlU3RyaW5nIiwiX3Nob3VsZFJldHJ5IiwiX3JldHJ5IiwiZm4iLCJjb25zb2xlIiwid2FybiIsIl9pc1Jlc3BvbnNlT0siLCJtZXNzYWdlIiwiU1RBVFVTX0NPREVTIiwic3RhdHVzIiwiZXJyb3JfIiwiX21heFJldHJpZXMiLCJsaXN0ZW5lcnMiLCJfaXNIb3N0Iiwib2JqZWN0IiwiYm9keSIsImZpbGVzIiwiX2VuZCIsIl9zZXRUaW1lb3V0cyIsIl9oZWFkZXJTZW50IiwiY29udGVudFR5cGUiLCJnZXRIZWFkZXIiLCJfc2VyaWFsaXplciIsImlzSlNPTiIsImJ5dGVMZW5ndGgiLCJfcmVzcG9uc2VUaW1lb3V0VGltZXIiLCJtYXgiLCJ0b0xvd2VyQ2FzZSIsInRyaW0iLCJtdWx0aXBhcnQiLCJyZWRpcmVjdCIsInJlc3BvbnNlVHlwZSIsIl9yZXNwb25zZVR5cGUiLCJwYXJzZXIiLCJfcGFyc2VyIiwiaW1hZ2UiLCJmb3JtIiwiaXNJbWFnZU9yVmlkZW8iLCJ0ZXh0IiwiaXNUZXh0IiwiX3Jlc0J1ZmZlcmVkIiwicGFyc2VySGFuZGxlc0VuZCIsInJlc3BvbnNlQnl0ZXNMZWZ0IiwiX21heFJlc3BvbnNlU2l6ZSIsImJ1ZiIsImRlc3Ryb3kiLCJ0aW1lZG91dCIsImdldFByb2dyZXNzTW9uaXRvciIsImxlbmd0aENvbXB1dGFibGUiLCJ0b3RhbCIsImxvYWRlZCIsInByb2dyZXNzIiwiVHJhbnNmb3JtIiwiX3RyYW5zZm9ybSIsImNodW5rIiwiY2IiLCJkaXJlY3Rpb24iLCJidWZmZXJUb0NodW5rcyIsImNodW5rU2l6ZSIsImNodW5raW5nIiwiUmVhZGFibGUiLCJ0b3RhbExlbmd0aCIsInJlbWFpbmRlciIsImN1dG9mZiIsInJlbWFpbmRlckJ1ZmZlciIsImdldExlbmd0aCIsImNvbm5lY3QiLCJjb25uZWN0T3ZlcnJpZGUiLCJ0cnVzdExvY2FsaG9zdCIsInRvZ2dsZSIsIm5hbWUiLCJ0b1VwcGVyQ2FzZSIsInNlbmQiLCJwYXJ0cyIsInN1YnR5cGUiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQUFBO0FBQ0E7QUFDQTtBQUVBO0FBQ0EsZUFBbUNBLE9BQU8sQ0FBQyxLQUFELENBQTFDO0FBQUEsSUFBUUMsS0FBUixZQUFRQSxLQUFSO0FBQUEsSUFBZUMsTUFBZixZQUFlQSxNQUFmO0FBQUEsSUFBdUJDLE9BQXZCLFlBQXVCQSxPQUF2Qjs7QUFDQSxJQUFNQyxNQUFNLEdBQUdKLE9BQU8sQ0FBQyxRQUFELENBQXRCOztBQUNBLElBQU1LLEtBQUssR0FBR0wsT0FBTyxDQUFDLE9BQUQsQ0FBckI7O0FBQ0EsSUFBTU0sSUFBSSxHQUFHTixPQUFPLENBQUMsTUFBRCxDQUFwQjs7QUFDQSxJQUFNTyxFQUFFLEdBQUdQLE9BQU8sQ0FBQyxJQUFELENBQWxCOztBQUNBLElBQU1RLElBQUksR0FBR1IsT0FBTyxDQUFDLE1BQUQsQ0FBcEI7O0FBQ0EsSUFBTVMsSUFBSSxHQUFHVCxPQUFPLENBQUMsTUFBRCxDQUFwQjs7QUFDQSxJQUFNVSxFQUFFLEdBQUdWLE9BQU8sQ0FBQyxJQUFELENBQWxCOztBQUNBLElBQU1XLElBQUksR0FBR1gsT0FBTyxDQUFDLE1BQUQsQ0FBcEI7O0FBQ0EsSUFBSVksT0FBTyxHQUFHWixPQUFPLENBQUMsU0FBRCxDQUFyQjs7QUFDQSxJQUFNYSxRQUFRLEdBQUdiLE9BQU8sQ0FBQyxXQUFELENBQXhCOztBQUNBLElBQU1jLFVBQVUs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QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBRUEsU0FBU2dJLE1BQVQsQ0FBZ0I5TSxJQUFoQixFQUFzQjtBQUNwQjtBQUNBO0FBQ0EsU0FBTyxzQkFBc0I0SixJQUF0QixDQUEyQjVKLElBQTNCLENBQVA7QUFDRDtBQUVEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFFQSxTQUFTaUcsVUFBVCxDQUFvQlMsSUFBcEIsRUFBMEI7QUFDeEIsU0FBTyxDQUFDLEdBQUQsRUFBTSxHQUFOLEVBQVcsR0FBWCxFQUFnQixHQUFoQixFQUFxQixHQUFyQixFQUEwQixHQUExQixFQUErQjFCLFFBQS9CLENBQXdDMEIsSUFBeEMsQ0FBUDtBQUNEIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBNb2R1bGUgZGVwZW5kZW5jaWVzLlxuICovXG5cbi8vIGVzbGludC1kaXNhYmxlLW5leHQtbGluZSBub2RlL25vLWRlcHJlY2F0ZWQtYXBpXG5jb25zdCB7IHBhcnNlLCBmb3JtYXQsIHJlc29sdmUgfSA9IHJlcXVpcmUoJ3VybCcpO1xuY29uc3QgU3RyZWFtID0gcmVxdWlyZSgnc3RyZWFtJyk7XG5jb25zdCBodHRwcyA9IHJlcXVpcmUoJ2h0dHBzJyk7XG5jb25zdCBodHRwID0gcmVxdWlyZSgnaHR0cCcpO1xuY29uc3QgZnMgPSByZXF1aXJlKCdmcycpO1xuY29uc3QgemxpYiA9IHJlcXVpcmUoJ3psaWInKTtcbmNvbnN0IHV0aWwgPSByZXF1aXJlKCd1dGlsJyk7XG5jb25zdCBxcyA9IHJlcXVpcmUoJ3FzJyk7XG5jb25zdCBtaW1lID0gcmVxdWlyZSgnbWltZScpO1xubGV0IG1ldGhvZHMgPSByZXF1aXJlKCdtZXRob2RzJyk7XG5jb25zdCBGb3JtRGF0YSA9IHJlcXVpcmUoJ2Zvcm0tZGF0YScpO1xuY29uc3QgZm9ybWlkYWJsZSA9IHJlcXVpcmUoJ2Zvcm1pZGFibGUnKTtcbmNvbnN0IGRlYnVnID0gcmVxdWlyZSgnZGVidWcnKSgnc3VwZXJhZ2VudCcpO1xuY29uc3QgQ29va2llSmFyID0gcmVxdWlyZSgnY29va2llamFyJyk7XG5jb25zdCBzZW12ZXJHdGUgPSByZXF1aXJlKCdzZW12ZXIvZnVuY3Rpb25zL2d0ZScpO1xuY29uc3Qgc2FmZVN0cmluZ2lmeSA9IHJlcXVpcmUoJ2Zhc3Qtc2FmZS1zdHJpbmdpZnknKTtcblxuY29uc3QgdXRpbHMgPSByZXF1aXJlKCcuLi91dGlscycpO1xuY29uc3QgUmVxdWVzdEJhc2UgPSByZXF1aXJlKCcuLi9yZXF1ZXN0LWJhc2UnKTtcbmNvbnN0IHsgdW56aXAgfSA9IHJlcXVpcmUoJy4vdW56aXAnKTtcbmNvbnN0IFJlc3BvbnNlID0gcmVxdWlyZSgnLi9yZXNwb25zZScpO1xuXG5jb25zdCB7IG1peGluLCBoYXNPd24gfSA9IHV0aWxzO1xuXG5sZXQgaHR0cDI7XG5cbmlmIChzZW12ZXJHdGUocHJvY2Vzcy52ZXJzaW9uLCAndjEwLjEwLjAnKSkgaHR0cDIgPSByZXF1aXJlKCcuL2h0dHAyd3JhcHBlcicpO1xuXG5mdW5jdGlvbiByZXF1ZXN0KG1ldGhvZCwgdXJsKSB7XG4gIC8vIGNhbGxiYWNrXG4gIGlmICh0eXBlb2YgdXJsID09PSAnZnVuY3Rpb24nKSB7XG4gICAgcmV0dXJuIG5ldyBleHBvcnRzLlJlcXVlc3QoJ0dFVCcsIG1ldGhvZCkuZW5kKHVybCk7XG4gIH1cblxuICAvLyB1cmwgZmlyc3RcbiAgaWYgKGFyZ3VtZW50cy5sZW5ndGggPT09IDEpIHtcbiAgICByZXR1cm4gbmV3IGV4cG9ydHMuUmVxdWVzdCgnR0VUJywgbWV0aG9kKTtcbiAgfVxuXG4gIHJldHVybiBuZXcgZXhwb3J0cy5SZXF1ZXN0KG1ldGhvZCwgdXJsKTtcbn1cblxubW9kdWxlLmV4cG9ydHMgPSByZXF1ZXN0O1xuZXhwb3J0cyA9IG1vZHVsZS5leHBvcnRzO1xuXG4vKipcbiAqIEV4cG9zZSBgUmVxdWVzdGAuXG4gKi9cblxuZXhwb3J0cy5SZXF1ZXN0ID0gUmVxdWVzdDtcblxuLyoqXG4gKiBFeHBvc2UgdGhlIGFnZW50IGZ1bmN0aW9uXG4gKi9cblxuZXhwb3J0cy5hZ2VudCA9IHJlcXVpcmUoJy4vYWdlbnQnKTtcblxuLyoqXG4gKiBOb29wLlxuICovXG5cbmZ1bmN0aW9uIG5vb3AoKSB7fVxuXG4vKipcbiAqIEV4cG9zZSBgUmVzcG9uc2VgLlxuICovXG5cbmV4cG9ydHMuUmVzcG9uc2UgPSBSZXNwb25zZTtcblxuLyoqXG4gKiBEZWZpbmUgXCJmb3JtXCIgbWltZSB0eXBlLlxuICovXG5cbm1pbWUuZGVmaW5lKFxuICB7XG4gICAgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCc6IFsnZm9ybScsICd1cmxlbmNvZGVkJywgJ2Zvcm0tZGF0YSddXG4gIH0sXG4gIHRydWVcbik7XG5cbi8qKlxuICogUHJvdG9jb2wgbWFwLlxuICovXG5cbmV4cG9ydHMucHJvdG9jb2xzID0ge1xuICAnaHR0cDonOiBodHRwLFxuICAnaHR0cHM6JzogaHR0cHMsXG4gICdodHRwMjonOiBodHRwMlxufTtcblxuLyoqXG4gKiBEZWZhdWx0IHNlcmlhbGl6YXRpb24gbWFwLlxuICpcbiAqICAgICBzdXBlcmFnZW50LnNlcmlhbGl6ZVsnYXBwbGljYXRpb24veG1sJ10gPSBmdW5jdGlvbihvYmope1xuICogICAgICAgcmV0dXJuICdnZW5lcmF0ZWQgeG1sIGhlcmUnO1xuICogICAgIH07XG4gKlxuICovXG5cbmV4cG9ydHMuc2VyaWFsaXplID0ge1xuICAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJzogcXMuc3RyaW5naWZ5LFxuICAnYXBwbGljYXRpb24vanNvbic6IHNhZmVTdHJpbmdpZnlcbn07XG5cbi8qKlxuICogRGVmYXVsdCBwYXJzZXJzLlxuICpcbiAqICAgICBzdXBlcmFnZW50LnBhcnNlWydhcHBsaWNhdGlvbi94bWwnXSA9IGZ1bmN0aW9uKHJlcywgZm4pe1xuICogICAgICAgZm4obnVsbCwgcmVzKTtcbiAqICAgICB9O1xuICpcbiAqL1xuXG5leHBvcnRzLnBhcnNlID0gcmVxdWlyZSgnLi9wYXJzZXJzJyk7XG5cbi8qKlxuICogRGVmYXVsdCBidWZmZXJpbmcgbWFwLiBDYW4gYmUgdXNlZCB0byBzZXQgY2VydGFpblxuICogcmVzcG9uc2UgdHlwZXMgdG8gYnVmZmVyL25vdCBidWZmZXIuXG4gKlxuICogICAgIHN1cGVyYWdlbnQuYnVmZmVyWydhcHBsaWNhdGlvbi94bWwnXSA9IHRydWU7XG4gKi9cbmV4cG9ydHMuYnVmZmVyID0ge307XG5cbi8qKlxuICogSW5pdGlhbGl6ZSBpbnRlcm5hbCBoZWFkZXIgdHJhY2tpbmcgcHJvcGVydGllcyBvbiBhIHJlcXVlc3QgaW5zdGFuY2UuXG4gKlxuICogQHBhcmFtIHtPYmplY3R9IHJlcSB0aGUgaW5zdGFuY2VcbiAqIEBhcGkgcHJpdmF0ZVxuICovXG5mdW5jdGlvbiBfaW5pdEhlYWRlcnMocmVxdWVzdF8pIHtcbiAgcmVxdWVzdF8uX2hlYWRlciA9IHtcbiAgICAvLyBjb2VyY2VzIGhlYWRlciBuYW1lcyB0byBsb3dlcmNhc2VcbiAgfTtcbiAgcmVxdWVzdF8uaGVhZGVyID0ge1xuICAgIC8vIHByZXNlcnZlcyBoZWFkZXIgbmFtZSBjYXNlXG4gIH07XG59XG5cbi8qKlxuICogSW5pdGlhbGl6ZSBhIG5ldyBgUmVxdWVzdGAgd2l0aCB0aGUgZ2l2ZW4gYG1ldGhvZGAgYW5kIGB1cmxgLlxuICpcbiAqIEBwYXJhbSB7U3RyaW5nfSBtZXRob2RcbiAqIEBwYXJhbSB7U3RyaW5nfE9iamVjdH0gdXJsXG4gKiBAYXBpIHB1YmxpY1xuICovXG5cbmZ1bmN0aW9uIFJlcXVlc3QobWV0aG9kLCB1cmwpIHtcbiAgU3RyZWFtLmNhbGwodGhpcyk7XG4gIGlmICh0eXBlb2YgdXJsICE9PSAnc3RyaW5nJykgdXJsID0gZm9ybWF0KHVybCk7XG4gIHRoaXMuX2VuYWJsZUh0dHAyID0gQm9vbGVhbihwcm9jZXNzLmVudi5IVFRQMl9URVNUKTsgLy8gaW50ZXJuYWwgb25seVxuICB0aGlzLl9hZ2VudCA9IGZhbHNlO1xuICB0aGlzLl9mb3JtRGF0YSA9IG51bGw7XG4gIHRoaXMubWV0aG9kID0gbWV0aG9kO1xuICB0aGlzLnVybCA9IHVybDtcbiAgX2luaXRIZWFkZXJzKHRoaXMpO1xuICB0aGlzLndyaXRhYmxlID0gdHJ1ZTtcbiAgdGhpcy5fcmVkaXJlY3RzID0gMDtcbiAgdGhpcy5yZWRpcmVjdHMobWV0aG9kID09PSAnSEVBRCcgPyAwIDogNSk7XG4gIHRoaXMuY29va2llcyA9ICcnO1xuICB0aGlzLnFzID0ge307XG4gIHRoaXMuX3F1ZXJ5ID0gW107XG4gIHRoaXMucXNSYXcgPSB0aGlzLl9xdWVyeTsgLy8gVW51c2VkLCBmb3IgYmFja3dhcmRzIGNvbXBhdGliaWxpdHkgb25seVxuICB0aGlzLl9yZWRpcmVjdExpc3QgPSBbXTtcbiAgdGhpcy5fc3RyZWFtUmVxdWVzdCA9IGZhbHNlO1xuICB0aGlzLm9uY2UoJ2VuZCcsIHRoaXMuY2xlYXJUaW1lb3V0LmJpbmQodGhpcykpO1xufVxuXG4vKipcbiAqIEluaGVyaXQgZnJvbSBgU3RyZWFtYCAod2hpY2ggaW5oZXJpdHMgZnJvbSBgRXZlbnRFbWl0dGVyYCkuXG4gKiBNaXhpbiBgUmVxdWVzdEJhc2VgLlxuICovXG51dGlsLmluaGVyaXRzKFJlcXVlc3QsIFN0cmVhbSk7XG5cbm1peGluKFJlcXVlc3QucHJvdG90eXBlLCBSZXF1ZXN0QmFzZS5wcm90b3R5cGUpO1xuXG4vKipcbiAqIEVuYWJsZSBvciBEaXNhYmxlIGh0dHAyLlxuICpcbiAqIEVuYWJsZSBodHRwMi5cbiAqXG4gKiBgYGAganNcbiAqIHJlcXVlc3QuZ2V0KCdodHRwOi8vbG9jYWxob3N0LycpXG4gKiAgIC5odHRwMigpXG4gKiAgIC5lbmQoY2FsbGJhY2spO1xuICpcbiAqIHJlcXVlc3QuZ2V0KCdodHRwOi8vbG9jYWxob3N0LycpXG4gKiAgIC5odHRwMih0cnVlKVxuICogICAuZW5kKGNhbGxiYWNrKTtcbiAqIGBgYFxuICpcbiAqIERpc2FibGUgaHR0cDIuXG4gKlxuICogYGBgIGpzXG4gKiByZXF1ZXN0ID0gcmVxdWVzdC5odHRwMigpO1xuICogcmVxdWVzdC5nZXQoJ2h0dHA6Ly9sb2NhbGhvc3QvJylcbiAqICAgLmh0dHAyKGZhbHNlKVxuICogICAuZW5kKGNhbGxiYWNrKTtcbiAqIGBgYFxuICpcbiAqIEBwYXJhbSB7Qm9vbGVhbn0gZW5hYmxlXG4gKiBAcmV0dXJuIHtSZXF1ZXN0fSBmb3IgY2hhaW5pbmdcbiAqIEBhcGkgcHVibGljXG4gKi9cblxuUmVxdWVzdC5wcm90b3R5cGUuaHR0cDIgPSBmdW5jdGlvbiAoYm9vbCkge1xuICBpZiAoZXhwb3J0cy5wcm90b2NvbHNbJ2h0dHAyOiddID09PSB1bmRlZmluZWQpIHtcbiAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAnc3VwZXJhZ2VudDogdGhpcyB2ZXJzaW9uIG9mIE5vZGUuanMgZG9lcyBub3Qgc3VwcG9ydCBodHRwMidcbiAgICApO1xuICB9XG5cbiAgdGhpcy5fZW5hYmxlSHR0cDIgPSBib29sID09PSB1bmRlZmluZWQgPyB0cnVlIDogYm9vbDtcbiAgcmV0dXJuIHRoaXM7XG59O1xuXG4vKipcbiAqIFF1ZXVlIHRoZSBnaXZlbiBgZmlsZWAgYXMgYW4gYXR0YWNobWVudCB0byB0aGUgc3BlY2lmaWVkIGBmaWVsZGAsXG4gKiB3aXRoIG9wdGlvbmFsIGBvcHRpb25zYCAob3IgZmlsZW5hbWUpLlxuICpcbiAqIGBgYCBqc1xuICogcmVxdWVzdC5wb3N0KCdodHRwOi8vbG9jYWxob3N0L3VwbG9hZCcpXG4gKiAgIC5hdHRhY2goJ2ZpZWxkJywgQnVmZmVyLmZyb20oJzxiPkhlbGxvIHdvcmxkPC9iPicpLCAnaGVsbG8uaHRtbCcpXG4gKiAgIC5lbmQoY2FsbGJhY2spO1xuICogYGBgXG4gKlxuICogQSBmaWxlbmFtZSBtYXkgYWxzbyBiZSB1c2VkOlxuICpcbiAqIGBgYCBqc1xuICogcmVxdWVzdC5wb3N0KCdodHRwOi8vbG9jYWxob3N0L3VwbG9hZCcpXG4gKiAgIC5hdHRhY2goJ2ZpbGVzJywgJ2ltYWdlLmpwZycpXG4gKiAgIC5lbmQoY2FsbGJhY2spO1xuICogYGBgXG4gKlxuICogQHBhcmFtIHtTdHJpbmd9IGZpZWxkXG4gKiBAcGFyYW0ge1N0cmluZ3xmcy5SZWFkU3RyZWFtfEJ1ZmZlcn0gZmlsZVxuICogQHBhcmFtIHtTdHJpbmd8T2JqZWN0fSBvcHRpb25zXG4gKiBAcmV0dXJuIHtSZXF1ZXN0fSBmb3IgY2hhaW5pbmdcbiAqIEBhcGkgcHVibGljXG4gKi9cblxuUmVxdWVzdC5wcm90b3R5cGUuYXR0YWNoID0gZnVuY3Rpb24gKGZpZWxkLCBmaWxlLCBvcHRpb25zKSB7XG4gIGlmIChmaWxlKSB7XG4gICAgaWYgKHRoaXMuX2RhdGEpIHtcbiAgICAgIHRocm93IG5ldyBFcnJvcihcInN1cGVyYWdlbnQgY2FuJ3QgbWl4IC5zZW5kKCkgYW5kIC5hdHRhY2goKVwiKTtcbiAgICB9XG5cbiAgICBsZXQgbyA9IG9wdGlvbnMgfHwge307XG4gICAgaWYgKHR5cGVvZiBvcHRpb25zID09PSAnc3RyaW5nJykge1xuICAgICAgbyA9IHsgZmlsZW5hbWU6IG9wdGlvbnMgfTtcbiAgICB9XG5cbiAgICBpZiAodHlwZW9mIGZpbGUgPT09ICdzdHJpbmcnKSB7XG4gICAgICBpZiAoIW8uZmlsZW5hbWUpIG8uZmlsZW5hbWUgPSBmaWxlO1xuICAgICAgZGVidWcoJ2NyZWF0aW5nIGBmcy5SZWFkU3RyZWFtYCBpbnN0YW5jZSBmb3IgZmlsZTogJXMnLCBmaWxlKTtcbiAgICAgIGZpbGUgPSBmcy5jcmVhdGVSZWFkU3RyZWFtKGZpbGUpO1xuICAgICAgZmlsZS5vbignZXJyb3InLCAoZXJyb3IpID0+IHtcbiAgICAgICAgY29uc3QgZm9ybURhdGEgPSB0aGlzLl9nZXRGb3JtRGF0YSgpO1xuICAgICAgICBmb3JtRGF0YS5lbWl0KCdlcnJvcicsIGVycm9yKTtcbiAgICAgIH0pO1xuICAgIH0gZWxzZSBpZiAoIW8uZmlsZW5hbWUgJiYgZmlsZS5wYXRoKSB7XG4gICAgICBvLmZpbGVuYW1lID0gZmlsZS5wYXRoO1xuICAgIH1cblxuICAgIHRoaXMuX2dldEZvcm1EYXRhKCkuYXBwZW5kKGZpZWxkLCBmaWxlLCBvKTtcbiAgfVxuXG4gIHJldHVybiB0aGlzO1xufTtcblxuUmVxdWVzdC5wcm90b3R5cGUuX2dldEZvcm1EYXRhID0gZnVuY3Rpb24gKCkge1xuICBpZiAoIXRoaXMuX2Zvcm1EYXRhKSB7XG4gICAgdGhpcy5fZm9ybURhdGEgPSBuZXcgRm9ybURhdGEoKTtcbiAgICB0aGlzLl9mb3JtRGF0YS5vbignZXJyb3InLCAoZXJyb3IpID0+IHtcbiAgICAgIGRlYnVnKCdGb3JtRGF0YSBlcnJvcicsIGVycm9yKTtcbiAgICAgIGlmICh0aGlzLmNhbGxlZCkge1xuICAgICAgICAvLyBUaGUgcmVxdWVzdCBoYXMgYWxyZWFkeSBmaW5pc2hlZCBhbmQgdGhlIGNhbGxiYWNrIHdhcyBjYWxsZWQuXG4gICAgICAgIC8vIFNpbGVudGx5IGlnbm9yZSB0aGUgZXJyb3IuXG4gICAgICAgIHJldHVybjtcbiAgICAgIH1cblxuICAgICAgdGhpcy5jYWxsYmFjayhlcnJvcik7XG4gICAgICB0aGlzLmFib3J0KCk7XG4gICAgfSk7XG4gIH1cblxuICByZXR1cm4gdGhpcy5fZm9ybURhdGE7XG59O1xuXG4vKipcbiAqIEdldHMvc2V0cyB0aGUgYEFnZW50YCB0byB1c2UgZm9yIHRoaXMgSFRUUCByZXF1ZXN0LiBUaGUgZGVmYXVsdCAoaWYgdGhpc1xuICogZnVuY3Rpb24gaXMgbm90IGNhbGxlZCkgaXMgdG8gb3B0IG91dCBvZiBjb25uZWN0aW9uIHBvb2xpbmcgKGBhZ2VudDogZmFsc2VgKS5cbiAqXG4gKiBAcGFyYW0ge2h0dHAuQWdlbnR9IGFnZW50XG4gKiBAcmV0dXJuIHtodHRwLkFnZW50fVxuICogQGFwaSBwdWJsaWNcbiAqL1xuXG5SZXF1ZXN0LnByb3RvdHlwZS5hZ2VudCA9IGZ1bmN0aW9uIChhZ2VudCkge1xuICBpZiAoYXJndW1lbnRzLmxlbmd0aCA9PT0gMCkgcmV0dXJuIHRoaXMuX2FnZW50O1xuICB0aGlzLl9hZ2VudCA9IGFnZW50O1xuICByZXR1cm4gdGhpcztcbn07XG5cbi8qKlxuICogU2V0IF9Db250ZW50LVR5cGVfIHJlc3BvbnNlIGhlYWRlciBwYXNzZWQgdGhyb3VnaCBgbWltZS5nZXRUeXBlKClgLlxuICpcbiAqIEV4YW1wbGVzOlxuICpcbiAqICAgICAgcmVxdWVzdC5wb3N0KCcvJylcbiAqICAgICAgICAudHlwZSgneG1sJylcbiAqICAgICAgICAuc2VuZCh4bWxzdHJpbmcpXG4gKiAgICAgICAgLmVuZChjYWxsYmFjayk7XG4gKlxuICogICAgICByZXF1ZXN0LnBvc3QoJy8nKVxuICogICAgICAgIC50eXBlKCdqc29uJylcbiAqICAgICAgICAuc2VuZChqc29uc3RyaW5nKVxuICogICAgICAgIC5lbmQoY2FsbGJhY2spO1xuICpcbiAqICAgICAgcmVxdWVzdC5wb3N0KCcvJylcbiAqICAgICAgICAudHlwZSgnYXBwbGljYXRpb24vanNvbicpXG4gKiAgICAgICAgLnNlbmQoanNvbnN0cmluZylcbiAqICAgICAgICAuZW5kKGNhbGxiYWNrKTtcbiAqXG4gKiBAcGFyYW0ge1N0cmluZ30gdHlwZVxuICogQHJldHVybiB7UmVxdWVzdH0gZm9yIGNoYWluaW5nXG4gKiBAYXBpIHB1YmxpY1xuICovXG5cblJlcXVlc3QucHJvdG90eXBlLnR5cGUgPSBmdW5jdGlvbiAodHlwZSkge1xuICByZXR1cm4gdGhpcy5zZXQoXG4gICAgJ0NvbnRlbnQtVHlwZScsXG4gICAgdHlwZS5pbmNsdWRlcygnLycpID8gdHlwZSA6IG1pbWUuZ2V0VHlwZSh0eXBlKVxuICApO1xufTtcblxuLyoqXG4gKiBTZXQgX0FjY2VwdF8gcmVzcG9uc2UgaGVhZGVyIHBhc3NlZCB0aHJvdWdoIGBtaW1lLmdldFR5cGUoKWAuXG4gKlxuICogRXhhbXBsZXM6XG4gKlxuICogICAgICBzdXBlcmFnZW50LnR5cGVzLmpzb24gPSAnYXBwbGljYXRpb24vanNvbic7XG4gKlxuICogICAgICByZXF1ZXN0LmdldCgnL2FnZW50JylcbiAqICAgICAgICAuYWNjZXB0KCdqc29uJylcbiAqICAgICAgICAuZW5kKGNhbGxiYWNrKTtcbiAqXG4gKiAgICAgIHJlcXVlc3QuZ2V0KCcvYWdlbnQnKVxuICogICAgICAgIC5hY2NlcHQoJ2FwcGxpY2F0aW9uL2pzb24nKVxuICogICAgICAgIC5lbmQoY2FsbGJhY2spO1xuICpcbiAqIEBwYXJhbSB7U3RyaW5nfSBhY2NlcHRcbiAqIEByZXR1cm4ge1JlcXVlc3R9IGZvciBjaGFpbmluZ1xuICogQGFwaSBwdWJsaWNcbiAqL1xuXG5SZXF1ZXN0LnByb3RvdHlwZS5hY2NlcHQgPSBmdW5jdGlvbiAodHlwZSkge1xuICByZXR1cm4gdGhpcy5zZXQoJ0FjY2VwdCcsIHR5cGUuaW5jbHVkZXMoJy8nKSA/IHR5cGUgOiBtaW1lLmdldFR5cGUodHlwZSkpO1xufTtcblxuLyoqXG4gKiBBZGQgcXVlcnktc3RyaW5nIGB2YWxgLlxuICpcbiAqIEV4YW1wbGVzOlxuICpcbiAqICAgcmVxdWVzdC5nZXQoJy9zaG9lcycpXG4gKiAgICAgLnF1ZXJ5KCdzaXplPTEwJylcbiAqICAgICAucXVlcnkoeyBjb2xvcjogJ2JsdWUnIH0pXG4gKlxuICogQHBhcmFtIHtPYmplY3R8U3RyaW5nfSB2YWxcbiAqIEByZXR1cm4ge1JlcXVlc3R9IGZvciBjaGFpbmluZ1xuICogQGFwaSBwdWJsaWNcbiAqL1xuXG5SZXF1ZXN0LnByb3RvdHlwZS5xdWVyeSA9IGZ1bmN0aW9uICh2YWx1ZSkge1xuICBpZiAodHlwZW9mIHZhbHVlID09PSAnc3RyaW5nJykge1xuICAgIHRoaXMuX3F1ZXJ5LnB1c2godmFsdWUpO1xuICB9IGVsc2Uge1xuICAgIE9iamVjdC5hc3NpZ24odGhpcy5xcywgdmFsdWUpO1xuICB9XG5cbiAgcmV0dXJuIHRoaXM7XG59O1xuXG4vKipcbiAqIFdyaXRlIHJhdyBgZGF0YWAgLyBgZW5jb2RpbmdgIHRvIHRoZSBzb2NrZXQuXG4gKlxuICogQHBhcmFtIHtCdWZmZXJ8U3RyaW5nfSBkYXRhXG4gKiBAcGFyYW0ge1N0cmluZ30gZW5jb2RpbmdcbiAqIEByZXR1cm4ge0Jvb2xlYW59XG4gKiBAYXBpIHB1YmxpY1xuICovXG5cblJlcXVlc3QucHJvdG90eXBlLndyaXRlID0gZnVuY3Rpb24gKGRhdGEsIGVuY29kaW5nKSB7XG4gIGNvbnN0IHJlcXVlc3RfID0gdGhpcy5yZXF1ZXN0KCk7XG4gIGlmICghdGhpcy5fc3RyZWFtUmVxdWVzdCkge1xuICAgIHRoaXMuX3N0cmVhbVJlcXVlc3QgPSB0cnVlO1xuICB9XG5cbiAgcmV0dXJuIHJlcXVlc3RfLndyaXRlKGRhdGEsIGVuY29kaW5nKTtcbn07XG5cbi8qKlxuICogUGlwZSB0aGUgcmVxdWVzdCBib2R5IHRvIGBzdHJlYW1gLlxuICpcbiAqIEBwYXJhbSB7U3RyZWFtfSBzdHJlYW1cbiAqIEBwYXJhbSB7T2JqZWN0fSBvcHRpb25zXG4gKiBAcmV0dXJuIHtTdHJlYW19XG4gKiBAYXBpIHB1YmxpY1xuICovXG5cblJlcXVlc3QucHJvdG90eXBlLnBpcGUgPSBmdW5jdGlvbiAoc3RyZWFtLCBvcHRpb25zKSB7XG4gIHRoaXMucGlwZWQgPSB0cnVlOyAvLyBIQUNLLi4uXG4gIHRoaXMuYnVmZmVyKGZhbHNlKTtcbiAgdGhpcy5lbmQoKTtcbiAgcmV0dXJuIHRoaXMuX3BpcGVDb250aW51ZShzdHJlYW0sIG9wdGlvbnMpO1xufTtcblxuUmVxdWVzdC5wcm90b3R5cGUuX3BpcGVDb250aW51ZSA9IGZ1bmN0aW9uIChzdHJlYW0sIG9wdGlvbnMpIHtcbiAgdGhpcy5yZXEub25jZSgncmVzcG9uc2UnLCAocmVzKSA9PiB7XG4gICAgLy8gcmVkaXJlY3RcbiAgICBpZiAoXG4gICAgICBpc1JlZGlyZWN0KHJlcy5zdGF0dXNDb2RlKSAmJlxuICAgICAgdGhpcy5fcmVkaXJlY3RzKysgIT09IHRoaXMuX21heFJlZGlyZWN0c1xuICAgICkge1xuICAgICAgcmV0dXJuIHRoaXMuX3JlZGlyZWN0KHJlcykgPT09IHRoaXNcbiAgICAgICAgPyB0aGlzLl9waXBlQ29udGludWUoc3RyZWFtLCBvcHRpb25zKVxuICAgICAgICA6IHVuZGVmaW5lZDtcbiAgICB9XG5cbiAgICB0aGlzLnJlcyA9IHJlcztcbiAgICB0aGlzLl9lbWl0UmVzcG9uc2UoKTtcbiAgICBpZiAodGhpcy5fYWJvcnRlZCkgcmV0dXJuO1xuXG4gICAgaWYgKHRoaXMuX3Nob3VsZFVuemlwKHJlcykpIHtcbiAgICAgIGNvbnN0IHVuemlwT2JqZWN0ID0gemxpYi5jcmVhdGVVbnppcCgpO1xuICAgICAgdW56aXBPYmplY3Qub24oJ2Vycm9yJywgKGVycm9yKSA9PiB7XG4gICAgICAgIGlmIChlcnJvciAmJiBlcnJvci5jb2RlID09PSAnWl9CVUZfRVJST1InKSB7XG4gICAgICAgICAgLy8gdW5leHBlY3RlZCBlbmQgb2YgZmlsZSBpcyBpZ25vcmVkIGJ5IGJyb3dzZXJzIGFuZCBjdXJsXG4gICAgICAgICAgc3RyZWFtLmVtaXQoJ2VuZCcpO1xuICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuXG4gICAgICAgIHN0cmVhbS5lbWl0KCdlcnJvcicsIGVycm9yKTtcbiAgICAgIH0pO1xuICAgICAgcmVzLnBpcGUodW56aXBPYmplY3QpLnBpcGUoc3RyZWFtLCBvcHRpb25zKTtcbiAgICB9IGVsc2Uge1xuICAgICAgcmVzLnBpcGUoc3RyZWFtLCBvcHRpb25zKTtcbiAgICB9XG5cbiAgICByZXMub25jZSgnZW5kJywgKCkgPT4ge1xuICAgICAgdGhpcy5lbWl0KCdlbmQnKTtcbiAgICB9KTtcbiAgfSk7XG4gIHJldHVybiBzdHJlYW07XG59O1xuXG4vKipcbiAqIEVuYWJsZSAvIGRpc2FibGUgYnVmZmVyaW5nLlxuICpcbiAqIEByZXR1cm4ge0Jvb2xlYW59IFt2YWxdXG4gKiBAcmV0dXJuIHtSZXF1ZXN0fSBmb3IgY2hhaW5pbmdcbiAqIEBhcGkgcHVibGljXG4gKi9cblxuUmVxdWVzdC5wcm90b3R5cGUuYnVmZmVyID0gZnVuY3Rpb24gKHZhbHVlKSB7XG4gIHRoaXMuX2J1ZmZlciA9IHZhbHVlICE9PSBmYWxzZTtcbiAgcmV0dXJuIHRoaXM7XG59O1xuXG4vKipcbiAqIFJlZGlyZWN0IHRvIGB1cmxcbiAqXG4gKiBAcGFyYW0ge0luY29taW5nTWVzc2FnZX0gcmVzXG4gKiBAcmV0dXJuIHtSZXF1ZXN0fSBmb3IgY2hhaW5pbmdcbiAqIEBhcGkgcHJpdmF0ZVxuICovXG5cblJlcXVlc3QucHJvdG90eXBlLl9yZWRpcmVjdCA9IGZ1bmN0aW9uIChyZXMpIHtcbiAgbGV0IHVybCA9IHJlcy5oZWFkZXJzLmxvY2F0aW9uO1xuICBpZiAoIXVybCkge1xuICAgIHJldHVybiB0aGlzLmNhbGxiYWNrKG5ldyBFcnJvcignTm8gbG9jYXRpb24gaGVhZGVyIGZvciByZWRpcmVjdCcpLCByZXMpO1xuICB9XG5cbiAgZGVidWcoJ3JlZGlyZWN0ICVzIC0+ICVzJywgdGhpcy51cmwsIHVybCk7XG5cbiAgLy8gbG9jYXRpb25cbiAgdXJsID0gcmVzb2x2ZSh0aGlzLnVybCwgdXJsKTtcblxuICAvLyBlbnN1cmUgdGhlIHJlc3BvbnNlIGlzIGJlaW5nIGNvbnN1bWVkXG4gIC8vIHRoaXMgaXMgcmVxdWlyZWQgZm9yIE5vZGUgdjAuMTArXG4gIHJlcy5yZXN1bWUoKTtcblxuICBsZXQgaGVhZGVycyA9IHRoaXMucmVxLmdldEhlYWRlcnMgPyB0aGlzLnJlcS5nZXRIZWFkZXJzKCkgOiB0aGlzLnJlcS5faGVhZGVycztcblxuICBjb25zdCBjaGFuZ2VzT3JpZ2luID0gcGFyc2UodXJsKS5ob3N0ICE9PSBwYXJzZSh0aGlzLnVybCkuaG9zdDtcblxuICAvLyBpbXBsZW1lbnRhdGlvbiBvZiAzMDIgZm9sbG93aW5nIGRlZmFjdG8gc3RhbmRhcmRcbiAgaWYgKHJlcy5zdGF0dXNDb2RlID09PSAzMDEgfHwgcmVzLnN0YXR1c0NvZGUgPT09IDMwMikge1xuICAgIC8vIHN0cmlwIENvbnRlbnQtKiByZWxhdGVkIGZpZWxkc1xuICAgIC8vIGluIGNhc2Ugb2YgUE9TVCBldGNcbiAgICBoZWFkZXJzID0gdXRpbHMuY2xlYW5IZWFkZXIoaGVhZGVycywgY2hhbmdlc09yaWdpbik7XG5cbiAgICAvLyBmb3JjZSBHRVRcbiAgICB0aGlzLm1ldGhvZCA9IHRoaXMubWV0aG9kID09PSAnSEVBRCcgPyAnSEVBRCcgOiAnR0VUJztcblxuICAgIC8vIGNsZWFyIGRhdGFcbiAgICB0aGlzLl9kYXRhID0gbnVsbDtcbiAgfVxuXG4gIC8vIDMwMyBpcyBhbHdheXMgR0VUXG4gIGlmIChyZXMuc3RhdHVzQ29kZSA9PT0gMzAzKSB7XG4gICAgLy8gc3RyaXAgQ29udGVudC0qIHJlbGF0ZWQgZmllbGRzXG4gICAgLy8gaW4gY2FzZSBvZiBQT1NUIGV0Y1xuICAgIGhlYWRlcnMgPSB1dGlscy5jbGVhbkhlYWRlcihoZWFkZXJzLCBjaGFuZ2VzT3JpZ2luKTtcblxuICAgIC8vIGZvcmNlIG1ldGhvZFxuICAgIHRoaXMubWV0aG9kID0gJ0dFVCc7XG5cbiAgICAvLyBjbGVhciBkYXRhXG4gICAgdGhpcy5fZGF0YSA9IG51bGw7XG4gIH1cblxuICAvLyAzMDcgcHJlc2VydmVzIG1ldGhvZFxuICAvLyAzMDggcHJlc2VydmVzIG1ldGhvZFxuICBkZWxldGUgaGVhZGVycy5ob3N0O1xuXG4gIGRlbGV0ZSB0aGlzLnJlcTtcbiAgZGVsZXRlIHRoaXMuX2Zvcm1EYXRhO1xuXG4gIC8vIHJlbW92ZSBhbGwgYWRkIGhlYWRlciBleGNlcHQgVXNlci1BZ2VudFxuICBfaW5pdEhlYWRlcnModGhpcyk7XG5cbiAgLy8gcmVkaXJlY3RcbiAgdGhpcy5fZW5kQ2FsbGVkID0gZmFsc2U7XG4gIHRoaXMudXJsID0gdXJsO1xuICB0aGlzLnFzID0ge307XG4gIHRoaXMuX3F1ZXJ5Lmxlbmd0aCA9IDA7XG4gIHRoaXMuc2V0KGhlYWRlcnMpO1xuICB0aGlzLmVtaXQoJ3JlZGlyZWN0JywgcmVzKTtcbiAgdGhpcy5fcmVkaXJlY3RMaXN0LnB1c2godGhpcy51cmwpO1xuICB0aGlzLmVuZCh0aGlzLl9jYWxsYmFjayk7XG4gIHJldHVybiB0aGlzO1xufTtcblxuLyoqXG4gKiBTZXQgQXV0aG9yaXphdGlvbiBmaWVsZCB2YWx1ZSB3aXRoIGB1c2VyYCBhbmQgYHBhc3NgLlxuICpcbiAqIEV4YW1wbGVzOlxuICpcbiAqICAgLmF1dGgoJ3RvYmknLCAnbGVhcm5ib29zdCcpXG4gKiAgIC5hdXRoKCd0b2JpOmxlYXJuYm9vc3QnKVxuICogICAuYXV0aCgndG9iaScpXG4gKiAgIC5hdXRoKGFjY2Vzc1Rva2VuLCB7IHR5cGU6ICdiZWFyZXInIH0pXG4gKlxuICogQHBhcmFtIHtTdHJpbmd9IHVzZXJcbiAqIEBwYXJhbSB7U3RyaW5nfSBbcGFzc11cbiAqIEBwYXJhbSB7T2JqZWN0fSBbb3B0aW9uc10gb3B0aW9ucyB3aXRoIGF1dGhvcml6YXRpb24gdHlwZSAnYmFzaWMnIG9yICdiZWFyZXInICgnYmFzaWMnIGlzIGRlZmF1bHQpXG4gKiBAcmV0dXJuIHtSZXF1ZXN0fSBmb3IgY2hhaW5pbmdcbiAqIEBhcGkgcHVibGljXG4gKi9cblxuUmVxdWVzdC5wcm90b3R5cGUuYXV0aCA9IGZ1bmN0aW9uICh1c2VyLCBwYXNzLCBvcHRpb25zKSB7XG4gIGlmIChhcmd1bWVudHMubGVuZ3RoID09PSAxKSBwYXNzID0gJyc7XG4gIGlmICh0eXBlb2YgcGFzcyA9PT0gJ29iamVjdCcgJiYgcGFzcyAhPT0gbnVsbCkge1xuICAgIC8vIHBhc3MgaXMgb3B0aW9uYWwgYW5kIGNhbiBiZSByZXBsYWNlZCB3aXRoIG9wdGlvbnNcbiAgICBvcHRpb25zID0gcGFzcztcbiAgICBwYXNzID0gJyc7XG4gIH1cblxuICBpZiAoIW9wdGlvbnMpIHtcbiAgICBvcHRpb25zID0geyB0eXBlOiAnYmFzaWMnIH07XG4gIH1cblxuICBjb25zdCBlbmNvZGVyID0gKHN0cmluZykgPT4gQnVmZmVyLmZyb20oc3RyaW5nKS50b1N0cmluZygnYmFzZTY0Jyk7XG5cbiAgcmV0dXJuIHRoaXMuX2F1dGgodXNlciwgcGFzcywgb3B0aW9ucywgZW5jb2Rlcik7XG59O1xuXG4vKipcbiAqIFNldCB0aGUgY2VydGlmaWNhdGUgYXV0aG9yaXR5IG9wdGlvbiBmb3IgaHR0cHMgcmVxdWVzdC5cbiAqXG4gKiBAcGFyYW0ge0J1ZmZlciB8IEFycmF5fSBjZXJ0XG4gKiBAcmV0dXJuIHtSZXF1ZXN0fSBmb3IgY2hhaW5pbmdcbiAqIEBhcGkgcHVibGljXG4gKi9cblxuUmVxdWVzdC5wcm90b3R5cGUuY2EgPSBmdW5jdGlvbiAoY2VydCkge1xuICB0aGlzLl9jYSA9IGNlcnQ7XG4gIHJldHVybiB0aGlzO1xufTtcblxuLyoqXG4gKiBTZXQgdGhlIGNsaWVudCBjZXJ0aWZpY2F0ZSBrZXkgb3B0aW9uIGZvciBodHRwcyByZXF1ZXN0LlxuICpcbiAqIEBwYXJhbSB7QnVmZmVyIHwgU3RyaW5nfSBjZXJ0XG4gKiBAcmV0dXJuIHtSZXF1ZXN0fSBmb3IgY2hhaW5pbmdcbiAqIEBhcGkgcHVibGljXG4gKi9cblxuUmVxdWVzdC5wcm90b3R5cGUua2V5ID0gZnVuY3Rpb24gKGNlcnQpIHtcbiAgdGhpcy5fa2V5ID0gY2VydDtcbiAgcmV0dXJuIHRoaXM7XG59O1xuXG4vKipcbiAqIFNldCB0aGUga2V5LCBjZXJ0aWZpY2F0ZSwgYW5kIENBIGNlcnRzIG9mIHRoZSBjbGllbnQgaW4gUEZYIG9yIFBLQ1MxMiBmb3JtYXQuXG4gKlxuICogQHBhcmFtIHtCdWZmZXIgfCBTdHJpbmd9IGNlcnRcbiAqIEByZXR1cm4ge1JlcXVlc3R9IGZvciBjaGFpbmluZ1xuICogQGFwaSBwdWJsaWNcbiAqL1xuXG5SZXF1ZXN0LnByb3RvdHlwZS5wZnggPSBmdW5jdGlvbiAoY2VydCkge1xuICBpZiAodHlwZW9mIGNlcnQgPT09ICdvYmplY3QnICYmICFCdWZmZXIuaXNCdWZmZXIoY2VydCkpIHtcbiAgICB0aGlzLl9wZnggPSBjZXJ0LnBmeDtcbiAgICB0aGlzLl9wYXNzcGhyYXNlID0gY2VydC5wYXNzcGhyYXNlO1xuICB9IGVsc2Uge1xuICAgIHRoaXMuX3BmeCA9IGNlcnQ7XG4gIH1cblxuICByZXR1cm4gdGhpcztcbn07XG5cbi8qKlxuICogU2V0IHRoZSBjbGllbnQgY2VydGlmaWNhdGUgb3B0aW9uIGZvciBodHRwcyByZXF1ZXN0LlxuICpcbiAqIEBwYXJhbSB7QnVmZmVyIHwgU3RyaW5nfSBjZXJ0XG4gKiBAcmV0dXJuIHtSZXF1ZXN0fSBmb3IgY2hhaW5pbmdcbiAqIEBhcGkgcHVibGljXG4gKi9cblxuUmVxdWVzdC5wcm90b3R5cGUuY2VydCA9IGZ1bmN0aW9uIChjZXJ0KSB7XG4gIHRoaXMuX2NlcnQgPSBjZXJ0O1xuICByZXR1cm4gdGhpcztcbn07XG5cbi8qKlxuICogRG8gbm90IHJlamVjdCBleHBpcmVkIG9yIGludmFsaWQgVExTIGNlcnRzLlxuICogc2V0cyBgcmVqZWN0VW5hdXRob3JpemVkPXRydWVgLiBCZSB3YXJuZWQgdGhhdCB0aGlzIGFsbG93cyBNSVRNIGF0dGFja3MuXG4gKlxuICogQHJldHVybiB7UmVxdWVzdH0gZm9yIGNoYWluaW5nXG4gKiBAYXBpIHB1YmxpY1xuICovXG5cblJlcXVlc3QucHJvdG90eXBlLmRpc2FibGVUTFNDZXJ0cyA9IGZ1bmN0aW9uICgpIHtcbiAgdGhpcy5fZGlzYWJsZVRMU0NlcnRzID0gdHJ1ZTtcbiAgcmV0dXJuIHRoaXM7XG59O1xuXG4vKipcbiAqIFJldHVybiBhbiBodHRwW3NdIHJlcXVlc3QuXG4gKlxuICogQHJldHVybiB7T3V0Z29pbmdNZXNzYWdlfVxuICogQGFwaSBwcml2YXRlXG4gKi9cblxuLy8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lIGNvbXBsZXhpdHlcblJlcXVlc3QucHJvdG90eXBlLnJlcXVlc3QgPSBmdW5jdGlvbiAoKSB7XG4gIGlmICh0aGlzLnJlcSkgcmV0dXJuIHRoaXMucmVxO1xuXG4gIGNvbnN0IG9wdGlvbnMgPSB7fTtcblxuICB0cnkge1xuICAgIGNvbnN0IHF1ZXJ5ID0gcXMuc3RyaW5naWZ5KHRoaXMucXMsIHtcbiAgICAgIGluZGljZXM6IGZhbHNlLFxuICAgICAgc3RyaWN0TnVsbEhhbmRsaW5nOiB0cnVlXG4gICAgfSk7XG4gICAgaWYgKHF1ZXJ5KSB7XG4gICAgICB0aGlzLnFzID0ge307XG4gICAgICB0aGlzLl9xdWVyeS5wdXNoKHF1ZXJ5KTtcbiAgICB9XG5cbiAgICB0aGlzLl9maW5hbGl6ZVF1ZXJ5U3RyaW5nKCk7XG4gIH0gY2F0Y2ggKGVycikge1xuICAgIHJldHVybiB0aGlzLmVtaXQoJ2Vycm9yJywgZXJyKTtcbiAgfVxuXG4gIGxldCB7IHVybCB9ID0gdGhpcztcbiAgY29uc3QgcmV0cmllcyA9IHRoaXMuX3JldHJpZXM7XG5cbiAgLy8gQ2FwdHVyZSBiYWNrdGlja3MgYXMtaXMgZnJvbSB0aGUgZmluYWwgcXVlcnkgc3RyaW5nIGJ1aWx0IGFib3ZlLlxuICAvLyBOb3RlOiB0aGlzJ2xsIG9ubHkgZmluZCBiYWNrdGlja3MgZW50ZXJlZCBpbiByZXEucXVlcnkoU3RyaW5nKVxuICAvLyBjYWxscywgYmVjYXVzZSBxcy5zdHJpbmdpZnkgdW5jb25kaXRpb25hbGx5IGVuY29kZXMgYmFja3RpY2tzLlxuICBsZXQgcXVlcnlTdHJpbmdCYWNrdGlja3M7XG4gIGlmICh1cmwuaW5jbHVkZXMoJ2AnKSkge1xuICAgIGNvbnN0IHF1ZXJ5U3RhcnRJbmRleCA9IHVybC5pbmRleE9mKCc/Jyk7XG5cbiAgICBpZiAocXVlcnlTdGFydEluZGV4ICE9PSAtMSkge1xuICAgICAgY29uc3QgcXVlcnlTdHJpbmcgPSB1cmwuc2xpY2UocXVlcnlTdGFydEluZGV4ICsgMSk7XG4gICAgICBxdWVyeVN0cmluZ0JhY2t0aWNrcyA9IHF1ZXJ5U3RyaW5nLm1hdGNoKC9gfCU2MC9nKTtcbiAgICB9XG4gIH1cblxuICAvLyBkZWZhdWx0IHRvIGh0dHA6Ly9cbiAgaWYgKHVybC5pbmRleE9mKCdodHRwJykgIT09IDApIHVybCA9IGBodHRwOi8vJHt1cmx9YDtcbiAgdXJsID0gcGFyc2UodXJsKTtcblxuICAvLyBTZWUgaHR0cHM6Ly9naXRodWIuY29tL3Zpc2lvbm1lZGlhL3N1cGVyYWdlbnQvaXNzdWVzLzEzNjdcbiAgaWYgKHF1ZXJ5U3RyaW5nQmFja3RpY2tzKSB7XG4gICAgbGV0IGkgPSAwO1xuICAgIHVybC5xdWVyeSA9IHVybC5xdWVyeS5yZXBsYWNlKC8lNjAvZywgKCkgPT4gcXVlcnlTdHJpbmdCYWNrdGlja3NbaSsrXSk7XG4gICAgdXJsLnNlYXJjaCA9IGA/JHt1cmwucXVlcnl9YDtcbiAgICB1cmwucGF0aCA9IHVybC5wYXRobmFtZSArIHVybC5zZWFyY2g7XG4gIH1cblxuICAvLyBzdXBwb3J0IHVuaXggc29ja2V0c1xuICBpZiAoL15odHRwcz9cXCt1bml4Oi8udGVzdCh1cmwucHJvdG9jb2wpID09PSB0cnVlKSB7XG4gICAgLy8gZ2V0IHRoZSBwcm90b2NvbFxuICAgIHVybC5wcm90b2NvbCA9IGAke3VybC5wcm90b2NvbC5zcGxpdCgnKycpWzBdfTpgO1xuXG4gICAgLy8gZ2V0IHRoZSBzb2NrZXQsIHBhdGhcbiAgICBjb25zdCB1bml4UGFydHMgPSB1cmwucGF0aC5tYXRjaCgvXihbXi9dKykoLispJC8pO1xuICAgIG9wdGlvbnMuc29ja2V0UGF0aCA9IHVuaXhQYXJ0c1sxXS5yZXBsYWNlKC8lMkYvZywgJy8nKTtcbiAgICB1cmwucGF0aCA9IHVuaXhQYXJ0c1syXTtcbiAgfVxuXG4gIC8vIE92ZXJyaWRlIElQIGFkZHJlc3Mgb2YgYSBob3N0bmFtZVxuICBpZiAodGhpcy5fY29ubmVjdE92ZXJyaWRlKSB7XG4gICAgY29uc3QgeyBob3N0bmFtZSB9ID0gdXJsO1xuICAgIGNvbnN0IG1hdGNoID1cbiAgICAgIGhvc3RuYW1lIGluIHRoaXMuX2Nvbm5lY3RPdmVycmlkZVxuICAgICAgICA/IHRoaXMuX2Nvbm5lY3RPdmVycmlkZVtob3N0bmFtZV1cbiAgICAgICAgOiB0aGlzLl9jb25uZWN0T3ZlcnJpZGVbJyonXTtcbiAgICBpZiAobWF0Y2gpIHtcbiAgICAgIC8vIGJhY2t1cCB0aGUgcmVhbCBob3N0XG4gICAgICBpZiAoIXRoaXMuX2hlYWRlci5ob3N0KSB7XG4gICAgICAgIHRoaXMuc2V0KCdob3N0JywgdXJsLmhvc3QpO1xuICAgICAgfVxuXG4gICAgICBsZXQgbmV3SG9zdDtcbiAgICAgIGxldCBuZXdQb3J0O1xuXG4gICAgICBpZiAodHlwZW9mIG1hdGNoID09PSAnb2JqZWN0Jykge1xuICAgICAgICBuZXdIb3N0ID0gbWF0Y2guaG9zdDtcbiAgICAgICAgbmV3UG9ydCA9IG1hdGNoLnBvcnQ7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBuZXdIb3N0ID0gbWF0Y2g7XG4gICAgICAgIG5ld1BvcnQgPSB1cmwucG9ydDtcbiAgICAgIH1cblxuICAgICAgLy8gd3JhcCBbaXB2Nl1cbiAgICAgIHVybC5ob3N0ID0gLzovLnRlc3QobmV3SG9zdCkgPyBgWyR7bmV3SG9zdH1dYCA6IG5ld0hvc3Q7XG4gICAgICBpZiAobmV3UG9ydCkge1xuICAgICAgICB1cmwuaG9zdCArPSBgOiR7bmV3UG9ydH1gO1xuICAgICAgICB1cmwucG9ydCA9IG5ld1BvcnQ7XG4gICAgICB9XG5cbiAgICAgIHVybC5ob3N0bmFtZSA9IG5ld0hvc3Q7XG4gICAgfVxuICB9XG5cbiAgLy8gb3B0aW9uc1xuICBvcHRpb25zLm1ldGhvZCA9IHRoaXMubWV0aG9kO1xuICBvcHRpb25zLnBvcnQgPSB1cmwucG9ydDtcbiAgb3B0aW9ucy5wYXRoID0gdXJsLnBhdGg7XG4gIG9wdGlvbnMuaG9zdCA9IHVybC5ob3N0bmFtZTtcbiAgb3B0aW9ucy5jYSA9IHRoaXMuX2NhO1xuICBvcHRpb25zLmtleSA9IHRoaXMuX2tleTtcbiAgb3B0aW9ucy5wZnggPSB0aGlzLl9wZng7XG4gIG9wdGlvbnMuY2VydCA9IHRoaXMuX2NlcnQ7XG4gIG9wdGlvbnMucGFzc3BocmFzZSA9IHRoaXMuX3Bhc3NwaHJhc2U7XG4gIG9wdGlvbnMuYWdlbnQgPSB0aGlzLl9hZ2VudDtcbiAgb3B0aW9ucy5yZWplY3RVbmF1dGhvcml6ZWQgPVxuICAgIHR5cGVvZiB0aGlzLl9kaXNhYmxlVExTQ2VydHMgPT09ICdib29sZWFuJ1xuICAgICAgPyAhdGhpcy5fZGlzYWJsZVRMU0NlcnRzXG4gICAgICA6IHByb2Nlc3MuZW52Lk5PREVfVExTX1JFSkVDVF9VTkFVVEhPUklaRUQgIT09ICcwJztcblxuICAvLyBBbGxvd3MgcmVxdWVzdC5nZXQoJ2h0dHBzOi8vMS4yLjMuNC8nKS5zZXQoJ0hvc3QnLCAnZXhhbXBsZS5jb20nKVxuICBpZiAodGhpcy5faGVhZGVyLmhvc3QpIHtcbiAgICBvcHRpb25zLnNlcnZlcm5hbWUgPSB0aGlzLl9oZWFkZXIuaG9zdC5yZXBsYWNlKC86XFxkKyQvLCAnJyk7XG4gIH1cblxuICBpZiAoXG4gICAgdGhpcy5fdHJ1c3RMb2NhbGhvc3QgJiZcbiAgICAvXig/OmxvY2FsaG9zdHwxMjdcXC4wXFwuMFxcLlxcZCt8KDAqOikrOjAqMSkkLy50ZXN0KHVybC5ob3N0bmFtZSlcbiAgKSB7XG4gICAgb3B0aW9ucy5yZWplY3RVbmF1dGhvcml6ZWQgPSBmYWxzZTtcbiAgfVxuXG4gIC8vIGluaXRpYXRlIHJlcXVlc3RcbiAgY29uc3QgbW9kID0gdGhpcy5fZW5hYmxlSHR0cDJcbiAgICA/IGV4cG9ydHMucHJvdG9jb2xzWydodHRwMjonXS5zZXRQcm90b2NvbCh1cmwucHJvdG9jb2wpXG4gICAgOiBleHBvcnRzLnByb3RvY29sc1t1cmwucHJvdG9jb2xdO1xuXG4gIC8vIHJlcXVlc3RcbiAgdGhpcy5yZXEgPSBtb2QucmVxdWVzdChvcHRpb25zKTtcbiAgY29uc3QgeyByZXEgfSA9IHRoaXM7XG5cbiAgLy8gc2V0IHRjcCBubyBkZWxheVxuICByZXEuc2V0Tm9EZWxheSh0cnVlKTtcblxuICBpZiAob3B0aW9ucy5tZXRob2QgIT09ICdIRUFEJykge1xuICAgIHJlcS5zZXRIZWFkZXIoJ0FjY2VwdC1FbmNvZGluZycsICdnemlwLCBkZWZsYXRlJyk7XG4gIH1cblxuICB0aGlzLnByb3RvY29sID0gdXJsLnByb3RvY29sO1xuICB0aGlzLmhvc3QgPSB1cmwuaG9zdDtcblxuICAvLyBleHBvc2UgZXZlbnRzXG4gIHJlcS5vbmNlKCdkcmFpbicsICgpID0+IHtcbiAgICB0aGlzLmVtaXQoJ2RyYWluJyk7XG4gIH0pO1xuXG4gIHJlcS5vbignZXJyb3InLCAoZXJyb3IpID0+IHtcbiAgICAvLyBmbGFnIGFib3J0aW9uIGhlcmUgZm9yIG91dCB0aW1lb3V0c1xuICAgIC8vIGJlY2F1c2Ugbm9kZSB3aWxsIGVtaXQgYSBmYXV4LWVycm9yIFwic29ja2V0IGhhbmcgdXBcIlxuICAgIC8vIHdoZW4gcmVxdWVzdCBpcyBhYm9ydGVkIGJlZm9yZSBhIGNvbm5lY3Rpb24gaXMgbWFkZVxuICAgIGlmICh0aGlzLl9hYm9ydGVkKSByZXR1cm47XG4gICAgLy8gaWYgbm90IHRoZSBzYW1lLCB3ZSBhcmUgaW4gdGhlICoqb2xkKiogKGNhbmNlbGxlZCkgcmVxdWVzdCxcbiAgICAvLyBzbyBuZWVkIHRvIGNvbnRpbnVlIChzYW1lIGFzIGZvciBhYm92ZSlcbiAgICBpZiAodGhpcy5fcmV0cmllcyAhPT0gcmV0cmllcykgcmV0dXJuO1xuICAgIC8vIGlmIHdlJ3ZlIHJlY2VpdmVkIGEgcmVzcG9uc2UgdGhlbiB3ZSBkb24ndCB3YW50IHRvIGxldFxuICAgIC8vIGFuIGVycm9yIGluIHRoZSByZXF1ZXN0IGJsb3cgdXAgdGhlIHJlc3BvbnNlXG4gICAgaWYgKHRoaXMucmVzcG9uc2UpIHJldHVybjtcbiAgICB0aGlzLmNhbGxiYWNrKGVycm9yKTtcbiAgfSk7XG5cbiAgLy8gYXV0aFxuICBpZiAodXJsLmF1dGgpIHtcbiAgICBjb25zdCBhdXRoID0gdXJsLmF1dGguc3BsaXQoJzonKTtcbiAgICB0aGlzLmF1dGgoYXV0aFswXSwgYXV0aFsxXSk7XG4gIH1cblxuICBpZiAodGhpcy51c2VybmFtZSAmJiB0aGlzLnBhc3N3b3JkKSB7XG4gICAgdGhpcy5hdXRoKHRoaXMudXNlcm5hbWUsIHRoaXMucGFzc3dvcmQpO1xuICB9XG5cbiAgZm9yIChjb25zdCBrZXkgaW4gdGhpcy5oZWFkZXIpIHtcbiAgICBpZiAoaGFzT3duKHRoaXMuaGVhZGVyLCBrZXkpKSByZXEuc2V0SGVhZGVyKGtleSwgdGhpcy5oZWFkZXJba2V5XSk7XG4gIH1cblxuICAvLyBhZGQgY29va2llc1xuICBpZiAodGhpcy5jb29raWVzKSB7XG4gICAgaWYgKGhhc093bih0aGlzLl9oZWFkZXIsICdjb29raWUnKSkge1xuICAgICAgLy8gbWVyZ2VcbiAgICAgIGNvbnN0IHRlbXBvcmFyeUphciA9IG5ldyBDb29raWVKYXIuQ29va2llSmFyKCk7XG4gICAgICB0ZW1wb3JhcnlKYXIuc2V0Q29va2llcyh0aGlzLl9oZWFkZXIuY29va2llLnNwbGl0KCc7JykpO1xuICAgICAgdGVtcG9yYXJ5SmFyLnNldENvb2tpZXModGhpcy5jb29raWVzLnNwbGl0KCc7JykpO1xuICAgICAgcmVxLnNldEhlYWRlcihcbiAgICAgICAgJ0Nvb2tpZScsXG4gICAgICAgIHRlbXBvcmFyeUphci5nZXRDb29raWVzKENvb2tpZUphci5Db29raWVBY2Nlc3NJbmZvLkFsbCkudG9WYWx1ZVN0cmluZygpXG4gICAgICApO1xuICAgIH0gZWxzZSB7XG4gICAgICByZXEuc2V0SGVhZGVyKCdDb29raWUnLCB0aGlzLmNvb2tpZXMpO1xuICAgIH1cbiAgfVxuXG4gIHJldHVybiByZXE7XG59O1xuXG4vKipcbiAqIEludm9rZSB0aGUgY2FsbGJhY2sgd2l0aCBgZXJyYCBhbmQgYHJlc2BcbiAqIGFuZCBoYW5kbGUgYXJpdHkgY2hlY2suXG4gKlxuICogQHBhcmFtIHtFcnJvcn0gZXJyXG4gKiBAcGFyYW0ge1Jlc3BvbnNlfSByZXNcbiAqIEBhcGkgcHJpdmF0ZVxuICovXG5cblJlcXVlc3QucHJvdG90eXBlLmNhbGxiYWNrID0gZnVuY3Rpb24gKGVycm9yLCByZXMpIHtcbiAgaWYgKHRoaXMuX3Nob3VsZFJldHJ5KGVycm9yLCByZXMpKSB7XG4gICAgcmV0dXJuIHRoaXMuX3JldHJ5KCk7XG4gIH1cblxuICAvLyBBdm9pZCB0aGUgZXJyb3Igd2hpY2ggaXMgZW1pdHRlZCBmcm9tICdzb2NrZXQgaGFuZyB1cCcgdG8gY2F1c2UgdGhlIGZuIHVuZGVmaW5lZCBlcnJvciBvbiBKUyBydW50aW1lLlxuICBjb25zdCBmbiA9IHRoaXMuX2NhbGxiYWNrIHx8IG5vb3A7XG4gIHRoaXMuY2xlYXJUaW1lb3V0KCk7XG4gIGlmICh0aGlzLmNhbGxlZCkgcmV0dXJuIGNvbnNvbGUud2Fybignc3VwZXJhZ2VudDogZG91YmxlIGNhbGxiYWNrIGJ1ZycpO1xuICB0aGlzLmNhbGxlZCA9IHRydWU7XG5cbiAgaWYgKCFlcnJvcikge1xuICAgIHRyeSB7XG4gICAgICBpZiAoIXRoaXMuX2lzUmVzcG9uc2VPSyhyZXMpKSB7XG4gICAgICAgIGxldCBtZXNzYWdlID0gJ1Vuc3VjY2Vzc2Z1bCBIVFRQIHJlc3BvbnNlJztcbiAgICAgICAgaWYgKHJlcykge1xuICAgICAgICAgIG1lc3NhZ2UgPSBodHRwLlNUQVRVU19DT0RFU1tyZXMuc3RhdHVzXSB8fCBtZXNzYWdlO1xuICAgICAgICB9XG5cbiAgICAgICAgZXJyb3IgPSBuZXcgRXJyb3IobWVzc2FnZSk7XG4gICAgICAgIGVycm9yLnN0YXR1cyA9IHJlcyA/IHJlcy5zdGF0dXMgOiB1bmRlZmluZWQ7XG4gICAgICB9XG4gICAgfSBjYXRjaCAoZXJyb3JfKSB7XG4gICAgICBlcnJvciA9IGVycm9yXztcbiAgICB9XG4gIH1cblxuICAvLyBJdCdzIGltcG9ydGFudCB0aGF0IHRoZSBjYWxsYmFjayBpcyBjYWxsZWQgb3V0c2lkZSB0cnkvY2F0Y2hcbiAgLy8gdG8gYXZvaWQgZG91YmxlIGNhbGxiYWNrXG4gIGlmICghZXJyb3IpIHtcbiAgICByZXR1cm4gZm4obnVsbCwgcmVzKTtcbiAgfVxuXG4gIGVycm9yLnJlc3BvbnNlID0gcmVzO1xuICBpZiAodGhpcy5fbWF4UmV0cmllcykgZXJyb3IucmV0cmllcyA9IHRoaXMuX3JldHJpZXMgLSAxO1xuXG4gIC8vIG9ubHkgZW1pdCBlcnJvciBldmVudCBpZiB0aGVyZSBpcyBhIGxpc3RlbmVyXG4gIC8vIG90aGVyd2lzZSB3ZSBhc3N1bWUgdGhlIGNhbGxiYWNrIHRvIGAuZW5kKClgIHdpbGwgZ2V0IHRoZSBlcnJvclxuICBpZiAoZXJyb3IgJiYgdGhpcy5saXN0ZW5lcnMoJ2Vycm9yJykubGVuZ3RoID4gMCkge1xuICAgIHRoaXMuZW1pdCgnZXJyb3InLCBlcnJvcik7XG4gIH1cblxuICBmbihlcnJvciwgcmVzKTtcbn07XG5cbi8qKlxuICogQ2hlY2sgaWYgYG9iamAgaXMgYSBob3N0IG9iamVjdCxcbiAqXG4gKiBAcGFyYW0ge09iamVjdH0gb2JqIGhvc3Qgb2JqZWN0XG4gKiBAcmV0dXJuIHtCb29sZWFufSBpcyBhIGhvc3Qgb2JqZWN0XG4gKiBAYXBpIHByaXZhdGVcbiAqL1xuUmVxdWVzdC5wcm90b3R5cGUuX2lzSG9zdCA9IGZ1bmN0aW9uIChvYmplY3QpIHtcbiAgcmV0dXJuIChcbiAgICBCdWZmZXIuaXNCdWZmZXIob2JqZWN0KSB8fFxuICAgIG9iamVjdCBpbnN0YW5jZW9mIFN0cmVhbSB8fFxuICAgIG9iamVjdCBpbnN0YW5jZW9mIEZvcm1EYXRhXG4gICk7XG59O1xuXG4vKipcbiAqIEluaXRpYXRlIHJlcXVlc3QsIGludm9raW5nIGNhbGxiYWNrIGBmbihlcnIsIHJlcylgXG4gKiB3aXRoIGFuIGluc3RhbmNlb2YgYFJlc3BvbnNlYC5cbiAqXG4gKiBAcGFyYW0ge0Z1bmN0aW9ufSBmblxuICogQHJldHVybiB7UmVxdWVzdH0gZm9yIGNoYWluaW5nXG4gKiBAYXBpIHB1YmxpY1xuICovXG5cblJlcXVlc3QucHJvdG90eXBlLl9lbWl0UmVzcG9uc2UgPSBmdW5jdGlvbiAoYm9keSwgZmlsZXMpIHtcbiAgY29uc3QgcmVzcG9uc2UgPSBuZXcgUmVzcG9uc2UodGhpcyk7XG4gIHRoaXMucmVzcG9uc2UgPSByZXNwb25zZTtcbiAgcmVzcG9uc2UucmVkaXJlY3RzID0gdGhpcy5fcmVkaXJlY3RMaXN0O1xuICBpZiAodW5kZWZpbmVkICE9PSBib2R5KSB7XG4gICAgcmVzcG9uc2UuYm9keSA9IGJvZHk7XG4gIH1cblxuICByZXNwb25zZS5maWxlcyA9IGZpbGVzO1xuICBpZiAodGhpcy5fZW5kQ2FsbGVkKSB7XG4gICAgcmVzcG9uc2UucGlwZSA9IGZ1bmN0aW9uICgpIHtcbiAgICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgICAgXCJlbmQoKSBoYXMgYWxyZWFkeSBiZWVuIGNhbGxlZCwgc28gaXQncyB0b28gbGF0ZSB0byBzdGFydCBwaXBpbmdcIlxuICAgICAgKTtcbiAgICB9O1xuICB9XG5cbiAgdGhpcy5lbWl0KCdyZXNwb25zZScsIHJlc3BvbnNlKTtcbiAgcmV0dXJuIHJlc3BvbnNlO1xufTtcblxuUmVxdWVzdC5wcm90b3R5cGUuZW5kID0gZnVuY3Rpb24gKGZuKSB7XG4gIHRoaXMucmVxdWVzdCgpO1xuICBkZWJ1ZygnJXMgJXMnLCB0aGlzLm1ldGhvZCwgdGhpcy51cmwpO1xuXG4gIGlmICh0aGlzLl9lbmRDYWxsZWQpIHtcbiAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAnLmVuZCgpIHdhcyBjYWxsZWQgdHdpY2UuIFRoaXMgaXMgbm90IHN1cHBvcnRlZCBpbiBzdXBlcmFnZW50J1xuICAgICk7XG4gIH1cblxuICB0aGlzLl9lbmRDYWxsZWQgPSB0cnVlO1xuXG4gIC8vIHN0b3JlIGNhbGxiYWNrXG4gIHRoaXMuX2NhbGxiYWNrID0gZm4gfHwgbm9vcDtcblxuICB0aGlzLl9lbmQoKTtcbn07XG5cblJlcXVlc3QucHJvdG90eXBlLl9lbmQgPSBmdW5jdGlvbiAoKSB7XG4gIGlmICh0aGlzLl9hYm9ydGVkKVxuICAgIHJldHVybiB0aGlzLmNhbGxiYWNrKFxuICAgICAgbmV3IEVycm9yKCdUaGUgcmVxdWVzdCBoYXMgYmVlbiBhYm9ydGVkIGV2ZW4gYmVmb3JlIC5lbmQoKSB3YXMgY2FsbGVkJylcbiAgICApO1xuXG4gIGxldCBkYXRhID0gdGhpcy5fZGF0YTtcbiAgY29uc3QgeyByZXEgfSA9IHRoaXM7XG4gIGNvbnN0IHsgbWV0aG9kIH0gPSB0aGlzO1xuXG4gIHRoaXMuX3NldFRpbWVvdXRzKCk7XG5cbiAgLy8gYm9keVxuICBpZiAobWV0aG9kICE9PSAnSEVBRCcgJiYgIXJlcS5faGVhZGVyU2VudCkge1xuICAgIC8vIHNlcmlhbGl6ZSBzdHVmZlxuICAgIGlmICh0eXBlb2YgZGF0YSAhPT0gJ3N0cmluZycpIHtcbiAgICAgIGxldCBjb250ZW50VHlwZSA9IHJlcS5nZXRIZWFkZXIoJ0NvbnRlbnQtVHlwZScpO1xuICAgICAgLy8gUGFyc2Ugb3V0IGp1c3QgdGhlIGNvbnRlbnQgdHlwZSBmcm9tIHRoZSBoZWFkZXIgKGlnbm9yZSB0aGUgY2hhcnNldClcbiAgICAgIGlmIChjb250ZW50VHlwZSkgY29udGVudFR5cGUgPSBjb250ZW50VHlwZS5zcGxpdCgnOycpWzBdO1xuICAgICAgbGV0IHNlcmlhbGl6ZSA9IHRoaXMuX3NlcmlhbGl6ZXIgfHwgZXhwb3J0cy5zZXJpYWxpemVbY29udGVudFR5cGVdO1xuICAgICAgaWYgKCFzZXJpYWxpemUgJiYgaXNKU09OKGNvbnRlbnRUeXBlKSkge1xuICAgICAgICBzZXJpYWxpemUgPSBleHBvcnRzLnNlcmlhbGl6ZVsnYXBwbGljYXRpb24vanNvbiddO1xuICAgICAgfVxuXG4gICAgICBpZiAoc2VyaWFsaXplKSBkYXRhID0gc2VyaWFsaXplKGRhdGEpO1xuICAgIH1cblxuICAgIC8vIGNvbnRlbnQtbGVuZ3RoXG4gICAgaWYgKGRhdGEgJiYgIXJlcS5nZXRIZWFkZXIoJ0NvbnRlbnQtTGVuZ3RoJykpIHtcbiAgICAgIHJlcS5zZXRIZWFkZXIoXG4gICAgICAgICdDb250ZW50LUxlbmd0aCcsXG4gICAgICAgIEJ1ZmZlci5pc0J1ZmZlcihkYXRhKSA/IGRhdGEubGVuZ3RoIDogQnVmZmVyLmJ5dGVMZW5ndGgoZGF0YSlcbiAgICAgICk7XG4gICAgfVxuICB9XG5cbiAgLy8gcmVzcG9uc2VcbiAgLy8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lIGNvbXBsZXhpdHlcbiAgcmVxLm9uY2UoJ3Jlc3BvbnNlJywgKHJlcykgPT4ge1xuICAgIGRlYnVnKCclcyAlcyAtPiAlcycsIHRoaXMubWV0aG9kLCB0aGlzLnVybCwgcmVzLnN0YXR1c0NvZGUpO1xuXG4gICAgaWYgKHRoaXMuX3Jlc3BvbnNlVGltZW91dFRpbWVyKSB7XG4gICAgICBjbGVhclRpbWVvdXQodGhpcy5fcmVzcG9uc2VUaW1lb3V0VGltZXIpO1xuICAgIH1cblxuICAgIGlmICh0aGlzLnBpcGVkKSB7XG4gICAgICByZXR1cm47XG4gICAgfVxuXG4gICAgY29uc3QgbWF4ID0gdGhpcy5fbWF4UmVkaXJlY3RzO1xuICAgIGNvbnN0IG1pbWUgPSB1dGlscy50eXBlKHJlcy5oZWFkZXJzWydjb250ZW50LXR5cGUnXSB8fCAnJykgfHwgJ3RleHQvcGxhaW4nO1xuICAgIGxldCB0eXBlID0gbWltZS5zcGxpdCgnLycpWzBdO1xuICAgIGlmICh0eXBlKSB0eXBlID0gdHlwZS50b0xvd2VyQ2FzZSgpLnRyaW0oKTtcbiAgICBjb25zdCBtdWx0aXBhcnQgPSB0eXBlID09PSAnbXVsdGlwYXJ0JztcbiAgICBjb25zdCByZWRpcmVjdCA9IGlzUmVkaXJlY3QocmVzLnN0YXR1c0NvZGUpO1xuICAgIGNvbnN0IHJlc3BvbnNlVHlwZSA9IHRoaXMuX3Jlc3BvbnNlVHlwZTtcblxuICAgIHRoaXMucmVzID0gcmVzO1xuXG4gICAgLy8gcmVkaXJlY3RcbiAgICBpZiAocmVkaXJlY3QgJiYgdGhpcy5fcmVkaXJlY3RzKysgIT09IG1heCkge1xuICAgICAgcmV0dXJuIHRoaXMuX3JlZGlyZWN0KHJlcyk7XG4gICAgfVxuXG4gICAgaWYgKHRoaXMubWV0aG9kID09PSAnSEVBRCcpIHtcbiAgICAgIHRoaXMuZW1pdCgnZW5kJyk7XG4gICAgICB0aGlzLmNhbGxiYWNrKG51bGwsIHRoaXMuX2VtaXRSZXNwb25zZSgpKTtcbiAgICAgIHJldHVybjtcbiAgICB9XG5cbiAgICAvLyB6bGliIHN1cHBvcnRcbiAgICBpZiAodGhpcy5fc2hvdWxkVW56aXAocmVzKSkge1xuICAgICAgdW56aXAocmVxLCByZXMpO1xuICAgIH1cblxuICAgIGxldCBidWZmZXIgPSB0aGlzLl9idWZmZXI7XG4gICAgaWYgKGJ1ZmZlciA9PT0gdW5kZWZpbmVkICYmIG1pbWUgaW4gZXhwb3J0cy5idWZmZXIpIHtcbiAgICAgIGJ1ZmZlciA9IEJvb2xlYW4oZXhwb3J0cy5idWZmZXJbbWltZV0pO1xuICAgIH1cblxuICAgIGxldCBwYXJzZXIgPSB0aGlzLl9wYXJzZXI7XG4gICAgaWYgKHVuZGVmaW5lZCA9PT0gYnVmZmVyICYmIHBhcnNlcikge1xuICAgICAgY29uc29sZS53YXJuKFxuICAgICAgICBcIkEgY3VzdG9tIHN1cGVyYWdlbnQgcGFyc2VyIGhhcyBiZWVuIHNldCwgYnV0IGJ1ZmZlcmluZyBzdHJhdGVneSBmb3IgdGhlIHBhcnNlciBoYXNuJ3QgYmVlbiBjb25maWd1cmVkLiBDYWxsIGByZXEuYnVmZmVyKHRydWUgb3IgZmFsc2UpYCBvciBzZXQgYHN1cGVyYWdlbnQuYnVmZmVyW21pbWVdID0gdHJ1ZSBvciBmYWxzZWBcIlxuICAgICAgKTtcbiAgICAgIGJ1ZmZlciA9IHRydWU7XG4gICAgfVxuXG4gICAgaWYgKCFwYXJzZXIpIHtcbiAgICAgIGlmIChyZXNwb25zZVR5cGUpIHtcbiAgICAgICAgcGFyc2VyID0gZXhwb3J0cy5wYXJzZS5pbWFnZTsgLy8gSXQncyBhY3R1YWxseSBhIGdlbmVyaWMgQnVmZmVyXG4gICAgICAgIGJ1ZmZlciA9IHRydWU7XG4gICAgICB9IGVsc2UgaWYgKG11bHRpcGFydCkge1xuICAgICAgICBjb25zdCBmb3JtID0gZm9ybWlkYWJsZSgpO1xuICAgICAgICBwYXJzZXIgPSBmb3JtLnBhcnNlLmJpbmQoZm9ybSk7XG4gICAgICAgIGJ1ZmZlciA9IHRydWU7XG4gICAgICB9IGVsc2UgaWYgKGlzSW1hZ2VPclZpZGVvKG1pbWUpKSB7XG4gICAgICAgIHBhcnNlciA9IGV4cG9ydHMucGFyc2UuaW1hZ2U7XG4gICAgICAgIGJ1ZmZlciA9IHRydWU7IC8vIEZvciBiYWNrd2FyZHMtY29tcGF0aWJpbGl0eSBidWZmZXJpbmcgZGVmYXVsdCBpcyBhZC1ob2MgTUlNRS1kZXBlbmRlbnRcbiAgICAgIH0gZWxzZSBpZiAoZXhwb3J0cy5wYXJzZVttaW1lXSkge1xuICAgICAgICBwYXJzZXIgPSBleHBvcnRzLnBhcnNlW21pbWVdO1xuICAgICAgfSBlbHNlIGlmICh0eXBlID09PSAndGV4dCcpIHtcbiAgICAgICAgcGFyc2VyID0gZXhwb3J0cy5wYXJzZS50ZXh0O1xuICAgICAgICBidWZmZXIgPSBidWZmZXIgIT09IGZhbHNlO1xuXG4gICAgICAgIC8vIGV2ZXJ5b25lIHdhbnRzIHRoZWlyIG93biB3aGl0ZS1sYWJlbGVkIGpzb25cbiAgICAgIH0gZWxzZSBpZiAoaXNKU09OKG1pbWUpKSB7XG4gICAgICAgIHBhcnNlciA9IGV4cG9ydHMucGFyc2VbJ2FwcGxpY2F0aW9uL2pzb24nXTtcbiAgICAgICAgYnVmZmVyID0gYnVmZmVyICE9PSBmYWxzZTtcbiAgICAgIH0gZWxzZSBpZiAoYnVmZmVyKSB7XG4gICAgICAgIHBhcnNlciA9IGV4cG9ydHMucGFyc2UudGV4dDtcbiAgICAgIH0gZWxzZSBpZiAodW5kZWZpbmVkID09PSBidWZmZXIpIHtcbiAgICAgICAgcGFyc2VyID0gZXhwb3J0cy5wYXJzZS5pbWFnZTsgLy8gSXQncyBhY3R1YWxseSBhIGdlbmVyaWMgQnVmZmVyXG4gICAgICAgIGJ1ZmZlciA9IHRydWU7XG4gICAgICB9XG4gICAgfVxuXG4gICAgLy8gYnkgZGVmYXVsdCBvbmx5IGJ1ZmZlciB0ZXh0LyosIGpzb24gYW5kIG1lc3NlZCB1cCB0aGluZyBmcm9tIGhlbGxcbiAgICBpZiAoKHVuZGVmaW5lZCA9PT0gYnVmZmVyICYmIGlzVGV4dChtaW1lKSkgfHwgaXNKU09OKG1pbWUpKSB7XG4gICAgICBidWZmZXIgPSB0cnVlO1xuICAgIH1cblxuICAgIHRoaXMuX3Jlc0J1ZmZlcmVkID0gYnVmZmVyO1xuICAgIGxldCBwYXJzZXJIYW5kbGVzRW5kID0gZmFsc2U7XG4gICAgaWYgKGJ1ZmZlcikge1xuICAgICAgLy8gUHJvdGVjdGlvbmEgYWdhaW5zdCB6aXAgYm9tYnMgYW5kIG90aGVyIG51aXNhbmNlXG4gICAgICBsZXQgcmVzcG9uc2VCeXRlc0xlZnQgPSB0aGlzLl9tYXhSZXNwb25zZVNpemUgfHwgMjAwXzAwMF8wMDA7XG4gICAgICByZXMub24oJ2RhdGEnLCAoYnVmKSA9PiB7XG4gICAgICAgIHJlc3BvbnNlQnl0ZXNMZWZ0IC09IGJ1Zi5ieXRlTGVuZ3RoIHx8IGJ1Zi5sZW5ndGggPiAwID8gYnVmLmxlbmd0aCA6IDA7XG4gICAgICAgIGlmIChyZXNwb25zZUJ5dGVzTGVmdCA8IDApIHtcbiAgICAgICAgICAvLyBUaGlzIHdpbGwgcHJvcGFnYXRlIHRocm91Z2ggZXJyb3IgZXZlbnRcbiAgICAgICAgICBjb25zdCBlcnJvciA9IG5ldyBFcnJvcignTWF4aW11bSByZXNwb25zZSBzaXplIHJlYWNoZWQnKTtcbiAgICAgICAgICBlcnJvci5jb2RlID0gJ0VUT09MQVJHRSc7XG4gICAgICAgICAgLy8gUGFyc2VycyBhcmVuJ3QgcmVxdWlyZWQgdG8gb2JzZXJ2ZSBlcnJvciBldmVudCxcbiAgICAgICAgICAvLyBzbyB3b3VsZCBpbmNvcnJlY3RseSByZXBvcnQgc3VjY2Vzc1xuICAgICAgICAgIHBhcnNlckhhbmRsZXNFbmQgPSBmYWxzZTtcbiAgICAgICAgICAvLyBXaWxsIG5vdCBlbWl0IGVycm9yIGV2ZW50XG4gICAgICAgICAgcmVzLmRlc3Ryb3koZXJyb3IpO1xuICAgICAgICAgIC8vIHNvIHdlIGRvIGNhbGxiYWNrIG5vd1xuICAgICAgICAgIHRoaXMuY2FsbGJhY2soZXJyb3IsIG51bGwpO1xuICAgICAgICB9XG4gICAgICB9KTtcbiAgICB9XG5cbiAgICBpZiAocGFyc2VyKSB7XG4gICAgICB0cnkge1xuICAgICAgICAvLyBVbmJ1ZmZlcmVkIHBhcnNlcnMgYXJlIHN1cHBvc2VkIHRvIGVtaXQgcmVzcG9uc2UgZWFybHksXG4gICAgICAgIC8vIHdoaWNoIGlzIHdlaXJkIEJUVywgYmVjYXVzZSByZXNwb25zZS5ib2R5IHdvbid0IGJlIHRoZXJlLlxuICAgICAgICBwYXJzZXJIYW5kbGVzRW5kID0gYnVmZmVyO1xuXG4gICAgICAgIHBhcnNlcihyZXMsIChlcnJvciwgb2JqZWN0LCBmaWxlcykgPT4ge1xuICAgICAgICAgIGlmICh0aGlzLnRpbWVkb3V0KSB7XG4gICAgICAgICAgICAvLyBUaW1lb3V0IGhhcyBhbHJlYWR5IGhhbmRsZWQgYWxsIGNhbGxiYWNrc1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgIH1cblxuICAgICAgICAgIC8vIEludGVudGlvbmFsIChub24tdGltZW91dCkgYWJvcnQgaXMgc3VwcG9zZWQgdG8gcHJlc2VydmUgcGFydGlhbCByZXNwb25zZSxcbiAgICAgICAgICAvLyBldmVuIGlmIGl0IGRvZXNuJ3QgcGFyc2UuXG4gICAgICAgICAgaWYgKGVycm9yICYmICF0aGlzLl9hYm9ydGVkKSB7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5jYWxsYmFjayhlcnJvcik7XG4gICAgICAgICAgfVxuXG4gICAgICAgICAgaWYgKHBhcnNlckhhbmRsZXNFbmQpIHtcbiAgICAgICAgICAgIHRoaXMuZW1pdCgnZW5kJyk7XG4gICAgICAgICAgICB0aGlzLmNhbGxiYWNrKG51bGwsIHRoaXMuX2VtaXRSZXNwb25zZShvYmplY3QsIGZpbGVzKSk7XG4gICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgIH0gY2F0Y2ggKGVycikge1xuICAgICAgICB0aGlzLmNhbGxiYWNrKGVycik7XG4gICAgICAgIHJldHVybjtcbiAgICAgIH1cbiAgICB9XG5cbiAgICB0aGlzLnJlcyA9IHJlcztcblxuICAgIC8vIHVuYnVmZmVyZWRcbiAgICBpZiAoIWJ1ZmZlcikge1xuICAgICAgZGVidWcoJ3VuYnVmZmVyZWQgJXMgJXMnLCB0aGlzLm1ldGhvZCwgdGhpcy51cmwpO1xuICAgICAgdGhpcy5jYWxsYmFjayhudWxsLCB0aGlzLl9lbWl0UmVzcG9uc2UoKSk7XG4gICAgICBpZiAobXVsdGlwYXJ0KSByZXR1cm47IC8vIGFsbG93IG11bHRpcGFydCB0byBoYW5kbGUgZW5kIGV2ZW50XG4gICAgICByZXMub25jZSgnZW5kJywgKCkgPT4ge1xuICAgICAgICBkZWJ1ZygnZW5kICVzICVzJywgdGhpcy5tZXRob2QsIHRoaXMudXJsKTtcbiAgICAgICAgdGhpcy5lbWl0KCdlbmQnKTtcbiAgICAgIH0pO1xuICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIC8vIHRlcm1pbmF0aW5nIGV2ZW50c1xuICAgIHJlcy5vbmNlKCdlcnJvcicsIChlcnJvcikgPT4ge1xuICAgICAgcGFyc2VySGFuZGxlc0VuZCA9IGZhbHNlO1xuICAgICAgdGhpcy5jYWxsYmFjayhlcnJvciwgbnVsbCk7XG4gICAgfSk7XG4gICAgaWYgKCFwYXJzZXJIYW5kbGVzRW5kKVxuICAgICAgcmVzLm9uY2UoJ2VuZCcsICgpID0+IHtcbiAgICAgICAgZGVidWcoJ2VuZCAlcyAlcycsIHRoaXMubWV0aG9kLCB0aGlzLnVybCk7XG4gICAgICAgIC8vIFRPRE86IHVubGVzcyBidWZmZXJpbmcgZW1pdCBlYXJsaWVyIHRvIHN0cmVhbVxuICAgICAgICB0aGlzLmVtaXQoJ2VuZCcpO1xuICAgICAgICB0aGlzLmNhbGxiYWNrKG51bGwsIHRoaXMuX2VtaXRSZXNwb25zZSgpKTtcbiAgICAgIH0pO1xuICB9KTtcblxuICB0aGlzLmVtaXQoJ3JlcXVlc3QnLCB0aGlzKTtcblxuICBjb25zdCBnZXRQcm9ncmVzc01vbml0b3IgPSAoKSA9PiB7XG4gICAgY29uc3QgbGVuZ3RoQ29tcHV0YWJsZSA9IHRydWU7XG4gICAgY29uc3QgdG90YWwgPSByZXEuZ2V0SGVhZGVyKCdDb250ZW50LUxlbmd0aCcpO1xuICAgIGxldCBsb2FkZWQgPSAwO1xuXG4gICAgY29uc3QgcHJvZ3Jlc3MgPSBuZXcgU3RyZWFtLlRyYW5zZm9ybSgpO1xuICAgIHByb2dyZXNzLl90cmFuc2Zvcm0gPSAoY2h1bmssIGVuY29kaW5nLCBjYikgPT4ge1xuICAgICAgbG9hZGVkICs9IGNodW5rLmxlbmd0aDtcbiAgICAgIHRoaXMuZW1pdCgncHJvZ3Jlc3MnLCB7XG4gICAgICAgIGRpcmVjdGlvbjogJ3VwbG9hZCcsXG4gICAgICAgIGxlbmd0aENvbXB1dGFibGUsXG4gICAgICAgIGxvYWRlZCxcbiAgICAgICAgdG90YWxcbiAgICAgIH0pO1xuICAgICAgY2IobnVsbCwgY2h1bmspO1xuICAgIH07XG5cbiAgICByZXR1cm4gcHJvZ3Jlc3M7XG4gIH07XG5cbiAgY29uc3QgYnVmZmVyVG9DaHVua3MgPSAoYnVmZmVyKSA9PiB7XG4gICAgY29uc3QgY2h1bmtTaXplID0gMTYgKiAxMDI0OyAvLyBkZWZhdWx0IGhpZ2hXYXRlck1hcmsgdmFsdWVcbiAgICBjb25zdCBjaHVua2luZyA9IG5ldyBTdHJlYW0uUmVhZGFibGUoKTtcbiAgICBjb25zdCB0b3RhbExlbmd0aCA9IGJ1ZmZlci5sZW5ndGg7XG4gICAgY29uc3QgcmVtYWluZGVyID0gdG90YWxMZW5ndGggJSBjaHVua1NpemU7XG4gICAgY29uc3QgY3V0b2ZmID0gdG90YWxMZW5ndGggLSByZW1haW5kZXI7XG5cbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IGN1dG9mZjsgaSArPSBjaHVua1NpemUpIHtcbiAgICAgIGNvbnN0IGNodW5rID0gYnVmZmVyLnNsaWNlKGksIGkgKyBjaHVua1NpemUpO1xuICAgICAgY2h1bmtpbmcucHVzaChjaHVuayk7XG4gICAgfVxuXG4gICAgaWYgKHJlbWFpbmRlciA+IDApIHtcbiAgICAgIGNvbnN0IHJlbWFpbmRlckJ1ZmZlciA9IGJ1ZmZlci5zbGljZSgtcmVtYWluZGVyKTtcbiAgICAgIGNodW5raW5nLnB1c2gocmVtYWluZGVyQnVmZmVyKTtcbiAgICB9XG5cbiAgICBjaHVua2luZy5wdXNoKG51bGwpOyAvLyBubyBtb3JlIGRhdGFcblxuICAgIHJldHVybiBjaHVua2luZztcbiAgfTtcblxuICAvLyBpZiBhIEZvcm1EYXRhIGluc3RhbmNlIGdvdCBjcmVhdGVkLCB0aGVuIHdlIHNlbmQgdGhhdCBhcyB0aGUgcmVxdWVzdCBib2R5XG4gIGNvbnN0IGZvcm1EYXRhID0gdGhpcy5fZm9ybURhdGE7XG4gIGlmIChmb3JtRGF0YSkge1xuICAgIC8vIHNldCBoZWFkZXJzXG4gICAgY29uc3QgaGVhZGVycyA9IGZvcm1EYXRhLmdldEhlYWRlcnMoKTtcbiAgICBmb3IgKGNvbnN0IGkgaW4gaGVhZGVycykge1xuICAgICAgaWYgKGhhc093bihoZWFkZXJzLCBpKSkge1xuICAgICAgICBkZWJ1Zygnc2V0dGluZyBGb3JtRGF0YSBoZWFkZXI6IFwiJXM6ICVzXCInLCBpLCBoZWFkZXJzW2ldKTtcbiAgICAgICAgcmVxLnNldEhlYWRlcihpLCBoZWFkZXJzW2ldKTtcbiAgICAgIH1cbiAgICB9XG5cbiAgICAvLyBhdHRlbXB0IHRvIGdldCBcIkNvbnRlbnQtTGVuZ3RoXCIgaGVhZGVyXG4gICAgZm9ybURhdGEuZ2V0TGVuZ3RoKChlcnJvciwgbGVuZ3RoKSA9PiB7XG4gICAgICAvLyBUT0RPOiBBZGQgY2h1bmtlZCBlbmNvZGluZyB3aGVuIG5vIGxlbmd0aCAoaWYgZXJyKVxuICAgICAgaWYgKGVycm9yKSBkZWJ1ZygnZm9ybURhdGEuZ2V0TGVuZ3RoIGhhZCBlcnJvcicsIGVycm9yLCBsZW5ndGgpO1xuXG4gICAgICBkZWJ1ZygnZ290IEZvcm1EYXRhIENvbnRlbnQtTGVuZ3RoOiAlcycsIGxlbmd0aCk7XG4gICAgICBpZiAodHlwZW9mIGxlbmd0aCA9PT0gJ251bWJlcicpIHtcbiAgICAgICAgcmVxLnNldEhlYWRlcignQ29udGVudC1MZW5ndGgnLCBsZW5ndGgpO1xuICAgICAgfVxuXG4gICAgICBmb3JtRGF0YS5waXBlKGdldFByb2dyZXNzTW9uaXRvcigpKS5waXBlKHJlcSk7XG4gICAgfSk7XG4gIH0gZWxzZSBpZiAoQnVmZmVyLmlzQnVmZmVyKGRhdGEpKSB7XG4gICAgYnVmZmVyVG9DaHVua3MoZGF0YSkucGlwZShnZXRQcm9ncmVzc01vbml0b3IoKSkucGlwZShyZXEpO1xuICB9IGVsc2Uge1xuICAgIHJlcS5lbmQoZGF0YSk7XG4gIH1cbn07XG5cbi8vIENoZWNrIHdoZXRoZXIgcmVzcG9uc2UgaGFzIGEgbm9uLTAtc2l6ZWQgZ3ppcC1lbmNvZGVkIGJvZHlcblJlcXVlc3QucHJvdG90eXBlLl9zaG91bGRVbnppcCA9IChyZXMpID0+IHtcbiAgaWYgKHJlcy5zdGF0dXNDb2RlID09PSAyMDQgfHwgcmVzLnN0YXR1c0NvZGUgPT09IDMwNCkge1xuICAgIC8vIFRoZXNlIGFyZW4ndCBzdXBwb3NlZCB0byBoYXZlIGFueSBib2R5XG4gICAgcmV0dXJuIGZhbHNlO1xuICB9XG5cbiAgLy8gaGVhZGVyIGNvbnRlbnQgaXMgYSBzdHJpbmcsIGFuZCBkaXN0aW5jdGlvbiBiZXR3ZWVuIDAgYW5kIG5vIGluZm9ybWF0aW9uIGlzIGNydWNpYWxcbiAgaWYgKHJlcy5oZWFkZXJzWydjb250ZW50LWxlbmd0aCddID09PSAnMCcpIHtcbiAgICAvLyBXZSBrbm93IHRoYXQgdGhlIGJvZHkgaXMgZW1wdHkgKHVuZm9ydHVuYXRlbHksIHRoaXMgY2hlY2sgZG9lcyBub3QgY292ZXIgY2h1bmtlZCBlbmNvZGluZylcbiAgICByZXR1cm4gZmFsc2U7XG4gIH1cblxuICAvLyBjb25zb2xlLmxvZyhyZXMpO1xuICByZXR1cm4gL15cXHMqKD86ZGVmbGF0ZXxnemlwKVxccyokLy50ZXN0KHJlcy5oZWFkZXJzWydjb250ZW50LWVuY29kaW5nJ10pO1xufTtcblxuLyoqXG4gKiBPdmVycmlkZXMgRE5TIGZvciBzZWxlY3RlZCBob3N0bmFtZXMuIFRha2VzIG9iamVjdCBtYXBwaW5nIGhvc3RuYW1lcyB0byBJUCBhZGRyZXNzZXMuXG4gKlxuICogV2hlbiBtYWtpbmcgYSByZXF1ZXN0IHRvIGEgVVJMIHdpdGggYSBob3N0bmFtZSBleGFjdGx5IG1hdGNoaW5nIGEga2V5IGluIHRoZSBvYmplY3QsXG4gKiB1c2UgdGhlIGdpdmVuIElQIGFkZHJlc3MgdG8gY29ubmVjdCwgaW5zdGVhZCBvZiB1c2luZyBETlMgdG8gcmVzb2x2ZSB0aGUgaG9zdG5hbWUuXG4gKlxuICogQSBzcGVjaWFsIGhvc3QgYCpgIG1hdGNoZXMgZXZlcnkgaG9zdG5hbWUgKGtlZXAgcmVkaXJlY3RzIGluIG1pbmQhKVxuICpcbiAqICAgICAgcmVxdWVzdC5jb25uZWN0KHtcbiAqICAgICAgICAndGVzdC5leGFtcGxlLmNvbSc6ICcxMjcuMC4wLjEnLFxuICogICAgICAgICdpcHY2LmV4YW1wbGUuY29tJzogJzo6MScsXG4gKiAgICAgIH0pXG4gKi9cblJlcXVlc3QucHJvdG90eXBlLmNvbm5lY3QgPSBmdW5jdGlvbiAoY29ubmVjdE92ZXJyaWRlKSB7XG4gIGlmICh0eXBlb2YgY29ubmVjdE92ZXJyaWRlID09PSAnc3RyaW5nJykge1xuICAgIHRoaXMuX2Nvbm5lY3RPdmVycmlkZSA9IHsgJyonOiBjb25uZWN0T3ZlcnJpZGUgfTtcbiAgfSBlbHNlIGlmICh0eXBlb2YgY29ubmVjdE92ZXJyaWRlID09PSAnb2JqZWN0Jykge1xuICAgIHRoaXMuX2Nvbm5lY3RPdmVycmlkZSA9IGNvbm5lY3RPdmVycmlkZTtcbiAgfSBlbHNlIHtcbiAgICB0aGlzLl9jb25uZWN0T3ZlcnJpZGUgPSB1bmRlZmluZWQ7XG4gIH1cblxuICByZXR1cm4gdGhpcztcbn07XG5cblJlcXVlc3QucHJvdG90eXBlLnRydXN0TG9jYWxob3N0ID0gZnVuY3Rpb24gKHRvZ2dsZSkge1xuICB0aGlzLl90cnVzdExvY2FsaG9zdCA9IHRvZ2dsZSA9PT0gdW5kZWZpbmVkID8gdHJ1ZSA6IHRvZ2dsZTtcbiAgcmV0dXJuIHRoaXM7XG59O1xuXG4vLyBnZW5lcmF0ZSBIVFRQIHZlcmIgbWV0aG9kc1xuaWYgKCFtZXRob2RzLmluY2x1ZGVzKCdkZWwnKSkge1xuICAvLyBjcmVhdGUgYSBjb3B5IHNvIHdlIGRvbid0IGNhdXNlIGNvbmZsaWN0cyB3aXRoXG4gIC8vIG90aGVyIHBhY2thZ2VzIHVzaW5nIHRoZSBtZXRob2RzIHBhY2thZ2UgYW5kXG4gIC8vIG5wbSAzLnhcbiAgbWV0aG9kcyA9IFsuLi5tZXRob2RzXTtcbiAgbWV0aG9kcy5wdXNoKCdkZWwnKTtcbn1cblxuZm9yIChsZXQgbWV0aG9kIG9mIG1ldGhvZHMpIHtcbiAgY29uc3QgbmFtZSA9IG1ldGhvZDtcbiAgbWV0aG9kID0gbWV0aG9kID09PSAnZGVsJyA/ICdkZWxldGUnIDogbWV0aG9kO1xuXG4gIG1ldGhvZCA9IG1ldGhvZC50b1VwcGVyQ2FzZSgpO1xuICByZXF1ZXN0W25hbWVdID0gKHVybCwgZGF0YSwgZm4pID0+IHtcbiAgICBjb25zdCByZXF1ZXN0XyA9IHJlcXVlc3QobWV0aG9kLCB1cmwpO1xuICAgIGlmICh0eXBlb2YgZGF0YSA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgZm4gPSBkYXRhO1xuICAgICAgZGF0YSA9IG51bGw7XG4gICAgfVxuXG4gICAgaWYgKGRhdGEpIHtcbiAgICAgIGlmIChtZXRob2QgPT09ICdHRVQnIHx8IG1ldGhvZCA9PT0gJ0hFQUQnKSB7XG4gICAgICAgIHJlcXVlc3RfLnF1ZXJ5KGRhdGEpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgcmVxdWVzdF8uc2VuZChkYXRhKTtcbiAgICAgIH1cbiAgICB9XG5cbiAgICBpZiAoZm4pIHJlcXVlc3RfLmVuZChmbik7XG4gICAgcmV0dXJuIHJlcXVlc3RfO1xuICB9O1xufVxuXG4vKipcbiAqIENoZWNrIGlmIGBtaW1lYCBpcyB0ZXh0IGFuZCBzaG91bGQgYmUgYnVmZmVyZWQuXG4gKlxuICogQHBhcmFtIHtTdHJpbmd9IG1pbWVcbiAqIEByZXR1cm4ge0Jvb2xlYW59XG4gKiBAYXBpIHB1YmxpY1xuICovXG5cbmZ1bmN0aW9uIGlzVGV4dChtaW1lKSB7XG4gIGNvbnN0IHBhcnRzID0gbWltZS5zcGxpdCgnLycpO1xuICBsZXQgdHlwZSA9IHBhcnRzWzBdO1xuICBpZiAodHlwZSkgdHlwZSA9IHR5cGUudG9Mb3dlckNhc2UoKS50cmltKCk7XG4gIGxldCBzdWJ0eXBlID0gcGFydHNbMV07XG4gIGlmIChzdWJ0eXBlKSBzdWJ0eXBlID0gc3VidHlwZS50b0xvd2VyQ2FzZSgpLnRyaW0oKTtcblxuICByZXR1cm4gdHlwZSA9PT0gJ3RleHQnIHx8IHN1YnR5cGUgPT09ICd4LXd3dy1mb3JtLXVybGVuY29kZWQnO1xufVxuXG5mdW5jdGlvbiBpc0ltYWdlT3JWaWRlbyhtaW1lKSB7XG4gIGxldCB0eXBlID0gbWltZS5zcGxpdCgnLycpWzBdO1xuICBpZiAodHlwZSkgdHlwZSA9IHR5cGUudG9Mb3dlckNhc2UoKS50cmltKCk7XG5cbiAgcmV0dXJuIHR5cGUgPT09ICdpbWFnZScgfHwgdHlwZSA9PT0gJ3ZpZGVvJztcbn1cblxuLyoqXG4gKiBDaGVjayBpZiBgbWltZWAgaXMganNvbiBvciBoYXMgK2pzb24gc3RydWN0dXJlZCBzeW50YXggc3VmZml4LlxuICpcbiAqIEBwYXJhbSB7U3RyaW5nfSBtaW1lXG4gKiBAcmV0dXJuIHtCb29sZWFufVxuICogQGFwaSBwcml2YXRlXG4gKi9cblxuZnVuY3Rpb24gaXNKU09OKG1pbWUpIHtcbiAgLy8gc2hvdWxkIG1hdGNoIC9qc29uIG9yICtqc29uXG4gIC8vIGJ1dCBub3QgL2pzb24tc2VxXG4gIHJldHVybiAvWy8rXWpzb24oJHxbXi1cXHddKS9pLnRlc3QobWltZSk7XG59XG5cbi8qKlxuICogQ2hlY2sgaWYgd2Ugc2hvdWxkIGZvbGxvdyB0aGUgcmVkaXJlY3QgYGNvZGVgLlxuICpcbiAqIEBwYXJhbSB7TnVtYmVyfSBjb2RlXG4gKiBAcmV0dXJuIHtCb29sZWFufVxuICogQGFwaSBwcml2YXRlXG4gKi9cblxuZnVuY3Rpb24gaXNSZWRpcmVjdChjb2RlKSB7XG4gIHJldHVybiBbMzAxLCAzMDIsIDMwMywgMzA1LCAzMDcsIDMwOF0uaW5jbHVkZXMoY29kZSk7XG59XG4iXX0=