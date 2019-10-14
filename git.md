git config --list
git config --global user.name 'name'
git config --global user.email '...@qq.com'
git config --system user.name 'name'
git config --local user.name 'name'
git add .
git add filenames
git reset filename
git reset --hard HEAD^
git reset --hard HEAD~100
git commit -m "提交描述"
git push origin master
git push ssh://root@192.168.1.106:22/srv/buyplus.git
git pull ssh://root@192.168.1.106:22/srv/buyplus.git
git clone ssh://root@192.168.1.106:22/srv/buyplus.git
git clone /srv/gitRepo/buyplus.git/ buyplus
git status
git diff
git diff --cached
git diff HEAD
git diff --stat
git rm file
git rm --cached -r .
git checkout --file
git init myweb
git init --bare myweb
git log
git reflog
git log -p
git log --pretty=online
git log --after '2017-5-22'
git log --before'2017-5-22'
git log --author 'bage'
git remote
git remote get-url origin
ssh://root@192.168.1.106:22/srv/gitRepo/buyplus.git
git remote add origin ssh://root@192.168.1.106:22/srv/buyplus.git


mv .env.txt .env
ssh-keygen -t rsa -b 4096 -C "yangys@hspaces.cn"
ssh-keygen -t rsa -C "your_email@youremail.com"
clip < ~/.ssh/id_rsa.pub
cat ~/.ssh/id_rsa.pub | pbcopy
git clone ssh://root@github.hspaces.cn:22222/php/laravel_template.git



    1  git init
    2  git add .
    3  git commit -m "first commit"
    4  git remote add origin ssh://root@github.hspaces.cn:22222/mini_program/quzhidao.git
    5  git push -u origin master



$git push origin master:master
 (在local repository中找到名字为master的branch，使用它去更新remote repository下名字为master的branch，如果remote repository下不存在名字是master的branch，那么新建一个)

$git push origin master 
（省略了<dst>，等价于“git push origin master:master”）

$git push origin master:refs/for/mybranch 
 (在local repository中找到名字为master的branch，用他去更新remote repository下面名字为mybranch的branch)

$git push origin HEAD:refs/for/mybranch 
（HEAD指向当前工作的branch，master不一定指向当前工作的branch，所以我觉得用HEAD还比master好些）

$git push origin :mybranch
（再origin repository里面查找mybranch，删除它。用一个空的去更新它，就相当于删除了）


从命令行创建一个新的仓库
touch README.md
git init
git add README.md
git commit -m "first commit"
git remote add origin ssh://root@github.hspaces.cn:22222/meiya/meiya-php.git
git push -u origin master
