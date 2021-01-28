## Windows给力！可以扔掉Linux虚拟机了！



## 安装Windows Terminal

在远程连接其他Linux的时候，我通常使用`Xshell`，就因为它长得比较漂亮耐看。

在Windows上，就可以安装`Windows Terminal`。有点类似于MacOS上的`iTerm`，可以说是Windows下最舒适的终端。

安装`Windows Terminal`需要从应用商店去获取，就是下面这个按钮。

![img](https://mmbiz.qpic.cn/mmbiz_png/cvQbJDZsKLo08E6fxsGqkb19ibKiamSjYuwHmnBPzu5MgXckINQTaEmKTMibo6mdAKNwHPuYs5dLI9KY7oy01Vjnw/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)

在搜索框里搜索`Windows Terminal`，即可找到这个软件。比较人性化的一点是，它不像Mac的应用商店一样，需要你先准备一个账号。WT不需要登录即可获取。

如果你的页面一直打转也不要紧，关闭重新打开几次就好了。由于众所周知的原因，国外网站就没有几个不转圈的。

![img](https://mmbiz.qpic.cn/mmbiz_png/cvQbJDZsKLo08E6fxsGqkb19ibKiamSjYurBJiaY3UKxw8zpTDE74Pibw7PQTw0ib4XeImWRzwlZia6AdXeicq7ACOWow/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)

## 安装Ubuntu子系统

此时，我们仅仅安装了一个命令行终端而已，离我们扔掉Linux的目标还差上一小节。别担心，下面就介绍怎么在`Windows`上安装`Ubuntu`。

方案一、通过虚拟机安装Linux，然后终端去访问？。这种方案太低级，是我过去一直用的方式，充满了坎坷。

方案二、划分一个分区安装Linux，然后重启的时候进行切换。开个玩笑，这种方式更加落后，属于古董级别玩家的产物。

我们只需要在系统上开启子系统功能，然后在应用商店安装Linux就可以了。

有多简单？简单到你操作的时间可能都没看我唠叨的时间花费多。

如下图，在控制面板，找到程序选项，点击  “启用或关闭Windows功能”。

![img](https://mmbiz.qpic.cn/mmbiz_png/cvQbJDZsKLo08E6fxsGqkb19ibKiamSjYuLibuiaibnibHOWN7lZ7l49UYfWSxV25oAhqPicXDuMf6Evm4dVadQuibF71w/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)

从弹出的对话框里，划到最下边，然后给“适用于Linux的Windows子系统“，打勾，完事！

![img](https://mmbiz.qpic.cn/mmbiz_png/cvQbJDZsKLo08E6fxsGqkb19ibKiamSjYuTsSVicRicGf1EFLGT2k3U4uKAmzxdIuNZXlJDaRszX8aiaDMwngj0wldg/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)

从应用商店安装Ubuntu系统，这个系统将会以软件的形式存在。我这里选择的是LTS版本，可以看到给它打分的人并不多，可能大多数都是像我一样没有微软账号的游客。

![img](https://mmbiz.qpic.cn/mmbiz_png/cvQbJDZsKLo08E6fxsGqkb19ibKiamSjYu3Zzh2BzFviaVILNqDMqvmsGBn63nwogiaI4jebq2R74dvzU7llEGBwkg/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)

## 事后配置

此时，神奇的事情发生了。在我们的`Windows Terminal`右上角，有一个向下的箭头，点击它，就可以看到刚刚安装的Ubuntu。

在Windows上离着Linux，只差一次点击而已。

![img](https://mmbiz.qpic.cn/mmbiz_png/cvQbJDZsKLo08E6fxsGqkb19ibKiamSjYuOMXMpRuIpOjslUJHL4MCh3Mxw62iaNiaReciahYrnLuXs1aCvr0zY2N9w/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)

进入Linux系统之后，我们就可以像配置一个普通Linux一样配置这台机器。

首先把ubuntu的软件源给换掉。编辑`/etc/apt/sources.list`文件，把它的内容换成下面的源。

```
deb https://mirrors.ustc.edu.cn/ubuntu/ bionic main restricted universe multiverse
deb https://mirrors.ustc.edu.cn/ubuntu/ bionic-updates main restricted universe multiverse
deb https://mirrors.ustc.edu.cn/ubuntu/ bionic-backports main restricted universe multiverse
deb https://mirrors.ustc.edu.cn/ubuntu/ bionic-security main restricted universe multiverse
deb https://mirrors.ustc.edu.cn/ubuntu/ bionic-proposed main restricted universe multiverse
deb-src https://mirrors.ustc.edu.cn/ubuntu/ bionic main restricted universe multiverse
deb-src https://mirrors.ustc.edu.cn/ubuntu/ bionic-updates main restricted universe multiverse
deb-src https://mirrors.ustc.edu.cn/ubuntu/ bionic-backports main restricted universe multiverse
deb-src https://mirrors.ustc.edu.cn/ubuntu/ bionic-security main restricted universe multiverse
deb-src https://mirrors.ustc.edu.cn/ubuntu/ bionic-proposed main restricted universe multiverse
```

然后，安装最好用的`oh-my-zsh`。先用`sudo apt install zsh`安装shell终端，然后运行下面的命令。

```
sh -c "$(curl -fsSL https://raw.github.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"
```

等待一小段时间，我们的终端颜值就更上一层楼了。

如果你想要你的终端更加漂亮，可以参考下面的主题页面。毕竟命令终端是你每天都要面对的，比你面对自己女朋友的时间还要长，长得丑是影响心情的。

```
https://terminalsplash.com/
```

![img](https://mmbiz.qpic.cn/mmbiz_png/cvQbJDZsKLo08E6fxsGqkb19ibKiamSjYurTtjXfAl4vZcrxib8qoxwAeR1uhmjQ0Hy5dgHXGxHGxlW5U8HsulAyA/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)

还有最后一个问题。我们Linux系统中的文件，在Windows中如何访问呢？

这个就有点魔幻了。在Linux下执行下面的命令。

```
cd /home
explorer.exe .
```

上面的命令，即可打开Linux目录对应的Windows目录，从文件管理器中我们就可以访问到。

为了操作方便，我把这个长长的目录，映射到了Z盘上。如图，下次在访问Linux的时候，直接访问Z盘就可以了。

![img](https://mmbiz.qpic.cn/mmbiz_png/cvQbJDZsKLo08E6fxsGqkb19ibKiamSjYu5xTxNzzkwEIY7h90MAOLpiayTq0YLORxkvhIiadibhU72OucabnI9N8dA/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)

