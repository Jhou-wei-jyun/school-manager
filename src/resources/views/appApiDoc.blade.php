<!DOCTYPE html><html><head>
    <title>ims</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="file:////Users/akiwang/.vscode/extensions/shd101wyy.markdown-preview-enhanced-0.5.12/node_modules/@shd101wyy/mume/dependencies/katex/katex.min.css">









    <style>
    /**
* prism.js Github theme based on GitHub's theme.
* @author Sam Clarke
*/
code[class*="language-"],
pre[class*="language-"] {
color: #333;
background: none;
font-family: Consolas, "Liberation Mono", Menlo, Courier, monospace;
text-align: left;
white-space: pre;
word-spacing: normal;
word-break: normal;
word-wrap: normal;
line-height: 1.4;

-moz-tab-size: 8;
-o-tab-size: 8;
tab-size: 8;

-webkit-hyphens: none;
-moz-hyphens: none;
-ms-hyphens: none;
hyphens: none;
}

/* Code blocks */
pre[class*="language-"] {
padding: .8em;
overflow: auto;
/* border: 1px solid #ddd; */
border-radius: 3px;
/* background: #fff; */
background: #f5f5f5;
}

/* Inline code */
:not(pre) > code[class*="language-"] {
padding: .1em;
border-radius: .3em;
white-space: normal;
background: #f5f5f5;
}

.token.comment,
.token.blockquote {
color: #969896;
}

.token.cdata {
color: #183691;
}

.token.doctype,
.token.punctuation,
.token.variable,
.token.macro.property {
color: #333;
}

.token.operator,
.token.important,
.token.keyword,
.token.rule,
.token.builtin {
color: #a71d5d;
}

.token.string,
.token.url,
.token.regex,
.token.attr-value {
color: #183691;
}

.token.property,
.token.number,
.token.boolean,
.token.entity,
.token.atrule,
.token.constant,
.token.symbol,
.token.command,
.token.code {
color: #0086b3;
}

.token.tag,
.token.selector,
.token.prolog {
color: #63a35c;
}

.token.function,
.token.namespace,
.token.pseudo-element,
.token.class,
.token.class-name,
.token.pseudo-class,
.token.id,
.token.url-reference .token.variable,
.token.attr-name {
color: #795da3;
}

.token.entity {
cursor: help;
}

.token.title,
.token.title .token.punctuation {
font-weight: bold;
color: #1d3e81;
}

.token.list {
color: #ed6a43;
}

.token.inserted {
background-color: #eaffea;
color: #55a532;
}

.token.deleted {
background-color: #ffecec;
color: #bd2c00;
}

.token.bold {
font-weight: bold;
}

.token.italic {
font-style: italic;
}


/* JSON */
.language-json .token.property {
color: #183691;
}

.language-markup .token.tag .token.punctuation {
color: #333;
}

/* CSS */
code.language-css,
.language-css .token.function {
color: #0086b3;
}

/* YAML */
.language-yaml .token.atrule {
color: #63a35c;
}

code.language-yaml {
color: #183691;
}

/* Ruby */
.language-ruby .token.function {
color: #333;
}

/* Markdown */
.language-markdown .token.url {
color: #795da3;
}

/* Makefile */
.language-makefile .token.symbol {
color: #795da3;
}

.language-makefile .token.variable {
color: #183691;
}

.language-makefile .token.builtin {
color: #0086b3;
}

/* Bash */
.language-bash .token.keyword {
color: #0086b3;
}

/* highlight */
pre[data-line] {
position: relative;
padding: 1em 0 1em 3em;
}
pre[data-line] .line-highlight-wrapper {
position: absolute;
top: 0;
left: 0;
background-color: transparent;
display: block;
width: 100%;
}

pre[data-line] .line-highlight {
position: absolute;
left: 0;
right: 0;
padding: inherit 0;
margin-top: 1em;
background: hsla(24, 20%, 50%,.08);
background: linear-gradient(to right, hsla(24, 20%, 50%,.1) 70%, hsla(24, 20%, 50%,0));
pointer-events: none;
line-height: inherit;
white-space: pre;
}

pre[data-line] .line-highlight:before,
pre[data-line] .line-highlight[data-end]:after {
content: attr(data-start);
position: absolute;
top: .4em;
left: .6em;
min-width: 1em;
padding: 0 .5em;
background-color: hsla(24, 20%, 50%,.4);
color: hsl(24, 20%, 95%);
font: bold 65%/1.5 sans-serif;
text-align: center;
vertical-align: .3em;
border-radius: 999px;
text-shadow: none;
box-shadow: 0 1px white;
}

