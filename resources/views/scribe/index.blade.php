<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Coderockr Backend Test API Doc</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@10.7.2/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@10.7.2/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var baseUrl = "http://localhost:8100";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-4.6.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-4.6.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-persons">
                                <a href="#endpoints-GETapi-v1-persons">Display a listing of the resource.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-persons">
                                <a href="#endpoints-POSTapi-v1-persons">Store a newly created resource in storage.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-persons--id-">
                                <a href="#endpoints-GETapi-v1-persons--id-">Display the specified resource.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-PUTapi-v1-persons--id-">
                                <a href="#endpoints-PUTapi-v1-persons--id-">Update the specified resource in storage.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-v1-persons--id-">
                                <a href="#endpoints-DELETEapi-v1-persons--id-">Remove the specified resource from storage.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-persons--id--investments">
                                <a href="#endpoints-GETapi-v1-persons--id--investments">GET api/v1/persons/{id}/investments</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-investments">
                                <a href="#endpoints-GETapi-v1-investments">Display a listing of the resource.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-v1-investments">
                                <a href="#endpoints-POSTapi-v1-investments">Store a newly created resource in storage.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-investments--id-">
                                <a href="#endpoints-GETapi-v1-investments--id-">Display the specified resource.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-PUTapi-v1-investments--id-">
                                <a href="#endpoints-PUTapi-v1-investments--id-">Update the specified resource in storage.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-v1-investments--id-">
                                <a href="#endpoints-DELETEapi-v1-investments--id-">Remove the specified resource from storage.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-investments--id--movements">
                                <a href="#endpoints-GETapi-v1-investments--id--movements">Display a listing of the resource.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-PATCHapi-v1-investments--id--withdrawn">
                                <a href="#endpoints-PATCHapi-v1-investments--id--withdrawn">PATCH api/v1/investments/{id}/withdrawn</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: November 22, 2022</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>API documentation for Coderockr backend test project</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost:8100</code>
</aside>
<p>This documentation aims to provide all the information you need to work with our API.</p>
<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-v1-persons">Display a listing of the resource.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-persons">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/persons" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/persons"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-persons">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 59
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;first_name&quot;: &quot;Daniel&quot;,
            &quot;last_name&quot;: &quot;Soares&quot;,
            &quot;ssn&quot;: &quot;123456&quot;,
            &quot;email&quot;: &quot;danielcarlossoares@gmail.com&quot;,
            &quot;created_at&quot;: &quot;2022-11-22T14:54:52.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2022-11-22T14:54:52.000000Z&quot;,
            &quot;links&quot;: {
                &quot;view&quot;: &quot;http://localhost:8100/api/v1/persons/1&quot;
            }
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-persons" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-persons"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-persons" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-persons" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-persons"></code></pre>
</span>
<form id="form-GETapi-v1-persons" data-method="GET"
      data-path="api/v1/persons"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-persons', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-persons"
                    onclick="tryItOut('GETapi-v1-persons');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-persons"
                    onclick="cancelTryOut('GETapi-v1-persons');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-persons" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/persons</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Content-Type"                data-endpoint="GETapi-v1-persons"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Accept"                data-endpoint="GETapi-v1-persons"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-v1-persons">Store a newly created resource in storage.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-persons">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8100/api/v1/persons" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"first_name\": \"nisi\",
    \"last_name\": \"est\",
    \"ssn\": \"32\",
    \"email\": \"glen67@example.com\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/persons"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "first_name": "nisi",
    "last_name": "est",
    "ssn": "32",
    "email": "glen67@example.com"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-persons">
</span>
<span id="execution-results-POSTapi-v1-persons" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-persons"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-persons" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-persons" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-persons"></code></pre>
</span>
<form id="form-POSTapi-v1-persons" data-method="POST"
      data-path="api/v1/persons"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-persons', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-persons"
                    onclick="tryItOut('POSTapi-v1-persons');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-persons"
                    onclick="cancelTryOut('POSTapi-v1-persons');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-persons" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/persons</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Content-Type"                data-endpoint="POSTapi-v1-persons"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Accept"                data-endpoint="POSTapi-v1-persons"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text"
               name="first_name"                data-endpoint="POSTapi-v1-persons"
               value="nisi"
               data-component="body" hidden>
    <br>
