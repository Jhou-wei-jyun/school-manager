<!DOCTYPE html><html><head>
    <title>ims_serv_setting</title>
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
    <h1 class="mume-header" id="%E5%AE%89%E8%A3%9D%E7%B3%BB%E7%B5%B1">&#x5B89;&#x88DD;&#x7CFB;&#x7D71;</h1>

<h2 class="mume-header" id="%E6%9B%B4%E6%96%B0%E5%A5%97%E4%BB%B6%E5%88%97%E8%A1%A8">&#x66F4;&#x65B0;&#x5957;&#x4EF6;&#x5217;&#x8868;</h2>

<p><code>sudo apt-get update</code></p>
<h2 class="mume-header" id="%E5%AE%89%E8%A3%9D%E5%B7%A5%E5%85%B7">&#x5B89;&#x88DD;&#x5DE5;&#x5177;</h2>

<p><code>sudo apt-get -y install git curl wget zip unzip</code></p>
<h2 class="mume-header" id="%E5%AE%89%E8%A3%9D-nginx">&#x5B89;&#x88DD; Nginx</h2>

<p><code>sudo apt-get -y install nginx</code></p>
<h2 class="mume-header" id="%E5%AE%89%E8%A3%9D-php-72">&#x5B89;&#x88DD; PHP 7.2</h2>

<p><code>sudo apt-get -y install php7.2 php7.2-mysqlnd php7.2-mbstring php7.2-pdo php7.2-xml php7.2-fpm php7.2-gd php7.2-opcache</code></p>
<h3 class="mume-header" id="%E8%A8%AD%E5%AE%9A-php-fpm">&#x8A2D;&#x5B9A; PHP-FPM</h3>

<p>&#x958B;&#x555F; <code>sudo vi /etc/php/7.2/fpm/pool.d/www.conf</code></p>
<p>&#x8A2D;&#x5B9A;&#x4E0B;&#x9762;&#x53C3;&#x6578;</p>
<pre data-role="codeBlock" data-info class="language-"><code>listen = /run/php/php7.2-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0664
;listen.acl_users = apache,nginx
user = www-data
group = www-data
</code></pre><h3 class="mume-header" id="%E8%A8%AD%E5%AE%9A-php-security">&#x8A2D;&#x5B9A; PHP Security</h3>

<p>&#x958B;&#x555F; <code>sudo vi /etc/php/7.2/fpm/php.ini</code></p>
<p>And comment out and set to 0</p>
<pre data-role="codeBlock" data-info class="language-"><code>cgi.fix_pathinfo=0
</code></pre><h3 class="mume-header" id="%E8%A8%AD%E5%AE%9A-php-%E6%AA%94%E6%A1%88%E4%B8%8A%E5%82%B3%E9%99%90%E5%88%B6">&#x8A2D;&#x5B9A; PHP &#x6A94;&#x6848;&#x4E0A;&#x50B3;&#x9650;&#x5236;</h3>

<p>&#x958B;&#x555F; <code>sudo vi /etc/php/7.2/fpm/php.ini</code></p>
<pre data-role="codeBlock" data-info class="language-"><code>post_max_size 16M
upload_max_filesize 16M
memory_limit 256M
</code></pre><h3 class="mume-header" id="%E5%AE%89%E8%A3%9D-composer">&#x5B89;&#x88DD; Composer</h3>

