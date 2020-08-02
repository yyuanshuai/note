### 快捷键

```
CTRL + U - 剪切光标前的内容
CTRL + K - 剪切光标至行末的内容
CTRL + Y - 粘贴
CTRL + E - 移动光标到行末
CTRL + A - 移动光标到行首
ALT + F - 跳向下一个空格
ALT + B - 跳回上一个空格
ALT + Backspace - 删除前一个单词
CTRL + W - 剪切光标前一个单词
Shift + Insert - 向终端内粘贴文本
```



### 查看内核版本

```
cat /proc/version
uname -a #查看内核
```



### 查看查看linux版本

```
lsb_release -a
cat /etc/redhat-release
```



# 更换源

```
sudo vim /etc/apt/sources.list#buster
deb http://mirrors.aliyun.com/debian/ buster main non-free contrib
deb http://mirrors.aliyun.com/debian/ buster-updates main non-free contrib
deb http://mirrors.aliyun.com/debian/ buster-backports main non-free contrib
deb-src http://mirrors.aliyun.com/debian/ buster main non-free contrib
deb-src http://mirrors.aliyun.com/debian/ buster-updates main non-free contrib
deb-src http://mirrors.aliyun.com/debian/ buster-backports main non-free contrib
deb http://mirrors.aliyun.com/debian-security/ buster/updates main non-free contrib
deb-src http://mirrors.aliyun.com/debian-security/ buster/updates main non-free contrib

sudo apt-get update
sudo apt-get upgrade -y
```



## 常用

```
echo "127.0.0.1 zhucan-admin.com" | sudo tee -a /etc/hosts
pidof nginx
kill -USR2 $(pidof nginx)'
pkill -f nginx
arp -a #查看当前局域网内所有的ip和mac地址
ps -ef | grep sshd//查看进程  ps -aux | grep nginx
kill 9 parocessId #杀死进程
netstat -lntup #查端口
lsof -i:4000 #查看4000端口的详细信息
lsof -p 50417 -nP | grep TCP
systemctl list-units --type=service//查看所有以启动服务
systemctl status firewalld
systemctl stop firewalld
systemctl start firewalld
systemctl restart firewalld
systemctl reload firewalld
systemctl disable firewalld
systemctl enable firewalld//开机自启
passwd root//修改密码
free -m//查看内存情况
df -hT//查看磁盘空间占用情况：
du -h --max-depth=1 ./* //查看当前目录下的文件及文件夹所占大小：
ifconfig //显示当前网络接口状态
netstat -rn//查看当前路由信息：
netstat -tulnp // 查看系统中启动的监听服务：
wget
scp -r local_folder remote_username@host:remote_folder
chmod -R 777 folder
chown -R 

```



# 打包和压缩文件

#### tar

```
tar -cvf archive.tar file1 创建一个非压缩的 tarball
tar -cvf archive.tar file1 file2 dir1 创建一个包含了 'file1', 'file2' 以及 'dir1'的档案文件
tar -tf archive.tar 显示一个包中的内容
tar -xvf archive.tar 释放一个包
tar -xvf archive.tar -C /tmp 将压缩包释放到 /tmp目录下
tar -jcvf archive.tar.bz2 dir1 创建一个bzip2格式的压缩包
tar -jxvf archive.tar.bz2 解压一个bzip2格式的压缩包
tar -zcvf archive.tar.gz dir1 创建一个gzip格式的压缩包
tar -zxvf archive.tar.gz 解压一个gzip格式的压缩包////-C<目的目录>或--directory=<目的目录> 切换到指定的目录。
```



#### zip

```
zip file1.zip file1 创建一个zip格式的压缩包
zip -rS file1.zip file1 file2 dir1 将几个文件(递归处理，将指定目录下的所有文件和子目录一并处理。)和目录同时压缩成一个zip格式的压缩包 -s包含系统和隐藏文件
unzip file1.zip 解压一个zip格式压缩包
unzip -l 查看压缩包内所有文件
zipinfo filename.zip 查看压缩包内文件
```



#### 其他

```
bunzip2 file1.bz2 解压一个叫做 'file1.bz2'的文件
bzip2 file1 压缩一个叫做 'file1' 的文件
gunzip file1.gz 解压一个叫做 'file1.gz'的文件
gzip file1 压缩一个叫做 'file1'的文件
gzip -9 file1 最大程度压缩
rar a file1.rar test_file 创建一个叫做 'file1.rar' 的包
rar a file1.rar file1 file2 dir1 同时压缩 'file1', 'file2' 以及目录 'dir1'
rar x file1.rar 解压rar包
unrar x file1.rar 解压rar包
```



