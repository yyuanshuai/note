
安装用的U盘安装，使用的ultariso。写入硬盘的时候选用的RAW的方式去写入的，否则会有问题
### 更换源
> sudo nano /etc/pacman.conf
> 加上 
```
[archlinuxcn]
SigLevel = Never
Server = https://mirrors.tuna.tsinghua.edu.cn/archlinuxcn/$arch
或
SigLevel = Optional TrustedOnly
Server = https://mirrors.ustc.edu.cn/archlinuxcn/$arch
```
> sudo pacman-mirrors -i -c China -m rank  //-c change就可以了  
> sudo pacman -Syy && sudo pacman -S archlinuxcn-keyring  
> sudo pacman -Syu  //更新  -Syyu 强制更新  
> sudo pacman -S vim //安装vim
> sudo vim /etc/pacman.conf // 打开Color,去掉#  
> sudo pacman -Syy  //同步软件库  


### 安装常用软件
> sudo pacman -S vim
> sudo pacman -S yaourt
#### 搜狗拼音
> sudo pacman -S fcitx//不执行这条好像更好
> sudo pacman -S fcitx-im 
> sudo pacman -S fcitx-configtool
> sudo pacman -S fcitx-sougoupinyin
> vim ~/.xprofile// 加上以下
export GTK_IM_MODULE=fcitx
export QT_IM_MODULE=fcitx
export XMODIFIERS="@im=fcitx"

> sudo pacman -S google-chrome
> sudo pacman -S netease-cloud-music
> sudo pacman -S deepin.com.qq.office或pacman -S deepin.com.qq.im
> sudo pacman -S electronic-wechat
> sudo pacman -S wps-office
> sudo pacman -S ttf-wps-fonts
> sudo pacman -S v2ray
> yaourt GitKraken
> yaourt typora
> yaourt foxit
> yaourt musicbox//命令行版
> sudo pacman -S gimp

sudo pacman -S wiznote #为知笔记
sudo pacman -S shadowsocks-qt5 # 和谐上网必备
sudo pacman -S qtcreator # 牛逼的IDE
sudo pacman -S visual-studio-code-bin # vscode

sudo pacman -S make # 牛逼的工具

sudo pacman -S screenfetch # 显示Linux环境工具
sudo pacman -S deepin.com.qq.office # qq
sudo pacman -S clang # 牛逼的编译器

sudo pacman -S electronic-wechat-git #微信

sudo pacman -S gdb # 默认没有

sudo pacman -S flameshot-git #截图工具
### 安装fish
> sudo pacman -S fish  
> which fish 
> chsh -s /usr/bin/fish  
> surl -L https://get.oh-my.fish | fish  
> 
### 安装i3
> sudo pacman -S i3  //重启进i3  
```
进入i3 回车回车
win加回车打开命令行
放大字体 vim ~/.Xresources
Xft.dpi:200
```
#### i3配置文件
> vim ~/.config/id/config
> 添加快捷键 bindsym $mod+c exec firefox --no-startup-id //$mod一般设置为win键.开启火狐. 加上参数否则鼠标会显示忙碌
> 


# pacman
> *sudo pacman -S package_name1 ...//安装  
> *sudo pacman -R package_name//删除  
> *sudo pacman -Rs package_name//删除指定软件包，及其所有没有被其他已安装软件包使用的依赖关系
> *sudo pacman -Ss string1 string2 //在包数据库中查询软件包，查询位置包含了软件包的名字和描述
> *sudo pacman -Qs string1 string2//查询已安装的软件包
> sudo pacman -Q --help//使用 -Q 参数查询本地软件包数据库
> sudo pacman -S --help//使用 -S 参数查询远程同步的数据库
> sudo pacman -U /path/to/package/package_name-version.pkg.tar.xz//安装一个本地包(不从源里下载）：
> sudo pacman -Sc//将下载的软件包保存在 /var/cache/pacman/pkg/ 并且不会自动移除旧的和未安装版本的软件包，因此需要手动清理，以免该文件夹过于庞大。
> sudo pacman -Qi package_name//查询本地安装包的详细信息