pre[data-line] .line-highlight[data-end]:after {
content: attr(data-end);
top: auto;
bottom: .4em;
}html body{font-family:"Helvetica Neue",Helvetica,"Segoe UI",Arial,freesans,sans-serif;font-size:16px;line-height:1.6;color:#333;background-color:#fff;overflow:initial;box-sizing:border-box;word-wrap:break-word}html body>:first-child{margin-top:0}html body h1,html body h2,html body h3,html body h4,html body h5,html body h6{line-height:1.2;margin-top:1em;margin-bottom:16px;color:#000}html body h1{font-size:2.25em;font-weight:300;padding-bottom:.3em}html body h2{font-size:1.75em;font-weight:400;padding-bottom:.3em}html body h3{font-size:1.5em;font-weight:500}html body h4{font-size:1.25em;font-weight:600}html body h5{font-size:1.1em;font-weight:600}html body h6{font-size:1em;font-weight:600}html body h1,html body h2,html body h3,html body h4,html body h5{font-weight:600}html body h5{font-size:1em}html body h6{color:#5c5c5c}html body strong{color:#000}html body del{color:#5c5c5c}html body a:not([href]){color:inherit;text-decoration:none}html body a{color:#08c;text-decoration:none}html body a:hover{color:#00a3f5;text-decoration:none}html body img{max-width:100%}html body>p{margin-top:0;margin-bottom:16px;word-wrap:break-word}html body>ul,html body>ol{margin-bottom:16px}html body ul,html body ol{padding-left:2em}html body ul.no-list,html body ol.no-list{padding:0;list-style-type:none}html body ul ul,html body ul ol,html body ol ol,html body ol ul{margin-top:0;margin-bottom:0}html body li{margin-bottom:0}html body li.task-list-item{list-style:none}html body li>p{margin-top:0;margin-bottom:0}html body .task-list-item-checkbox{margin:0 .2em .25em -1.8em;vertical-align:middle}html body .task-list-item-checkbox:hover{cursor:pointer}html body blockquote{margin:16px 0;font-size:inherit;padding:0 15px;color:#5c5c5c;background-color:#f0f0f0;border-left:4px solid #d6d6d6}html body blockquote>:first-child{margin-top:0}html body blockquote>:last-child{margin-bottom:0}html body hr{height:4px;margin:32px 0;background-color:#d6d6d6;border:0 none}html body table{margin:10px 0 15px 0;border-collapse:collapse;border-spacing:0;display:block;width:100%;overflow:auto;word-break:normal;word-break:keep-all}html body table th{font-weight:bold;color:#000}html body table td,html body table th{border:1px solid #d6d6d6;padding:6px 13px}html body dl{padding:0}html body dl dt{padding:0;margin-top:16px;font-size:1em;font-style:italic;font-weight:bold}html body dl dd{padding:0 16px;margin-bottom:16px}html body code{font-family:Menlo,Monaco,Consolas,'Courier New',monospace;font-size:.85em !important;color:#000;background-color:#f0f0f0;border-radius:3px;padding:.2em 0}html body code::before,html body code::after{letter-spacing:-0.2em;content:"\00a0"}html body pre>code{padding:0;margin:0;font-size:.85em !important;word-break:normal;white-space:pre;background:transparent;border:0}html body .highlight{margin-bottom:16px}html body .highlight pre,html body pre{padding:1em;overflow:auto;font-size:.85em !important;line-height:1.45;border:#d6d6d6;border-radius:3px}html body .highlight pre{margin-bottom:0;word-break:normal}html body pre code,html body pre tt{display:inline;max-width:initial;padding:0;margin:0;overflow:initial;line-height:inherit;word-wrap:normal;background-color:transparent;border:0}html body pre code:before,html body pre tt:before,html body pre code:after,html body pre tt:after{content:normal}html body p,html body blockquote,html body ul,html body ol,html body dl,html body pre{margin-top:0;margin-bottom:16px}html body kbd{color:#000;border:1px solid #d6d6d6;border-bottom:2px solid #c7c7c7;padding:2px 4px;background-color:#f0f0f0;border-radius:3px}@media print{html body{background-color:#fff}html body h1,html body h2,html body h3,html body h4,html body h5,html body h6{color:#000;page-break-after:avoid}html body blockquote{color:#5c5c5c}html body pre{page-break-inside:avoid}html body table{display:table}html body img{display:block;max-width:100%;max-height:100%}html body pre,html body code{word-wrap:break-word;white-space:pre}}.markdown-preview{width:100%;height:100%;box-sizing:border-box}.markdown-preview .pagebreak,.markdown-preview .newpage{page-break-before:always}.markdown-preview pre.line-numbers{position:relative;padding-left:3.8em;counter-reset:linenumber}.markdown-preview pre.line-numbers>code{position:relative}.markdown-preview pre.line-numbers .line-numbers-rows{position:absolute;pointer-events:none;top:1em;font-size:100%;left:0;width:3em;letter-spacing:-1px;border-right:1px solid #999;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.markdown-preview pre.line-numbers .line-numbers-rows>span{pointer-events:none;display:block;counter-increment:linenumber}.markdown-preview pre.line-numbers .line-numbers-rows>span:before{content:counter(linenumber);color:#999;display:block;padding-right:.8em;text-align:right}.markdown-preview .mathjax-exps .MathJax_Display{text-align:center !important}.markdown-preview:not([for="preview"]) .code-chunk .btn-group{display:none}.markdown-preview:not([for="preview"]) .code-chunk .status{display:none}.markdown-preview:not([for="preview"]) .code-chunk .output-div{margin-bottom:16px}.scrollbar-style::-webkit-scrollbar{width:8px}.scrollbar-style::-webkit-scrollbar-track{border-radius:10px;background-color:transparent}.scrollbar-style::-webkit-scrollbar-thumb{border-radius:5px;background-color:rgba(150,150,150,0.66);border:4px solid rgba(150,150,150,0.66);background-clip:content-box}html body[for="html-export"]:not([data-presentation-mode]){position:relative;width:100%;height:100%;top:0;left:0;margin:0;padding:0;overflow:auto}html body[for="html-export"]:not([data-presentation-mode]) .markdown-preview{position:relative;top:0}@media screen and (min-width:914px){html body[for="html-export"]:not([data-presentation-mode]) .markdown-preview{padding:2em calc(50% - 457px + 2em)}}@media screen and (max-width:914px){html body[for="html-export"]:not([data-presentation-mode]) .markdown-preview{padding:2em}}@media screen and (max-width:450px){html body[for="html-export"]:not([data-presentation-mode]) .markdown-preview{font-size:14px !important;padding:1em}}@media print{html body[for="html-export"]:not([data-presentation-mode]) #sidebar-toc-btn{display:none}}html body[for="html-export"]:not([data-presentation-mode]) #sidebar-toc-btn{position:fixed;bottom:8px;left:8px;font-size:28px;cursor:pointer;color:inherit;z-index:99;width:32px;text-align:center;opacity:.4}html body[for="html-export"]:not([data-presentation-mode])[html-show-sidebar-toc] #sidebar-toc-btn{opacity:1}html body[for="html-export"]:not([data-presentation-mode])[html-show-sidebar-toc] .md-sidebar-toc{position:fixed;top:0;left:0;width:300px;height:100%;padding:32px 0 48px 0;font-size:14px;box-shadow:0 0 4px rgba(150,150,150,0.33);box-sizing:border-box;overflow:auto;background-color:inherit}html body[for="html-export"]:not([data-presentation-mode])[html-show-sidebar-toc] .md-sidebar-toc::-webkit-scrollbar{width:8px}html body[for="html-export"]:not([data-presentation-mode])[html-show-sidebar-toc] .md-sidebar-toc::-webkit-scrollbar-track{border-radius:10px;background-color:transparent}html body[for="html-export"]:not([data-presentation-mode])[html-show-sidebar-toc] .md-sidebar-toc::-webkit-scrollbar-thumb{border-radius:5px;background-color:rgba(150,150,150,0.66);border:4px solid rgba(150,150,150,0.66);background-clip:content-box}html body[for="html-export"]:not([data-presentation-mode])[html-show-sidebar-toc] .md-sidebar-toc a{text-decoration:none}html body[for="html-export"]:not([data-presentation-mode])[html-show-sidebar-toc] .md-sidebar-toc ul{padding:0 1.6em;margin-top:.8em}html body[for="html-export"]:not([data-presentation-mode])[html-show-sidebar-toc] .md-sidebar-toc li{margin-bottom:.8em}html body[for="html-export"]:not([data-presentation-mode])[html-show-sidebar-toc] .md-sidebar-toc ul{list-style-type:none}html body[for="html-export"]:not([data-presentation-mode])[html-show-sidebar-toc] .markdown-preview{left:300px;width:calc(100% -  300px);padding:2em calc(50% - 457px -  150px);margin:0;box-sizing:border-box}@media screen and (max-width:1274px){html body[for="html-export"]:not([data-presentation-mode])[html-show-sidebar-toc] .markdown-preview{padding:2em}}@media screen and (max-width:450px){html body[for="html-export"]:not([data-presentation-mode])[html-show-sidebar-toc] .markdown-preview{width:100%}}html body[for="html-export"]:not([data-presentation-mode]):not([html-show-sidebar-toc]) .markdown-preview{left:50%;transform:translateX(-50%)}html body[for="html-export"]:not([data-presentation-mode]):not([html-show-sidebar-toc]) .md-sidebar-toc{display:none}
/* Please visit the URL below for more information: */
/*   https://shd101wyy.github.io/markdown-preview-enhanced/#/customize-css */

    </style>
  </head>
  <body for="html-export">
    <div class="mume markdown-preview  ">
    <h1 class="mume-header undefined" id="ims-api">IMS API</h1>

<ul>
<li><a href="#scanner-api">Scanner API</a>
<ul>
<li><a href="#post-upload-records">POST Upload Records</a></li>
<li><a href="#get-area-and-wifi-setting">GET Area and wifi setting</a></li>
<li><a href="#get-check-network">GET Check Network</a></li>
<li><a href="#get-updata-file">GET Updata File</a></li>
</ul>
</li>
<li><a href="#app-api">App API</a>
<ul>
<li><a href="#get-businessinfo">GET BusinessInfo</a></li>
<li><a href="#post-login">POST Login</a></li>
<li><a href="#get-profile">GET Profile</a></li>
<li><a href="#post-edit-profile">POST Edit Profile</a></li>
<li><a href="#get-user-record">GET User Record</a></li>
<li><a href="#get-user-temper">GET User Temper</a></li>
<li><a href="#get-user-areas">GET User Areas</a></li>
<li><a href="#get-area-list">GET Area List</a></li>
<li><a href="#get-starts-list">GET Starts List</a></li>
<li><a href="#get-positions-list">GET Positions List</a></li>
<li><a href="#get-department-list">GET Department List</a></li>
<li><a href="#get-do-you-know">GET Do You Know</a></li>
<li><a href="#get-employees">GET Employees</a></li>
<li><a href="#get-employees-detail">GET Employees Detail</a></li>
<li><a href="#get-categories">GET Categories</a></li>
<li><a href="#get-category-items">GET Category Items</a></li>
<li><a href="#get-notify">GET Notify</a></li>
<li><a href="#firebase-notify-message">Firebase Notify Message</a></li>
</ul>
</li>
</ul>
<hr>
<h1 class="mume-header" id="scanner-api">Scanner API</h1>

<hr>
<h2 class="mume-header" id="post-upload-records">POST Upload Records</h2>

<h4 class="mume-header undefined" id="url">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>server<span class="token punctuation">}</span>/api/records
</pre><h4 class="mume-header undefined" id="headers">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;scanner_mac&quot;</span><span class="token operator">:</span> <span class="token string">&quot;11:22:33:44:55:66&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;scanner_ip&quot;</span><span class="token operator">:</span> <span class="token string">&quot;192.168.0.13&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;scanner_ssid&quot;</span><span class="token operator">:</span> <span class="token string">&quot;smartcube-5G&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;records&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
      <span class="token punctuation">{</span>
          <span class="token property">&quot;mac&quot;</span> <span class="token operator">:</span> <span class="token string">&quot;AA:BB:CC:DD:EE:FF&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;date_time&quot;</span> <span class="token operator">:</span> <span class="token string">&quot;2020-01-02 03:04:05&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;date_long&quot;</span> <span class="token operator">:</span><span class="token string">&quot;1577905440&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;rssi&quot;</span> <span class="token operator">:</span> <span class="token number">-50</span><span class="token punctuation">,</span>
          <span class="token property">&quot;area_id&quot;</span> <span class="token operator">:</span> <span class="token number">1</span>
      <span class="token punctuation">}</span><span class="token punctuation">,</span>
  <span class="token punctuation">]</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="response">Response</h4>

