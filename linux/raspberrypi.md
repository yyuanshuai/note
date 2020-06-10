## debian

#### 打开树莓派配置选项

* sudo raspi-config

```
Localisation Options 
Change Locale
去掉 en_GB.UTF-8 UTF-8 ，勾上 en_US.UTF-8 UTF-8 和zh_CN.UTF-8 UTF-8 和 zh_CN.GBK GBK
然后按下Tab键，选确定，然后选zh_CN.UTF-8
```





#### 更换源

* sudo vim /etc/apt/sources.list

> deb http://mirrors.aliyun.com/raspbian/raspbian/ buster main non-free contrib rpi
>
> deb-src http://mirrors.aliyun.com/raspbian/raspbian/ buster main non-free contrib rpi

* sudo vim /etc/apt/sources.list.d/raspi.list

> deb http://mirrors.aliyun.com/raspbian/raspbian/ buster main ui
>
> deb-src http://mirrors.aliyun.com/raspbian/raspbian/ buster main ui

sudo apt-get update && apt-get upgrade -y

##### 中文支持及中文输入法

* sudo apt-get install -y ttf-wqy-zenhei
* sudo apt-get install -y scim-pinyin

##### 安装vim

* sudo apt-get install -y vim

##### 设置时区

* sudo dpkg-reconfigure tzdata
* sudo timedatectl set-ntp true//设置同步时间服务器

##### 安装docker

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





