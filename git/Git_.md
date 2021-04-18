https://git-scm.com/book/zh/v2/

https://backlog.com/git-tutorial/cn

https://www.liaoxuefeng.com/wiki/896043488029600/

# 查看命令

```shell
git log --graph --pretty=oneline --abbrev-commit
git reflog
git status

```

# 基本版本操作

```shell
git clone <repo> <directory>
git add .
git add *
git add <filename>
git commit -m "代码提交信息"
git commit --amend#修正(覆盖)上一次的commit, 
git remote add origin ssh://root@github.hspaces.cn:22222/mini_program/quzhidao.git
git push origin master
```

# 远程操作

```shell
git remote
git remote -v
git remote show
git remote show origin
git remote add origin ssh://root@github.hspaces.cn:22222/mini_program/quzhidao.git


git clone https://github.com/schacon/ticgit
==
git fetch <remote>
git merge

git fetch --all
git fetch <remote>#执行完成后，你将会拥有那个远程仓库中所有分支的引用，可以随时合并或查看

git pull <remote> <branch>
git push <remote> <branch>

#删除远程分支
git push origin --delete serverfix#基本上这个命令做的只是从服务器上移除这个指针。 Git 服务器通常会保留数据一段时间直到垃圾回收运行，所以如果不小心删除掉了，通常是很容易恢复的。



```



# 分支操作

```shell
git branch#列出所有分支
git branch -v#查看每一个分支的最后一次提交
git branch -vv#列出本地每一个分支正在跟踪哪个远程分支与本地分支是否是领先、落后或是都有
git branch --merged#查看已合并到当前分支的分支
git branch --no-merged master
git branch <branch>#创建一个名为 `<branch>` 的分支。不会自动切换到新分支
git branch -d <branch>
git branch -D <branch>#强制删除指定分支，即使包含未合并更改。如果你希望永远删除某条开发线的所有提交，你应该用这个命令。
git branch -m <branch>#将当前分支命名为 `<branch>`。
git branch --set-upstream-to=origin/dev dev#将远程origin/dev分支关联本地dev分支
git branch --set-upstream-to <branch-name> origin/<branch-name>#
git branch -u origin/dev

git checkout <branch>|git switch <branch>#切换分支
git checkout -b <branch>#创建并切换分支
git checkout -b <branch> <existing-branch>#已existing-branch为基,创建并切换分支
git checkout -b dev origin/dev#创建dev分支并连接远程origin/dev分支


git merge <branch>#将指定分支并入当前分支Fast-forward模式`fast forward`合并就看不出来曾经做过合并。
git merge --no-ff  -m "commit msg" <branch>#将指定分支并入当前分支，但总是生成一个合并提交（即使是快速向前合并）。这可以用来记录仓库中发生的所有合并。

git stash#将当前分支修改的隐藏起来
git stash list#查看stash
git stash apply#将stash恢复, 并不清除stash
git stash apply stash@{0}
git stash drop#清除stash
git stash pop#将stash恢复, 并清除stash

git cherry-pick 4c805e2#同样的bug，修好后, 合并到master后, 还要在dev上修复，我们只需要把4c805e2 fix bug 101这个提交所做的修改“复制”到dev分支

git checkout experiment
git rebase master#experiment分支将变基到 master 分支, 此时master分支上会多一个提交,experiment指针移动到该提交, 还需手动快速合并
git checkout master
git merge experiment

git rebase --onto master server client
# 以上命令的意思是：“取出 client 分支，找出它从 server 分支分歧之后的补丁， 然后把这些补丁在 master 分支上重放一遍，让 client 看起来像直接基于 master 修改一样”。这理解起来有一点复杂，不过效果非常酷。

git rebase master server#使用 git rebase <basebranch> <topicbranch> 命令可以直接将主题分支 （即本例中的 server）变基到目标分支（即 master）上。 这样做能省去你先切换到 server 分支，再对其执行变基命令的多个步骤。
#变基的风险
#呃，奇妙的变基也并非完美无缺，要用它得遵守一条准则：
#如果提交存在于你的仓库之外，而别人可能基于这些提交进行开发，那么不要执行变基。
#如果你遵循这条金科玉律，就不会出差错。 否则，人民群众会仇恨你，你的朋友和家人也会嘲笑你，唾弃你。


```

![https://backlog.com/git-tutorial/cn/img/post/stepup/capture_stepup1_5_6.png](https://backlog.com/git-tutorial/cn/img/post/stepup/capture_stepup1_5_6.png)

## 主分支

主分支有两种：master分支和develop分支

- **master**
    master分支只负责管理发布的状态。在提交时使用标签记录发布版本号。
- **develop**
    develop分支是针对发布的日常开发分支。刚才我们已经讲解过有合并分支的功用。

## 特性分支

特性分支就是我们在前面讲解过的topic分支的功用。

这个分支是针对新功能的开发，在bug修正的时候从develop分支分叉出来的。基本上不需要共享特性分支的操作，所以不需要远端控制。完成开发后，把分支合并回develop分支后发布。

## release分支

release分支是为release做准备的。通常会在分支名称的最前面加上release-。release前需要在这个分支进行最后的调整，而且为了下一版release开发用develop分支的上游分支。

一般的开发是在develop分支上进行的，到了可以发布的状态时再创建release分支，为release做最后的bug修正。

到了可以release的状态时，把release分支合并到master分支，并且在合并提交里添加release版本号的标签。

要导入在release分支所作的修改，也要合并回develop分支。

## hotFix分支

hotFix分支是在发布的产品需要紧急修正时，从master分支创建的分支。通常会在分支名称的最前面加上 hotfix-。

例如，在develop分支上的开发还不完整时，需要紧急修改。这个时候在develop分支创建可以发布的版本要花许多的时间，所以最好选择从master分支直接创建分支进行修改，然后合并分支。

修改时创建的hotFix分支要合并回develop分支。

# 配置操作

```shell
git config --list --show-origin#查看所有的配置以及它们所在的文件
git config user.name#检查 Git 的某一项配置

git config --global user.name "underpants"
git config --global user.email 872871142@qq.com
git config --global --edit#编辑器(vim)打开全局配置文件

#代理设置
git config --global https.proxy http://127.0.0.1:1080
git config --global https.proxy https://127.0.0.1:1080
git config --global --unset http.proxy
git config --global --unset https.proxy

```



# 不常用操作

```shell
git init .
git init --bare my-project.git
```