<p>Example: <code>nisi</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text"
               name="last_name"                data-endpoint="POSTapi-v1-persons"
               value="est"
               data-component="body" hidden>
    <br>
<p>Example: <code>est</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>ssn</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text"
               name="ssn"                data-endpoint="POSTapi-v1-persons"
               value="32"
               data-component="body" hidden>
    <br>
<p>Must match the regex /^[0-9]+$/. Example: <code>32</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text"
               name="email"                data-endpoint="POSTapi-v1-persons"
               value="glen67@example.com"
               data-component="body" hidden>
    <br>
<p>validation.email. Example: <code>glen67@example.com</code></p>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-v1-persons--id-">Display the specified resource.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-persons--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/persons/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/persons/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-persons--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 58
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;first_name&quot;: &quot;Daniel&quot;,
        &quot;last_name&quot;: &quot;Soares&quot;,
        &quot;ssn&quot;: &quot;123456&quot;,
        &quot;email&quot;: &quot;danielcarlossoares@gmail.com&quot;,
        &quot;created_at&quot;: &quot;2022-11-22T14:54:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2022-11-22T14:54:52.000000Z&quot;,
        &quot;links&quot;: {
            &quot;view&quot;: &quot;http://localhost:8100/api/v1/persons/1&quot;
        },
        &quot;investments&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;description&quot;: &quot;Default Investment&quot;,
                &quot;created_at&quot;: &quot;2022-03-10T16:15:00.000000Z&quot;,
                &quot;withdrawn_at&quot;: &quot;2022-11-22T10:50:00.000000Z&quot;,
                &quot;is_withdrawn&quot;: 1,
                &quot;initial_investment&quot;: &quot;13000.00&quot;,
                &quot;balance&quot;: 550.75,
                &quot;links&quot;: {
                    &quot;view&quot;: &quot;http://localhost:8100/api/v1/investments/1&quot;,
                    &quot;movements&quot;: &quot;http://localhost:8100/api/v1/investments/1/movements&quot;
                }
            },
            {
                &quot;id&quot;: 2,
                &quot;description&quot;: &quot;Default Investment&quot;,
                &quot;created_at&quot;: &quot;2022-11-10T16:15:00.000000Z&quot;,
                &quot;withdrawn_at&quot;: null,
                &quot;is_withdrawn&quot;: 0,
                &quot;initial_investment&quot;: &quot;3000.00&quot;,
                &quot;balance&quot;: 3000,
                &quot;links&quot;: {
                    &quot;view&quot;: &quot;http://localhost:8100/api/v1/investments/2&quot;,
                    &quot;movements&quot;: &quot;http://localhost:8100/api/v1/investments/2/movements&quot;
                }
            }
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-persons--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-persons--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-persons--id-" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-persons--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-persons--id-"></code></pre>
</span>
<form id="form-GETapi-v1-persons--id-" data-method="GET"
      data-path="api/v1/persons/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-persons--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-persons--id-"
                    onclick="tryItOut('GETapi-v1-persons--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-persons--id-"
                    onclick="cancelTryOut('GETapi-v1-persons--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-persons--id-" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/persons/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Content-Type"                data-endpoint="GETapi-v1-persons--id-"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Accept"                data-endpoint="GETapi-v1-persons--id-"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number"
               name="id"                data-endpoint="GETapi-v1-persons--id-"
               value="1"
               data-component="url" hidden>
    <br>
<p>The ID of the person. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-PUTapi-v1-persons--id-">Update the specified resource in storage.</h2>

<p>
</p>



<span id="example-requests-PUTapi-v1-persons--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/persons/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"ssn\": \"1146777\",
    \"email\": \"anderson.mikel@example.com\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/persons/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "ssn": "1146777",
    "email": "anderson.mikel@example.com"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-persons--id-">
</span>
<span id="execution-results-PUTapi-v1-persons--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-persons--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-persons--id-" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-persons--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-persons--id-"></code></pre>
</span>
<form id="form-PUTapi-v1-persons--id-" data-method="PUT"
      data-path="api/v1/persons/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-persons--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-persons--id-"
                    onclick="tryItOut('PUTapi-v1-persons--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-persons--id-"
                    onclick="cancelTryOut('PUTapi-v1-persons--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-persons--id-" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/persons/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/persons/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Content-Type"                data-endpoint="PUTapi-v1-persons--id-"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Accept"                data-endpoint="PUTapi-v1-persons--id-"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number"
               name="id"                data-endpoint="PUTapi-v1-persons--id-"
               value="1"
               data-component="url" hidden>
    <br>
