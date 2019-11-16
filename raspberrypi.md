
## debian

#### 更换源
sudo vim /etc/apt/sources.list
deb http://mirrors.aliyun.com/raspbian/raspbian/ buster main non-free contrib rpi
deb-src http://mirrors.aliyun.com/raspbian/raspbian/ buster main non-free contrib rpi
sudo apt-get update
sudo apt-get upgrade -y
