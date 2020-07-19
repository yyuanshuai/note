

# Vtube

### 命令

* pm2 start dist/bin/server/index-dev.js --watch
* pm2 ls
* pm2 delete app  // 指定进程名删除    
* pm2 delete 0    // 指定进程id删除



## typeorm

* this.rep.save(order);

* this.rep.update({ id: orderId }, values);

* this.rep.findOne({ id: orderId });

* this.rep.find({ id: In(orderIds) });

* this.rep.createQueryBuilder()

  ​            .where({ type: type, status: In(status) })

  ​            .andWhere(`HOUR(timediff( now(), updateAt)) >= ${hour}`)

  ​            .getMany();

* this.rep.createQueryBuilder()

  ​            .where(sql).orderBy('updateAt', 'DESC')

  ​            .skip((page - 1) * size)

  ​            .take(size).getManyAndCount();

* .getManyAndCount();

* .getCount();

* this.rep.query(sql) as Array<{ orderNums: number }>;







### pm2

* 安装

  >npm install pm2 -g

* 启动一个node程序

  > pm2 start index.js

* 启动进程并指定应用的程序名

  > pm2 start app.js --name application1

* 集群模式启动

  > // -i 表示 number-instances 实例数量    
  >
  > // max 表示 PM2将自动检测可用CPU的数量 可以自己指定数量   
  >
  > pm2 start start.js -i max

* 添加进程监视

  > // 在文件改变的时候会重新启动程序    
  >
  > pm2 start app.js --name start --watch

* 列出所有进程

  > pm2 ls

* 从进程列表中删除进程

  > // pm2 delete [appname] | id    
  >
  > pm2 delete app  // 指定进程名删除    
  >
  > pm2 delete 0    // 指定进程id删除

*  查看某个进程具体情况

  >  pm2 describe app

* 查看进程的资源消耗情况

  > pm2 monit

* 查看进程日志 

  >  pm2 logs app | all