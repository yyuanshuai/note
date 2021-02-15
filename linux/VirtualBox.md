# 安装virtualBox

https://www.bilibili.com/video/BV1np4y1C7Yf?p=6

https://zhuanlan.zhihu.com/p/111567471

下载&安装 VirtualBox https://www.virtualbox.org/，要开启 CPU 虚拟化



**配置 VirtualBox**

启动 VirtualBox 后，通过菜单 `管理` -> `全局设定`，或者按下快捷键 `Ctrl + g`，在全局设定对话框中，修改 `默认虚拟电脑位置`，指定一个容量较大的磁盘。

E:/VirtualBox



下载Vagrant 

- 下载&安装 Vagrant 

- - https://app.vagrantup.com/boxes/search Vagrant 官方镜像仓库 
  - https://www.vagrantup.com/downloads.html Vagrant 下载 
  
- 打开 window cmd 窗口，新建空文件夹(非空的话会将文件夹内的所有文件同步到虚拟centos中, 会炸的), 并进入运行 vagrant init centos/7，即可初始化一个 centos7 系统 

- 运行 vagrant up 即可启动虚拟机。系统 root 用户的密码是 vagrant , **这里下载很慢使用迅雷下载centos-7 box后[CentOS 7 的最新版本](https://link.zhihu.com/?target=http%3A//cloud.centos.org/centos/7/vagrant/x86_64/images/CentOS-7.box)手动添加`vagrant box add e:\Downloads\CentOS-7.box --name centos/7`即可**

- vagrant 其他常用命令 
  - vagrant ssh：自动使用 vagrant 用户连接虚拟机。 
  - vagrant upload source [destination] [name|id]：上传文件 
  - https://www.vagrantup.com/docs/cli/init.html Vagrant 命令行
  
- 默认虚拟机的 ip 地址不是固定 ip，开发不方便 
  
  - 修改 Vagrantfile
  
      config.vm.network "private_network", ip: "192.168.56.10" //这里的 ip 需要在物理机下使用 ipconfig 命令找到 192.168.56
  
  - 重新使用 vagrant up 启动机器即可。然后再 vagrant ssh 连接机器
  
- 默认只允许 ssh 登录方式，为了后来操作方便，文件上传等，我们可以配置允许账 号密码登录 Vagrant ssh 进去系统之后 vi /etc/ssh/sshd_config 修改 PasswordAuthentication yes/no 重启服务 service sshd restart  以后可以使用提供的 ssh 连接工具直接连接

- 注意：VirtualBox 会与包括但不限于如下软件冲突，需要卸载这些软件，然后重启电脑； 冲突的软件：红蜘蛛，360，净网大师（有可能）等

- 修改 linux 的 yum 源

    - 1）、备份原 yum 源 mv /etc/yum.repos.d/CentOS-Base.repo /etc/yum.repos.d/CentOS-Base.repo.backup 
    
    - 2）、使用新 yum 源 curl -o /etc/yum.repos.d/CentOS-Base.repo http://mirrors.163.com/.help/CentOS7-Base-163.repo
    
    - ```
        curl -o /etc/yum.repos.d/CentOS-Base.repo https://mirrors.aliyun.com/repo/Centos-7.repo
        curl -o /etc/yum.repos.d/CentOS-Base.repo http://mirrors.163.com/.help/CentOS7-Base-163.repo
        ```
    
    - 3）、生成缓存 yum makecache





## 下载虚机镜像

使用 Vagrant 创建虚机时，需要指定一个镜像，也就是 `box`。开始这个 box 不存在，所以 Vagrant 会先从网上下载，然后缓存在本地目录中。

Vagrant 有一个[镜像网站](https://link.zhihu.com/?target=https%3A//app.vagrantup.com/boxes/search)，里面列出了都有哪些镜像可以用，并且提供了操作文档。

但是这里默认下载往往会比较慢，所以下面我会介绍如何在其它地方下载到基础镜像，然后按照自己的需要重置。如果网速较好，下载顺利的朋友可以选择性地跳过部分内容。

下面我给出最常用的两个 Linux 操作系统镜像的下载地址：

**CentOS**

CentOS 的镜像下载网站是： [http://cloud.centos.org/centos/](https://link.zhihu.com/?target=http%3A//cloud.centos.org/centos/)

在其中选择自己想要下载的版本，列表中有一个 `vagrant` 目录，里面是专门为 vagrant 构建的镜像。选择其中的 `.box` 后缀的文件下载即可。这里可以使用下载工具，以较快的速度下载下来。

这里我们选择下载的是 [CentOS 7 的最新版本](https://link.zhihu.com/?target=http%3A//cloud.centos.org/centos/7/vagrant/x86_64/images/CentOS-7.box)

**Ubuntu**

Ubuntu 的镜像下载网站是： [http://cloud-images.ubuntu.com/](https://link.zhihu.com/?target=http%3A//cloud-images.ubuntu.com/)

同样先选择想要的版本，然后选择针对 vagrant 的 `.box` 文件即可。

如果这里官网的速度较慢，还可以从 [清华大学的镜像站](https://link.zhihu.com/?target=https%3A//mirror.tuna.tsinghua.edu.cn/ubuntu-cloud-images/) 下载。

下面的例子以 CentOS 7 为例，使用其它版本操作系统的也可以参考。

## 添加 box

接下来我们需要将下载后的 `.box` 文件添加到 vagrant 中。

Vagrant 没有 GUI，只能从命令行访问，先启动一个命令行，然后执行:

```text
$ vagrant box list
There are no installed boxes! Use `vagrant box add` to add some.
```

提示现在还没有 box。如果这是第一次运行，此时 `VAGRANT_HOME` 目录下会自动生成若干的文件和文件夹，其中有一个 `boxes` 文件夹，这就是要存放 box 文件的地方。

执行 `vagrant box add` 命令添加 box:

```text
$ vagrant box add e:\Downloads\CentOS-7.box --name centos-7
==> box: Box file was not detected as metadata. Adding it directly...
==> box: Adding box 'centos-7' (v0) for provider:
    box: Unpacking necessary files from: file:///e:/Downloads/CentOS-7.box
    box:
==> box: Successfully added box 'centos-7' (v0) for 'virtualbox'!
```

命令后面跟着的是下载的文件路径，并且通过 `--name centos-7` 为这个 box 指定一个名字。

后面创建虚机都需要指定这个名字，所以尽量把名字取得简短一点，同时也要能标识出这个镜像的信息（我们后面会定制自己的基础镜像，所以这里可以简单点）。

再次查询，可以看到有了一个 box：

```text
$ vagrant box list
centos-7 (virtualbox, 0)
```

```shell
#所有的 vagrant 命令都需要在 Vagrantfile 所在的目录下执行。
vagrant halt#关闭虚拟机
vagrant suspend#暂停
vagrant resume#恢复
vagrant reload#重载
vagrant destroy#删除
```

