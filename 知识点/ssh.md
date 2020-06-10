SSH是一种网络协议，用于计算机之间的加密登录。
SSH只是一种协议，存在多种实现，既有商业实现，也有开源实现。本文针对的实现是OpenSSH，它是自由软件，应用非常广泛。
当远程主机的公钥被接受以后，它就会被保存在文件**$HOME/.ssh/known_hosts**之中。下次再连接这台主机，系统就会认出它的公钥已经保存在本地了，从而跳过警告部分，直接提示输入密码。
每个SSH用户都有自己的known_hosts文件，此外系统也有一个这样的文件，通常是，保存一些对所有用户都可信赖的远程主机的公钥。

/etc/ssh/ssh_known_hosts
//保存一些对所有用户都可信赖的远程主机的公钥
$HOME/.ssh/known_hosts
//保存远程主机的公钥文件
$HOME/.ssh/config
//

# SSH

* $HOME/.ssh
* ssh -p 2222 root@host
* ssh-keygen//生成ssh私钥和公钥在上面的目录下
* ssh-keygen -R 192.168.0.105//清理该IP的认证
* ssh-copy-id user@host//复制到远程主机,, 之后就可以不用密码登录了
* ssh user@host 'mkdir -p .ssh && cat >> .ssh/authorized_keys' < ~/.ssh/id_rsa.pub//是上面一条命令的解释