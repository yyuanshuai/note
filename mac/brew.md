brew services start nginx

下载目录

/Users/yangyuanshuai/Library/Caches/Homebrew/

/Users/yangyuanshuai/Library/Caches/Homebrew/cask

/Users/yangyuanshuai/Library/Caches/Homebrew/downloads

```
brew search git
brew install git
brew upgrade git
brew uninstall git
# 更新brew
brew update
# 查看可更新软件
brew outdated
brew list --versions [FORMULA...]
brew cleanup
brew search [TEXT|/REGEX/]

# brwe info
which brew 
brew info [FORMULA...]
brew config
brew doctor
Homebrew下载后的软件包放在/Library/Caches/Homebrew中
安装时因为网络原因， 经常下载失败，可以先用其他下载工具下载好， 然后放到brew的缓存目录(运行brew --cache可得， 一般是/Library/Caches/Homebrew)里

brew services list  # 查看使用brew安装的服务列表
brew services run formula|--all  # 启动服务（仅启动不注册）
brew services start formula|--all  # 启动服务，并注册
brew services stop formula|--all   # 停止服务，并取消注册
brew services restart formula|--all  # 重启服务，并注册
brew services cleanup  # 清除已卸载应用的无用的配置
```

# 替换更新源
```
# 替换brew.git:
$ cd "$(brew --repo)"
# 中国科大:
$ git remote set-url origin https://mirrors.ustc.edu.cn/brew.git
# 清华大学:
$ git remote set-url origin https://mirrors.tuna.tsinghua.edu.cn/git/homebrew/brew.git

# 替换homebrew-core.git:
$ cd "$(brew --repo)/Library/Taps/homebrew/homebrew-core"
# 中国科大:
$ git remote set-url origin https://mirrors.ustc.edu.cn/homebrew-core.git
# 清华大学:
$ git remote set-url origin https://mirrors.tuna.tsinghua.edu.cn/git/homebrew/homebrew-core.git

# 替换homebrew-bottles:
# 中国科大:
$ echo 'export HOMEBREW_BOTTLE_DOMAIN=https://mirrors.ustc.edu.cn/homebrew-bottles' >> ~/.bash_profile
$ source ~/.bash_profile
# 清华大学:
$ echo 'export HOMEBREW_BOTTLE_DOMAIN=https://mirrors.tuna.tsinghua.edu.cn/homebrew-bottles' >> ~/.bash_profile
$ source ~/.bash_profile

# 应用生效:
$ brew update
```
