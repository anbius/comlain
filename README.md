LsyTPAdmin for PHP
--
* LsyTPAdmin 是一个基于 Thinkphp 5.1.x 开发的后台管理系统，集成后台系统常用功能。
>* 当前版本使用 ThinkPHP 5.1.x 版本，对PHP版本要求不低于php5.6，具体请查阅ThinkPHP官方文档。


Environment
---
>1. PHP 版本不低于 PHP5.6，推荐使用 PHP7 以达到最优效果；
>2. 需开启 PATHINFO，不再支持 ThinkPHP 的 URL 兼容模式运行。

* Apache

```xml
<IfModule mod_rewrite.c>
  Options +FollowSymlinks -Multiviews
  RewriteEngine On
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ index.php/$1 [QSA,PT,L]
</IfModule>
```

* Nginx

```
server {
	listen 80;
	server_name longshangyun.com;
	root /home/wwwroot/LsyTPAdmin/public;
	index index.php index.html index.htm;
	
	add_header X-Powered-Host $hostname;
	fastcgi_hide_header X-Powered-By;
	
	if (!-e $request_filename) {
		rewrite  ^/(.+?\.php)/?(.*)$  /$1/$2  last;
		rewrite  ^/(.*)$  /index.php/$1  last;
	}
	
	location ~ \.php($|/){
		fastcgi_index   index.php;
		fastcgi_pass    127.0.0.1:9000;
		include         fastcgi_params;
		set $real_script_name $fastcgi_script_name;
		if ($real_script_name ~ "^(.+?\.php)(/.+)$") {
			set $real_script_name $1;
		}
		fastcgi_split_path_info ^(.+?\.php)(/.*)$;
		fastcgi_param   PATH_INFO               $fastcgi_path_info;
		fastcgi_param   SCRIPT_NAME             $real_script_name;
		fastcgi_param   SCRIPT_FILENAME         $document_root$real_script_name;
		fastcgi_param   PHP_VALUE               open_basedir=$document_root:/tmp/:/proc/;
		access_log      /home/wwwlog/domain_access.log    access;
		error_log       /home/wwwlog/domain_error.log     error;
	}
	
	location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$ {
		access_log  off;
		error_log   off;
		expires     3d;
	}
	
	location ~ .*\.(js|css)?$ {
		access_log   off;
		error_log    off;
		expires      12h;
	}
}
```

* IIS
```web.config
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>

		<rewrite>
			<rules>
				<rule name="OrgPage" stopProcessing="true">
                    <match url="^(.*)$" />
                    <conditions logicalGrouping="MatchAll">
                    <add input="{HTTP_HOST}" pattern="^(.*)$" />
                    <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
                    <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="index.php/{R:1}" />
				</rule>
			</rules>
		</rewrite>

	</system.webServer>
</configuration>
```

* public
```xml
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ /public/$1 [L]
</IfModule>
```

```xml
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <rewrite>
            <rules>
                <rule name="public" stopProcessing="true">
                    <match url="^(.*)$" ignoreCase="false" />
                    <conditions logicalGrouping="MatchAll">
                      <add input="{URL}" pattern="^/public/" ignoreCase="false" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="/public/{R:1}" />
                </rule>
            </rules>
         </rewrite>
    </system.webServer>
</configuration>
```

Copyright
--
* LsyTPAdmin 商业代码，未经许可，禁止他用或进行二次开发
* LsyTPAdmin 部分代码来自互联网，若有异议，可以联系作者进行删除