<h5 class="mume-header undefined" id="status-code-200">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><hr>
<h2 class="mume-header" id="get-area-and-wifi-setting">GET Area and wifi setting</h2>

<h4 class="mume-header undefined" id="url-1">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>server<span class="token punctuation">}</span>/api/area?device_mac=<span class="token punctuation">{</span>scanner_mac<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="headers-1">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-1">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-1">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-1">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;area_id&quot;</span> <span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
      <span class="token property">&quot;ssid&quot;</span> <span class="token operator">:</span> <span class="token string">&quot;smartcube-5G&quot;</span><span class="token punctuation">,</span>
      <span class="token property">&quot;password&quot;</span> <span class="token operator">:</span> <span class="token string">&quot;0928910000&quot;</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-check-network">GET Check Network</h2>

<h4 class="mume-header undefined" id="url-2">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>server<span class="token punctuation">}</span>/api/network
</pre><h4 class="mume-header undefined" id="headers-2">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-2">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-2">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-2">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token comment">// &#x56DE;&#x50B3; 200 &#x4EE3;&#x8868;&#x7DB2;&#x8DEF;&#x6B63;&#x5E38;&#x53EF;&#x7528;</span>
</pre><hr>
<h2 class="mume-header" id="get-updata-file">GET Updata File</h2>

<h4 class="mume-header undefined" id="url-3">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>server<span class="token punctuation">}</span>/api/filess/<span class="token punctuation">{</span>scanner_mac<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="headers-3">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-3">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-3">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-3">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
      <span class="token string">&quot;https://smartcubeims.s3.com/pi/ble_scan_and_update.py&quot;</span><span class="token punctuation">,</span>
      <span class="token string">&quot;https://smartcubeims.s3.com/pi/crontab.txt&quot;</span><span class="token punctuation">,</span>
      <span class="token string">&quot;https://smartcubeims.s3.com/pi/update_code.py&quot;</span><span class="token punctuation">,</span>
      <span class="token string">&quot;https://smartcubeims.s3.com/pi/wifi.py&quot;</span>
  <span class="token punctuation">]</span>
<span class="token punctuation">}</span>
</pre><hr>
<h1 class="mume-header" id="app-api">App API</h1>

<hr>
<h2 class="mume-header" id="get-businessinfo">GET BusinessInfo</h2>

<h4 class="mume-header undefined" id="url-4">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>server<span class="token punctuation">}</span>/api/business
</pre><h4 class="mume-header undefined" id="headers-4">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-4">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-4">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-4">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;temperature_def&quot;</span><span class="token operator">:</span> <span class="token number">37.5</span><span class="token punctuation">,</span> <span class="token comment">//&#x6EAB;&#x5EA6;&#x6A19;&#x6E96; &#x7528;&#x4F86;&#x5224;&#x65B7;&#x662F;&#x5426;&#x767C;&#x71D2;&#x7684;&#x4F9D;&#x64DA;</span>
      <span class="token property">&quot;location_photo&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http://img.png&quot;</span><span class="token punctuation">,</span> <span class="token comment">//&#x516C;&#x53F8;&#x5E73;&#x9762;&#x5716;</span>
      <span class="token property">&quot;location_emergency_exit&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http://img.png&quot;</span><span class="token punctuation">,</span> <span class="token comment">//&#x7DCA;&#x6025;&#x51FA;&#x53E3;&#x5716;</span>
      <span class="token property">&quot;privacy_page&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;contact-us&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;link&quot;</span><span class="token operator">:</span> <span class="token string">&quot;https://traqshoes.com/pages/contact-us&quot;</span>
          <span class="token punctuation">}</span><span class="token punctuation">,</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;privacy-policy&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;link&quot;</span><span class="token operator">:</span> <span class="token string">&quot;https://traqshoes.com/pages/traq-by-alegria%C2%AE-app-privacy-policy&quot;</span>
          <span class="token punctuation">}</span><span class="token punctuation">,</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;terms-of-service&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;link&quot;</span><span class="token operator">:</span> <span class="token string">&quot;https://traqshoes.com/pages/traq-by-alegria%C2%AE-app-terms-of-service&quot;</span>
          <span class="token punctuation">}</span><span class="token punctuation">,</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;introduction&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;link&quot;</span><span class="token operator">:</span> <span class="token string">&quot;https://traqshoes.com/pages/introduction-to-traq&quot;</span>
          <span class="token punctuation">}</span>
      <span class="token punctuation">]</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="post-login">POST Login</h2>

