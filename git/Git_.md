# git命令

### 1. 查看命令

git reflog

git status





### 2. 基本版本操作

git clone <repo> <directory>

git add .

git add *

git add <filename>

git commit -m "代码提交信息"

git remote add origin ssh://root@github.hspaces.cn:22222/mini_program/quzhidao.git

git push origin master





### 3. 不常用操作

git init .

git init --bare my-project.git



### 4. 分支操作

git branch//列出所有分支

git branch <branch>//创建一个名为 `<branch>` 的分支。*不会* 自动切换到那个分支去。

git branch -d <branch>

git branch -D <branch>//强制删除指定分支，即使包含未合并更改。如果你希望永远删除某条开发线的所有提交，你应该用这个命令。

git branch -m <branch>//将当前分支命名为 `<branch>`。

git checkout <branch>//切换分支

git checkout -b <branch>//创建并切换分支

git checkout -b <branch> <existing-branch>//已existing-branch为基,创建并切换分支

git merge <branch>//将指定分支并入当前分支

git merge --no-ff <branch>//将指定分支并入当前分支，但 *总是* 生成一个合并提交（即使是快速向前合并）。这可以用来记录仓库中发生的所有合并。



### 5. 配置操作

git config --global user.name "underpants"

git config --global user.email 872871142@qq.com

git config --global --edit//编辑器(vim)打开全局配置文件