****************

#### 查看日志

```
cat -n cpuinfo | tail -n -10 | head 20 | more#查看10行以下20行的内容
cat -n cpuinfo | tail -n -10 | head 20 | > /home/aa.txt
cat -n cpuint | grep keyword
cat cpuint | wc -l
```



### 复制文件到远程主机

```
scp -r local_file remote_username@host:remote_folder
scp -r remote_username@host:remote_folder local_file
scp local_file remote_username@host:remote_file
scp local_file host:remote_folder
scp local_file host:remote_file
scp -r local_folder remote_username@host:remote_folder
scp -r local_folder host:remote_folder
```



## 重要的目录

| 目录                                              | 描述                                                         |
| ------------------------------------------------- | ------------------------------------------------------------ |
| **/etc/rc\|/etc/rc.d\|/etc/rc*.d**                | 启动、或改变运行级时运行的scripts或scripts的目录.            |
| /etc/hosts                                        | 本地域名解析文件                                             |
| **/etc/sysconfig/network**                        | IP、掩码、网关、主机名配置                                   |
| **/etc/resolv.conf**                              | DNS服务器配置                                                |
| **/etc/fstab**                                    | 开机自动挂载系统，所有分区开机都会自动挂载                   |
| **/etc/group**                                    | 类似/etc/passwd ，但说明的不是用户而是组.                    |
| **/etc/passwd**                                   | 用户数据库，其中的域给出了用户名、真实姓名、家目录、加密的口令和用户的其他信息. |
| **/etc/init.d**                                   | 这个目录来存放系统启动脚本                                   |
| **/etc/profile**, /etc/csh.login,  /etc/csh.cshrc | **全局系统环境配置变量**                                     |
| **/etc/sudoers**                                  | 可以sudo命令的配置文件                                       |
| **/etc/shadow**                                   | 在安装了影子口令软件的系统上的影子口令文件.影子口令文件将/etc/passwd 文件中的加密口令移动到/etc/shadow 中，而后者只对root可读.这使破译口令更困难. |
| **/etc/skel/**                                    | 默认创建用户时，把该目录拷贝到家目录下                       |
| /etc/ssh/ssh_known_hosts                          | 保存一些对所有用户都可信赖的远程主机的公钥                   |
| $HOME/.ssh/known_hosts                            | 保存远程主机的公钥文件                                       |
| $HOME/.ssh/config                                 |                                                              |
| /usr/src                                          | Linux开放的源代码，就存在这个目录，爱好者们别放过哦；        |
| /usr/bin/                                         | 非必要[可执行文件](http://zh.wikipedia.org/wiki/可执行文件) (在[单用户模式](http://zh.wikipedia.org/w/index.php?title=单用户模式&action=edit&redlink=1)中不需要)；面向所有用户。 |
| /usr/lib/                                         | /usr/bin/和/usr/sbin/中二进制文件的[库](http://zh.wikipedia.org/wiki/库)。 |
| /usr/sbin/                                        | 非必要的系统二进制文件，例如：大量[网络服务](http://zh.wikipedia.org/wiki/网络服务)的[守护进程](http://zh.wikipedia.org/wiki/守护进程)。 |
| /usr/src/                                         | [源代码](http://zh.wikipedia.org/wiki/源代码),例如:内核源代码及其头文件。 |
| /usr/local/                                       | 本地数据的第三层次，具体到本台主机。通常而言有进一步的子目录，例如：bin/、lib/、share/.这是提供给一般用户的/usr目录，在这里安装一般的应用软件； |
| **/proc/meminfo**                                 | 查看内存信息                                                 |
| **/proc/loadavg**                                 | 还记得 top 以及 uptime 吧？没错！上头的三个平均数值就是记录在此！ |
| **/proc/uptime**                                  | 就是用 uptime 的时候，会出现的资讯啦！                       |
| **/proc/cpuinfo**                                 | 关于处理器的信息，如类型、厂家、型号和性能等。               |
| /dev/hd[a-t]                                      | IDE设备                                                      |
| /dev/sd[a-z]                                      | SCSI设备                                                     |
| /dev/null                                         | 无限数据接收设备,相当于黑洞                                  |
| /var/spool/cron/root                              | 定时器配置文件目录，默认按用户命名                           |
| /var/log/message                                  | 日志信息，按周自动轮询                                       |
| /var/log/secure                                   | 记录登陆系统存取信息的文件，不管认证成功还是认证失败都会记录 |
| /var/local                                        | /usr/local 中安装的程序的可变数据(即系统管理员安装的程序).注意，如果必要，即使本地安装的程序也会使用其他/var 目录，例如/var/lock . |