<h4 class="mume-header undefined" id="url-5">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>server<span class="token punctuation">}</span>/api/auth/login
</pre><h4 class="mume-header undefined" id="headers-5">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-5">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;system&quot;</span><span class="token operator">:</span> <span class="token string">&quot;android&quot;</span><span class="token punctuation">,</span> <span class="token comment">//android or ios</span>
  <span class="token property">&quot;device_token&quot;</span><span class="token operator">:</span><span class="token string">&quot;000000000000000&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;account&quot;</span><span class="token operator">:</span> <span class="token string">&quot;wang&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;macs&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span><span class="token string">&quot;aa:bb:cc:dd:ee:ff&quot;</span><span class="token punctuation">,</span> <span class="token string">&quot;11:22:33:44:55:66&quot;</span><span class="token punctuation">]</span><span class="token punctuation">,</span>
  <span class="token string">&quot;password&quot;</span><span class="token punctuation">,</span> <span class="token string">&quot;12345678&quot;</span> <span class="token comment">// macs &#x8207; password &#x53EF;&#x4EE5;&#x4E00;&#x8D77;&#x50B3;</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="response-5">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-5">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;user_id&quot;</span><span class="token operator">:</span> <span class="token number">15</span><span class="token punctuation">,</span>
      <span class="token property">&quot;api_token&quot;</span><span class="token operator">:</span> <span class="token string">&quot;9OIyltHvAH2DIaRjfidZtlI6F7KgUfThj39QeY5xYJFququQwm9egWCX9usp&quot;</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-profile">GET Profile</h2>

<h4 class="mume-header undefined" id="url-6">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/self
</pre><h4 class="mume-header undefined" id="headers-6">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Authorization&quot;</span><span class="token operator">:</span> <span class="token string">&quot;Bearer 9OIyltHvAH2DIaRjfidZtlI6F7KgUfThj39QeY5xYJFququQwm9egWCX9usp&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-6">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-6">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-6">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;id&quot;</span><span class="token operator">:</span> <span class="token number">15</span><span class="token punctuation">,</span>
      <span class="token property">&quot;account&quot;</span><span class="token operator">:</span> <span class="token string">&quot;wang&quot;</span><span class="token punctuation">,</span>
      <span class="token property">&quot;mac&quot;</span><span class="token operator">:</span> <span class="token string">&quot;19:07:19:00:B3:53&quot;</span><span class="token punctuation">,</span>
      <span class="token property">&quot;device_token&quot;</span><span class="token operator">:</span> <span class="token string">&quot;000000000000000&quot;</span><span class="token punctuation">,</span>
      <span class="token property">&quot;api_token&quot;</span><span class="token operator">:</span> <span class="token string">&quot;WarPJJRJU1m1LGawlzokADEKikrTrBadr5LNCb5SoLcGlVHJN0iNQsb3tl4s&quot;</span><span class="token punctuation">,</span>
      <span class="token property">&quot;name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;Aki wang&quot;</span><span class="token punctuation">,</span>
      <span class="token property">&quot;gender&quot;</span><span class="token operator">:</span> <span class="token number">2</span><span class="token punctuation">,</span>
      <span class="token property">&quot;avatar&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http://img.png&quot;</span><span class="token punctuation">,</span>
      <span class="token property">&quot;level_id&quot;</span><span class="token operator">:</span> <span class="token number">0</span><span class="token punctuation">,</span>
      <span class="token property">&quot;department_id&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
      <span class="token property">&quot;position_id&quot;</span><span class="token operator">:</span> <span class="token number">2</span><span class="token punctuation">,</span>
      <span class="token property">&quot;onboard_date&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-04-08&quot;</span><span class="token punctuation">,</span>
      <span class="token property">&quot;created_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-04-08 07:17:36&quot;</span><span class="token punctuation">,</span>
      <span class="token property">&quot;updated_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-05-19 03:54:54&quot;</span><span class="token punctuation">,</span>
      <span class="token property">&quot;department&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
          <span class="token property">&quot;id&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
          <span class="token property">&quot;name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;RD&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;photo&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http://img.png&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;start_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;09:00&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;finish_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;17:00&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;supervisor_id&quot;</span><span class="token operator">:</span> <span class="token number">14</span><span class="token punctuation">,</span>
          <span class="token property">&quot;supervisor&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
              <span class="token property">&quot;id&quot;</span><span class="token operator">:</span> <span class="token number">14</span><span class="token punctuation">,</span>
              <span class="token property">&quot;name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;Bert-shoe&quot;</span><span class="token punctuation">,</span>
          <span class="token punctuation">}</span>
      <span class="token punctuation">}</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="post-edit-profile">POST Edit Profile</h2>

<h4 class="mume-header undefined" id="url-7">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/selfInfo
</pre><h4 class="mume-header undefined" id="headers-7">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Authorization&quot;</span><span class="token operator">:</span> <span class="token string">&quot;Bearer 9OIyltHvAH2DIaRjfidZtlI6F7KgUfThj39QeY5xYJFququQwm9egWCX9usp&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-7">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token comment">// &#x4EE5;&#x4E0B;&#x6B04;&#x4F4D;&#x7686;&#x70BA;&#x9078;&#x586B; &#x4F46;&#x81F3;&#x5C11;&#x50B3;&#x9001;&#x4E00;&#x500B;</span>
  <span class="token property">&quot;avatar&quot;</span><span class="token operator">:</span> <span class="token string">&quot;file&quot;</span><span class="token punctuation">,</span> <span class="token comment">// &#x9019;&#x908A;&#x50B3;&#x7684;&#x662F;&#x6A94;&#x6848;</span>
  <span class="token property">&quot;nick_name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;&#x66B1;&#x7A31;&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;birthday&quot;</span><span class="token operator">:</span> <span class="token string">&quot;1987-07-03&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;gender&quot;</span><span class="token operator">:</span> <span class="token number">3</span> <span class="token comment">// 0 = &#x672A;&#x9078;&#x64C7;, 1 = &#x7537;, 2 = &#x5973;, 3 = &#x4E0D;&#x544A;&#x77E5;</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="response-7">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-7">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token string">&quot;&quot;</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-user-record">GET User Record</h2>

