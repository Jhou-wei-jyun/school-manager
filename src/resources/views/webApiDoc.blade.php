<!DOCTYPE html><html><head>
    <title>ims_web_api</title>
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
    <h2 class="mume-header" id="departmentdepartment"><a href="#department">Department</a></h2>

<ul>
<li><strong><a href="#dept-store">Store</a></strong></li>
<li><strong><a href="#dept-getall">Departments</a></strong></li>
<li><strong><a href="#dept-delete">Delete department</a></strong></li>
</ul>
<h2 class="mume-header" id="devicedevice"><a href="#device">Device</a></h2>

<ul>
<li><strong><a href="#devices">Devices</a></strong></li>
<li><strong><a href="#device-store">Store</a></strong></li>
<li><strong><a href="#device-delete">Delete</a></strong></li>
<li><strong><a href="#device-upload-file">Upload file</a></strong></li>
<li><strong><a href="#device-delete-file">Delete file</a></strong></li>
<li><strong><a href="#device-get-records">Get scan records</a></strong></li>
<li><strong><a href="#device-edit">Edit device info</a></strong></li>
<li><strong><a href="#devices-get">Get new deivce</a></strong></li>
<li><strong><a href="#device-get-files">Get python files</a></strong></li>
</ul>
<h2 class="mume-header" id="recordsrecord"><a href="#record">Records</a></h2>

<ul>
<li><strong><a href="#records-get">Get records</a></strong></li>
</ul>
<h2 class="mume-header" id="employeeemployee"><a href="#employee">Employee</a></h2>

<ul>
<li><strong><a href="#employees-get-record">Get employees records</a></strong></li>
<li><strong><a href="#employees-get">Get employees</a></strong></li>
<li><strong><a href="#employee-store">Store</a></strong></li>
<li><strong><a href="#employee-update">Update employee info</a></strong></li>
<li><strong><a href="#employee-delete">Delete employee </a></strong></li>
</ul>
<h2 class="mume-header" id="notificationnotify"><a href="#notify">Notification</a></h2>

<ul>
<li><strong><a href="#notify-users">Get all users</a></strong></li>
<li><strong><a href="#notify-push">Push notify</a></strong></li>
</ul>
<h2></h2><h3 id="h3-iddepartmentdepartmenth3" class="mume-header">Department</h3>

<blockquote>
<h3></h3><h3 id="dept-store">Store</h3>
</blockquote>
<hr>
<p>&#x65B0;&#x589E;&#x4E00;&#x500B;&#x65B0;&#x90E8;&#x9580;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/department/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>POST</code></p>
</li>
<li>
<p><strong>URL Params</strong></p>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code> name=[string]
supervisor=[integer]
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Not Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code> startTime=[string]
finishTime=[string]
editImage=[file]
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong></li>
</ul>
</li>
</ul>
<pre data-role="codeBlock" data-info class="language-"><code>    {
      &quot;result&quot;: true,
      &quot;data&quot;: {
          &quot;id&quot;: 10,
          &quot;owner_name&quot;: Bert
      }
  }
</code></pre><ul>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>{ message : &quot;&quot; }
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">  <span class="token keyword">let</span> formData <span class="token operator">=</span> <span class="token keyword">new</span> <span class="token class-name">FormData</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">;</span>