<p>The ID of the person. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text"
               name="first_name"                data-endpoint="PUTapi-v1-persons--id-"
               value=""
               data-component="body" hidden>
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text"
               name="last_name"                data-endpoint="PUTapi-v1-persons--id-"
               value=""
               data-component="body" hidden>
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>ssn</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text"
               name="ssn"                data-endpoint="PUTapi-v1-persons--id-"
               value="1146777"
               data-component="body" hidden>
    <br>
<p>Must match the regex /^[0-9]+$/. Example: <code>1146777</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text"
               name="email"                data-endpoint="PUTapi-v1-persons--id-"
               value="anderson.mikel@example.com"
               data-component="body" hidden>
    <br>
<p>validation.email. Example: <code>anderson.mikel@example.com</code></p>
        </div>
        </form>

                    <h2 id="endpoints-DELETEapi-v1-persons--id-">Remove the specified resource from storage.</h2>

<p>
</p>



<span id="example-requests-DELETEapi-v1-persons--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8100/api/v1/persons/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/persons/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-persons--id-">
</span>
<span id="execution-results-DELETEapi-v1-persons--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-persons--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-persons--id-" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-persons--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-persons--id-"></code></pre>
</span>
<form id="form-DELETEapi-v1-persons--id-" data-method="DELETE"
      data-path="api/v1/persons/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-persons--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-persons--id-"
                    onclick="tryItOut('DELETEapi-v1-persons--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-persons--id-"
                    onclick="cancelTryOut('DELETEapi-v1-persons--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-persons--id-" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/persons/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Content-Type"                data-endpoint="DELETEapi-v1-persons--id-"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Accept"                data-endpoint="DELETEapi-v1-persons--id-"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number"
               name="id"                data-endpoint="DELETEapi-v1-persons--id-"
               value="1"
               data-component="url" hidden>
    <br>
<p>The ID of the person. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-persons--id--investments">GET api/v1/persons/{id}/investments</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-persons--id--investments">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/persons/1/investments" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/persons/1/investments"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-persons--id--investments">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 57
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;data&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;description&quot;: &quot;Default Investment&quot;,
                &quot;created_at&quot;: &quot;2022-03-10T16:15:00.000000Z&quot;,
                &quot;withdrawn_at&quot;: &quot;2022-11-22T10:50:00.000000Z&quot;,
                &quot;is_withdrawn&quot;: 1,
                &quot;initial_investment&quot;: &quot;13000.00&quot;,
                &quot;balance&quot;: 550.75,
                &quot;links&quot;: {
                    &quot;view&quot;: &quot;http://localhost:8100/api/v1/investments/1&quot;,
                    &quot;movements&quot;: &quot;http://localhost:8100/api/v1/investments/1/movements&quot;
                }
            },
            {
                &quot;id&quot;: 2,
                &quot;description&quot;: &quot;Default Investment&quot;,
                &quot;created_at&quot;: &quot;2022-11-10T16:15:00.000000Z&quot;,
                &quot;withdrawn_at&quot;: null,
                &quot;is_withdrawn&quot;: 0,
                &quot;initial_investment&quot;: &quot;3000.00&quot;,
                &quot;balance&quot;: 3000,
                &quot;links&quot;: {
                    &quot;view&quot;: &quot;http://localhost:8100/api/v1/investments/2&quot;,
                    &quot;movements&quot;: &quot;http://localhost:8100/api/v1/investments/2/movements&quot;
                }
            }
        ],
        &quot;links&quot;: {
            &quot;first&quot;: &quot;http://localhost:8100/api/v1/persons/1/investments?page=1&quot;,
            &quot;last&quot;: &quot;http://localhost:8100/api/v1/persons/1/investments?page=1&quot;,
            &quot;prev&quot;: null,
            &quot;next&quot;: null
        },
        &quot;meta&quot;: {
            &quot;current_page&quot;: 1,
            &quot;from&quot;: 1,
            &quot;last_page&quot;: 1,
            &quot;links&quot;: [
                {
                    &quot;url&quot;: null,
                    &quot;label&quot;: &quot;pagination.previous&quot;,
                    &quot;active&quot;: false
                },
                {
                    &quot;url&quot;: &quot;http://localhost:8100/api/v1/persons/1/investments?page=1&quot;,
                    &quot;label&quot;: &quot;1&quot;,
                    &quot;active&quot;: true
                },
                {
                    &quot;url&quot;: null,
                    &quot;label&quot;: &quot;pagination.next&quot;,
                    &quot;active&quot;: false
                }
            ],
            &quot;path&quot;: &quot;http://localhost:8100/api/v1/persons/1/investments&quot;,
            &quot;per_page&quot;: 5,
            &quot;to&quot;: 2,
            &quot;total&quot;: 2
        }
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-persons--id--investments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-persons--id--investments"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-persons--id--investments" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-persons--id--investments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-persons--id--investments"></code></pre>
</span>
<form id="form-GETapi-v1-persons--id--investments" data-method="GET"
      data-path="api/v1/persons/{id}/investments"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-persons--id--investments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-persons--id--investments"
                    onclick="tryItOut('GETapi-v1-persons--id--investments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-persons--id--investments"
                    onclick="cancelTryOut('GETapi-v1-persons--id--investments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-persons--id--investments" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/persons/{id}/investments</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Content-Type"                data-endpoint="GETapi-v1-persons--id--investments"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Accept"                data-endpoint="GETapi-v1-persons--id--investments"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number"
               name="id"                data-endpoint="GETapi-v1-persons--id--investments"
               value="1"
               data-component="url" hidden>
    <br>