<h4 class="mume-header undefined" id="url-8">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/records/<span class="token punctuation">{</span>timestamp<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="headers-8">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Authorization&quot;</span><span class="token operator">:</span> <span class="token string">&quot;Bearer 9OIyltHvAH2DIaRjfidZtlI6F7KgUfThj39QeY5xYJFququQwm9egWCX9usp&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-8">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-8">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-8">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;timestamp&quot;</span><span class="token operator">:</span> <span class="token number">1589247112000</span><span class="token punctuation">,</span>
      <span class="token property">&quot;records&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;record_id&quot;</span><span class="token operator">:</span> <span class="token number">2171</span><span class="token punctuation">,</span>
              <span class="token property">&quot;department_id&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;department&quot;</span><span class="token operator">:</span> <span class="token string">&quot;&#x5DE5;&#x7A0B;&#x90E8;&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;started_work_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;1588729067000&quot;</span><span class="token punctuation">,</span> <span class="token comment">// time long</span>
              <span class="token property">&quot;finished_work_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;1588729479000&quot;</span> <span class="token comment">// time long</span>
          <span class="token punctuation">}</span>
      <span class="token punctuation">]</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-user-temper">GET User Temper</h2>

<h4 class="mume-header undefined" id="url-9">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/temper?timestamp=<span class="token punctuation">{</span>timestamp<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="headers-9">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Authorization&quot;</span><span class="token operator">:</span> <span class="token string">&quot;Bearer 9OIyltHvAH2DIaRjfidZtlI6F7KgUfThj39QeY5xYJFququQwm9egWCX9usp&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-9">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-9">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-9">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;timestamp&quot;</span><span class="token operator">:</span> <span class="token number">1592873809</span><span class="token punctuation">,</span>
      <span class="token property">&quot;user_temper&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;id&quot;</span><span class="token operator">:</span> <span class="token number">16</span><span class="token punctuation">,</span>
              <span class="token property">&quot;temperature_val&quot;</span><span class="token operator">:</span> <span class="token number">36.1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;recognition_name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;ice&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;user_id&quot;</span><span class="token operator">:</span> <span class="token number">28</span><span class="token punctuation">,</span>
              <span class="token property">&quot;record_time&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-06-22 19:15:44&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;statu_id&quot;</span><span class="token operator">:</span> <span class="token number">0</span><span class="token punctuation">,</span>
              <span class="token property">&quot;equipment_verification_id&quot;</span><span class="token operator">:</span> <span class="token string">&quot;1391d8f51d82057f29ea2efe5404902e&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;created_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-06-22 11:15:47&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;updated_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-06-22 11:15:47&quot;</span>
          <span class="token punctuation">}</span><span class="token punctuation">,</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;id&quot;</span><span class="token operator">:</span> <span class="token number">18</span><span class="token punctuation">,</span>
              <span class="token property">&quot;temperature_val&quot;</span><span class="token operator">:</span> <span class="token number">36.0</span><span class="token punctuation">,</span>
              <span class="token property">&quot;recognition_name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;ice&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;user_id&quot;</span><span class="token operator">:</span> <span class="token number">28</span><span class="token punctuation">,</span>
              <span class="token property">&quot;record_time&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-06-23 08:56:44&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;statu_id&quot;</span><span class="token operator">:</span> <span class="token number">0</span><span class="token punctuation">,</span>
              <span class="token property">&quot;equipment_verification_id&quot;</span><span class="token operator">:</span> <span class="token string">&quot;8d9b9b757b3461ec9db76e1557e6e3ed&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;created_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-06-23 00:56:49&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;updated_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-06-23 00:56:49&quot;</span>
          <span class="token punctuation">}</span>
      <span class="token punctuation">]</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-user-areas">GET User Areas</h2>

<h4 class="mume-header undefined" id="url-10">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/areas
</pre><h4 class="mume-header undefined" id="headers-10">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Authorization&quot;</span><span class="token operator">:</span> <span class="token string">&quot;Bearer 9OIyltHvAH2DIaRjfidZtlI6F7KgUfThj39QeY5xYJFququQwm9egWCX9usp&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-10">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-10">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-10">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
      <span class="token punctuation">{</span>
          <span class="token property">&quot;id&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
          <span class="token property">&quot;name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;&#x5DE5;&#x7A0B;&#x90E8;&quot;</span>
      <span class="token punctuation">}</span><span class="token punctuation">,</span>
  <span class="token punctuation">]</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-area-list">GET Area List</h2>

<h4 class="mume-header undefined" id="url-11">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/areaList?timestamp=<span class="token punctuation">{</span>timestamp<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="headers-11">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-11">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-11">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-11">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;timestamp&quot;</span><span class="token operator">:</span> <span class="token number">1587532869</span><span class="token punctuation">,</span>
      <span class="token property">&quot;areas&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;id&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;&#x7A0B;&#x5F0F;&#x8A2D;&#x8A08;&#x5340;&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;num_devices&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;num_peoples&quot;</span> <span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span> <span class="token comment">//&#x5340;&#x57DF;&#x7576;&#x524D;&#x4EBA;&#x6578;</span>
              <span class="token property">&quot;max_num_peoples&quot;</span> <span class="token operator">:</span> <span class="token number">10</span><span class="token punctuation">,</span> <span class="token comment">//&#x5340;&#x57DF;&#x6700;&#x591A;&#x5BB9;&#x7D0D;&#x4EBA;&#x6578;</span>
              <span class="token property">&quot;area_statu_id&quot;</span><span class="token operator">:</span> <span class="token number">8</span><span class="token punctuation">,</span>
              <span class="token property">&quot;photo&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http:\\img.png&quot;</span><span class="token punctuation">,</span> <span class="token comment">//&#x5340;&#x57DF;&#x7167;&#x7247;</span>
              <span class="token property">&quot;lottie&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http:\\lottie.json&quot;</span><span class="token punctuation">,</span> <span class="token comment">//&#x5340;&#x57DF;&#x5E73;&#x9762;&#x52D5;&#x756B;&#x5716;</span>
              <span class="token property">&quot;location_photo&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http:\\img.png&quot;</span><span class="token punctuation">,</span> <span class="token comment">//&#x5340;&#x57DF;&#x5E73;&#x9762;&#x5716;</span>
              <span class="token property">&quot;location_photo_social_0&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http:\\img.png&quot;</span><span class="token punctuation">,</span> <span class="token comment">//&#x793E;&#x4EA4;&#x5B89;&#x5168; &#x5340;&#x57DF;&#x5E73;&#x9762;&#x5716;</span>
              <span class="token property">&quot;location_photo_social_1&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http:\\img.png&quot;</span><span class="token punctuation">,</span> <span class="token comment">//&#x793E;&#x4EA4;&#x5B89;&#x5168; &#x5340;&#x57DF;&#x5E73;&#x9762;&#x5716;</span>
              <span class="token property">&quot;location_photo_social_2&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http:\\img.png&quot;</span><span class="token punctuation">,</span> <span class="token comment">//&#x793E;&#x4EA4;&#x5B89;&#x5168; &#x5340;&#x57DF;&#x5E73;&#x9762;&#x5716;</span>
              <span class="token property">&quot;location_emergency_exit&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http:\\lottie.json&quot;</span> <span class="token comment">//&#x7DCA;&#x6025;&#x51FA;&#x53E3;&#x52D5;&#x756B;&#x5716;</span>
          <span class="token punctuation">}</span>
      <span class="token punctuation">]</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-starts-list">GET Starts List</h2>

