#### 快速开机指南

树莓派新手指南中文版，PDF 下载：
https://pan.baidu.com/s/1mkkbNVa2x7Rf-i7wFJBgmQ（提取码: isir）

# 工具

#### 镜像烧录工具

[Win32DiskImager](https://make.quwj.com/bookmark/327/go) ([使用介绍](https://shumeipai.nxez.com/2013/09/07/raspberry-pi-under-windows-system-installation-to-sd-card.html))

#### SSH 客户端

[PUTTY](https://make.quwj.com/bookmark/328/go)（[使用介绍](https://shumeipai.nxez.com/2013/09/07/using-putty-to-log-in-to-the-raspberry-pie.html)）

#### SD卡格式化工具，可选。

[SD Formatter for SD/SDHC/SDXC](https://make.quwj.com/bookmark/80/go)

#### Pi Dashboard

**Pi Dashboard** 是树莓派实验室发布的一个开源的 IoT 设备监控工具，目前主要针对树莓派平台，也尽可能兼容其他类树莓派硬件产品。你只需要在树莓派上安装好 PHP 服务器环境，即可方便的部署一个 Pi 仪表盘，通过炫酷的 WebUI 来监控树莓派的状态！
项目主页：http://make.quwj.com/project/10
GitHub地址：https://github.com/spoonysonny/pi-dashboard

**更多工具和 APP 被收录在这里：**
https://make.quwj.com/member/2/bookmarks?category=36



# 安装

1. 下载镜像https://www.raspberrypi.org/downloads/

2. 使用SD Card Formatter格式化sd卡(FAT格式)

3. balenaEtcher写入镜像到sd卡

4. 在boot新建ssh空白文件

5. 开启WiFi.wpa_supplicant.conf

   ```
   country=CN
   ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev
   update_config=1
    
   network={
        ssid="无线名称"
        psk="无线密码"
   }
   ```

6. 插入raspberry pi , 接通电源. ssh登入

```
user:pi
password:raspberry

#树莓派配置工具
sudo raspi-config

    #修改地区.字符集和语言
    Localisation Options 
    Change Locale
    #去掉 en_GB.UTF-8 UTF-8 ，勾上 en_US.UTF-8 UTF-8 和zh_CN.UTF-8 UTF-8 和 zh_CN.GBK GBK
    #然后按下Tab键，选确定，然后选zh_CN.UTF-8

    #进入设置页面
    sudo dpkg-reconfigure keyboard-configuration

sudo apt-get -y install vim git zsh autojump 
#sudo apt-get install dnsmasq hostapd udhcpd

#换源
sudo sh -c 'echo deb http://mirrors.aliyun.com/raspbian/raspbian/ buster main non-free contrib rpi deb-src http://mirrors.aliyun.com/raspbian/raspbian/ buster main non-free contrib rpi > /etc/apt/sources.list'

echo 'deb http://mirrors.aliyun.com/raspbian/raspbian/ buster main ui deb-src http://mirrors.aliyun.com/raspbian/raspbian/ buster main ui' | sudo tee /etc/apt/sources.list.d/raspi.list

sudo apt-get update && sudo apt-get upgrade -y

// 设置时区为 亚洲（Asia） 上海（Shanghai）
sudo dpkg-reconfigure tzdata
// 启动 NTP 使计算机时钟与 Internet 时间服务器同步
sudo timedatectl set-ntp true

1.安装 Nginx 和 PHP
sudo apt-get update
sudo apt-get install nginx php7.3-fpm php7.3-cli php7.3-curl php7.3-gd php7.3-cgi
sudo service nginx start
sudo service php7.3-fpm restart

2.部署 Pi Dashboard
#如果已安装过 git 客户端可以跳过下一行
sudo apt-get install git
cd /var/www/html
sudo git clone https://github.com/spoonysonny/pi-dashboard.git
即可通过 http://树莓派IP/pi-dashboard 访问部署好了的 Pi Dashboard。
cd /var/www/html
同样如果页面无法显示，可以尝试在树莓派终端给源码添加运行权限，例如你上传之后的路径是 /var/www/html/pi-dashboard，则运行。
sudo chown -R www-data pi-dashboard
```





## 中文支持及中文输入法

* sudo apt-get install -y ttf-wqy-zenhei
* sudo apt-get install -y scim-pinyin

## 安装docker

* sudo curl -sSL https://get.docker.com | sh
* curl -fsSL https://get.docker.com | bash -s docker --mirror Aliyun(用这个安装比较好??)
* sudo docker run hello-world

```
#重启 systemctl 守护进程
sudo systemctl daemon-reload
#设置 Docker 开机启动
sudo systemctl enable docker
#开启 Docker 服务
sudo systemctl start docker
```

```
#下载 Docker 图形化界面 portainer
sudo docker pull portainer/portainer
#创建 portainer 容器
sudo docker volume create portainer_data
#运行 portainer
sudo docker run -d -p 9000:9000 --name portainer --restart always -v /var/run/docker.sock:/var/run/docker.sock -v portainer_data:/data portainer/portainer
```

```
sudo mkdir -p /etc/docker
sudo tee /etc/docker/daemon.json <<-'EOF'
{
  "registry-mirrors": ["https://yprtfvq7.mirror.aliyuncs.com"]
}
EOF
sudo systemctl daemon-reload
sudo systemctl restart docker
```





# 操作系统

根据偏好选择下列之一。

## Raspberry Pi OS（Raspbian）

树莓派官方深度定制的硬件驱动与软件程序，官方推荐系统。如果你第一次使用树莓派，请下载这个。
[32 位 Lite 版（无桌面）](https://downloads.raspberrypi.org/raspios_lite_armhf_latest) | [32 位桌面版（推荐使用）](https://downloads.raspberrypi.org/raspios_armhf_latest) | [32 位桌面版（含常用软件）](https://downloads.raspberrypi.org/raspios_full_armhf_latest)
默认帐号：Username: pi Password: raspberry

## Ubuntu MATE for the Raspberry Pi

Ubuntu MATE 是桌面 Linux 发行，其宗旨是通过 MATE 这个经典、传统的桌面环境来提供 Ubuntu 操作系统的简介和典雅。MATE 是 GNOME 2 桌面环境的继续，曾经作为 Ubuntu 的缺省桌面，Ubuntu MATE 同样适合树莓派新手使用，界面是最好看的，但是在 CPU 优化方面不如官方的系统做得好。
[官网](https://ubuntu-mate.org/) | [下载页面](https://make.quwj.com/bookmark/334/go)

## Arch Linux ARM

著名轻量系统 Arch Linux 在 ARM 架构上的移植。注重对于开发者的简洁，任何可有可无的软件一律不自带。仅有命令行界面，不建议初学者使用。Arch Linux 的软件策略是相当激进的，使用 Arch Linux 能用到最新的软件包，但也需要承担尝鲜可能的风险。
[官网](https://archlinuxarm.org/) | [下载页面](https://make.quwj.com/bookmark/342/go)
默认帐号：Username: root Password: root

## RetroPie

这是一个基于 Raspbian 构建的家用机模拟器系统，内置了 FC、SFC、GB、GBA、DOS 等游戏平台的模拟器软件，可以将树莓派快速配置成多功能老游戏主机。
[官网](https://make.quwj.com/bookmark/75/go)
默认帐号：Username: pi Password: raspberry

## Volumio

这是一个发烧级音乐播放器系统，功能更丰富 UI 漂亮，旨在以最高保真度播放音乐。
[官网](https://make.quwj.com/bookmark/344/go)
默认帐号：Username: volumio Password: volumio 预设热点 volumio 密码 volumio2

**更多操作系统，被收录在这里：**
https://make.quwj.com/member/2/bookmarks?category=37

# 开箱上手教程

- [树莓派新手指南中文版 PDF](https://pan.baidu.com/s/1mkkbNVa2x7Rf-i7wFJBgmQ)（提取码: isir）
- [首次使用树莓派，如何安装、启动及配置](https://shumeipai.nxez.com/2013/09/07/how-to-install-and-activate-raspberry-pi.html)
- [使用PuTTY登录到树莓派](https://shumeipai.nxez.com/2013/09/07/using-putty-to-log-in-to-the-raspberry-pie.html)
- [最常用的树莓派 Linux 命令及说明](https://shumeipai.nxez.com/2019/02/14/the-most-common-raspberry-pi-commands-and-what-they-do.html)
- [如何让树莓派显示中文？](https://shumeipai.nxez.com/2016/03/13/how-to-make-raspberry-pi-display-chinese.html)
- [树莓派开箱配置之更改键盘布局](https://shumeipai.nxez.com/2017/11/13/raspberry-pi-change-the-keyboard-layout.html)
- [树莓派新系统SSH连接被拒绝的解决方法](https://shumeipai.nxez.com/2017/02/27/raspbian-ssh-connection-refused.html)
- [树莓派 VNC Viewer 远程桌面配置教程](https://shumeipai.nxez.com/2018/08/31/raspberry-pi-vnc-viewer-configuration-tutorial.html)
- [如何设置树莓派 VNC 的分辨率](https://shumeipai.nxez.com/2019/07/08/set-the-resolution-of-the-raspberry-pi-vnc.html)
- [无屏幕和键盘配置树莓派WiFi和SSH](https://shumeipai.nxez.com/2017/09/13/raspberry-pi-network-configuration-before-boot.html)
- [没有显示器且IP未知的情况下登录树莓派](https://shumeipai.nxez.com/2013/09/07/no-screen-unknow-ip-login-pi.html)
- [配置树莓派的音频输出：3.5MM/HDMI](https://shumeipai.nxez.com/2018/12/22/configure-raspberry-pi-audio-output-35mm-hdmi.html)
- [树莓派+一根网线直连笔记本电脑](https://shumeipai.nxez.com/2013/10/15/raspberry-pi-and-a-network-cable-directly-connected-laptop.html)
- [树莓派 Raspberry Pi 设置无线上网](https://shumeipai.nxez.com/2016/09/17/raspberry-pi-set-up-a-wireless-internet-access.html)
- [用Windows远程桌面连接树莓派的方法](https://shumeipai.nxez.com/2013/10/06/windows-remote-desktop-connection-raspberry-pi.html)
- [树莓派上的软件安装和卸载命令汇总](https://shumeipai.nxez.com/2015/01/03/raspberry-pi-software-installation-and-uninstallation-command.html)
- [Linux上vi(vim)编辑器使用教程](https://shumeipai.nxez.com/2013/12/26/linux-on-vim-editor-tutorials.html)
- [Linux/Raspbian 每个目录用途说明](https://shumeipai.nxez.com/2018/01/05/directory-introduction-in-raspbian.html)
- [树莓派如何安全关机重启？](https://shumeipai.nxez.com/2013/08/25/raspberry-pi-how-to-safely-shutdown-restart.html)
- [Mac OS X上使用USB转串口线连接树莓派](https://shumeipai.nxez.com/2015/09/06/mac-os-x-rpi-serial-connection.html)
- [Mac OSX下给树莓派安装Raspbian系统](https://shumeipai.nxez.com/2014/05/18/raspberry-pi-under-mac-osx-to-install-raspbian-system.html)



# Raspbian 中国软件源

[2013年8月31日](https://shumeipai.nxez.com/2013/08) [Spoony](https://shumeipai.nxez.com/author/admin) [未分类](https://shumeipai.nxez.com/category/uncategorized) [32](https://shumeipai.nxez.com/2013/08/31/raspbian-chinese-software-source.html#mh-comments)

花了些时间整理了目前最新的树莓派中国大陆地区的软件源，记下来，希望对大家有帮助。

中国科学技术大学
Raspbian http://mirrors.ustc.edu.cn/raspbian/raspbian/

阿里云
Raspbian http://mirrors.aliyun.com/raspbian/raspbian/

清华大学
Raspbian http://mirrors.tuna.tsinghua.edu.cn/raspbian/raspbian/

华中科技大学
Raspbian http://mirrors.hustunique.com/raspbian/raspbian/
Arch Linux ARM http://mirrors.hustunique.com/archlinuxarm/

华南农业大学（华南用户）
Raspbian http://mirrors.scau.edu.cn/raspbian/

大连东软信息学院源（北方用户）
Raspbian http://mirrors.neusoft.edu.cn/raspbian/raspbian/

重庆大学源（中西部用户）
Raspbian http://mirrors.cqu.edu.cn/Raspbian/raspbian/

~~中山大学~~ 已跳转至中国科学技术大学源
Raspbian [~~http://mirror.sysu.edu.cn/raspbian/raspbian/~~](http://mirror.sysu.edu.cn/raspbian/raspbian/)

新加坡国立大学
Raspbian http://mirror.nus.edu.sg/raspbian/raspbian

牛津大学
Raspbian http://mirror.ox.ac.uk/sites/archive.raspbian.org/archive/raspbian/

韩国KAIST大学
Raspbian http://ftp.kaist.ac.kr/raspbian/raspbian/

------

### 使用说明

#### 备份原始文件（可选步骤）

```shell
sudo cp /etc/apt/sources.list /etc/apt/sources.list.baksudo 
cp /etc/apt/sources.list.d/raspi.list /etc/apt/sources.list.d/raspi.list.bak
```

#### 编辑软件源配置

1、编辑 /etc/apt/sources.list 文件（软件源），参考如下命令：

```shell
sudo nano /etc/apt/sources.list
```

2、删除原文件所有内容，**buster** 系统用以下内容取代：

```
deb http://mirrors.tuna.tsinghua.edu.cn/raspbian/raspbian/ buster main non-free contrib 
deb-src http://mirrors.tuna.tsinghua.edu.cn/raspbian/raspbian/ buster main non-free contrib
```

注：网址末尾的raspbian重复两次是必须的。因为Raspbian的仓库中除了APT软件源还包含其他代码。APT软件源不在仓库的根目录，而在raspbian/子目录下。

**stretch** 系统用以下内容取代：

```shell
deb http://mirrors.sysu.edu.cn/raspbian/raspbian/ stretch main contrib non-free deb-src http://mirrors.sysu.edu.cn/raspbian/raspbian/ stretch main contrib non-free
```

**jessie** 用以下内容取代：

```shell
deb http://mirrors.sysu.edu.cn/raspbian/raspbian/ jessie main contrib non-free deb-src http://mirrors.sysu.edu.cn/raspbian/raspbian/ jessie main contrib non-free
```

**wheezy** 用以下内容取代：

```shell
deb http://mirrors.sysu.edu.cn/raspbian/raspbian/ wheezy main contrib non-free deb-src http://mirrors.sysu.edu.cn/raspbian/raspbian/ wheezy main contrib non-free
```

Ctrl+o 保存，之后回车确认，然后 Ctrl+x 退出。

#### 编辑系统源配置

1、编辑 /etc/apt/sources.list.d/raspi.list 文件（系统更新源），参考如下命令：

```sh
sudo nano /etc/apt/sources.list.d/raspi.list
```

2、同样修改首行网址，修改后文件如下：

```shell
deb http://mirrors.tuna.tsinghua.edu.cn/raspberrypi/ buster main ui
# Uncomment line below then 'apt-get update' to enable 'apt-get source'#deb-src http://archive.raspberrypi.org/debian/ stretch main ui
```

jessie、wheezy 版本的系统按照之前修改软件源的的规则修改即可，这里不再赘述。
Ctrl+o 保存，之后回车确认，然后 Ctrl+x 退出。

#### 更新

配置好了可以尝试更新，用下面的命令分别更新软件源列表、软件版本和系统内核版本，完整的更新过程需要等挺久的。一般只用更新软件源列表即可。

```shell
#更新软件源列表
sudo apt-get update
#更新软件版本
sudo apt-get upgrade 
sudo apt-get dist-upgrade
#更新系统内核
sudo rpi-update
```

如果需要，你可以执行以下命令将Raspbian public key加入你的 apt-get keyring :

```shell
wget http://archive.raspbian.org/raspbian.public.key -O - | sudo apt-key add -
```

> **2015.11.23 add a update from comments：**
> 现在版本升级了，版本号要更改，把 wheezy 改成 jessie 这样大部分源是在中国，不然像楼上那样升级，大部分源在国外。
>
> **2019.3.7 add a update：**
> 添加系统更新源（/etc/apt/sources.d/raspi.list）的步骤。



# 树莓派 Raspberry Pi 设置无线上网

[2016年9月17日](https://shumeipai.nxez.com/2016/09) [Spoony](https://shumeipai.nxez.com/author/admin) [未分类](https://shumeipai.nxez.com/category/uncategorized) 

#### 一、查看网卡状态是否正常

把无线网卡插到树莓派上，输入命令ifconfig -a查看是否有wlan0的信息，如果有说明网卡状态正常，可以跳过第二步，直接配置无线网络。如果查不到wlan0的信息，则需要安装无线网卡的驱动。

#### 二、查看无线网卡的信息

输入命令dmesg | grep usb查看无线网卡的信息，主要是看制造厂家（Manufacturer）。比如，我的网卡信息是
usb 1-1.3: Manufacturer: Realtek

以Realtek为例，安装无线网卡驱动。
如果现在你的树莓派能联网，输入安装命令就可以安装Realtek的驱动了。

首先搜索Realtek驱动：

```
apt-cache search realtek
```

看到下面信息：
firmware-realtek – Binary firmware for Realtek wired and wireless network adapters
安装Realtek驱动：

```
sudo` `apt-get ``install` `firmware-realtek
```

如果你的树莓派现在不能上网，那么你可以去镜像站点中下载相关驱动。我推荐阿里云的镜像站点，速度比较快。http://mirrors.aliyun.com/raspbian/raspbian/pool/non-free/f/firmware-nonfree

下载firmware-realtek_0.43_all.deb，用winscp上传到树莓派的/tmp目录中。输入命令安装：

```
sudo` `dpkg -i ``/tmp/firmware-realtek_0``.43_all.deb
```

#### 三、配置无线网络

用编辑器nano打开interfaces文件

```
sudo` `nano ``/etc/network/interfaces
```

我的interfaces文件是这样的：

```
auto lo` `iface lo inet loopback``iface eth0 inet dhcp` `allow-hotplug wlan0``iface wlan0 inet manual``wpa-roam /etc/wpa_supplicant/wpa_supplicant.conf``iface default inet dhcp
```

我们把无线网卡部分全部用#注释掉，然后添加自己的配置信息，最终结果如下：

```
auto lo` `iface lo inet loopback``iface eth0 inet dhcp` `auto wlan0``#allow-hotplug wlan0``#iface wlan0 inet manual``iface wlan0 inet dhcp``wpa-conf /etc/wpa.conf``#wpa-roam /etc/wpa_supplicant/wpa_supplicant.conf``iface default inet dhcp
```

使用nano编辑器，ctrl+o保存，ctrl+x退出。

用编辑器nano创建 /etc/wpa.conf 文件：

```
sudo` `nano ``/etc/wpa``.conf
```

如果你的wifi没有密码

```
network={``[Tab] ssid="你的无线网络名称（ssid）"``[Tab] key_mgmt=NONE``}
```

如果你的wifi使用WEP加密

```
network={``[Tab] ssid="你的无线网络名称（ssid）"``[Tab] key_mgmt=NONE``[Tab] wep_key0="你的wifi密码"``}
```

如果你的wifi使用WPA/WPA2加密

```
network={``[Tab] ssid="你的无线网络名称（ssid）"``[Tab] key_mgmt=WPA-PSK``[Tab] psk="你的wifi密码"``}
```

注1：所有符号都是半角符号（英文状态下的符号），“[Tab]”表示按一次Tab键
注2：如果你不清楚wifi的加密模式，可以在安卓手机上用root explorer打开 /data/misc/wifi/wpa/wpa_supplicant.conf，查看wifi的信息。

比如，我的wpa.conf文件是这样的：

```
network={``    ssid="1234"``    key_mgmt=WPA-PSK``    psk="MTIzNA1234"``}
```

最后输入命令启用无线网卡：

```
sudo` `ifup wlan0
```

可以连无线网了。