<p><code>php -r &quot;readfile(&apos;http://getcomposer.org/installer&apos;);&quot; | sudo php -- --install-dir=/usr/bin/ --filename=composer</code></p>
<h2 class="mume-header" id="%E8%A8%AD%E5%AE%9A-nginx">&#x8A2D;&#x5B9A; Nginx</h2>

<p>&#x958B;&#x555F; <code>sudo vi /etc/nginx/nginx.conf</code></p>
<p>&#x8A2D;&#x5B9A;&#x4E0B;&#x9762;&#x53C3;&#x6578;</p>
<pre data-role="codeBlock" data-info class="language-"><code>user = www-data
</code></pre><p>&#x4FEE;&#x6539; <code>sudo vi /etc/nginx/sites-available/default</code></p>
<ul>
<li>HTTP &#x7BC4;&#x4F8B;</li>
</ul>
<pre data-role="codeBlock" data-info class="language-"><code>  server {
  listen 80 default_server;
  listen [::]:80 default_server;

  root /var/www/APP_NAME/current/public;

  index index.php;

  client_max_body_size 16M;

  server_name _;

  location / {
      try_files $uri $uri/ /index.php?$query_string;
  }

  location ~ \.php$ {
     try_files $uri =404;

     include fastcgi_params;
     fastcgi_split_path_info ^(.+\.php)(/.+)$;
     fastcgi_pass unix:/run/php/php7.2-fpm.sock;
     fastcgi_index index.php;
     fastcgi_param SCRIPT_FILENAME  $document_root$fastcgi_script_name;
  }

  // deny access to .htaccess files, if Apache&apos;s document root concurs with nginx&apos;s one
  location ~ /\.ht {
      deny all;
  }
}
</code></pre><ul>
<li>HTTPS &#x7BC4;&#x4F8B;</li>
</ul>
<pre data-role="codeBlock" data-info class="language-"><code>server {
  server_name          jourtrip.com;
  ssl_certificate /var/www/ssl/ssl-bundle.crt;
  ssl_certificate_key /var/www/ssl/jourtrip.key;
  listen               *:80;
  listen               *:443 ssl;
  listen               [::]:80 ipv6only=on;
  listen               [::]:443 ssl ipv6only=on;

  client_max_body_size 16M;

  return 301 https://www.jourtrip.com$request_uri;
}

server {
  listen   80;
  listen   [::]:80;

  server_name www.jourtrip.com;

  client_max_body_size 16M;

  return 301 https://$server_name$request_uri;
}

server {
  listen 443 ssl;
  server_name www.jourtrip.com;

  ssl_certificate /var/www/ssl/ssl-bundle.crt;
  ssl_certificate_key /var/www/ssl/jourtrip.key;
  ssl_session_cache shared:SSL:1m;
  ssl_session_timeout 10m;
  ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
  ssl_ciphers EECDH+CHACHA20:EECDH+CHACHA20-draft:EECDH+AES128:RSA+AES128:EECDH+AES256:RSA+AES256:EECDH+3DES:RSA+3DES:!MD5;
  ssl_prefer_server_ciphers on;

  root /var/www/SMART_CUBE/current/public;
  index index.php index.html index.htm;

  client_max_body_size 16M;

  access_log /var/log/nginx/www.jourtrip.com.access.log;
  error_log /var/log/nginx/www.jourtrip.com.error.log;

  charset utf-8;

  # Compression
  # Enable Gzip compressed.
  gzip on;

  # Enable compression both for HTTP/1.0 and HTTP/1.1.
  gzip_http_version  1.1;

  # Compression level (1-9).
  # 5 is a perfect compromise between size and cpu usage, offering about
  # 75% reduction for most ascii files (almost identical to level 9).
  gzip_comp_level    5;

  # Don&apos;t compress anything that&apos;s already small and unlikely to shrink much
  # if at all (the default is 20 bytes, which is bad as that usually leads to
  # larger files after gzipping).
  gzip_min_length    256;

  # Compress data even for clients that are connecting to us via proxies,
  # identified by the &quot;Via&quot; header (required for CloudFront).
  gzip_proxied       any;

  # Tell proxies to cache both the gzipped and regular version of a resource
  # whenever the client&apos;s Accept-Encoding capabilities header varies;
  # Avoids the issue where a non-gzip capable client (which is extremely rare
  # today) would display gibberish if their proxy gave them the gzipped version.
  gzip_vary          on;

  # Compress all output labeled with one of the following MIME-types.
  gzip_types
    application/atom+xml
    application/javascript
    application/json
    application/rss+xml
    application/vnd.ms-fontobject
    application/x-font-ttf
    application/x-web-app-manifest+json
    application/xhtml+xml
    application/xml
    font/opentype
    image/svg+xml
    image/x-icon
    text/css
    text/plain
    text/x-component;
  # text/html is always compressed by HttpGzipModule

  location / {
      try_files $uri $uri/ /index.php?$query_string;
  }

  location ~ \.php$ {
     try_files $uri =404;

     include fastcgi_params;
     fastcgi_split_path_info ^(.+\.php)(/.+)$;
     fastcgi_pass unix:/var/run/php-fpm/php-fpm-7.2.sock;
     fastcgi_index index.php;
     fastcgi_param SCRIPT_FILENAME  $document_root$fastcgi_script_name;
  }

  # deny access to .htaccess files, if Apache&apos;s document root concurs with nginx&apos;s one
  location ~ /\.ht {
      deny all;
  }
}
</code></pre><p>&#x6AA2;&#x67E5; Nginx config</p>
<p><code>sudo nginx -t</code></p>
<h2 class="mume-header" id="%E5%95%9F%E5%8B%95-nginx-%E5%8F%8A-php">&#x555F;&#x52D5; Nginx &#x53CA; PHP</h2>

<p><code>sudo service nginx start</code></p>
<p><code>sudo service php7.2-fpm start</code></p>
<h2 class="mume-header" id="%E8%A8%AD%E5%AE%9A%E9%96%8B%E6%A9%9F%E8%87%AA%E5%8B%95%E5%95%9F%E5%8B%95-nginx-%E5%8F%8A-php">&#x8A2D;&#x5B9A;&#x958B;&#x6A5F;&#x81EA;&#x52D5;&#x555F;&#x52D5; Nginx &#x53CA; PHP</h2>

<p><code>sudo systemctl enable nginx</code></p>
<p><code>sudo systemctl enable php7.2-fpm</code></p>
<h2 class="mume-header" id="%E9%87%8D%E6%96%B0%E5%95%9F%E5%8B%95-nginx-%E5%8F%8A-php">&#x91CD;&#x65B0;&#x555F;&#x52D5; Nginx &#x53CA; PHP</h2>

<p><code>sudo service nginx restart</code></p>
<p><code>sudo service php7.2-fpm restart</code></p>

    </div>
    <div class="md-sidebar-toc"><ul>
<li><a href="#%E5%AE%89%E8%A3%9D%E7%B3%BB%E7%B5%B1">&#x5B89;&#x88DD;&#x7CFB;&#x7D71;</a>
<ul>
<li><a href="#%E6%9B%B4%E6%96%B0%E5%A5%97%E4%BB%B6%E5%88%97%E8%A1%A8">&#x66F4;&#x65B0;&#x5957;&#x4EF6;&#x5217;&#x8868;</a></li>
<li><a href="#%E5%AE%89%E8%A3%9D%E5%B7%A5%E5%85%B7">&#x5B89;&#x88DD;&#x5DE5;&#x5177;</a></li>
<li><a href="#%E5%AE%89%E8%A3%9D-nginx">&#x5B89;&#x88DD; Nginx</a></li>
<li><a href="#%E5%AE%89%E8%A3%9D-php-72">&#x5B89;&#x88DD; PHP 7.2</a>
<ul>
<li><a href="#%E8%A8%AD%E5%AE%9A-php-fpm">&#x8A2D;&#x5B9A; PHP-FPM</a></li>
<li><a href="#%E8%A8%AD%E5%AE%9A-php-security">&#x8A2D;&#x5B9A; PHP Security</a></li>
<li><a href="#%E8%A8%AD%E5%AE%9A-php-%E6%AA%94%E6%A1%88%E4%B8%8A%E5%82%B3%E9%99%90%E5%88%B6">&#x8A2D;&#x5B9A; PHP &#x6A94;&#x6848;&#x4E0A;&#x50B3;&#x9650;&#x5236;</a></li>
<li><a href="#%E5%AE%89%E8%A3%9D-composer">&#x5B89;&#x88DD; Composer</a></li>
</ul>
</li>
<li><a href="#%E8%A8%AD%E5%AE%9A-nginx">&#x8A2D;&#x5B9A; Nginx</a></li>
<li><a href="#%E5%95%9F%E5%8B%95-nginx-%E5%8F%8A-php">&#x555F;&#x52D5; Nginx &#x53CA; PHP</a></li>
<li><a href="#%E8%A8%AD%E5%AE%9A%E9%96%8B%E6%A9%9F%E8%87%AA%E5%8B%95%E5%95%9F%E5%8B%95-nginx-%E5%8F%8A-php">&#x8A2D;&#x5B9A;&#x958B;&#x6A5F;&#x81EA;&#x52D5;&#x555F;&#x52D5; Nginx &#x53CA; PHP</a></li>
<li><a href="#%E9%87%8D%E6%96%B0%E5%95%9F%E5%8B%95-nginx-%E5%8F%8A-php">&#x91CD;&#x65B0;&#x555F;&#x52D5; Nginx &#x53CA; PHP</a></li>
</ul>
</li>
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