<h4 class="mume-header undefined" id="url-12">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/status?timestamp=<span class="token punctuation">{</span>timestamp<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="headers-12">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-12">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-12">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-12">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;timestamp&quot;</span><span class="token operator">:</span> <span class="token number">1589155200</span><span class="token punctuation">,</span>
      <span class="token property">&quot;status&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;id&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;&#x9072;&#x5230;&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;created_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-04-23 00:00:00&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;updated_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-04-23 00:00:00&quot;</span>
          <span class="token punctuation">}</span>
      <span class="token punctuation">]</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-positions-list">GET Positions List</h2>

<h4 class="mume-header undefined" id="url-13">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/positions?timestamp=<span class="token punctuation">{</span>timestamp<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="headers-13">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-13">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-13">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-13">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;timestamp&quot;</span><span class="token operator">:</span> <span class="token number">1588748073</span><span class="token punctuation">,</span>
      <span class="token property">&quot;positions&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;id&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;&#x54E1;&#x5DE5;&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;level&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;created_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-03-31 05:47:34&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;updated_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-03-31 05:47:34&quot;</span>
          <span class="token punctuation">}</span>
      <span class="token punctuation">]</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-department-list">GET Department List</h2>

<h4 class="mume-header undefined" id="url-14">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/departmentList?timestamp=<span class="token punctuation">{</span>timestamp<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="headers-14">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-14">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-14">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-14">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;timestamp&quot;</span><span class="token operator">:</span> <span class="token number">1587532869</span><span class="token punctuation">,</span>
      <span class="token property">&quot;departments&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;id&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;&#x5DE5;&#x7A0B;&#x90E8;&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;start_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;10:00&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;finish_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;17:30&quot;</span>
          <span class="token punctuation">}</span>
      <span class="token punctuation">]</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-do-you-know">GET Do You Know</h2>

<h4 class="mume-header undefined" id="url-15">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/doyouknow?timestamp=<span class="token punctuation">{</span>timestamp<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="headers-15">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-15">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-15">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-15">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;timestamp&quot;</span><span class="token operator">:</span> <span class="token number">1582164669</span><span class="token punctuation">,</span>
      <span class="token property">&quot;doyouknows&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;id&quot;</span><span class="token operator">:</span> <span class="token number">13</span><span class="token punctuation">,</span>
              <span class="token property">&quot;message&quot;</span><span class="token operator">:</span> <span class="token string">&quot;80 steps a minute could be considered a leisurely pace; 100 steps a minute, could be a moderate to brisk pace; and 120 steps a minute, could be a fast pace.&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;active&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;created_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-01-30 03:05:59&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;updated_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-02-20 02:11:09&quot;</span>
          <span class="token punctuation">}</span>
      <span class="token punctuation">]</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-employees">GET Employees</h2>

<h4 class="mume-header undefined" id="url-16">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/employees
</pre><h4 class="mume-header undefined" id="headers-16">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Authorization&quot;</span><span class="token operator">:</span> <span class="token string">&quot;Bearer 9OIyltHvAH2DIaRjfidZtlI6F7KgUfThj39QeY5xYJFququQwm9egWCX9usp&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-16">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-16">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-16">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
      <span class="token punctuation">{</span>
          <span class="token property">&quot;employee_id&quot;</span><span class="token operator">:</span> <span class="token number">15</span><span class="token punctuation">,</span>
          <span class="token property">&quot;employee_name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;Aki wang&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;department_id&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
          <span class="token property">&quot;department&quot;</span><span class="token operator">:</span> <span class="token string">&quot;&#x5DE5;&#x7A0B;&#x90E8;&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;started_work_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;1589160641000&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;finished_work_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;1589182311000&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;employee_statu_id&quot;</span><span class="token operator">:</span> <span class="token number">5</span>
      <span class="token punctuation">}</span>
  <span class="token punctuation">]</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-employees-detail">GET Employees Detail</h2>

<h4 class="mume-header undefined" id="url-17">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/employeeDetails/<span class="token punctuation">{</span>employee_id<span class="token punctuation">}</span>/<span class="token punctuation">{</span>timestamp<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="headers-17">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Authorization&quot;</span><span class="token operator">:</span> <span class="token string">&quot;Bearer 9OIyltHvAH2DIaRjfidZtlI6F7KgUfThj39QeY5xYJFququQwm9egWCX9usp&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-17">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-17">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-17">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token comment">// &#x56DE;&#x50B3;&#x8207; user record(/api/records/{timestamp}) &#x540C;</span>
<span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;timestamp&quot;</span><span class="token operator">:</span> <span class="token number">1589171409000</span><span class="token punctuation">,</span>
      <span class="token property">&quot;records&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;record_id&quot;</span><span class="token operator">:</span> <span class="token number">2171</span><span class="token punctuation">,</span>
              <span class="token property">&quot;department_id&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;department&quot;</span><span class="token operator">:</span> <span class="token string">&quot;&#x5DE5;&#x7A0B;&#x90E8;&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;started_work_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;1588729067000&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;finished_work_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;1588729479000&quot;</span>
          <span class="token punctuation">}</span>
      <span class="token punctuation">]</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-categories">GET Categories</h2>

<h4 class="mume-header undefined" id="url-18">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/categories?timestamp=<span class="token punctuation">{</span>timestamp<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="headers-18">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Authorization&quot;</span><span class="token operator">:</span> <span class="token string">&quot;Bearer 9OIyltHvAH2DIaRjfidZtlI6F7KgUfThj39QeY5xYJFququQwm9egWCX9usp&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-18">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-18">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-18">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;timestamp&quot;</span><span class="token operator">:</span> <span class="token number">1590051600</span><span class="token punctuation">,</span>
      <span class="token property">&quot;category&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;id&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;&#x6D88;&#x9632;&#x8A2D;&#x5099;&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;active&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;created_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-05-21 09:00:00&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;updated_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-05-21 09:00:00&quot;</span>
          <span class="token punctuation">}</span>
      <span class="token punctuation">]</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-category-items">GET Category Items</h2>