### 文件的权限
```
ls -lh 显示权限

ls /tmp | pr -T5 -W$COLUMNS 将终端划分成5栏显示

chmod ugo+rwx directory1 设置目录的所有人(u)、群组(g)以及其他人(o)以读（r ）、写(w)和执行(x)的权限

chmod go-rwx directory1 删除群组(g)与其他人(o)对目录的读写执行权限

chown user1 file1 改变一个文件的所有人属性

chown -R user1 directory1 改变一个目录的所有人属性并同时改变改目录下所有文件的属性

chgrp group1 file1 改变文件的群组

chown user1:group1 file1 改变一个文件的所有人和群组属性

find / -perm -u+s 罗列一个系统中所有使用了SUID控制的文件

chmod u+s /bin/file1 设置一个二进制文件的 SUID 位 - 运行该文件的用户也被赋予和所有者同样的权限

chmod u-s /bin/file1 禁用一个二进制文件的 SUID位

chmod g+s /home/public 设置一个目录的SGID 位 - 类似SUID ，不过这是针对目录的

chmod g-s /home/public 禁用一个目录的 SGID 位

chmod o+t /home/public 设置一个文件的 STIKY 位 - 只允许合法所有人删除文件

chmod o-t /home/public 禁用一个目录的 STIKY 位
```



### RPM 包 - （Fedora, Redhat及类似系统）

```
rpm -ivh package.rpm 安装一个rpm包

rpm -ivh --nodeeps package.rpm 安装一个rpm包而忽略依赖关系警告

rpm -U package.rpm 更新一个rpm包但不改变其配置文件

rpm -F package.rpm 更新一个确定已经安装的rpm包

rpm -e package_name.rpm 删除一个rpm包

rpm -qa 显示系统中所有已经安装的rpm包

rpm -qa | grep httpd 显示所有名称中包含 "httpd" 字样的rpm包

rpm -qi package_name 获取一个已安装包的特殊信息

rpm -qg "System Environment/Daemons" 显示一个组件的rpm包

rpm -ql package_name 显示一个已经安装的rpm包提供的文件列表

rpm -qc package_name 显示一个已经安装的rpm包提供的配置文件列表

rpm -q package_name --whatrequires 显示与一个rpm包存在依赖关系的列表

rpm -q package_name --whatprovides 显示一个rpm包所占的体积

rpm -q package_name --scripts 显示在安装/删除期间所执行的脚本l

rpm -q package_name --changelog 显示一个rpm包的修改历史

rpm -qf /etc/httpd/conf/httpd.conf 确认所给的文件由哪个rpm包所提供

rpm -qp package.rpm -l 显示由一个尚未安装的rpm包提供的文件列表

rpm --import /media/cdrom/RPM-GPG-KEY 导入公钥数字证书

rpm --checksig package.rpm 确认一个rpm包的完整性

rpm -qa gpg-pubkey 确认已安装的所有rpm包的完整性

rpm -V package_name 检查文件尺寸、 许可、类型、所有者、群组、MD5检查以及最后修改时间

rpm -Va 检查系统中所有已安装的rpm包- 小心使用

rpm -Vp package.rpm 确认一个rpm包还未安装

rpm2cpio package.rpm | cpio --extract --make-directories *bin* 从一个rpm包运行可执行文件

rpm -ivh /usr/src/redhat/RPMS/`arch`/package.rpm 从一个rpm源码安装一个构建好的包

rpmbuild --rebuild package_name.src.rpm 从一个rpm源码构建一个 rpm 包
```



### DEB 包 (Debian, Ubuntu 以及类似系统)
```
dpkg -i package.deb 安装/更新一个 deb 包

dpkg -r package_name 从系统删除一个 deb 包

dpkg -l 显示系统中所有已经安装的 deb 包

dpkg -l | grep httpd 显示所有名称中包含 "httpd" 字样的deb包

dpkg -s package_name 获得已经安装在系统中一个特殊包的信息

dpkg -L package_name 显示系统中已经安装的一个deb包所提供的文件列表

dpkg --contents package.deb 显示尚未安装的一个包所提供的文件列表

dpkg -S /bin/ping 确认所给的文件由哪个deb包提供
```