<span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>name<span class="token punctuation">)</span> <span class="token punctuation">{</span>
    formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&apos;name&apos;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>name<span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span>
<span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>startTime<span class="token punctuation">)</span> <span class="token punctuation">{</span>
    formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;startTime&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>startTime<span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span>
<span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>selectedOption<span class="token punctuation">)</span> <span class="token punctuation">{</span>
    formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;supervisor&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>selectedOption<span class="token punctuation">.</span>id<span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span>
<span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>finishTime<span class="token punctuation">)</span> <span class="token punctuation">{</span>
    formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;finishTime&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>finishTime<span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span>
    <span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>editImage<span class="token punctuation">)</span> <span class="token punctuation">{</span>
    formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;editImage&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>editImage<span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span>
axios<span class="token punctuation">.</span><span class="token function">post</span><span class="token punctuation">(</span><span class="token string">&quot;department&quot;</span><span class="token punctuation">,</span> formData<span class="token punctuation">,</span> <span class="token punctuation">{</span>
        headers<span class="token punctuation">:</span> <span class="token punctuation">{</span>
            <span class="token string">&quot;Content-Type&quot;</span><span class="token punctuation">:</span> <span class="token string">&quot;multipart/form-data&quot;</span>
        <span class="token punctuation">}</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">finally</span><span class="token punctuation">(</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="dept-getall">Get Departments</h3>
</blockquote>
<hr>
<p>&#x53D6;&#x5F97;&#x6240;&#x6709;&#x90E8;&#x9580;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/departments/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>GET</code></p>
</li>
<li>
<p><strong>URL Params</strong></p>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code> None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Not Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code> None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>{
&quot;data&quot;: [
  {
      &quot;id&quot;:1,
      &quot;name&quot;:&quot;RD&quot;,
      &quot;photo&quot;:&quot;&quot;,
      &quot;start_at&quot;:&quot;09:00&quot;,
      &quot;finish_at&quot;:&quot;18:00&quot;,
      &quot;created_at&quot;:null,
      &quot;updated_at&quot;:null,
      &quot;supervisor_id&quot;:1,
      &quot;owner_name&quot;:&quot;admin&quot;,
      &quot;avatar&quot;:null,
      &quot;showUp&quot;:0,
      &quot;total&quot;:10,
      &quot;percent&quot;:0
  },{
      &quot;id&quot;:2,
      &quot;name&quot;:&quot;NEO&quot;,
      &quot;photo&quot;:&quot;&quot;,
      &quot;start_at&quot;:&quot;09:00&quot;,
      &quot;finish_at&quot;:&quot;18:00&quot;,
      &quot;created_at&quot;:null,
      &quot;updated_at&quot;:null,
      &quot;supervisor_id&quot;:1,
      &quot;owner_name&quot;:&quot;admin&quot;,
      &quot;avatar&quot;:null,
      &quot;showUp&quot;:0,
      &quot;total&quot;:4,
      &quot;percent&quot;:0
  }
]
}
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">   axios<span class="token punctuation">.</span><span class="token keyword">get</span><span class="token punctuation">(</span><span class="token string">&apos;departments&apos;</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
        <span class="token keyword">this</span><span class="token punctuation">.</span>departmentsData <span class="token operator">=</span> response<span class="token punctuation">.</span>data<span class="token punctuation">;</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="dept-delete">Delete Department</h3>
</blockquote>
<hr>
<p>&#x522A;&#x9664;&#x90E8;&#x9580;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/department/:id</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>PUT</code></p>
</li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>id=[integer]
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code> None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Not Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code> None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>{
  &quot;result&quot;:true,
  &quot;data&quot;:&quot;&quot;
}
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">  axios<span class="token punctuation">.</span><span class="token function">put</span><span class="token punctuation">(</span><span class="token string">&apos;department?id=&apos;</span> <span class="token operator">+</span> id<span class="token punctuation">)</span>
<span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span>
    <span class="token punctuation">}</span>
<span class="token punctuation">}</span><span class="token punctuation">)</span>
</pre></li>
</ul>
<h2></h2><h3 id="h3-iddevicedeviceh3" class="mume-header">Device</h3>

