#井字棋
##运行环境要求
+ PHP >= 7.0
+ Redis
+ Nginx
##命令行运行
cd 进入ticTacToe目录，输入 php index.php 回车后执行即可
##网页运行
配置nginx的虚拟主机
```
server {
	listen 80;
	server_name php.test;
	index index.php index.html;
	root /code/test/;

	location / {
	    try_files $uri $uri/ /index.php$is_args$args;
   	 }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
   	 }
}
```
打开谷歌浏览器，输入网址 http://php.test/ticTacToe ，即可开始游戏