### 用户和群组
```
groupadd group_name 创建一个新用户组

groupdel group_name 删除一个用户组

groupmod -n new_group_name old_group_name 重命名一个用户组

useradd -c "Name Surname " -g admin -d /home/user1 -s /bin/bash user1 创建一个属于 "admin" 用户组的用户

useradd user1 创建一个新用户

userdel -r user1 删除一个用户 ( '-r' 排除主目录)

usermod -c "User FTP" -g system -d /ftp/user1 -s /bin/nologin user1 修改用户属性

usermod -G groupNmame username//将用户添加到组

groups yuanshuai查看当前用户所属的组

passwd 修改口令

passwd user1 修改一个用户的口令 (只允许root执行)

chage -E 2005-12-31 user1 设置用户口令的失效期限

pwck 检查 '/etc/passwd' 的文件格式和语法修正以及存在的用户

grpck 检查 '/etc/passwd' 的文件格式和语法修正以及存在的群组

newgrp group_name 登陆进一个新的群组以改变新创建文件的预设群组
```



### 关机 (系统的关机、重启以及登出 )
```
shutdown -h now 关闭系统(1)

init 0 关闭系统(2)

telinit 0 关闭系统(3)

shutdown -h hours:minutes & 按预定时间关闭系统

shutdown -c 取消按预定时间关闭系统

shutdown -r now 重启(1)

reboot 重启(2)

logout 注销
```



### 查看文件内容

```
cat file1 从第一个字节开始正向查看文件的内容
tac file1 从最后一行开始反向查看一个文件的内容
more file1 查看一个长文件的内容
less file1 类似于 'more' 命令，但是它允许在文件中和正向操作一样的反向操作
head -2 file1 查看一个文件的前两行
```



### 文本处理

```
cat file1 file2 ... | command <> file1_in.txt_or_file1_out.txt general syntax for text manipulation using PIPE, STDIN and STDOUT
cat file1 | command( sed, grep, awk, grep, etc...) > result.txt 合并一个文件的详细说明文本，并将简介写入一个新文件中
cat file1 | command( sed, grep, awk, grep, etc...) >> result.txt 合并一个文件的详细说明文本，并将简介写入一个已有的文件中
grep Aug /var/log/messages 在文件 '/var/log/messages'中查找关键词"Aug"
grep ^Aug /var/log/messages 在文件 '/var/log/messages'中查找以"Aug"开始的词汇
grep [0-9] /var/log/messages 选择 '/var/log/messages' 文件中所有包含数字的行
grep Aug -R /var/log/* 在目录 '/var/log' 及随后的目录中搜索字符串"Aug"
sed 's/stringa1/stringa2/g' example.txt 将example.txt文件中的 "string1" 替换成 "string2"
sed '/^$/d' example.txt 从example.txt文件中删除所有空白行
sed '/ *#/d; /^$/d' example.txt 从example.txt文件中删除所有注释和空白行
echo 'esempio' | tr '[:lower:]' '[:upper:]' 合并上下单元格内容
sed -e '1d' result.txt 从文件example.txt 中排除第一行
sed -n '/stringa1/p' 查看只包含词汇 "string1"的行
sed -e 's/ *$//' example.txt 删除每一行最后的空白字符
sed -e 's/stringa1//g' example.txt 从文档中只删除词汇 "string1" 并保留剩余全部
sed -n '1,5p;5q' example.txt 查看从第一行到第5行内容
sed -n '5p;5q' example.txt 查看第5行
sed -e 's/00*/0/g' example.txt 用单个零替换多个零

//将当前路径下的所有文件/usr/local/etc/nginx/fastcgi.conf替换为/etc/nginx/fastcgi_params

//mac下-i后需要接''空字符串

sed -i '' "s#/usr/local/etc/nginx/fastcgi.conf#/etc/nginx/fastcgi_params#g" \`ls`