<h4 class="mume-header undefined" id="url-19">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/categoryItems?category_id=<span class="token punctuation">{</span>category_id<span class="token punctuation">}</span>&amp;timestamp=<span class="token punctuation">{</span>timestamp<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="headers-19">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Authorization&quot;</span><span class="token operator">:</span> <span class="token string">&quot;Bearer 9OIyltHvAH2DIaRjfidZtlI6F7KgUfThj39QeY5xYJFququQwm9egWCX9usp&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-19">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-19">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-19">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;timestamp&quot;</span><span class="token operator">:</span> <span class="token number">1590138000</span><span class="token punctuation">,</span>
      <span class="token property">&quot;category_items&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;id&quot;</span><span class="token operator">:</span> <span class="token number">2</span><span class="token punctuation">,</span>
              <span class="token property">&quot;mac&quot;</span><span class="token operator">:</span> <span class="token string">&quot;11:22:33:44:55:66&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;name&quot;</span><span class="token operator">:</span> <span class="token string">&quot;coffee&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;photo&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http://img.png&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;level_id&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;area_id&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;active&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
              <span class="token property">&quot;detail&quot;</span><span class="token operator">:</span> <span class="token string">&quot;json string&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;pivot&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span><span class="token comment">// APP&#x53EF;&#x4EE5;&#x4E0D;&#x7528;&#x6536;&#x9019;&#x6BB5;</span>
                  <span class="token property">&quot;category_id&quot;</span><span class="token operator">:</span> <span class="token number">4</span><span class="token punctuation">,</span>
                  <span class="token property">&quot;item_id&quot;</span><span class="token operator">:</span> <span class="token number">2</span><span class="token punctuation">,</span>
                  <span class="token property">&quot;created_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-05-21 09:00:00&quot;</span><span class="token punctuation">,</span>
                  <span class="token property">&quot;updated_at&quot;</span><span class="token operator">:</span> <span class="token string">&quot;2020-05-21 09:00:00&quot;</span>
              <span class="token punctuation">}</span>
          <span class="token punctuation">}</span>
      <span class="token punctuation">]</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><pre data-role="codeBlock" data-info="json" class="language-json"><span class="token comment">// detail json string</span>
<span class="token punctuation">{</span>
  <span class="token property">&quot;title&quot;</span><span class="token operator">:</span> <span class="token string">&quot;HOW TO USE&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;list&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
      <span class="token punctuation">{</span>
          <span class="token property">&quot;title&quot;</span><span class="token operator">:</span> <span class="token string">&quot;step 1&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;video&quot;</span><span class="token punctuation">,</span> <span class="token comment">// video, image, text</span>
          <span class="token property">&quot;image&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http://img.png&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;video&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http://video.mp4&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;text&quot;</span><span class="token operator">:</span> <span class="token string">&quot;push door&quot;</span>
      <span class="token punctuation">}</span>
  <span class="token punctuation">]</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="get-notify">GET Notify</h2>