<blockquote>
<h3></h3><h3 id="devices">Get Devices</h3>
</blockquote>
<hr>
<p>&#x53D6;&#x5F97;&#x6240;&#x6709;&#x6A39;&#x8393;&#x6D3E;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/devices/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>GET</code></p>
</li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code> None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Not Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code> None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>{
  [
  {
      &quot;id&quot;:52,
      &quot;area_id&quot;:3,
      &quot;name&quot;:&quot;&#x4E3B;&#x7BA1;&#x5BA4;&quot;,
      &quot;mac&quot;:&quot;B8:27:EB:D0:55:BF&quot;,
      &quot;ip&quot;:&quot;10.112.10.117&quot;,
      &quot;ssid&quot;:&quot;Shoesconn&quot;,
      &quot;password&quot;:&quot;Shoesconn168&quot;,
      &quot;created_at&quot;:&quot;2020-06-11 07:15:51&quot;,
      &quot;updated_at&quot;:&quot;2020-06-17 06:30:18&quot;,
      &quot;rssi_setting&quot;:12,
      &quot;area_name&quot;:&quot;&#x54E1;&#x5DE5;&#x8FA6;&#x516C;&#x5340;&quot;,
      &quot;is_alive&quot;:false
  },{
      &quot;id&quot;:53,
      &quot;area_id&quot;:3,
      &quot;name&quot;:&quot;&#x958B;&#x7248;&#x8A13;&#x7DF4;&#x5BA4;&quot;,
      &quot;mac&quot;:&quot;B8:27:EB:74:61:6C&quot;,
      &quot;ip&quot;:&quot;10.112.10.119&quot;,
      &quot;ssid&quot;:&quot;Shoesconn&quot;,
      &quot;password&quot;:&quot;Shoesconn168&quot;,
      &quot;created_at&quot;:&quot;2020-06-11 07:34:29&quot;,
      &quot;updated_at&quot;:&quot;2020-06-17 06:30:15&quot;,
      &quot;rssi_setting&quot;:10,
      &quot;area_name&quot;:&quot;&#x54E1;&#x5DE5;&#x8FA6;&#x516C;&#x5340;&quot;,
      &quot;is_alive&quot;:false}
      ,]
}
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">  axios<span class="token punctuation">.</span><span class="token keyword">get</span><span class="token punctuation">(</span><span class="token string">&apos;devices&apos;</span><span class="token punctuation">)</span>
    <span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span>
    <span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span>
    <span class="token punctuation">.</span><span class="token function">finally</span><span class="token punctuation">(</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="device-store">Store</h3>
</blockquote>
<hr>
<p>&#x65B0;&#x589E;&#x6A39;&#x8393;&#x6D3E;&#x88DD;&#x7F6E;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/device/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>POST</code></p>
</li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code> name=[string]
mac=[string]
area=[integer]
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Not Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>ssid=[string]
password=[string]
rssi=[integer]
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>{
  &quot;result&quot;:true,
  &quot;data&quot;:123
}
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>{
  &quot;result&quot;: false,
  &quot;data&quot;:&quot;&quot;
}
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">     <span class="token keyword">let</span> formData <span class="token operator">=</span> <span class="token keyword">new</span> <span class="token class-name">FormData</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">;</span>

    <span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>mac<span class="token punctuation">)</span> <span class="token punctuation">{</span>
        formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&apos;mac&apos;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>mac<span class="token punctuation">)</span><span class="token punctuation">;</span>
    <span class="token punctuation">}</span>
    <span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>name<span class="token punctuation">)</span> <span class="token punctuation">{</span>
        formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;name&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>name<span class="token punctuation">)</span><span class="token punctuation">;</span>
    <span class="token punctuation">}</span>
    <span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>selectedOption<span class="token punctuation">)</span> <span class="token punctuation">{</span>
        formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;area&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>selectedOption<span class="token punctuation">)</span><span class="token punctuation">;</span>
    <span class="token punctuation">}</span>
    <span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>ssid<span class="token punctuation">)</span> <span class="token punctuation">{</span>
        formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;ssid&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>ssid<span class="token punctuation">)</span><span class="token punctuation">;</span>
    <span class="token punctuation">}</span>
    <span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>password<span class="token punctuation">)</span> <span class="token punctuation">{</span>
        formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;password&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>password<span class="token punctuation">)</span><span class="token punctuation">;</span>
    <span class="token punctuation">}</span>
    <span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>rssi<span class="token punctuation">)</span> <span class="token punctuation">{</span>
        formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;rssi&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>rssi<span class="token punctuation">)</span><span class="token punctuation">;</span>
    <span class="token punctuation">}</span>
    axios
        <span class="token punctuation">.</span><span class="token function">post</span><span class="token punctuation">(</span><span class="token string">&quot;editDevice&quot;</span><span class="token punctuation">,</span> formData<span class="token punctuation">,</span> <span class="token punctuation">{</span>
            headers<span class="token punctuation">:</span> <span class="token punctuation">{</span>
                <span class="token string">&quot;Content-Type&quot;</span><span class="token punctuation">:</span> <span class="token string">&quot;multipart/form-data&quot;</span>
            <span class="token punctuation">}</span>
        <span class="token punctuation">}</span><span class="token punctuation">)</span>
        <span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span>
        <span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span>
        <span class="token punctuation">.</span><span class="token function">finally</span><span class="token punctuation">(</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="device-delete">Delete</h3>
</blockquote>
<hr>
<p>&#x522A;&#x6389;&#x6A39;&#x8393;&#x6D3E;&#x88DD;&#x7F6E;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/device/:id</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>PUT</code></p>
</li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>id=[integer]
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Not Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">     axios<span class="token punctuation">.</span><span class="token function">put</span><span class="token punctuation">(</span><span class="token string">&apos;device?id=&apos;</span> <span class="token operator">+</span> id<span class="token punctuation">)</span>
    <span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span>
    <span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="device-upload-file">Upload file</h3>
</blockquote>
<hr>
<p>&#x4E0A;&#x50B3;&#x6A39;&#x8393;&#x6D3E;&#x6240;&#x9700;&#x6A94;&#x6848;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/files/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>POST</code></p>
</li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>file=[file]
</code></pre><blockquote>
<p><strong>Optional:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">    <span class="token keyword">let</span> formData <span class="token operator">=</span> <span class="token keyword">new</span> <span class="token class-name">FormData</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
    <span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>file<span class="token punctuation">)</span> <span class="token punctuation">{</span>
        formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;file&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>file<span class="token punctuation">)</span><span class="token punctuation">;</span>
    <span class="token punctuation">}</span>
    axios<span class="token punctuation">.</span><span class="token function">post</span><span class="token punctuation">(</span><span class="token string">&quot;files&quot;</span><span class="token punctuation">,</span> formData<span class="token punctuation">,</span> <span class="token punctuation">{</span>
        headers<span class="token punctuation">:</span> <span class="token punctuation">{</span>
            <span class="token string">&quot;Content-Type&quot;</span><span class="token punctuation">:</span> <span class="token string">&quot;multipart/form-data&quot;</span>
        <span class="token punctuation">}</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span>
    <span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span>
    <span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="device-delete-file">Delete file</h3>
</blockquote>
<hr>
<p>&#x522A;&#x9664;&#x6A39;&#x8393;&#x6D3E;&#x6A94;&#x6848;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/file/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>PUT</code></p>
</li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>name=[string]
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre><blockquote>
<p><strong>Optional:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">   axios<span class="token punctuation">.</span><span class="token function">put</span><span class="token punctuation">(</span><span class="token string">&apos;file?name=&apos;</span> <span class="token operator">+</span> file<span class="token punctuation">)</span>
      <span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="device-get-records">Get scan records</h3>
</blockquote>
<hr>
<p>&#x53D6;&#x5F97;&#x76EE;&#x524D;&#x6383;&#x63CF;&#x5230;&#x7684;cube</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/scannerRecords/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>GET</code></p>
</li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br>
<code>&#x6A39;&#x8393;&#x6D3E;&#x7684;mac adress</code></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>mac=[string]
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre><blockquote>
<p><strong>Optional:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">    axios<span class="token punctuation">.</span><span class="token keyword">get</span><span class="token punctuation">(</span><span class="token string">&apos;scannerRecords?mac=&apos;</span> <span class="token operator">+</span> <span class="token keyword">this</span><span class="token punctuation">.</span>mac<span class="token punctuation">)</span>
  <span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span>
  <span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="device-edit">Edit Device info</h3>
</blockquote>
<hr>
<p>&#x7DE8;&#x8F2F;&#x6A39;&#x8393;&#x6D3E;&#x8CC7;&#x8A0A;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/editDevice/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>POST</code></p>
</li>
<li>
<p><strong>Headers</strong></p>
<pre data-role="codeBlock" data-info class="language-"><code>&quot;Content-Type&quot;: &quot;multipart/form-data&quot;
</code></pre></li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre><blockquote>
<p><strong>Optional:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>mac=[string]
name=[string]
area=[integer]
ssid=[string]
password=[string]
rssi=[integer]
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">  axios
<span class="token punctuation">.</span><span class="token function">post</span><span class="token punctuation">(</span><span class="token string">&quot;editDevice&quot;</span><span class="token punctuation">,</span> formData<span class="token punctuation">,</span> <span class="token punctuation">{</span>
    headers<span class="token punctuation">:</span> <span class="token punctuation">{</span>
        <span class="token string">&quot;Content-Type&quot;</span><span class="token punctuation">:</span> <span class="token string">&quot;multipart/form-data&quot;</span>
    <span class="token punctuation">}</span><span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">finally</span><span class="token punctuation">(</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="devices-get">Get scanner</h3>
</blockquote>
<hr>
<p>&#x53D6;&#x5F97;&#x672A;&#x8A2D;&#x5B9A;&#x4E4B;&#x6A39;&#x8393;&#x6D3E;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/scanner/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>GET</code></p>
</li>
<li>
<p><strong>Headers</strong></p>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre><blockquote>
<p><strong>Optional:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">  axios
<span class="token punctuation">.</span><span class="token keyword">get</span><span class="token punctuation">(</span><span class="token string">&apos;scanner&apos;</span><span class="token punctuation">)</span>
<span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>

<span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>

<span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">finally</span><span class="token punctuation">(</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
<span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="device-get-files">Get device python files</h3>
</blockquote>
<hr>
<p>&#x53D6;&#x5F97;&#x6240;&#x6709;python&#x6A94;&#x6848;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/allFiles/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>GET</code></p>
</li>
<li>
<p><strong>Headers</strong></p>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre><blockquote>
<p><strong>Optional:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript"> axios<span class="token punctuation">.</span><span class="token keyword">get</span><span class="token punctuation">(</span><span class="token string">&apos;allFiles&apos;</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">finally</span><span class="token punctuation">(</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</pre></li>
</ul>
<h2></h2><h3 id="h3-idrecordrecordh3" class="mume-header">Record</h3>

<blockquote>
<h3></h3><h3 id="records-get">Get users records</h3>
</blockquote>
<hr>
<p>&#x53D6;&#x5F97;&#x67D0;&#x90E8;&#x9580;&#x7684;&#x54E1;&#x5DE5;&#x7D00;&#x9304;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/employees/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>GET</code></p>
</li>
<li>
<p><strong>Headers</strong></p>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br>
``</p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>id=[integer]
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre><blockquote>
<p><strong>Optional:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript"> axios<span class="token punctuation">.</span><span class="token keyword">get</span><span class="token punctuation">(</span><span class="token string">&apos;employees?id=&apos;</span><span class="token operator">+</span>id<span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">finally</span><span class="token punctuation">(</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</pre></li>
</ul>
<h2></h2><h3 id="h3-idemployeeemployee" class="mume-header">Employee</h3>

<blockquote>
<h3></h3><h3 id="employees-get-record">Get all employees records</h3>
</blockquote>
<hr>
<p>&#x53D6;&#x5F97;&#x54E1;&#x5DE5;&#x7D00;&#x9304;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/departmentsName/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>GET</code></p>
</li>
<li>
<p><strong>Headers</strong></p>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre><blockquote>
<p><strong>Optional:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript"> axios<span class="token punctuation">.</span><span class="token keyword">get</span><span class="token punctuation">(</span><span class="token string">&apos;departmentsName&apos;</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">finally</span><span class="token punctuation">(</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="employees-get">Get department employees</h3>
</blockquote>
<hr>
<p>&#x53D6;&#x5F97;&#x67D0;&#x90E8;&#x9580;&#x7684;&#x6240;&#x6709;&#x54E1;&#x5DE5;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/departmentEmployees/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>GET</code></p>
</li>
<li>
<p><strong>Headers</strong></p>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre><blockquote>
<p><strong>Optional:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript"> axios<span class="token punctuation">.</span><span class="token keyword">get</span><span class="token punctuation">(</span><span class="token string">&apos;departmentEmployees&apos;</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">finally</span><span class="token punctuation">(</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="employee-store">Store</h3>
</blockquote>
<hr>
<p>&#x65B0;&#x589E;&#x54E1;&#x5DE5;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/employee/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>POST</code></p>
</li>
<li>
<p><strong>Headers</strong></p>
<pre data-role="codeBlock" data-info class="language-"><code>&quot;Content-Type&quot;: &quot;multipart/form-data&quot;
</code></pre></li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>namp=[string]
account=[string]
mac=[string]
start_date=[string]
position_id=[integer]
department_id=[integer]
genger=[integer]
</code></pre><blockquote>
<p><strong>Optional:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">  axios<span class="token punctuation">.</span><span class="token function">post</span><span class="token punctuation">(</span><span class="token string">&apos;employee&apos;</span><span class="token punctuation">,</span> formData<span class="token punctuation">,</span> <span class="token punctuation">{</span>
    headers<span class="token punctuation">:</span> <span class="token punctuation">{</span>
        <span class="token string">&quot;Content-Type&quot;</span><span class="token punctuation">:</span> <span class="token string">&quot;multipart/form-data&quot;</span>
    <span class="token punctuation">}</span>
<span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
<span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">finally</span><span class="token punctuation">(</span><span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
<span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="employee-update">Update employee info</h3>
</blockquote>
<hr>
<p>&#x4FEE;&#x6539;&#x54E1;&#x5DE5;&#x8CC7;&#x8A0A;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/updateEmployee/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>POST</code></p>
</li>
<li>
<p><strong>Headers</strong></p>
<pre data-role="codeBlock" data-info class="language-"><code>&quot;Content-Type&quot;: &quot;multipart/form-data&quot;
</code></pre></li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>id=[integer]
name=[string]
account=[string]
mac=[string]
position_id=[integer]
imageFile=[file]
</code></pre><blockquote>
<p><strong>Optional:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">   <span class="token keyword">let</span> formData <span class="token operator">=</span> <span class="token keyword">new</span> <span class="token class-name">FormData</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>editID<span class="token punctuation">)</span> <span class="token punctuation">{</span>
    formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;id&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>editID<span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span>
<span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>name<span class="token punctuation">)</span> <span class="token punctuation">{</span>
    formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;name&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>name<span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span>
<span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>mac<span class="token punctuation">)</span> <span class="token punctuation">{</span>
    formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;mac&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>mac<span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span>
<span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>account<span class="token punctuation">)</span> <span class="token punctuation">{</span>
    formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;account&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>account<span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span>
<span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>editImage<span class="token punctuation">)</span> <span class="token punctuation">{</span>
    formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;imageFile&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>editImage<span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span>
<span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>selectPosition<span class="token punctuation">)</span> <span class="token punctuation">{</span>
    formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;position_id&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>selectPosition<span class="token punctuation">)</span><span class="token punctuation">;</span>
<span class="token punctuation">}</span>
axios<span class="token punctuation">.</span><span class="token function">post</span><span class="token punctuation">(</span><span class="token string">&apos;updateEmployee&apos;</span><span class="token punctuation">,</span> formData<span class="token punctuation">,</span> <span class="token punctuation">{</span>
        headers<span class="token punctuation">:</span> <span class="token punctuation">{</span>
            <span class="token string">&quot;Content-Type&quot;</span><span class="token punctuation">:</span> <span class="token string">&quot;multipart/form-data&quot;</span>
        <span class="token punctuation">}</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span>
    <span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
        <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="employee-delete">Delete employee</h3>
</blockquote>
<hr>
<p>&#x522A;&#x9664;&#x54E1;&#x5DE5;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/deleteEmployee/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>PUT</code></p>
</li>
<li>
<p><strong>Headers</strong></p>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>id=[integer]
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code> None
</code></pre><blockquote>
<p><strong>Optional:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">    axios<span class="token punctuation">.</span><span class="token function">put</span><span class="token punctuation">(</span><span class="token string">&apos;deleteEmployee?id=&apos;</span> <span class="token operator">+</span> user_id<span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
        <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
    <span class="token punctuation">}</span><span class="token punctuation">)</span>
</pre></li>
</ul>
<h2></h2><h3 id="h3-idnotifynotificationh3" class="mume-header">Notification</h3>

<blockquote>
<h3></h3><h3 id="notify-users">Get users for notify</h3>
</blockquote>
<hr>
<p>&#x53D6;&#x5F97;&#x6240;&#x6709;&#x54E1;&#x5DE5;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/users/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>GET</code></p>
</li>
<li>
<p><strong>Headers</strong></p>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>id=[integer]
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code> None
</code></pre><blockquote>
<p><strong>Optional:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">    axios<span class="token punctuation">.</span><span class="token keyword">get</span><span class="token punctuation">(</span><span class="token string">&apos;users&apos;</span><span class="token punctuation">)</span>
  <span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span>
  <span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span><span class="token punctuation">}</span><span class="token punctuation">)</span>
</pre></li>
</ul>
<blockquote>
<h3></h3><h3 id="notify-push">Push notification</h3>
</blockquote>
<hr>
<p>&#x63A8;&#x64AD;</p>
<ul>
<li>
<p><strong>URL</strong></p>
<p><code>/notify/</code></p>
</li>
<li>
<p><strong>Method:</strong></p>
<p><code>POST</code></p>
</li>
<li>
<p><strong>Headers</strong></p>
<pre data-role="codeBlock" data-info class="language-"><code>&quot;Content-Type&quot;: &quot;multipart/form-data&quot;
</code></pre></li>
<li>
<p><strong>URL Params</strong></p>
<blockquote>
<p><strong>Required:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Data Params</strong></p>
<blockquote>
<p><strong>Required:</strong><br></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code> title=[string]
message=[string]
user=[integer]
type=[string]
</code></pre><blockquote>
<p><strong>Optional:</strong></p>
</blockquote>
<pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
<li>
<p><strong>Success Response:</strong></p>
<ul>
<li><strong>Code:</strong> 200 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Error Response:</strong></p>
<ul>
<li><strong>Code:</strong> 400 <br><br>
<strong>Content:</strong><pre data-role="codeBlock" data-info class="language-"><code>None
</code></pre></li>
</ul>
</li>
<li>
<p><strong>Sample Call:</strong></p>
<pre data-role="codeBlock" data-info="javascript" class="language-javascript">   <span class="token keyword">let</span> formData <span class="token operator">=</span> <span class="token keyword">new</span> <span class="token class-name">FormData</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
        <span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>title<span class="token punctuation">)</span> <span class="token punctuation">{</span>
            formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;title&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>title<span class="token punctuation">)</span><span class="token punctuation">;</span>
        <span class="token punctuation">}</span>
        <span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>message<span class="token punctuation">)</span> <span class="token punctuation">{</span>
            formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;message&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>message<span class="token punctuation">)</span><span class="token punctuation">;</span>
        <span class="token punctuation">}</span>
        <span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>selectUser<span class="token punctuation">)</span> <span class="token punctuation">{</span>
            formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;user&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>selectUser<span class="token punctuation">)</span><span class="token punctuation">;</span>
        <span class="token punctuation">}</span>
        <span class="token keyword">if</span> <span class="token punctuation">(</span><span class="token keyword">this</span><span class="token punctuation">.</span>selectType<span class="token punctuation">)</span> <span class="token punctuation">{</span>
            formData<span class="token punctuation">.</span><span class="token function">append</span><span class="token punctuation">(</span><span class="token string">&quot;type&quot;</span><span class="token punctuation">,</span> <span class="token keyword">this</span><span class="token punctuation">.</span>selectType<span class="token punctuation">)</span><span class="token punctuation">;</span>
        <span class="token punctuation">}</span>

        axios<span class="token punctuation">.</span><span class="token function">post</span><span class="token punctuation">(</span><span class="token string">&quot;notify&quot;</span><span class="token punctuation">,</span> formData<span class="token punctuation">,</span> <span class="token punctuation">{</span>
            headers<span class="token punctuation">:</span> <span class="token punctuation">{</span>
                <span class="token string">&quot;Content-Type&quot;</span><span class="token punctuation">:</span> <span class="token string">&quot;multipart/form-data&quot;</span>
            <span class="token punctuation">}</span>
        <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">then</span><span class="token punctuation">(</span><span class="token parameter">response</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>

        <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">.</span><span class="token function">catch</span><span class="token punctuation">(</span><span class="token parameter">error</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>

            <span class="token punctuation">}</span><span class="token punctuation">)</span>
        <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</pre></li>
</ul>

    </div>
    <div class="md-sidebar-toc"><ul>
<li><a href="#departmentdepartment">Department</a></li>
<li><a href="#devicedevice">Device</a></li>
<li><a href="#recordsrecord">Records</a></li>
<li><a href="#employeeemployee">Employee</a></li>
<li><a href="#notificationnotify">Notification</a></li>
<li><a href="#h3-iddepartmentdepartmenth3">Department</a></li>
<li><a href="#h3-iddevicedeviceh3">Device</a></li>
<li><a href="#h3-idrecordrecordh3">Record</a></li>
<li><a href="#h3-idemployeeemployee"></a><h3 id="employee"><a href="#h3-idemployeeemployee">Employee</a></h3></li>
<li><a href="#h3-idnotifynotificationh3">Notification</a></li>
</ul>
</div>
    <a id="sidebar-toc-btn">&#x2261;</a>








<script>

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