cat -n file1 标示文件的行数
cat example.txt | awk 'NR%2==1' 删除example.txt文件中的所有偶数行
echo a b c | awk '{print $1}' 查看一行第一栏
echo a b c | awk '{print $1,$3}' 查看一行的第一和第三栏
paste file1 file2 合并两个文件或两栏的内容
paste -d '+' file1 file2 合并两个文件或两栏的内容，中间用"+"区分
sort file1 file2 排序两个文件的内容
sort file1 file2 | uniq 取出两个文件的并集(重复的行只保留一份)
sort file1 file2 | uniq -u 删除交集，留下其他的行
sort file1 file2 | uniq -d 取出两个文件的交集(只留下同时存在于两个文件中的文件)
comm -1 file1 file2 比较两个文件的内容只删除 'file1' 所包含的内容
comm -2 file1 file2 比较两个文件的内容只删除 'file2' 所包含的内容
comm -3 file1 file2 比较两个文件的内容只删除两个文件共有的部分
```



### 字符设置和文件格式转换
```
dos2unix filedos.txt fileunix.txt 将一个文本文件的格式从MSDOS转换成UNIX
unix2dos fileunix.txt filedos.txt 将一个文本文件的格式从UNIX转换成MSDOS
recode ..HTML < page.txt > page.html 将一个文本文件转换成html
recode -l | more 显示所有允许的转换格式
```



### 文件系统分析

```
badblocks -v /dev/hda1 检查磁盘hda1上的坏磁块
fsck /dev/hda1 修复/检查hda1磁盘上linux文件系统的完整性
fsck.ext2 /dev/hda1 修复/检查hda1磁盘上ext2文件系统的完整性
e2fsck /dev/hda1 修复/检查hda1磁盘上ext2文件系统的完整性
e2fsck -j /dev/hda1 修复/检查hda1磁盘上ext3文件系统的完整性
fsck.ext3 /dev/hda1 修复/检查hda1磁盘上ext3文件系统的完整性
fsck.vfat /dev/hda1 修复/检查hda1磁盘上fat文件系统的完整性
fsck.msdos /dev/hda1 修复/检查hda1磁盘上dos文件系统的完整性
dosfsck /dev/hda1 修复/检查hda1磁盘上dos文件系统的完整性
```



### 初始化一个文件系统

```
mkfs /dev/hda1 在hda1分区创建一个文件系统
mke2fs /dev/hda1 在hda1分区创建一个linux ext2的文件系统
mke2fs -j /dev/hda1 在hda1分区创建一个linux ext3(日志型)的文件系统
mkfs -t vfat 32 -F /dev/hda1 创建一个 FAT32 文件系统
fdformat -n /dev/fd0 格式化一个软盘
mkswap /dev/hda3 创建一个swap文件系统
```



### SWAP文件系统
```
mkswap /dev/hda3 创建一个swap文件系统
swapon /dev/hda3 启用一个新的swap文件系统
swapon /dev/hda2 /dev/hdb3 启用两个swap分区
```



### 备份
```
dump -0aj -f /tmp/home0.bak /home 制作一个 '/home' 目录的完整备份dump -1aj -f /tmp/home0.bak /home 制作一个 '/home' 目录的交互式备份
restore -if /tmp/home0.bak 还原一个交互式备份
rsync -rogpav --delete /home /tmp 同步两边的目录
rsync -rogpav -e ssh --delete /home ip_address:/tmp 通过SSH通道rsync
rsync -az -e ssh --delete ip_addr:/home/public /home/local 通过ssh和压缩将一个远程目录同步到本地目录
rsync -az -e ssh --delete /home/local ip_addr:/home/public 通过ssh和压缩将本地目录同步到远程目录
dd bs=1M if=/dev/hda | gzip | ssh user@ip_addr 'dd of=hda.gz' 通过ssh在远程主机上执行一次备份本地磁盘的操作
dd if=/dev/sda of=/tmp/file1 备份磁盘内容到一个文件
tar -Puf backup.tar /home/user 执行一次对 '/home/user' 目录的交互式备份操作( cd /tmp/local/ && tar c . ) | ssh -C user@ip_addr 'cd /home/share/ && tar x -p' 通过ssh在远程目录中复制一个目录内容( tar c /home ) | ssh -C user@ip_addr 'cd /home/backup-home && tar x -p' 通过ssh在远程目录中复制一个本地目录
tar cf - . | (cd /tmp/backup ; tar xf - ) 本地将一个目录复制到另一个地方，保留原有权限及链接
find /home/user1 -name '*.txt' | xargs cp -av --target-directory=/home/backup/ --parents 从一个目录查找并复制所有以 '.txt' 结尾的文件到另一个目录
find /var/log -name '*.log' | tar cv --files-from=- | bzip2 > log.tar.bz2 查找所有以 '.log' 结尾的文件并做成一个bzip包
dd if=/dev/hda of=/dev/fd0 bs=512 count=1 做一个将 MBR (Master Boot Record)内容复制到软盘的动作
dd if=/dev/fd0 of=/dev/hda bs=512 count=1 从已经保存到软盘的备份中恢复MBR内容
```



### 光盘

```
cdrecord -v gracetime=2 dev=/dev/cdrom -eject blank=fast -force 清空一个可复写的光盘内容
mkisofs /dev/cdrom > cd.iso 在磁盘上创建一个光盘的iso镜像文件
mkisofs /dev/cdrom | gzip > cd_iso.gz 在磁盘上创建一个压缩了的光盘iso镜像文件
mkisofs -J -allow-leading-dots -R -V "Label CD" -iso-level 4 -o ./cd.iso data_cd 创建一个目录的iso镜像文件
cdrecord -v dev=/dev/cdrom cd.iso 刻录一个ISO镜像文件
gzip -dc cd_iso.gz | cdrecord dev=/dev/cdrom - 刻录一个压缩了的ISO镜像文件
mount -o loop cd.iso /mnt/iso 挂载一个ISO镜像文件
cd-paranoia -B 从一个CD光盘转录音轨到 wav 文件中
cd-paranoia -- "-3" 从一个CD光盘转录音轨到 wav 文件中（参数-3）
cdrecord --scanbus 扫描总线以识别scsi通道
dd if=/dev/hdc | md5sum 校验一个设备的md5sum编码，例如一张 CD
```



### 网络 - （以太网和WIFI无线）
```
ifconfig eth0 显示一个以太网卡的配置
ifup eth0 启用一个 'eth0' 网络设备
ifdown eth0 禁用一个 'eth0' 网络设备
ifconfig eth0 192.168.1.1 netmask 255.255.255.0 控制IP地址
ifconfig eth0 promisc 设置 'eth0' 成混杂模式以嗅探数据包 (sniffing)
dhclient eth0 以dhcp模式启用 'eth0'
route -n show routing table
route add -net 0/0 gw IP_Gateway configura default gateway
route add -net 192.168.0.0 netmask 255.255.0.0 gw 192.168.1.1 configure static route to reach network '192.168.0.0/16'
route del 0/0 gw IP_gateway remove static route
echo "1" > /proc/sys/net/ipv4/ip_forward activate ip routing
hostname show hostname of system
host www.example.com lookup hostname to resolve name to ip address and viceversa(1)
nslookup www.example.com lookup hostname to resolve name to ip address and viceversa(2)
ip link show show link status of all interfaces
mii-tool eth0 show link status of 'eth0'
ethtool eth0 show statistics of network card 'eth0'
netstat -tup show all active network connections and their PID
netstat -tupl show all network services listening on the system and their PID
tcpdump tcp port 80 show all HTTP traffic
iwlist scan show wireless networks
iwconfig eth1 show configuration of a wireless network card
hostname show hostname
host www.example.com lookup hostname to resolve name to ip address and viceversa
nslookup www.example.com lookup hostname to resolve name to ip address and viceversa
whois www.example.com lookup on Whois database
Microsoft Windows networks (SAMBA)
nbtscan ip_addr netbios name resolution
nmblookup -A ip_addr netbios name resolution
smbclient -L ip_addr/hostname show remote shares of a windows host
smbget -Rr smb://ip_addr/share like wget can download files from a host windows via smb
mount -t smbfs -o username=user,password=pass //WinClient/share /mnt/share mount a windows network share
```



### 挂载一个文件系统
```
mount /dev/hda2 /mnt/hda2 挂载一个叫做hda2的盘 - 确定目录 '/ mnt/hda2' 已经存在
umount /dev/hda2 卸载一个叫做hda2的盘 - 先从挂载点 '/ mnt/hda2' 退出
fuser -km /mnt/hda2 当设备繁忙时强制卸载
umount -n /mnt/hda2 运行卸载操作而不写入 /etc/mtab 文件- 当文件为只读或当磁盘写满时非常有用
mount /dev/fd0 /mnt/floppy 挂载一个软盘
mount /dev/cdrom /mnt/cdrom 挂载一个cdrom或dvdrom
mount /dev/hdc /mnt/cdrecorder 挂载一个cdrw或dvdrom
mount /dev/hdb /mnt/cdrecorder 挂载一个cdrw或dvdrom
mount -o loop file.iso /mnt/cdrom 挂载一个文件或ISO镜像文件
mount -t vfat /dev/hda5 /mnt/hda5 挂载一个Windows FAT32文件系统
mount /dev/sda1 /mnt/usbdisk 挂载一个usb 捷盘或闪存设备
mount -t smbfs -o username=user,password=pass //WinClient/share /mnt/share 挂载一个windows网络共享
```