<h4 class="mume-header undefined" id="url-20">URL</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span><span class="token punctuation">{</span>server<span class="token punctuation">}</span><span class="token punctuation">}</span>/api/notifies?timestamp=<span class="token punctuation">{</span>timestamp<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="headers-20">Headers</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;Authorization&quot;</span><span class="token operator">:</span> <span class="token string">&quot;Bearer 9OIyltHvAH2DIaRjfidZtlI6F7KgUfThj39QeY5xYJFququQwm9egWCX9usp&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Content-Type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;Accept&quot;</span><span class="token operator">:</span> <span class="token string">&quot;application/json&quot;</span><span class="token punctuation">,</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="body-20">Body</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"></pre><h4 class="mume-header undefined" id="response-20">Response</h4>

<h5 class="mume-header undefined" id="status-code-200-20">status code 200</h5>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;result&quot;</span><span class="token operator">:</span> <span class="token boolean">true</span><span class="token punctuation">,</span>
  <span class="token property">&quot;data&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;timestamp&quot;</span><span class="token operator">:</span> <span class="token number">1590138000</span><span class="token punctuation">,</span>
      <span class="token property">&quot;notify&quot;</span><span class="token operator">:</span> <span class="token punctuation">[</span>
          <span class="token punctuation">{</span>
              <span class="token property">&quot;id&quot;</span><span class="token operator">:</span> <span class="token number">2</span><span class="token punctuation">,</span>
              <span class="token property">&quot;title&quot;</span><span class="token operator">:</span> <span class="token string">&quot;&#x63A8;&#x64AD;&#x6A19;&#x984C;&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;message&quot;</span><span class="token operator">:</span> <span class="token string">&quot;&#x63A8;&#x64AD;&#x5167;&#x6587;&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;image&quot;</span><span class="token operator">:</span> <span class="token string">&quot;http://img.png&quot;</span><span class="token punctuation">,</span>
              <span class="token property">&quot;statu_id&quot;</span><span class="token operator">:</span> <span class="token number">1</span><span class="token punctuation">,</span>
          <span class="token punctuation">}</span>
      <span class="token punctuation">]</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><hr>
<h2 class="mume-header" id="firebase-notify-message">Firebase Notify Message</h2>

<h3 class="mume-header undefined" id="%E4%BB%A5%E4%B8%8B%E7%82%BA-firebase-%E6%8E%A8%E6%92%AD%E7%B5%A6%E6%89%8B%E6%A9%9F%E7%AB%AF%E6%94%B6%E5%88%B0%E7%9A%84%E8%B3%87%E6%96%99">&#x4EE5;&#x4E0B;&#x70BA; Firebase &#x63A8;&#x64AD;&#x7D66;&#x624B;&#x6A5F;&#x7AEF;&#x6536;&#x5230;&#x7684;&#x8CC7;&#x6599;</h3>

<h4 class="mume-header undefined" id="android-kotlin-code">Android Kotlin code</h4>

<pre data-role="codeBlock" data-info="kotlin" class="language-kotlin"><span class="token keyword">class</span> MyFirebaseMessagingService <span class="token operator">:</span> <span class="token function">FirebaseMessagingService</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token punctuation">{</span>
  <span class="token keyword">override</span> <span class="token keyword">fun</span> <span class="token function">onMessageReceived</span><span class="token punctuation">(</span>remoteMessage<span class="token operator">:</span> RemoteMessage<span class="token punctuation">)</span> <span class="token punctuation">{</span>
      <span class="token keyword">super</span><span class="token punctuation">.</span><span class="token function">onMessageReceived</span><span class="token punctuation">(</span>remoteMessage<span class="token punctuation">)</span>
      <span class="token keyword">val</span> app <span class="token operator">=</span> application <span class="token keyword">as</span> App
      <span class="token keyword">val</span> jsonString <span class="token operator">=</span> <span class="token function">Gson</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">toJson</span><span class="token punctuation">(</span>remoteMessage<span class="token punctuation">.</span>data<span class="token punctuation">)</span> <span class="token comment">// &#x8A73;&#x898B;&#x4E0B;&#x65B9; Android Json String</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="android-json-string">Android Json String</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;img&quot;</span><span class="token operator">:</span> <span class="token string">&quot;img&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;url&quot;</span><span class="token operator">:</span> <span class="token string">&quot;url&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;body&quot;</span><span class="token operator">:</span> <span class="token string">&quot;long_body&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;normal&quot;</span><span class="token punctuation">,</span> <span class="token comment">// normal or emergency</span>
  <span class="token property">&quot;sound&quot;</span><span class="token operator">:</span> <span class="token string">&quot;default&quot;</span><span class="token punctuation">,</span> <span class="token comment">// default or spaceship_alarm.mp3</span>
  <span class="token property">&quot;title&quot;</span><span class="token operator">:</span> <span class="token string">&quot;title&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;message&quot;</span><span class="token operator">:</span> <span class="token string">&quot;message&quot;</span>
<span class="token punctuation">}</span>
</pre><h4 class="mume-header undefined" id="ios-swift-code">IOS Swift code</h4>

<pre data-role="codeBlock" data-info="swift" class="language-swift"><span class="token keyword">extension</span> <span class="token builtin">AppDelegate</span><span class="token punctuation">:</span> <span class="token builtin">UNUserNotificationCenterDelegate</span> <span class="token punctuation">{</span>
  <span class="token keyword">func</span> <span class="token function">userNotificationCenter</span><span class="token punctuation">(</span><span class="token number">_</span> center<span class="token punctuation">:</span> <span class="token builtin">UNUserNotificationCenter</span><span class="token punctuation">,</span> willPresent notification<span class="token punctuation">:</span> <span class="token builtin">UNNotification</span><span class="token punctuation">,</span> withCompletionHandler completionHandler<span class="token punctuation">:</span> @<span class="token function">escaping</span> <span class="token punctuation">(</span><span class="token builtin">UNNotificationPresentationOptions</span><span class="token punctuation">)</span> <span class="token operator">-</span><span class="token operator">&gt;</span> <span class="token builtin">Void</span><span class="token punctuation">)</span> <span class="token punctuation">{</span>
      <span class="token function">completionHandler</span><span class="token punctuation">(</span><span class="token punctuation">[</span><span class="token punctuation">.</span>badge<span class="token punctuation">,</span> <span class="token punctuation">.</span>sound<span class="token punctuation">,</span> <span class="token punctuation">.</span>alert<span class="token punctuation">]</span><span class="token punctuation">)</span>
      <span class="token keyword">let</span> userInfo <span class="token operator">=</span> notification<span class="token punctuation">.</span>request<span class="token punctuation">.</span>content<span class="token punctuation">.</span>userInfo
      <span class="token keyword">let</span> jsonString <span class="token operator">=</span> userInfo<span class="token punctuation">.</span><span class="token function">toJsonString</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token comment">// &#x8A73;&#x898B;&#x4E0B;&#x65B9; IOS Json String</span>
  <span class="token punctuation">}</span>

  <span class="token keyword">func</span> <span class="token function">userNotificationCenter</span><span class="token punctuation">(</span><span class="token number">_</span> center<span class="token punctuation">:</span> <span class="token builtin">UNUserNotificationCenter</span><span class="token punctuation">,</span> didReceive response<span class="token punctuation">:</span> <span class="token builtin">UNNotificationResponse</span><span class="token punctuation">,</span> withCompletionHandler completionHandler<span class="token punctuation">:</span> @<span class="token function">escaping</span> <span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token operator">-</span><span class="token operator">&gt;</span> <span class="token builtin">Void</span><span class="token punctuation">)</span> <span class="token punctuation">{</span>
      <span class="token keyword">let</span> userInfo <span class="token operator">=</span> response<span class="token punctuation">.</span>notification<span class="token punctuation">.</span>request<span class="token punctuation">.</span>content<span class="token punctuation">.</span>userInfo
      <span class="token keyword">let</span> jsonString <span class="token operator">=</span> userInfo<span class="token punctuation">.</span><span class="token function">toJsonString</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token comment">// &#x8A73;&#x898B;&#x4E0B;&#x65B9; IOS Json String</span>
      <span class="token function">completionHandler</span><span class="token punctuation">(</span><span class="token punctuation">)</span>
  <span class="token punctuation">}</span>
<span class="token punctuation">}</span>

</pre><h4 class="mume-header undefined" id="ios-json-string">IOS Json String</h4>

<pre data-role="codeBlock" data-info="json" class="language-json"><span class="token punctuation">{</span>
  <span class="token property">&quot;google.c.sender.id&quot;</span><span class="token operator">:</span> <span class="token string">&quot;380563663400&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;gcm.message_id&quot;</span><span class="token operator">:</span> <span class="token string">&quot;1594015994491326&quot;</span><span class="token punctuation">,</span>
  <span class="token property">&quot;type&quot;</span><span class="token operator">:</span> <span class="token string">&quot;normal&quot;</span><span class="token punctuation">,</span> <span class="token comment">// normal or emergency</span>
  <span class="token property">&quot;aps&quot;</span><span class="token operator">:</span> <span class="token punctuation">{</span>
      <span class="token property">&quot;alert&quot;</span><span class="token operator">:</span><span class="token punctuation">{</span>
          <span class="token property">&quot;title&quot;</span><span class="token operator">:</span><span class="token string">&quot;title&quot;</span><span class="token punctuation">,</span>
          <span class="token property">&quot;body&quot;</span><span class="token operator">:</span><span class="token string">&quot;mesaage&quot;</span>
      <span class="token punctuation">}</span><span class="token punctuation">,</span>
      <span class="token property">&quot;sound&quot;</span><span class="token operator">:</span> <span class="token string">&quot;default&quot;</span> <span class="token comment">// default or spaceship_alarm.mp3</span>
  <span class="token punctuation">}</span><span class="token punctuation">,</span>
  <span class="token property">&quot;google.c.a.e&quot;</span><span class="token operator">:</span> <span class="token string">&quot;1&quot;</span>
<span class="token punctuation">}</span>
</pre>
    </div>
    <div class="md-sidebar-toc"><ul>
<li><a href="#scanner-api">Scanner API</a>
<ul>
<li><a href="#post-upload-records">POST Upload Records</a></li>
<li><a href="#get-area-and-wifi-setting">GET Area and wifi setting</a></li>
<li><a href="#get-check-network">GET Check Network</a></li>
<li><a href="#get-updata-file">GET Updata File</a></li>
</ul>
</li>
<li><a href="#app-api">App API</a>
<ul>
<li><a href="#get-businessinfo">GET BusinessInfo</a></li>
<li><a href="#post-login">POST Login</a></li>
<li><a href="#get-profile">GET Profile</a></li>
<li><a href="#post-edit-profile">POST Edit Profile</a></li>
<li><a href="#get-user-record">GET User Record</a></li>
<li><a href="#get-user-temper">GET User Temper</a></li>
<li><a href="#get-user-areas">GET User Areas</a></li>
<li><a href="#get-area-list">GET Area List</a></li>
<li><a href="#get-starts-list">GET Starts List</a></li>
<li><a href="#get-positions-list">GET Positions List</a></li>
<li><a href="#get-department-list">GET Department List</a></li>
<li><a href="#get-do-you-know">GET Do You Know</a></li>
<li><a href="#get-employees">GET Employees</a></li>
<li><a href="#get-employees-detail">GET Employees Detail</a></li>
<li><a href="#get-categories">GET Categories</a></li>
<li><a href="#get-category-items">GET Category Items</a></li>
<li><a href="#get-notify">GET Notify</a></li>
<li><a href="#firebase-notify-message">Firebase Notify Message</a></li>
</ul>
</li>
</ul>
</div>
    <a id="sidebar-toc-btn">&#x2261;</a>








<script>
document.body.setAttribute('html-show-sidebar-toc', true)
var sidebarTOCBtn = document.getElementById('sidebar-toc-btn')
sidebarTOCBtn.addEventListener('click', function(event) {
event.stopPropagation()
if (document.body.hasAttribute('html-show-sidebar-toc')) {
  document.body.removeAttribute('html-show-sidebar-toc')
} else {
  document.body.setAttribute('html-show-sidebar-toc', true)
}
})
</script>


  </body></html>
