
## 杜甫
* echo "127.0.0.1 text.com" | sudo tee -a /etc/hosts
* pidof nginx
* kill -USR2 $(pidof nginx)'
* pkill -f nginx
* arp -a #查看当前局域网内所有的ip和mac地址
+ ps -ef | java
+ pa aux #查看进程
+ kill 9 parocessId #杀死进程
+ netstat -lntup #查端口
+ lsof -i:4000 #查看4000端口的详细信息
# 打包和压缩文件

* bunzip2 file1.bz2 解压一个叫做 'file1.bz2'的文件
* bzip2 file1 压缩一个叫做 'file1' 的文件
* gunzip file1.gz 解压一个叫做 'file1.gz'的文件
* gzip file1 压缩一个叫做 'file1'的文件
* gzip -9 file1 最大程度压缩
* rar a file1.rar test_file 创建一个叫做 'file1.rar' 的包
* rar a file1.rar file1 file2 dir1 同时压缩 'file1', 'file2' 以及目录 'dir1'
* rar x file1.rar 解压rar包
* unrar x file1.rar 解压rar包
* tar -cvf archive.tar file1 创建一个非压缩的 tarball
* tar -cvf archive.tar file1 file2 dir1 创建一个包含了 'file1', 'file2' 以及 'dir1'的档案文件
* tar -tf archive.tar 显示一个包中的内容
* tar -xvf archive.tar 释放一个包
* tar -xvf archive.tar -C /tmp 将压缩包释放到 /tmp目录下
****************
* tar -jcvf archive.tar.bz2 dir1 创建一个bzip2格式的压缩包
* tar -jxvf archive.tar.bz2 解压一个bzip2格式的压缩包
* 
* tar -zcvf archive.tar.gz dir1 创建一个gzip格式的压缩包
* tar -zxvf archive.tar.gz 解压一个gzip格式的压缩包
* 
* zip file1.zip file1 创建一个zip格式的压缩包
* zip -r file1.zip file1 file2 dir1 将几个文件和目录同时压缩成一个zip格式的压缩包
* unzip file1.zip 解压一个zip格式压缩包
****************

#### 查看日志

+ cat -n cpuinfo | tail -n -10 | head 20 | more
+ cat -n cpuinfo | tail -n -10 | head 20 | > /home/aa.txt
+ cat -n cpuint | grep keyword
+ cat cpuint | wc -l
> 查看10行以下20行的内容

### 复制文件到远程主机

* scp local_file remote_username@remote_ip:remote_folder
* scp local_file remote_username@remote_ip:remote_file
* scp local_file remote_ip:remote_folder
* scp local_file remote_ip:remote_file
* scp -r local_folder remote_username@remote_ip:remote_folder
* scp -r local_folder remote_ip:remote_folder

# SSH
* $HOME/.ssh
* ssh -p 2222 root@host
* ssh-keygen//生成ssh私钥和公钥在上面的目录下
* ssh-copy-id user@host//复制到远程主机,, 之后就可以不用密码登录了
* ssh user@host 'mkdir -p .ssh && cat >> .ssh/authorized_keys' < ~/.ssh/id_rsa.pub//是上面一条命令的解释