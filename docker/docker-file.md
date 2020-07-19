
* Dockerfile常用指令
	* FROM scratch 

	* RUN <命令>
	* RUN ["可执行文件", "参数1", "参数2"] 

	* CMD <命令>
	* CMD ["可执行文件", "参数1", "参数2"...]
		* CMD 指令就是用于指定默认的容器主进程的启动命令的。在运行时可以指定新的命令来替代镜像设置中的这个默认命令
		* < CMD service nginx start//等同于以下//错误写法
		* < CMD [ "sh", "-c", "service nginx start"]//错误写法
		* < CMD ["nginx", "-g", "daemon off;"]//直接执行 nginx 可执行文件，并且要求以前台形式运行//正确写法

	* COPY [--chown=<user>:<group>] <源路径>... <目标路径>
	* COPY [--chown=<user>:<group>] ["<源路径1>",... "<目标路径>"]
		* COPY 指令将从构建上下文目录中 <源路径> 的文件/目录复制到新的一层的镜像内的 <目标路径> 位置。比如：
		* > COPY package.json /usr/src/app/

	* ADD [--chown=<user>:<group>] <源路径>... <目标路径>
	* ADD [--chown=<user>:<group>] ["<源路径1>",... "<目标路径>"]
		
* ADD 指令将会自动解压缩这个压缩文件到 <目标路径> 去。最适合使用 ADD 的场合，就是所提及的需要自动解压缩的场合。尽可能的使用 COPY
		
	* ENTRYPOINT "<CMD>"
	* ENTRYPOINT [ "curl", "-s", "https://ip.cn" ]
	
	* 当存在 ENTRYPOINT 后，CMD 的内容将会作为参数传给 ENTRYPOINT
		
* VOLUME ["<路径1>", "<路径2>"...]
	* VOLUME <路径>
	
* ENV <key> <value>
	* ENV <key1>=<value1> <key2>=<value2>...
	
	* WORKDIR <工作目录路径>
	





```
FROM debian:stretch

RUN buildDeps='gcc libc6-dev make wget' \
    && apt-get update \
    && apt-get install -y $buildDeps \
    && wget -O redis.tar.gz "http://download.redis.io/releases/redis-5.0.3.tar.gz" \
    && mkdir -p /usr/src/redis \
    && tar -xzf redis.tar.gz -C /usr/src/redis --strip-components=1 \
    && make -C /usr/src/redis \
    && make -C /usr/src/redis install \
    && rm -rf /var/lib/apt/lists/* \
    && rm redis.tar.gz \
    && rm -r /usr/src/redis \
    && apt-get purge -y --auto-remove $buildDeps
```