<p>The ID of the person. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-investments">Display a listing of the resource.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-investments">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/investments" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/investments"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-investments">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 56
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;investments&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;description&quot;: &quot;Default Investment&quot;,
            &quot;created_at&quot;: &quot;2022-03-10T16:15:00.000000Z&quot;,
            &quot;withdrawn_at&quot;: &quot;2022-11-22T10:50:00.000000Z&quot;,
            &quot;is_withdrawn&quot;: 1,
            &quot;initial_investment&quot;: &quot;13000.00&quot;,
            &quot;balance&quot;: 550.75,
            &quot;links&quot;: {
                &quot;view&quot;: &quot;http://localhost:8100/api/v1/investments/1&quot;,
                &quot;movements&quot;: &quot;http://localhost:8100/api/v1/investments/1/movements&quot;
            }
        },
        {
            &quot;id&quot;: 2,
            &quot;description&quot;: &quot;Default Investment&quot;,
            &quot;created_at&quot;: &quot;2022-11-10T16:15:00.000000Z&quot;,
            &quot;withdrawn_at&quot;: null,
            &quot;is_withdrawn&quot;: 0,
            &quot;initial_investment&quot;: &quot;3000.00&quot;,
            &quot;balance&quot;: 3000,
            &quot;links&quot;: {
                &quot;view&quot;: &quot;http://localhost:8100/api/v1/investments/2&quot;,
                &quot;movements&quot;: &quot;http://localhost:8100/api/v1/investments/2/movements&quot;
            }
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-investments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-investments"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-investments" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-investments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-investments"></code></pre>
</span>
<form id="form-GETapi-v1-investments" data-method="GET"
      data-path="api/v1/investments"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-investments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-investments"
                    onclick="tryItOut('GETapi-v1-investments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-investments"
                    onclick="cancelTryOut('GETapi-v1-investments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-investments" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/investments</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Content-Type"                data-endpoint="GETapi-v1-investments"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Accept"                data-endpoint="GETapi-v1-investments"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-v1-investments">Store a newly created resource in storage.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-investments">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8100/api/v1/investments" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"person_id\": \"unde\",
    \"description\": \"qui\",
    \"gain\": \"soluta\",
    \"created_at\": \"1998-02-04\",
    \"initial_investment\": 9.6449357
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/investments"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "person_id": "unde",
    "description": "qui",
    "gain": "soluta",
    "created_at": "1998-02-04",
    "initial_investment": 9.6449357
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-investments">
</span>
<span id="execution-results-POSTapi-v1-investments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-investments"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-investments" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-investments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-investments"></code></pre>
</span>
<form id="form-POSTapi-v1-investments" data-method="POST"
      data-path="api/v1/investments"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-investments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-investments"
                    onclick="tryItOut('POSTapi-v1-investments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-investments"
                    onclick="cancelTryOut('POSTapi-v1-investments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-investments" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/investments</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Content-Type"                data-endpoint="POSTapi-v1-investments"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Accept"                data-endpoint="POSTapi-v1-investments"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>person_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text"
               name="person_id"                data-endpoint="POSTapi-v1-investments"
               value="unde"
               data-component="body" hidden>
    <br>
<p>Example: <code>unde</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text"
               name="description"                data-endpoint="POSTapi-v1-investments"
               value="qui"
               data-component="body" hidden>
    <br>
<p>Example: <code>qui</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>gain</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text"
               name="gain"                data-endpoint="POSTapi-v1-investments"
               value="soluta"
               data-component="body" hidden>
    <br>
<p>Example: <code>soluta</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>created_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text"
               name="created_at"                data-endpoint="POSTapi-v1-investments"
               value="1998-02-04"
               data-component="body" hidden>
    <br>
<p>validation.date Must be a valid date in the format <code>Y-m-d H:i:s</code>. validation.before_or_equal. Example: <code>1998-02-04</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>initial_investment</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number"
               name="initial_investment"                data-endpoint="POSTapi-v1-investments"
               value="9.6449357"
               data-component="body" hidden>
    <br>
<p>Example: <code>9.6449357</code></p>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-v1-investments--id-">Display the specified resource.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-investments--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/investments/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/investments/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-investments--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 55
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;description&quot;: &quot;Default Investment&quot;,
        &quot;created_at&quot;: &quot;2022-03-10T16:15:00.000000Z&quot;,
        &quot;withdrawn_at&quot;: &quot;2022-11-22T10:50:00.000000Z&quot;,
        &quot;is_withdrawn&quot;: 1,
        &quot;initial_investment&quot;: &quot;13000.00&quot;,
        &quot;balance&quot;: 550.75,
        &quot;owner&quot;: {
            &quot;id&quot;: 1,
            &quot;first_name&quot;: &quot;Daniel&quot;,
            &quot;last_name&quot;: &quot;Soares&quot;,
            &quot;ssn&quot;: &quot;123456&quot;,
            &quot;email&quot;: &quot;danielcarlossoares@gmail.com&quot;,
            &quot;created_at&quot;: &quot;2022-11-22T14:54:52.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2022-11-22T14:54:52.000000Z&quot;,
            &quot;links&quot;: {
                &quot;view&quot;: &quot;http://localhost:8100/api/v1/persons/1&quot;
            }
        },
        &quot;movements&quot;: [
            {
                &quot;id&quot;: 10,
                &quot;investment_id&quot;: 1,
                &quot;description&quot;: &quot;Initial Investment Amount&quot;,
                &quot;value&quot;: &quot;13000.00&quot;,
                &quot;type&quot;: &quot;1&quot;
            },
            {
                &quot;id&quot;: 11,
                &quot;investment_id&quot;: 1,
                &quot;description&quot;: &quot;Investment Gain&quot;,
                &quot;value&quot;: &quot;67.60&quot;,
                &quot;type&quot;: &quot;2&quot;
            },
            {
                &quot;id&quot;: 12,
                &quot;investment_id&quot;: 1,
                &quot;description&quot;: &quot;Investment Gain&quot;,
                &quot;value&quot;: &quot;67.95&quot;,
                &quot;type&quot;: &quot;2&quot;
            },
            {
                &quot;id&quot;: 13,
                &quot;investment_id&quot;: 1,
                &quot;description&quot;: &quot;Investment Gain&quot;,
                &quot;value&quot;: &quot;68.30&quot;,
                &quot;type&quot;: &quot;2&quot;
            },
            {
                &quot;id&quot;: 14,
                &quot;investment_id&quot;: 1,
                &quot;description&quot;: &quot;Investment Gain&quot;,
                &quot;value&quot;: &quot;68.66&quot;,
                &quot;type&quot;: &quot;2&quot;
            },
            {
                &quot;id&quot;: 15,
                &quot;investment_id&quot;: 1,
                &quot;description&quot;: &quot;Investment Gain&quot;,
                &quot;value&quot;: &quot;69.02&quot;,
                &quot;type&quot;: &quot;2&quot;
            },
            {
                &quot;id&quot;: 16,
                &quot;investment_id&quot;: 1,
                &quot;description&quot;: &quot;Investment Gain&quot;,
                &quot;value&quot;: &quot;69.38&quot;,
                &quot;type&quot;: &quot;2&quot;
            },
            {
                &quot;id&quot;: 17,
                &quot;investment_id&quot;: 1,
                &quot;description&quot;: &quot;Investment Gain&quot;,
                &quot;value&quot;: &quot;69.74&quot;,
                &quot;type&quot;: &quot;2&quot;
            },
            {
                &quot;id&quot;: 18,
                &quot;investment_id&quot;: 1,
                &quot;description&quot;: &quot;Investment Gain&quot;,
                &quot;value&quot;: &quot;70.10&quot;,
                &quot;type&quot;: &quot;2&quot;
            },
            {
                &quot;id&quot;: 19,
                &quot;investment_id&quot;: 1,
                &quot;description&quot;: &quot;Withdrawn Taxes&quot;,
                &quot;value&quot;: &quot;123.92&quot;,
                &quot;type&quot;: &quot;3&quot;
            },
            {
                &quot;id&quot;: 20,
                &quot;investment_id&quot;: 1,
                &quot;description&quot;: &quot;Withdrawn&quot;,
                &quot;value&quot;: &quot;13426.83&quot;,
                &quot;type&quot;: &quot;4&quot;
            }
        ],
        &quot;links&quot;: {
            &quot;view&quot;: &quot;http://localhost:8100/api/v1/investments/1&quot;,
            &quot;movements&quot;: &quot;http://localhost:8100/api/v1/investments/1/movements&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-investments--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-investments--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-investments--id-" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-investments--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-investments--id-"></code></pre>
</span>
<form id="form-GETapi-v1-investments--id-" data-method="GET"
      data-path="api/v1/investments/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-investments--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-investments--id-"
                    onclick="tryItOut('GETapi-v1-investments--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-investments--id-"
                    onclick="cancelTryOut('GETapi-v1-investments--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-investments--id-" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/investments/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Content-Type"                data-endpoint="GETapi-v1-investments--id-"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Accept"                data-endpoint="GETapi-v1-investments--id-"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number"
               name="id"                data-endpoint="GETapi-v1-investments--id-"
               value="1"
               data-component="url" hidden>
    <br>
<p>The ID of the investment. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-PUTapi-v1-investments--id-">Update the specified resource in storage.</h2>

<p>
</p>



<span id="example-requests-PUTapi-v1-investments--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/investments/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"created_at\": \"2010-10-12\",
    \"initial_investment\": 482.4504598
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/investments/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "created_at": "2010-10-12",
    "initial_investment": 482.4504598
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-investments--id-">
</span>
<span id="execution-results-PUTapi-v1-investments--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-investments--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-investments--id-" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-investments--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-investments--id-"></code></pre>
</span>
<form id="form-PUTapi-v1-investments--id-" data-method="PUT"
      data-path="api/v1/investments/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-investments--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-investments--id-"
                    onclick="tryItOut('PUTapi-v1-investments--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-investments--id-"
                    onclick="cancelTryOut('PUTapi-v1-investments--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-investments--id-" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/investments/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/investments/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Content-Type"                data-endpoint="PUTapi-v1-investments--id-"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Accept"                data-endpoint="PUTapi-v1-investments--id-"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number"
               name="id"                data-endpoint="PUTapi-v1-investments--id-"
               value="1"
               data-component="url" hidden>
    <br>
<p>The ID of the investment. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>person_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text"
               name="person_id"                data-endpoint="PUTapi-v1-investments--id-"
               value=""
               data-component="body" hidden>
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text"
               name="description"                data-endpoint="PUTapi-v1-investments--id-"
               value=""
               data-component="body" hidden>
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>gain</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text"
               name="gain"                data-endpoint="PUTapi-v1-investments--id-"
               value=""
               data-component="body" hidden>
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>created_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text"
               name="created_at"                data-endpoint="PUTapi-v1-investments--id-"
               value="2010-10-12"
               data-component="body" hidden>
    <br>
<p>validation.date Must be a valid date in the format <code>Y-m-d H:i:s</code>. validation.before_or_equal. Example: <code>2010-10-12</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>initial_investment</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number"
               name="initial_investment"                data-endpoint="PUTapi-v1-investments--id-"
               value="482.4504598"
               data-component="body" hidden>
    <br>
<p>Example: <code>482.4504598</code></p>
        </div>
        </form>

                    <h2 id="endpoints-DELETEapi-v1-investments--id-">Remove the specified resource from storage.</h2>

<p>
</p>



<span id="example-requests-DELETEapi-v1-investments--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8100/api/v1/investments/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/investments/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-investments--id-">
</span>
<span id="execution-results-DELETEapi-v1-investments--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-investments--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-investments--id-" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-investments--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-investments--id-"></code></pre>
</span>
<form id="form-DELETEapi-v1-investments--id-" data-method="DELETE"
      data-path="api/v1/investments/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-investments--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-investments--id-"
                    onclick="tryItOut('DELETEapi-v1-investments--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-investments--id-"
                    onclick="cancelTryOut('DELETEapi-v1-investments--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-investments--id-" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/investments/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Content-Type"                data-endpoint="DELETEapi-v1-investments--id-"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Accept"                data-endpoint="DELETEapi-v1-investments--id-"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number"
               name="id"                data-endpoint="DELETEapi-v1-investments--id-"
               value="1"
               data-component="url" hidden>
    <br>
<p>The ID of the investment. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-investments--id--movements">Display a listing of the resource.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-investments--id--movements">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/investments/1/movements" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/investments/1/movements"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-investments--id--movements">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 54
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;investment&quot;: {
        &quot;id&quot;: 1,
        &quot;description&quot;: &quot;Default Investment&quot;,
        &quot;created_at&quot;: &quot;2022-03-10T16:15:00.000000Z&quot;,
        &quot;withdrawn_at&quot;: &quot;2022-11-22T10:50:00.000000Z&quot;,
        &quot;is_withdrawn&quot;: 1,
        &quot;initial_investment&quot;: &quot;13000.00&quot;,
        &quot;balance&quot;: 550.75,
        &quot;links&quot;: {
            &quot;view&quot;: &quot;http://localhost:8100/api/v1/investments/1&quot;,
            &quot;movements&quot;: &quot;http://localhost:8100/api/v1/investments/1/movements&quot;
        }
    },
    &quot;movements&quot;: [
        {
            &quot;id&quot;: 10,
            &quot;investment_id&quot;: 1,
            &quot;description&quot;: &quot;Initial Investment Amount&quot;,
            &quot;value&quot;: &quot;13000.00&quot;,
            &quot;type&quot;: &quot;1&quot;
        },
        {
            &quot;id&quot;: 11,
            &quot;investment_id&quot;: 1,
            &quot;description&quot;: &quot;Investment Gain&quot;,
            &quot;value&quot;: &quot;67.60&quot;,
            &quot;type&quot;: &quot;2&quot;
        },
        {
            &quot;id&quot;: 12,
            &quot;investment_id&quot;: 1,
            &quot;description&quot;: &quot;Investment Gain&quot;,
            &quot;value&quot;: &quot;67.95&quot;,
            &quot;type&quot;: &quot;2&quot;
        },
        {
            &quot;id&quot;: 13,
            &quot;investment_id&quot;: 1,
            &quot;description&quot;: &quot;Investment Gain&quot;,
            &quot;value&quot;: &quot;68.30&quot;,
            &quot;type&quot;: &quot;2&quot;
        },
        {
            &quot;id&quot;: 14,
            &quot;investment_id&quot;: 1,
            &quot;description&quot;: &quot;Investment Gain&quot;,
            &quot;value&quot;: &quot;68.66&quot;,
            &quot;type&quot;: &quot;2&quot;
        },
        {
            &quot;id&quot;: 15,
            &quot;investment_id&quot;: 1,
            &quot;description&quot;: &quot;Investment Gain&quot;,
            &quot;value&quot;: &quot;69.02&quot;,
            &quot;type&quot;: &quot;2&quot;
        },
        {
            &quot;id&quot;: 16,
            &quot;investment_id&quot;: 1,
            &quot;description&quot;: &quot;Investment Gain&quot;,
            &quot;value&quot;: &quot;69.38&quot;,
            &quot;type&quot;: &quot;2&quot;
        },
        {
            &quot;id&quot;: 17,
            &quot;investment_id&quot;: 1,
            &quot;description&quot;: &quot;Investment Gain&quot;,
            &quot;value&quot;: &quot;69.74&quot;,
            &quot;type&quot;: &quot;2&quot;
        },
        {
            &quot;id&quot;: 18,
            &quot;investment_id&quot;: 1,
            &quot;description&quot;: &quot;Investment Gain&quot;,
            &quot;value&quot;: &quot;70.10&quot;,
            &quot;type&quot;: &quot;2&quot;
        },
        {
            &quot;id&quot;: 19,
            &quot;investment_id&quot;: 1,
            &quot;description&quot;: &quot;Withdrawn Taxes&quot;,
            &quot;value&quot;: &quot;123.92&quot;,
            &quot;type&quot;: &quot;3&quot;
        },
        {
            &quot;id&quot;: 20,
            &quot;investment_id&quot;: 1,
            &quot;description&quot;: &quot;Withdrawn&quot;,
            &quot;value&quot;: &quot;13426.83&quot;,
            &quot;type&quot;: &quot;4&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-investments--id--movements" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-investments--id--movements"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-investments--id--movements" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-investments--id--movements" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-investments--id--movements"></code></pre>
</span>
<form id="form-GETapi-v1-investments--id--movements" data-method="GET"
      data-path="api/v1/investments/{id}/movements"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-investments--id--movements', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-investments--id--movements"
                    onclick="tryItOut('GETapi-v1-investments--id--movements');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-investments--id--movements"
                    onclick="cancelTryOut('GETapi-v1-investments--id--movements');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-investments--id--movements" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/investments/{id}/movements</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Content-Type"                data-endpoint="GETapi-v1-investments--id--movements"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Accept"                data-endpoint="GETapi-v1-investments--id--movements"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number"
               name="id"                data-endpoint="GETapi-v1-investments--id--movements"
               value="1"
               data-component="url" hidden>
    <br>
<p>The ID of the investment. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-PATCHapi-v1-investments--id--withdrawn">PATCH api/v1/investments/{id}/withdrawn</h2>

<p>
</p>



<span id="example-requests-PATCHapi-v1-investments--id--withdrawn">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost:8100/api/v1/investments/1/withdrawn" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"withdrawn_at\": \"2022-11-22 21:17:55\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/investments/1/withdrawn"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "withdrawn_at": "2022-11-22 21:17:55"
};

fetch(url, {
    method: "PATCH",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-v1-investments--id--withdrawn">
</span>
<span id="execution-results-PATCHapi-v1-investments--id--withdrawn" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-v1-investments--id--withdrawn"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-v1-investments--id--withdrawn" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-v1-investments--id--withdrawn" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-v1-investments--id--withdrawn"></code></pre>
</span>
<form id="form-PATCHapi-v1-investments--id--withdrawn" data-method="PATCH"
      data-path="api/v1/investments/{id}/withdrawn"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-v1-investments--id--withdrawn', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-v1-investments--id--withdrawn"
                    onclick="tryItOut('PATCHapi-v1-investments--id--withdrawn');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-v1-investments--id--withdrawn"
                    onclick="cancelTryOut('PATCHapi-v1-investments--id--withdrawn');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-v1-investments--id--withdrawn" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/investments/{id}/withdrawn</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Content-Type"                data-endpoint="PATCHapi-v1-investments--id--withdrawn"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text"
               name="Accept"                data-endpoint="PATCHapi-v1-investments--id--withdrawn"
               value="application/json"
               data-component="header" hidden>
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number"
               name="id"                data-endpoint="PATCHapi-v1-investments--id--withdrawn"
               value="1"
               data-component="url" hidden>
    <br>
<p>The ID of the investment. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>withdrawn_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text"
               name="withdrawn_at"                data-endpoint="PATCHapi-v1-investments--id--withdrawn"
               value="2022-11-22 21:17:55"
               data-component="body" hidden>
    <br>
<p>validation.date Must be a valid date in the format <code>Y-m-d H:i:s</code>. Example: <code>2022-11-22 21:17:55</code></p>
        </div>
        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
