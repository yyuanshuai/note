[打工四年总结的数据库知识点](https://juejin.cn/post/6883270227078070286)

[MySQL 三万字精华总结 + 面试100 问，和面试官扯皮绰绰有余（收藏系列）](https://juejin.cn/post/6850037271233331208)

[实时刷新缓存-处理mysql主从延迟的一些设计方案](https://juejin.cn/post/6844903496106377223)



[面试官问你B树和B+树，就把这篇文章丢给他](https://juejin.cn/post/6844903944292925447)

[腾讯面试：一条SQL语句执行得很慢的原因有哪些？---不看后悔系列](https://mp.weixin.qq.com/s?__biz=Mzg2OTA0Njk0OA==&mid=2247485185&idx=1&sn=66ef08b4ab6af5757792223a83fc0d45&chksm=cea248caf9d5c1dc72ec8a281ec16aa3ec3e8066dbb252e27362438a26c33fbe842b0e0adf47&token=79317275&lang=zh_CN#rd)

[MySQL的万字总结（缓存，索引，Explain，事务，redo日志等）](https://juejin.cn/post/6844904039860142088)

[面试中的老大难-mysql事务和锁，一次性讲清楚！](https://juejin.im/post/6855129007336521741)

[必须了解的mysql三大日志-binlog、redo log和undo log](https://juejin.cn/post/6860252224930070536)

[数据库两大神器【索引和锁】](https://juejin.cn/post/6844903645125820424)

[MySQL命令，一篇文章替你全部搞定](https://juejin.cn/post/6844903599915401230)

[解决死锁之路 - 了解常见的锁类型](https://www.aneasystone.com/archives/2017/11/solving-dead-locks-two.html)





[不会看 Explain执行计划，劝你简历别写熟悉 SQL优化](https://juejin.cn/post/6844904163969630221)

[mysql执行计划Explain详解，再也不用怕sql优化了！](https://juejin.cn/post/6850418111272009735)







--孤独烟 -------------------------------------------------------------------------------------

[【原创】面试官:谈谈你对mysql联合索引的认识？](https://www.cnblogs.com/rjzheng/p/12557314.html)

[【原创】面试官:谈谈你对mysql索引的认识？](https://blog.csdn.net/fujiandiyi008/article/details/102617916)

[【原创】MySQL(Innodb)索引的原理](https://www.cnblogs.com/rjzheng/p/9915754.html)

[【原创】Mysql中select的正确姿势](https://www.cnblogs.com/rjzheng/p/9902911.html)

[【原创】研发应该懂的binlog知识(上)](https://www.cnblogs.com/rjzheng/p/9721765.html)

[【原创】研发应该懂的binlog知识(下)](https://www.cnblogs.com/rjzheng/p/9745551.html)

[【原创】数据库优化的几个阶段](https://www.cnblogs.com/rjzheng/p/9619855.html)

[【原创】惊！史上最全的select加锁分析(Mysql)](https://www.cnblogs.com/rjzheng/p/9950951.html)



# 分库分表

垂直拆分: 

1. 每个库(表) 的结构都不一样

2. 每个库(表) 的数据都(至少有一列)一样

3. 每个库(表) 的并集是全量数据

    优点: 

    1. 拆分后业务清晰(专库专用按业务拆分)
    2. 数据维护简单, 按业务不同业务放到不同机器上

    缺点: 

    1. 如果单表的数据量大, 写读压力大
    2. 收某种业务来决定, 或者被限制. 也就是说一个业务旺旺会影响到数据库的瓶颈(性能问题)
    3. 部分业务无法关联join, 只能通过java程序接口调用, 提高了开发复杂度

水平拆分: 

1. 每个库(表) 的结构都一样

2. 每个库(表) 的数据都不一样

3. 每个库(表) 的并集是全量数据

    优点:

    1. 单库(表)的数据保持在一定的量(减少), 有助于性能提高
    2. 提高了系统的稳定性和负载能力
    3. 切分的表的结构相同, 重新改造较少

    缺点: 

    1. 数据的扩容很有难度维护量大
    2. 拆分规则很难抽象出来
    3. 分片事务的一致性的问题部分业务无法关联join, 只能通过java重新调用

带来的问题: 

1. 分布式事务问题
2. 维护成本
3. 分布式id生成问题
4. 跨库查询问题

分库分表中间件

​	proxy代理层: 

​		mycat, atlas, mysql-proxy

​	jdbc应用层: 

​		shardingsphere, TDDL









1. ### 针对偶尔很慢的情况

   1. #### 数据库在刷新脏页（flush）我也无奈啊

      当我们要往数据库插入一条数据、或者要更新一条数据的时候，我们知道数据库会在**内存**中把对应字段的数据更新了，但是更新之后，这些更新的字段并不会马上同步持久化到**磁盘**中去，而是把这些更新的记录写入到 redo log 日记中去，等到空闲的时候，在通过 redo log 里的日记把最新的数据同步到**磁盘**中去。

      > 当内存数据页跟磁盘数据页内容不一致的时候，我们称这个内存页为“脏页”。内存数据写入到磁盘后，内存和磁盘上的数据页的内容就一致了，称为“干净页”。

      **刷脏页有下面4种场景（后两种不用太关注“性能”问题）：**

      - **redolog写满了：**redo log 里的容量是有限的，如果数据库一直很忙，更新又很频繁，这个时候 redo log 很快就会被写满了，这个时候就没办法等到空闲的时候再把数据同步到磁盘的，只能暂停其他操作，全身心来把数据同步到磁盘中去的，而这个时候，**就会导致我们平时正常的SQL语句突然执行的很慢**，所以说，数据库在在同步数据到磁盘的时候，就有可能导致我们的SQL语句执行的很慢了。
      - **内存不够用了：**如果一次查询较多的数据，恰好碰到所查数据页不在内存中时，需要申请内存，而此时恰好内存不足的时候就需要淘汰一部分内存数据页，如果是干净页，就直接释放，如果恰好是脏页就需要刷脏页。
      - **MySQL 认为系统“空闲”的时候：**这时系统没什么压力。
      - **MySQL 正常关闭的时候：**这时候，MySQL 会把内存的脏页都 flush 到磁盘上，这样下次 MySQL 启动的时候，就可以直接从磁盘上读数据，启动速度会很快。

   2. #### 拿不到锁我能怎么办

      当前数据表或行被加锁了,如果要判断是否真的在等待锁，我们可以用 **show processlist**这个命令来查看

2. ### 一直都这么慢的情况

   1. #### 没用到索引

      1. 字段没有索引,走全表扫描

      2. 有索引, 没用上(索引失效)

         ```sql
         #对SQL 进行函数操作*****
         select * from t1 where date(c) ='2019-05-21';#索引失效
         select * from t1 where c>='2019-05-21 00:00:00' and c<='2019-05-21 23:59:59';
         #条件隐式转换->字符串不加单引号索引失效。*****
         select * from t1 where a=1000;#索引失效
         select * from t1 where a='1000';
         #模糊查询 like*****
         select * from t1 where a like '%1111%';#索引失效
         select * from t1 where a like '1111%';
         #范围查询太大*****
         select * from t1 where b>=1 and b <=2000;#索引失效
         select * from t1 where b>=1 and b <=1000;
         select * from t1 where b>=1001 and b <=2000;
         #对字段计算操作*****
         select * from t1 where b-1 =1000;
         select * from t1 where b =1000 + 1;
         
         #DATE_FORMAT()格式化时间，格式化后的时间再去比较，可能会导致索引失效。*****
         
         #使用 is null或 is not null 也无法使用索引。*****
         
         #全表扫描快于索引扫描（数据量小时）*****
         
         #OR 其中一个条件有索引，另一个没有索引
         select * from order where customer_id = 123 or status = 'Y';
         使用union改进
         select * from order where customer_id = 123
         UNION
         select * from order where status = 'Y';
         
         #反向条件都用不到索引*****
         select * from student where name != 'Jone';
         select * from student where name not like 'jone%';
         select * from student where status not in (1,2,3);
         
         #使用多列索引的非首列作为条件*****
         
         #索引字段作为范围查找的条件时。范围字段之后的索引全部失效。*****
         ```

   2. #### 数据库选错索引

   3. #### 其他特定情况







#### 使用order by 和limit查询变慢解决办法

```sql
显示行 0 - 9 (10 总计, 查询花费 32.4894 秒) 
SQL 查询: SELECT * FROM tables WHERE m_id IN ( 50, 49 ) ORDER BY id DESC LIMIT 10 

显示行 0 - 9 (10 总计, 查询花费 0.0497 秒) 
SQL 查询: SELECT * FROM tables WHERE m_id IN ( 50, 49 ) LIMIT 10 
 
显示行 0 - 29 (1,333 总计, 查询花费 0.0068 秒) 
SQL 查询: SELECT * FROM tables WHERE m_id IN ( 50, 49 ) ORDER BY id DESC 
 
显示行 0 - 29 (1,333 总计, 查询花费 0.12秒) 
SQL 查询: SELECT * FROM tables WHERE m_id IN ( 50, 49 ) ORDER BY m_id, id DESC 
 
显示行 0 - 29 (1,333 总计, 查询花费 0.0068 秒)//强制索引    
SQL 查询: SELECT * FROM tables FORCE INDEX ( m_id ) WHERE m_id IN ( 50, 49 ) ORDER BY id DESC    
```



**优化limit和offset,**MySQL的limit工作原理就是先读取n条记录,然后抛弃前n条,读m条想要的,所以n越大,性能会越差,代码如下:

优化前SQL:SELECT * FROM member ORDER BY last_active LIMIT 50,5 

优化后SQL:SELECT * FROM member INNER JOIN (SELECT member_id FROM member ORDER BY last_active LIMIT 50,5) USING (member_id)

分别在于,优化前的SQL需要更多I/O浪费,因为先读索引,再读数据,然后抛弃无需的行,而优化后的SQL(子查询那条)只读索引(Cover index)就可以了,然后通过member_id读取需要的列.



#### mysql服务器优化,提升性能

**1、只返回需要的数据**

返回数据到客户端至少需要数据库提取数据、网络传输数据、客户端接收数据以及客户端处理数据等环节，如果返回不需要的数据,就会增加服务器、网络和客户端的无效劳动，其害处是显而易见的,避免这类事件需要注意:

- A、横向来看,不要写SELECT *的语句,而是选择你需要的字段.
- B、纵向来看,合理写WHERE子句,不要写没有WHERE的SQL语句.
- C、注意SELECT INTO后的WHERE子句,因为SELECT INTO把数据插入到临时表,这个过程会锁定一些系统表,如果这个WHERE子句返回的数据过多或者速度太慢,会造成系统表长期锁定,诸塞其他进程.
- D、对于聚合查询,可以用HAVING子句进一步限定返回的行.

**2、尽量少做重复的工作**

这一点和上一点的目的是一样的,就是尽量减少无效工作,但是这一点的侧重点在客户端程序,需要注意的如下:

- A、控制同一语句的多次执行，特别是一些基础数据的多次执行是很多程序员很少注意的。

- B、减少多次的数据转换，也许需要数据转换是设计的问题，但是减少次数是程序员可以做到的。

- C、杜绝不必要的子查询和连接表，子查询在执行计划一般解释成外连接，多余的连接表带来额外的开销。

- D、合并对同一表同一条件的多次UPDATE,比如代码如下:

  ```mysql
  UPDATE EMPLOYEE SET FNAME='HAIWER' WHERE EMP_ID='VPA30890F';
  UPDATE EMPLOYEE SET LNAME='YANG' WHERE EMP_ID='VPA30890F'; 
  #这两个语句应该合并成以下一个语句,代码如下:
  UPDATE EMPLOYEE SET FNAME='HAIWER',LNAME='YANG' WHERE EMP_ID='VPA30890F';
  ```

- E、UPDATE操作不要拆成DELETE操作+INSERT操作的形式,虽然功能相同,但是性能差别是很大的.

- F、不要写一些没有意义的查询,比如:SELECT * FROM EMPLOYEE WHERE 1=2

**3、注意事务和锁**

事务是数据库应用中和重要的工具，它有原子性、一致性、隔离性、持久性这四个属性，很多操作我们都需要利用事务来保证数据的正确性。在使用事务中我们需要做到尽量避免死锁、尽量减少阻塞。具体以下方面需要特别注意.

- A、事务操作过程要尽量小，能拆分的事务要拆分开来。
- B、事务操作过程不应该有交互，因为交互等待的时候，事务并未结束，可能锁定了很多资源。
- C、事务操作过程要按同一顺序访问对象。
- D、提高事务中每个语句的效率，利用索引和其他方法提高每个语句的效率可以有效地减少整个事务的执行时间。
- E、尽量不要指定锁类型和索引，SQL SERVER允许我们自己指定语句使用的锁类型和索引，但是一般情况下，SQLSERVER优化器选择的锁类型和索引是在当前数据量和查询条件下是最优的，我们指定的可能只是在目前情况下更有,但是数据量和数据分布在将来是会变化的。
- F、查询时可以用较低的隔离级别，特别是报表查询的时候，可以选择最低的隔离级别



#### 查看sql语句执行时间

> show profile
>
> show Profiles

starting	0.00004
checking permissions	0.000011
Opening tables	0.000013
init	0.000031
System lock	0.000007
optimizing	0.000004
optimizing	0.000003
statistics	0.000009
preparing	0.000008
statistics	0.000004
preparing	0.000004
executing	0.000006
Sending data	0.000006
executing	0.000003
Sending data	0.000521
end	0.000009
query end	0.000006
closing tables	0.000003
removing tmp table	0.000006
closing tables	0.000006
freeing items	0.000079
cleaning up	0.000012

#### [SQL优化案例](https://juejin.cn/post/6895507965899063310)



#### 如何查找MySQL中查询慢的SQL语句

（1） 查看慢SQL日志是否启用

**mysql> show variables like 'log_slow_queries';**

（2） 查看执行慢于多少秒的SQL会记录到日志文件中
**mysql> show variables like 'long_query_time';**

1，slow_query_log

这个参数设置为ON，可以捕获执行时间超过一定数值的SQL语句。

2，long_query_time

当SQL语句执行时间超过此数值时，就会被记录到日志中，建议设置为1或者更短。

3，slow_query_log_file

记录日志的文件名。

4，log_queries_not_using_indexes

这个参数设置为ON，可以捕获到所有未使用索引的SQL语句，尽管这个SQL语句有可能执行得挺快。

**（1）、Windows下开启MySQL慢查询**

MySQL在Windows系统中的配置文件一般是是my.ini找到[mysqld]下面加上

**代码如下**

log-slow-queries = F:/MySQL/log/mysqlslowquery。log
long_query_time = 2


**（2）、Linux下启用MySQL慢查询**

MySQL在Windows系统中的配置文件一般是是my.cnf找到[mysqld]下面加上

**代码如下**

log-slow-queries=/data/mysqldata/slowquery。log
long_query_time=2

**说明**

log-slow-queries = F:/MySQL/log/mysqlslowquery。

为慢查询日志存放的位置，一般这个目录要有MySQL的运行帐号的可写权限，一般都将这个目录设置为MySQL的数据存放目录；
long_query_time=2中的2表示查询超过两秒才记录；











## MYSQL performance schema详解

### performance_schema的介绍

 **MySQL的performance schema 用于监控MySQL server在一个较低级别的运行过程中的资源消耗、资源等待等情况**。

 特点如下：

 1、提供了一种在数据库运行时实时检查server的内部执行情况的方法。performance_schema 数据库中的表使用performance_schema存储引擎。该数据库主要关注数据库运行过程中的性能相关的数据，与information_schema不同，information_schema主要关注server运行过程中的元数据信息

 2、performance_schema通过监视server的事件来实现监视server内部运行情况， “事件”就是server内部活动中所做的任何事情以及对应的时间消耗，利用这些信息来判断server中的相关资源消耗在了哪里？一般来说，事件可以是函数调用、操作系统的等待、SQL语句执行的阶段（如sql语句执行过程中的parsing 或 sorting阶段）或者整个SQL语句与SQL语句集合。事件的采集可以方便的提供server中的相关存储引擎对磁盘文件、表I/O、表锁等资源的同步调用信息。 3、performance_schema中的事件与写入二进制日志中的事件（描述数据修改的events）、事件计划调度程序（这是一种存储程序）的事件不同。performance_schema中的事件记录的是server执行某些活动对某些资源的消耗、耗时、这些活动执行的次数等情况。 4、performance_schema中的事件只记录在本地server的performance_schema中，其下的这些表中数据发生变化时不会被写入binlog中，也不会通过复制机制被复制到其他server中。 5、 当前活跃事件、历史事件和事件摘要相关的表中记录的信息。能提供某个事件的执行次数、使用时长。进而可用于分析某个特定线程、特定对象（如mutex或file）相关联的活动。 6、PERFORMANCE_SCHEMA存储引擎使用server源代码中的“检测点”来实现事件数据的收集。对于performance_schema实现机制本身的代码没有相关的单独线程来检测，这与其他功能（如复制或事件计划程序）不同 7、收集的事件数据存储在performance_schema数据库的表中。这些表可以使用SELECT语句查询，也可以使用SQL语句更新performance_schema数据库中的表记录（如动态修改performance_schema的setup_*开头的几个配置表，但要注意：配置表的更改会立即生效，这会影响数据收集） 8、performance_schema的表中的数据不会持久化存储在磁盘中，而是保存在内存中，一旦服务器重启，这些数据会丢失（包括配置表在内的整个performance_schema下的所有数据） 9、MySQL支持的所有平台中事件监控功能都可用，但不同平台中用于统计事件时间开销的计时器类型可能会有所差异。

### performance schema入门

 在mysql的5.7版本中，性能模式是默认开启的，如果想要显式的关闭的话需要修改配置文件，不能直接进行修改，会报错Variable 'performance_schema' is a read only variable。

```sql
--查看performance_schema的属性
mysql> SHOW VARIABLES LIKE 'performance_schema';
+--------------------+-------+
| Variable_name      | Value |
+--------------------+-------+
| performance_schema | ON    |
+--------------------+-------+
1 row in set (0.01 sec)

--在配置文件中修改performance_schema的属性值，on表示开启，off表示关闭
[mysqld]
performance_schema=ON

--切换数据库
use performance_schema;

--查看当前数据库下的所有表,会看到有很多表存储着相关的信息
show tables;

--可以通过show create table tablename来查看创建表的时候的表结构
mysql> show create table setup_consumers;
+-----------------+---------------------------------
| Table           | Create Table                    
+-----------------+---------------------------------
| setup_consumers | CREATE TABLE `setup_consumers` (
  `NAME` varchar(64) NOT NULL,                      
  `ENABLED` enum('YES','NO') NOT NULL               
) ENGINE=PERFORMANCE_SCHEMA DEFAULT CHARSET=utf8 |  
+-----------------+---------------------------------
1 row in set (0.00 sec)                             
```

 想要搞明白后续的内容，同学们需要理解两个基本概念：

 instruments: 生产者，用于采集mysql中各种各样的操作产生的事件信息，对应配置表中的配置项我们可以称为监控采集配置项。

 consumers:消费者，对应的消费者表用于存储来自instruments采集的数据，对应配置表中的配置项我们可以称为消费存储配置项。

### performance_schema表的分类

 performance_schema库下的表可以按照监视不同的纬度就行分组。

```sql
--语句事件记录表，这些表记录了语句事件信息，当前语句事件表events_statements_current、历史语句事件表events_statements_history和长语句历史事件表events_statements_history_long、以及聚合后的摘要表summary，其中，summary表还可以根据帐号(account)，主机(host)，程序(program)，线程(thread)，用户(user)和全局(global)再进行细分)
show tables like '%statement%';

--等待事件记录表，与语句事件类型的相关记录表类似：
show tables like '%wait%';

--阶段事件记录表，记录语句执行的阶段事件的表
show tables like '%stage%';

--事务事件记录表，记录事务相关的事件的表
show tables like '%transaction%';

--监控文件系统层调用的表
show tables like '%file%';

--监视内存使用的表
show tables like '%memory%';

--动态对performance_schema进行配置的配置表
show tables like '%setup%';
```

### performance_schema的简单配置与使用

 数据库刚刚初始化并启动时，并非所有instruments(事件采集项，在采集项的配置表中每一项都有一个开关字段，或为YES，或为NO)和consumers(与采集项类似，也有一个对应的事件类型保存表配置项，为YES就表示对应的表保存性能数据，为NO就表示对应的表不保存性能数据)都启用了，所以默认不会收集所有的事件，可能你需要检测的事件并没有打开，需要进行设置，可以使用如下两个语句打开对应的instruments和consumers（行计数可能会因MySQL版本而异)。

```sql
--打开等待事件的采集器配置项开关，需要修改setup_instruments配置表中对应的采集器配置项
UPDATE setup_instruments SET ENABLED = 'YES', TIMED = 'YES'where name like 'wait%';

--打开等待事件的保存表配置开关，修改setup_consumers配置表中对应的配置项
UPDATE setup_consumers SET ENABLED = 'YES'where name like '%wait%';

--当配置完成之后可以查看当前server正在做什么，可以通过查询events_waits_current表来得知，该表中每个线程只包含一行数据，用于显示每个线程的最新监视事件
select * from events_waits_current\G
*************************** 1. row ***************************
            THREAD_ID: 11
             EVENT_ID: 570
         END_EVENT_ID: 570
           EVENT_NAME: wait/synch/mutex/innodb/buf_dblwr_mutex
               SOURCE: 
          TIMER_START: 4508505105239280
            TIMER_END: 4508505105270160
           TIMER_WAIT: 30880
                SPINS: NULL
        OBJECT_SCHEMA: NULL
          OBJECT_NAME: NULL
           INDEX_NAME: NULL
          OBJECT_TYPE: NULL
OBJECT_INSTANCE_BEGIN: 67918392
     NESTING_EVENT_ID: NULL
   NESTING_EVENT_TYPE: NULL
            OPERATION: lock
      NUMBER_OF_BYTES: NULL
                FLAGS: NULL
/*该信息表示线程id为11的线程正在等待buf_dblwr_mutex锁，等待事件为30880
属性说明：
	id:事件来自哪个线程，事件编号是多少
	event_name:表示检测到的具体的内容
	source:表示这个检测代码在哪个源文件中以及行号
	timer_start:表示该事件的开始时间
	timer_end:表示该事件的结束时间
	timer_wait:表示该事件总的花费时间
注意：_current表中每个线程只保留一条记录，一旦线程完成工作，该表中不会再记录该线程的事件信息
*/

/*
_history表中记录每个线程应该执行完成的事件信息，但每个线程的事件信息只会记录10条，再多就会被覆盖，*_history_long表中记录所有线程的事件信息，但总记录数量是10000，超过就会被覆盖掉
*/
select thread_id,event_id,event_name,timer_wait from events_waits_history order by thread_id limit 21;

/*
summary表提供所有事件的汇总信息，该组中的表以不同的方式汇总事件数据（如：按用户，按主机，按线程等等）。例如：要查看哪些instruments占用最多的时间，可以通过对events_waits_summary_global_by_event_name表的COUNT_STAR或SUM_TIMER_WAIT列进行查询（这两列是对事件的记录数执行COUNT（*）、事件记录的TIMER_WAIT列执行SUM（TIMER_WAIT）统计而来）
*/
SELECT EVENT_NAME,COUNT_STAR FROM events_waits_summary_global_by_event_name  ORDER BY COUNT_STAR DESC LIMIT 10;

/*
instance表记录了哪些类型的对象会被检测。这些对象在被server使用时，在该表中将会产生一条事件记录，例如，file_instances表列出了文件I/O操作及其关联文件名
*/
select * from file_instances limit 20; 
```

### 用配置项的参数说明

1、启动选项

```sql
performance_schema_consumer_events_statements_current=TRUE
是否在mysql server启动时就开启events_statements_current表的记录功能(该表记录当前的语句事件信息)，启动之后也可以在setup_consumers表中使用UPDATE语句进行动态更新setup_consumers配置表中的events_statements_current配置项，默认值为TRUE

performance_schema_consumer_events_statements_history=TRUE
与performance_schema_consumer_events_statements_current选项类似，但该选项是用于配置是否记录语句事件短历史信息，默认为TRUE

performance_schema_consumer_events_stages_history_long=FALSE
与performance_schema_consumer_events_statements_current选项类似，但该选项是用于配置是否记录语句事件长历史信息，默认为FALSE

除了statement(语句)事件之外，还支持：wait(等待)事件、state(阶段)事件、transaction(事务)事件，他们与statement事件一样都有三个启动项分别进行配置，但这些等待事件默认未启用，如果需要在MySQL Server启动时一同启动，则通常需要写进my.cnf配置文件中
performance_schema_consumer_global_instrumentation=TRUE
是否在MySQL Server启动时就开启全局表（如：mutex_instances、rwlock_instances、cond_instances、file_instances、users、hostsaccounts、socket_summary_by_event_name、file_summary_by_instance等大部分的全局对象计数统计和事件汇总统计信息表 ）的记录功能，启动之后也可以在setup_consumers表中使用UPDATE语句进行动态更新全局配置项
默认值为TRUE

performance_schema_consumer_statements_digest=TRUE
是否在MySQL Server启动时就开启events_statements_summary_by_digest 表的记录功能，启动之后也可以在setup_consumers表中使用UPDATE语句进行动态更新digest配置项
默认值为TRUE

performance_schema_consumer_thread_instrumentation=TRUE
是否在MySQL Server启动时就开启

events_xxx_summary_by_yyy_by_event_name表的记录功能，启动之后也可以在setup_consumers表中使用UPDATE语句进行动态更新线程配置项
默认值为TRUE

performance_schema_instrument[=name]
是否在MySQL Server启动时就启用某些采集器，由于instruments配置项多达数千个，所以该配置项支持key-value模式，还支持%号进行通配等，如下:

# [=name]可以指定为具体的Instruments名称（但是这样如果有多个需要指定的时候，就需要使用该选项多次），也可以使用通配符，可以指定instruments相同的前缀+通配符，也可以使用%代表所有的instruments

## 指定开启单个instruments

--performance-schema-instrument= 'instrument_name=value'

## 使用通配符指定开启多个instruments

--performance-schema-instrument= 'wait/synch/cond/%=COUNTED'

## 开关所有的instruments

--performance-schema-instrument= '%=ON'

--performance-schema-instrument= '%=OFF'

注意，这些启动选项要生效的前提是，需要设置performance_schema=ON。另外，这些启动选项虽然无法使用show variables语句查看，但我们可以通过setup_instruments和setup_consumers表查询这些选项指定的值。
```

2、系统变量

```sql
show variables like '%performance_schema%';
--重要的属性解释
performance_schema=ON
/*
控制performance_schema功能的开关，要使用MySQL的performance_schema，需要在mysqld启动时启用，以启用事件收集功能
该参数在5.7.x之前支持performance_schema的版本中默认关闭，5.7.x版本开始默认开启
注意：如果mysqld在初始化performance_schema时发现无法分配任何相关的内部缓冲区，则performance_schema将自动禁用，并将performance_schema设置为OFF
*/

performance_schema_digests_size=10000
/*
控制events_statements_summary_by_digest表中的最大行数。如果产生的语句摘要信息超过此最大值，便无法继续存入该表，此时performance_schema会增加状态变量
*/
performance_schema_events_statements_history_long_size=10000
/*
控制events_statements_history_long表中的最大行数，该参数控制所有会话在events_statements_history_long表中能够存放的总事件记录数，超过这个限制之后，最早的记录将被覆盖
全局变量，只读变量，整型值，5.6.3版本引入 * 5.6.x版本中，5.6.5及其之前的版本默认为10000，5.6.6及其之后的版本默认值为-1，通常情况下，自动计算的值都是10000 * 5.7.x版本中，默认值为-1，通常情况下，自动计算的值都是10000
*/
performance_schema_events_statements_history_size=10
/*
控制events_statements_history表中单个线程（会话）的最大行数，该参数控制单个会话在events_statements_history表中能够存放的事件记录数，超过这个限制之后，单个会话最早的记录将被覆盖
全局变量，只读变量，整型值，5.6.3版本引入 * 5.6.x版本中，5.6.5及其之前的版本默认为10，5.6.6及其之后的版本默认值为-1，通常情况下，自动计算的值都是10 * 5.7.x版本中，默认值为-1，通常情况下，自动计算的值都是10
除了statement(语句)事件之外，wait(等待)事件、state(阶段)事件、transaction(事务)事件，他们与statement事件一样都有三个参数分别进行存储限制配置，有兴趣的同学自行研究，这里不再赘述
*/
performance_schema_max_digest_length=1024
/*
用于控制标准化形式的SQL语句文本在存入performance_schema时的限制长度，该变量与max_digest_length变量相关(max_digest_length变量含义请自行查阅相关资料)
全局变量，只读变量，默认值1024字节，整型值，取值范围0~1048576
*/
performance_schema_max_sql_text_length=1024
/*
控制存入events_statements_current，events_statements_history和events_statements_history_long语句事件表中的SQL_TEXT列的最大SQL长度字节数。 超出系统变量performance_schema_max_sql_text_length的部分将被丢弃，不会记录，一般情况下不需要调整该参数，除非被截断的部分与其他SQL比起来有很大差异
全局变量，只读变量，整型值，默认值为1024字节，取值范围为0~1048576，5.7.6版本引入
降低系统变量performance_schema_max_sql_text_length值可以减少内存使用，但如果汇总的SQL中，被截断部分有较大差异，会导致没有办法再对这些有较大差异的SQL进行区分。 增加该系统变量值会增加内存使用，但对于汇总SQL来讲可以更精准地区分不同的部分。
*/
```

### 要配置表的相关说明

 配置表之间存在相互关联关系，按照配置影响的先后顺序，可添加为

[![image-20191203125003597]()](https://github.com/bjmashibing/InternetArchitect/blob/master/13mysql调优/MYSQL performance schema详解.md)

```sql
/*
performance_timers表中记录了server中有哪些可用的事件计时器
字段解释：
	timer_name:表示可用计时器名称，CYCLE是基于CPU周期计数器的定时器
	timer_frequency:表示每秒钟对应的计时器单位的数量,CYCLE计时器的换算值与CPU的频率相关、
	timer_resolution:计时器精度值，表示在每个计时器被调用时额外增加的值
	timer_overhead:表示在使用定时器获取事件时开销的最小周期值
*/
select * from performance_timers;

/*
setup_timers表中记录当前使用的事件计时器信息
字段解释：
	name:计时器类型，对应某个事件类别
	timer_name:计时器类型名称
*/
select * from setup_timers;

/*
setup_consumers表中列出了consumers可配置列表项
字段解释：
	NAME：consumers配置名称
	ENABLED：consumers是否启用，有效值为YES或NO，此列可以使用UPDATE语句修改。
*/
select * from setup_consumers;

/*
setup_instruments 表列出了instruments 列表配置项，即代表了哪些事件支持被收集：
字段解释：
	NAME：instruments名称，instruments名称可能具有多个部分并形成层次结构
	ENABLED：instrumetns是否启用，有效值为YES或NO，此列可以使用UPDATE语句修改。如果设置为NO，则这个instruments不会被执行，不会产生任何的事件信息
	TIMED：instruments是否收集时间信息，有效值为YES或NO，此列可以使用UPDATE语句修改，如果设置为NO，则这个instruments不会收集时间信息
*/
SELECT * FROM setup_instruments;

/*
setup_actors表的初始内容是匹配任何用户和主机，因此对于所有前台线程，默认情况下启用监视和历史事件收集功能
字段解释：
	HOST：与grant语句类似的主机名，一个具体的字符串名字，或使用“％”表示“任何主机”
	USER：一个具体的字符串名称，或使用“％”表示“任何用户”
	ROLE：当前未使用，MySQL 8.0中才启用角色功能
	ENABLED：是否启用与HOST，USER，ROLE匹配的前台线程的监控功能，有效值为：YES或NO
	HISTORY：是否启用与HOST， USER，ROLE匹配的前台线程的历史事件记录功能，有效值为：YES或NO
*/
SELECT * FROM setup_actors;

/*
setup_objects表控制performance_schema是否监视特定对象。默认情况下，此表的最大行数为100行。
字段解释：
	OBJECT_TYPE：instruments类型，有效值为：“EVENT”（事件调度器事件）、“FUNCTION”（存储函数）、“PROCEDURE”（存储过程）、“TABLE”（基表）、“TRIGGER”（触发器），TABLE对象类型的配置会影响表I/O事件（wait/io/table/sql/handler instrument）和表锁事件（wait/lock/table/sql/handler instrument）的收集
	OBJECT_SCHEMA：某个监视类型对象涵盖的数据库名称，一个字符串名称，或“％”(表示“任何数据库”)
	OBJECT_NAME：某个监视类型对象涵盖的表名，一个字符串名称，或“％”(表示“任何数据库内的对象”)
	ENABLED：是否开启对某个类型对象的监视功能，有效值为：YES或NO。此列可以修改
	TIMED：是否开启对某个类型对象的时间收集功能，有效值为：YES或NO，此列可以修改
*/
SELECT * FROM setup_objects;

/*
threads表对于每个server线程生成一行包含线程相关的信息，
字段解释：
	THREAD_ID：线程的唯一标识符（ID）
	NAME：与server中的线程检测代码相关联的名称(注意，这里不是instruments名称)
	TYPE：线程类型，有效值为：FOREGROUND、BACKGROUND。分别表示前台线程和后台线程
	PROCESSLIST_ID：对应INFORMATION_SCHEMA.PROCESSLIST表中的ID列。
	PROCESSLIST_USER：与前台线程相关联的用户名，对于后台线程为NULL。
	PROCESSLIST_HOST：与前台线程关联的客户端的主机名，对于后台线程为NULL。
	PROCESSLIST_DB：线程的默认数据库，如果没有，则为NULL。
	PROCESSLIST_COMMAND：对于前台线程，该值代表着当前客户端正在执行的command类型，如果是sleep则表示当前会话处于空闲状态
	PROCESSLIST_TIME：当前线程已处于当前线程状态的持续时间（秒）
	PROCESSLIST_STATE：表示线程正在做什么事情。
	PROCESSLIST_INFO：线程正在执行的语句，如果没有执行任何语句，则为NULL。
	PARENT_THREAD_ID：如果这个线程是一个子线程（由另一个线程生成），那么该字段显示其父线程ID
	ROLE：暂未使用
	INSTRUMENTED：线程执行的事件是否被检测。有效值：YES、NO 
	HISTORY：是否记录线程的历史事件。有效值：YES、NO * 
	THREAD_OS_ID：由操作系统层定义的线程或任务标识符（ID）：
*/
select * from threads
```

注意：在performance_schema库中还包含了很多其他的库和表，能对数据库的性能做完整的监控，大家需要参考官网详细了解。

### performance_schema实践操作

 基本了解了表的相关信息之后，可以通过这些表进行实际的查询操作来进行实际的分析。

```sql
--1、哪类的SQL执行最多？
SELECT DIGEST_TEXT,COUNT_STAR,FIRST_SEEN,LAST_SEEN FROM events_statements_summary_by_digest ORDER BY COUNT_STAR DESC
--2、哪类SQL的平均响应时间最多？
SELECT DIGEST_TEXT,AVG_TIMER_WAIT FROM events_statements_summary_by_digest ORDER BY COUNT_STAR DESC
--3、哪类SQL排序记录数最多？
SELECT DIGEST_TEXT,SUM_SORT_ROWS FROM events_statements_summary_by_digest ORDER BY COUNT_STAR DESC
--4、哪类SQL扫描记录数最多？
SELECT DIGEST_TEXT,SUM_ROWS_EXAMINED FROM events_statements_summary_by_digest ORDER BY COUNT_STAR DESC
--5、哪类SQL使用临时表最多？
SELECT DIGEST_TEXT,SUM_CREATED_TMP_TABLES,SUM_CREATED_TMP_DISK_TABLES FROM events_statements_summary_by_digest ORDER BY COUNT_STAR DESC
--6、哪类SQL返回结果集最多？
SELECT DIGEST_TEXT,SUM_ROWS_SENT FROM events_statements_summary_by_digest ORDER BY COUNT_STAR DESC
--7、哪个表物理IO最多？
SELECT file_name,event_name,SUM_NUMBER_OF_BYTES_READ,SUM_NUMBER_OF_BYTES_WRITE FROM file_summary_by_instance ORDER BY SUM_NUMBER_OF_BYTES_READ + SUM_NUMBER_OF_BYTES_WRITE DESC
--8、哪个表逻辑IO最多？
SELECT object_name,COUNT_READ,COUNT_WRITE,COUNT_FETCH,SUM_TIMER_WAIT FROM table_io_waits_summary_by_table ORDER BY sum_timer_wait DESC
--9、哪个索引访问最多？
SELECT OBJECT_NAME,INDEX_NAME,COUNT_FETCH,COUNT_INSERT,COUNT_UPDATE,COUNT_DELETE FROM table_io_waits_summary_by_index_usage ORDER BY SUM_TIMER_WAIT DESC
--10、哪个索引从来没有用过？
SELECT OBJECT_SCHEMA,OBJECT_NAME,INDEX_NAME FROM table_io_waits_summary_by_index_usage WHERE INDEX_NAME IS NOT NULL AND COUNT_STAR = 0 AND OBJECT_SCHEMA <> 'mysql' ORDER BY OBJECT_SCHEMA,OBJECT_NAME;
--11、哪个等待事件消耗时间最多？
SELECT EVENT_NAME,COUNT_STAR,SUM_TIMER_WAIT,AVG_TIMER_WAIT FROM events_waits_summary_global_by_event_name WHERE event_name != 'idle' ORDER BY SUM_TIMER_WAIT DESC
--12-1、剖析某条SQL的执行情况，包括statement信息，stege信息，wait信息
SELECT EVENT_ID,sql_text FROM events_statements_history WHERE sql_text LIKE '%count(*)%';
--12-2、查看每个阶段的时间消耗
SELECT event_id,EVENT_NAME,SOURCE,TIMER_END - TIMER_START FROM events_stages_history_long WHERE NESTING_EVENT_ID = 1553;
--12-3、查看每个阶段的锁等待情况
SELECT even
```

## explain执行计划

 在企业的应用场景中，为了知道优化SQL语句的执行，需要查看SQL语句的具体执行过程，以加快SQL语句的执行效率。

 可以使用explain+SQL语句来模拟优化器执行SQL查询语句，从而知道mysql是如何处理sql语句的。

 官网地址： https://dev.mysql.com/doc/refman/5.5/en/explain-output.html

### 1、执行计划中包含的信息

| Column        | Meaning                                                      |
| ------------- | ------------------------------------------------------------ |
| id            | The `SELECT` identifier/表示查询中执行select子句或者操作表的顺序 |
| select_type   | The `SELECT` type/分辨查询的类型，是普通查询还是联合查询还是子查询 |
| table         | The table for the output row对应行正在访问哪一个表           |
| partitions    | The matching partitions                                      |
| type          | The join type以何种方式去查找数据(全表, const)               |
| possible_keys | The possible indexes to choose可能应用在这张表中的索引       |
| key           | The index actually chosen实际使用的索引                      |
| key_len       | The length of the chosen key                                 |
| ref           | The columns compared to the index索引的哪一列被使用了, 一或多列 |
| rows          | Estimate of rows to be examined根据表的统计信息及索引使用情况，大致估算出找出所需记录需要读取的行数，此参数很重要 |
| filtered      | Percentage of rows filtered by table condition               |
| extra         | Additional information                                       |

**id**

select查询的序列号，包含一组数字，表示查询中执行select子句或者操作表的顺序

id号分为三种情况： 

 1、如果id相同，那么执行顺序从上到下

```sql
explain select * from emp e join dept d on e.deptno = d.deptno join salgrade sg on e.sal between sg.losal and sg.hisal;
```

 2、如果id不同，如果是子查询，id的序号会递增，id值越大优先级越高，越先被执行

```sql
explain select * from emp e where e.deptno in (select d.deptno from dept d where d.dname = 'SALES');
```

 3、id相同和不同的，同时存在：相同的可以认为是一组，从上往下顺序执行，在所有组中，id值越大，优先级越高，越先执行

```sql
explain select * from emp e join dept d on e.deptno = d.deptno join salgrade sg on e.sal between sg.losal and sg.hisal where e.deptno in (select d.deptno from dept d where d.dname = 'SALES');
```

**select_type**

主要用来分辨查询的类型，是普通查询还是联合查询还是子查询

| `select_type` Value  | Meaning                                                      |
| -------------------- | ------------------------------------------------------------ |
| SIMPLE               | Simple SELECT (not using UNION or subqueries)                |
| PRIMARY              | Outermost SELECT                                             |
| UNION                | Second or later SELECT statement in a UNION                  |
| DEPENDENT UNION      | Second or later SELECT statement in a UNION, dependent on outer query |
| UNION RESULT         | Result of a UNION.                                           |
| SUBQUERY             | First SELECT in subquery                                     |
| DEPENDENT SUBQUERY   | First SELECT in subquery, dependent on outer query           |
| DERIVED              | Derived table                                                |
| UNCACHEABLE SUBQUERY | A subquery for which the result cannot be cached and must be re-evaluated for each row of the outer query |
| UNCACHEABLE UNION    | The second or later select in a UNION that belongs to an uncacheable subquery (see UNCACHEABLE SUBQUERY) |

```sql
--sample:简单的查询，不包含子查询和union
explain select * from emp;

--primary:查询中若包含任何复杂的子查询，最外层查询则被标记为Primary
explain select staname,ename supname from (select ename staname,mgr from emp) t join emp on t.mgr=emp.empno ;

--union:若第二个select出现在union之后，则被标记为union
explain select * from emp where deptno = 10 union select * from emp where sal >2000;

--dependent union:跟union类似，此处的depentent表示union或union all联合而成的结果会受外部表影响
explain select * from emp e where e.empno  in ( select empno from emp where deptno = 10 union select empno from emp where sal >2000)

--union result:从union表获取结果的select
explain select * from emp where deptno = 10 union select * from emp where sal >2000;

--subquery:在select或者where列表中包含子查询
explain select * from emp where sal > (select avg(sal) from emp) ;

--dependent subquery:subquery的子查询要受到外部表查询的影响
explain select * from emp e where e.deptno in (select distinct deptno from dept);

--DERIVED: from子句中出现的子查询，也叫做派生类，
explain select staname,ename supname from (select ename staname,mgr from emp) t join emp on t.mgr=emp.empno ;

--UNCACHEABLE SUBQUERY：表示使用子查询的结果不能被缓存
 explain select * from emp where empno = (select empno from emp where deptno=@@sort_buffer_size);
 
--uncacheable union:表示union的查询结果不能被缓存：sql语句未验证
```

**table**

对应行正在访问哪一个表，表名或者别名，可能是临时表或者union合并结果集 

1、如果是具体的表名，则表明从实际的物理表中获取数据，当然也可以是表的别名

 2、表名是derivedN的形式，表示使用了id为N的查询产生的衍生表

 3、当有union result的时候，表名是union n1,n2等的形式，n1,n2表示参与union的id

**type**

type显示的是访问类型，访问类型表示我是以何种方式去访问我们的数据，最容易想的是全表扫描，直接暴力的遍历一张表去寻找需要的数据，效率非常低下，访问的类型有很多，效率从最好到最坏依次是：

system > const > eq_ref > ref > fulltext > ref_or_null > index_merge > unique_subquery > index_subquery > range > index > ALL

一般情况下，得保证查询至少达到range级别，最好能达到ref

```sql
--all:全表扫描，一般情况下出现这样的sql语句而且数据量比较大的话那么就需要进行优化。
explain select * from emp;

--index：全索引扫描这个比all的效率要好，主要有两种情况，一种是当前的查询时覆盖索引，即我们需要的数据在索引中就可以索取，或者是使用了索引进行排序，这样就避免数据的重排序
explain  select empno from emp;

--range：表示利用索引查询的时候限制了范围，在指定范围内进行查询，这样避免了index的全索引扫描，适用的操作符： =, <>, >, >=, <, <=, IS NULL, BETWEEN, LIKE, or IN() 
explain select * from emp where empno between 7000 and 7500;

--index_subquery：利用索引来关联子查询，不再扫描全表
explain select * from emp where emp.job in (select job from t_job);

--unique_subquery:该连接类型类似与index_subquery,使用的是唯一索引
 explain select * from emp e where e.deptno in (select distinct deptno from dept);
 
--index_merge：在查询过程中需要多个索引组合使用，没有模拟出来

--ref_or_null：对于某个字段即需要关联条件，也需要null值的情况下，查询优化器会选择这种访问方式
explain select * from emp e where  e.mgr is null or e.mgr=7369;

--ref：使用了非唯一性索引进行数据的查找
 create index idx_3 on emp(deptno);
 explain select * from emp e,dept d where e.deptno =d.deptno;

--eq_ref ：使用唯一性索引进行数据查找
explain select * from emp,emp2 where emp.empno = emp2.empno;

--const：这个表至多有一个匹配行，
explain select * from emp where empno = 7369;
 
--system：表只有一行记录（等于系统表），这是const类型的特例，平时不会出现
```

**possible_keys**

 显示可能应用在这张表中的索引，一个或多个，查询涉及到的字段上若存在索引，则该索引将被列出，但不一定被查询实际使用

```sql
explain select * from emp,dept where emp.deptno = dept.deptno and emp.deptno = 10;
```

**key**

 实际使用的索引，如果为null，则没有使用索引，查询中若使用了覆盖索引，则该索引和查询的select字段重叠。

```sql
explain select * from emp,dept where emp.deptno = dept.deptno and emp.deptno = 10;
```

**key_len**

表示索引中使用的字节数，可以通过key_len计算查询中使用的索引长度，在不损失精度的情况下长度越短越好。

```sql
explain select * from emp,dept where emp.deptno = dept.deptno and emp.deptno = 10;
```

**ref**

显示索引的哪一列被使用了，如果可能的话，是一个常数

```
explain select * from emp,dept where emp.deptno = dept.deptno and emp.deptno = 10;
```

**rows**

根据表的统计信息及索引使用情况，大致估算出找出所需记录需要读取的行数，此参数很重要，直接反应的sql找了多少数据，在完成目的的情况下越少越好

```sql
explain select * from emp;
```

**extra**

包含额外的信息。

```sql
--using filesort:说明mysql无法利用索引进行排序，只能利用排序算法进行排序，会消耗额外的位置
explain select * from emp order by sal;

--using temporary:建立临时表来保存中间结果，查询完成之后把临时表删除
explain select ename,count(*) from emp where deptno = 10 group by ename;

--using index:这个表示当前的查询时覆盖索引的，直接从索引中读取数据，而不用访问数据表。如果同时出现using where 表名索引被用来执行索引键值的查找，如果没有，表面索引被用来读取数据，而不是真的查找
explain select deptno,count(*) from emp group by deptno limit 10;

--using where:使用where进行条件过滤
explain select * from t_user where id = 1;

--using join buffer:使用连接缓存，情况没有模拟出来

--impossible where：where语句的结果总是false
explain select * from emp where empno = 7469;
```



## 数据类型优化

1. 整型能用更小范围的用小字节的TINYINT, SMALLINT, MEDIUMINT, int, BIGINT
2. 时间类型用datetime, timestamp, date
3. 定长用char, 变长用varchar,
4. 能用整型存的不要用字符串,比如IP





## 查询优化

### 避免回表查询

> 覆盖索引避免了回表现象的产生，从而减少树的搜索次数，显著提升查询性能

回表流程为：

1. 在name索引树上找到名称为小李的节点 id为03
2. 从id索引树上找到id为03的节点 获取所有数据
3. 从数据中获取字段命为age的值返回 12

**在流程中从非主键索引树搜索回到主键索引树搜索的过程称为：回表**，在本次查询中因为查询结果只存在主键索引树中，我们必须回表才能查询到结果

覆盖索引（covering index ，或称为索引覆盖）即从非主键索引中就能查到的记录，而不需要查询主键索引中的记录，避免了回表的产生减少了树的搜索次数，显著提升性能。

**将需要查询的数据, 联合索引起来, 使普通索引树存需要的数据, 就不需要回表操作了**

```sql
ALTER TABLE student DROP INDEX I_name;--去掉name普通索引
ALTER TABLE student ADD INDEX I_name_age(name, age);--以name和age两个字段建立联合索引
SELECT age FROM student WHERE name = '小李'；
```









## 一些概念

- 回表

> 根据索引查出记录id之后, 在根据id查询其他字段信息

- 索引覆盖

> 查询一条记录时, 直接可以从索引中找到需要查询的字段, 不需要回表

- 最左匹配

> MySQL中的索引可以以一定顺序引用多列，这种索引叫作联合索引。如User表的name和city加联合索引就是(name,city)，而最左前缀原则指的是，如果查询的时候查询条件精确匹配索引的左边连续一列或几列，则此列就可以被用到。

- 索引下推

> 

- 聚簇索引

  **聚集索引即索引结构和数据一起存放的索引。主键索引属于聚集索引。**

  在 Mysql 中，InnoDB引擎的表的 `.ibd`文件就包含了该表的索引和数据，对于 InnoDB 引擎表来说，该表的索引(B+树)的每个非叶子节点存储索引，叶子节点存储索引和索引对应的数据。

  优点

  聚集索引的查询速度非常的快，因为整个B+树本身就是一颗多叉平衡树，叶子节点也都是有序的，定位到索引的节点，就相当于定位到了数据。

  缺点

1. **依赖于有序的数据** ：因为B+树是多路平衡树，如果索引的数据不是有序的，那么就需要在插入时排序，如果数据是整型还好，否则类似于字符串或UUID这种又长又难比较的数据，插入或查找的速度肯定比较慢。

2. **更新代价大** ： 如果对索引列的数据被修改时，那么对应的索引也将会被修改， 而且况聚集索引的叶子节点还存放着数据，修改代价肯定是较大的， 所以对于主键索引来说，主键一般都是不可被修改的。

   > 注：聚簇索引不需要我们显示的创建，他是由InnoDB存储引擎自动为我们创建的。如果没有主键，其也会默认创建一个。

- 非聚簇索引

>  普通索引建立的B+树, 节点内部(链表)使用索引字段排序, 叶子节点不再是完整的数据记录，而是索引字段和主键值
>
> ```
> 如果我搜索条件是基于name，需要查询所有字段的信息，那查询过程是啥？
> ```
>
> 1.根据查询条件，采用name的非聚簇索引，先定位到该非聚簇索引某些记录行。
>
> 2.根据记录行找到相应的id，再根据id到聚簇索引中找到相关记录。这个过程叫做`回表`。



### 一些原则

1. 最左前缀匹配原则。这是非常重要、非常重要、非常重要（重要的事情说三遍）的原则，MySQL会一直向右匹配直到遇到范围查询（>,<,BETWEEN,LIKE）就停止匹配。

2. 使用唯一索引。具有多个重复值的列，其索引效果最差。例如，存放姓名的列具有不同值，很容易区分每行。而用来记录性别的列，只含有“男”，“女”，不管搜索哪个值，都会得出大约一半的行，这样的索引对性能的提升不够高。
3. 尽量选择**区分度高的列作为索引**，区分度的公式是 `COUNT(DISTINCT col) / COUNT(*)`。表示字段不重复的比率，比率越大我们扫描的记录数就越少。

3. 不要过度索引。每个额外的索引都要占用额外的磁盘空间，并降低写操作的性能。在修改表的内容时，索引必须进行更新，有时可能需要重构，因此，索引越多，所花的时间越长。

4. 索引列不能参与计算，保持列“干净”，比如from_unixtime(create_time) = ’2014-05-29’就不能使用到索引，原因很简单，b+树中存的都是数据表中的字段值，但进行检索时，需要把所有元素都应用函数才能比较，显然成本太大。所以语句应该写成create_time = unix_timestamp(’2014-05-29’);

5. 一定要设置一个主键。前面聚簇索引说到如果不指定主键，InnoDB会自动为其指定主键，这个我们是看不见的。反正都要生成一个主键的，还不如我们设置，以后在某些搜索条件时还能用到主键的聚簇索引。

6. 主键推荐用自增id，而不是uuid。上面的聚簇索引说到每页数据都是排序的，并且页之间也是排序的，如果是uuid，那么其肯定是随机的，其可能从中间插入，导致页的分裂，产生很多表碎片。如果是自增的，那么其有从小到大自增的，有顺序，那么在插入的时候就添加到当前索引的后续位置。当一页写满，就会自动开辟一个新的页。

7. 





# 一条SQL语句过来的流程是什么样的？

1.当客户端连接到MySQL服务器时，服务器对其进行认证。可以通过用户名与密码认证，也可以通过SSL证书进行认证。登录认证后，服务器还会验证客户端是否有执行某个查询的操作权限。

2.在正式查询之前，服务器会检查查询缓存，如果能找到对应的查询，则不必进行查询解析，优化，执行等过程，直接返回缓存中的结果集。

3.MySQL的解析器会根据查询语句，构造出一个解析树，主要用于根据语法规则来验证语句是否正确，比如SQL的关键字是否正确，关键字的顺序是否正确。

而预处理器主要是进一步校验，比如表名，字段名是否正确等

4.查询优化器将解析树转化为查询计划，一般情况下，一条查询可以有很多种执行方式，最终返回相同的结果，优化器就是根据`成本`找到这其中最优的执行计划

5.执行计划调用查询执行引擎，而查询引擎通过一系列API接口查询到数据

6.得到数据之后，在返回给客户端的同时，会将数据存在查询缓存中

![img](https://user-gold-cdn.xitu.io/2019/6/17/16b631a00ea229f1?imageView2/0/w/1280/h/960/format/webp/ignore-error/1)

## 查询缓存

MYSQL的查询缓存实质上是缓存SQL的hash值和该SQL的查询结果，如果运行相同的SQL,服务器直接从缓存中去掉结果，而不再去解析，优化，寻找最低成本的执行计划等一系列操作，大大提升了查询速度。

- 第一个弊端就是如果表的数据有一条发生变化，那么缓存好的结果将全部不再有效。这对于频繁更新的表，查询缓存是不适合的。

```
所以MySQL对于数据有变化的表来说，会直接清空关于该表的所有缓存。这样其实是效率是很差的。
```

- 第二个弊端就是缓存机制是通过对SQL的hash，得出的值为key，查询结果为value来存放的，那么就意味着SQL必须完完全全一模一样，否则就命不中缓存。

```
我们都知道hash值的规则，就算很小的变化，哈希出来的结果差距是很多的,大小写和空格影响了他们，所以并不能命中缓存，但其实他们搜索结果是完全一样的。
```


网上资料和各大云厂商提供的云服务器都是将这个功能关闭的，从上面的原理来看，在一般情况下，`他的弊端大于优点`



### 万年面试题（为什么索引用B+树）

1、 B+树的磁盘读写代价更低：B+树的内部节点并没有指向关键字具体信息的指针，因此其内部节点相对B树更小，如果把所有同一内部节点的关键字存放在同一盘块中，那么盘块所能容纳的关键字数量也越多，一次性读入内存的需要查找的关键字也就越多，相对`IO读写次数就降低`了。

2、由于B+树的数据都存储在叶子结点中，分支结点均为索引，方便扫库，只需要扫一遍叶子结点即可，但是B树因为其分支结点同样存储着数据，我们要找到具体的数据，需要进行一次中序遍历按序来扫，所以B+树更加适合在`区间查询`的情况，所以通常B+树用于数据库索引。



## log

https://juejin.cn/post/6860252224930070536

## binlog

`binlog`用于记录数据库执行的写入性操作(不包括查询)信息，以二进制的形式保存在磁盘中。`binlog`是`mysql`的逻辑日志，并且由`Server`层进行记录，使用任何存储引擎的`mysql`数据库都会记录`binlog`日志。

> 逻辑日志：**可以简单理解为记录的就是sql语句**。

> 物理日志：**因为`mysql`数据最终是保存在数据页中的，物理日志记录的就是数据页变更**。

`binlog`是通过追加的方式进行写入的，可以通过`max_binlog_size`参数设置每个`binlog`文件的大小，当文件大小达到给定值之后，会生成新的文件来保存日志。

### binlog使用场景

在实际应用中，`binlog`的主要使用场景有两个，分别是**主从复制**和**数据恢复**。

1. **主从复制**：在`Master`端开启`binlog`，然后将`binlog`发送到各个`Slave`端，`Slave`端重放`binlog`从而达到主从数据一致。
2. **数据恢复**：通过使用`mysqlbinlog`工具来恢复数据。

### binlog刷盘时机

对于`InnoDB`存储引擎而言，只有在事务提交时才会记录`biglog`，此时记录还在内存中，那么`biglog`是什么时候刷到磁盘中的呢？`mysql`通过`sync_binlog`参数控制`biglog`的刷盘时机，取值范围是`0-N`：

- 0：不去强制要求，由系统自行判断何时写入磁盘；
- 1：每次`commit`的时候都要将`binlog`写入磁盘；
- N：每N个事务，才会将`binlog`写入磁盘。

从上面可以看出，`sync_binlog`最安全的是设置是`1`，这也是`MySQL 5.7.7`之后版本的默认值。但是设置一个大一些的值可以提升数据库性能，因此实际情况下也可以将值适当调大，牺牲一定的一致性来获取更好的性能。

> `binlog`日志有三种格式，分别为`STATMENT`、`ROW`和`MIXED`。
>
> 在 `MySQL 5.7.7`之前，默认的格式是`STATEMENT`，`MySQL 5.7.7`之后，默认值是`ROW`。日志格式通过`binlog-format`指定。



## redo日志（物理日志）

### 为什么需要redo log

我们都知道，事务的四大特性里面有一个是**持久性**，具体来说就是**只要事务提交成功，那么对数据库做的修改就被永久保存下来了，不可能因为任何原因再回到原来的状态**。那么`mysql`是如何保证持久性的呢？最简单的做法是在每次事务提交的时候，将该事务涉及修改的数据页全部刷新到磁盘中。但是这么做会有严重的性能问题，主要体现在两个方面：

1. 因为`Innodb`是以`页`为单位进行磁盘交互的，而一个事务很可能只修改一个数据页里面的几个字节，这个时候将完整的数据页刷到磁盘的话，太浪费资源了！
2. 一个事务可能涉及修改多个数据页，并且这些数据页在物理上并不连续，使用随机IO写入性能太差！

因此`mysql`设计了`redo log`，**具体来说就是只记录事务对数据页做了哪些修改**，这样就能完美地解决性能问题了(相对而言文件更小并且是顺序IO)。

### redo log基本概念

`redo log`包括两部分：一个是内存中的日志缓冲(`redo log buffer`)，另一个是磁盘上的日志文件(`redo log file`)。`mysql`每执行一条`DML`语句，先将记录写入`redo log buffer`，后续某个时间点再一次性将多个操作记录写到`redo log file`。这种**先写日志，再写磁盘**的技术就是`MySQL`里经常说到的`WAL(Write-Ahead Logging)` 技术。

在计算机操作系统中，用户空间(`user space`)下的缓冲区数据一般情况下是无法直接写入磁盘的，中间必须经过操作系统内核空间(`kernel space`)缓冲区(`OS Buffer`)。因此，`redo log buffer`写入`redo log file`实际上是先写入`OS Buffer`，然后再通过系统调用`fsync()`将其刷到`redo log file`中，过程如下： ![img](https://p3-juejin.byteimg.com/tos-cn-i-k3u1fbpfcp/dbc4395ebadf4dbd87e1c64a6bdb68e0~tplv-k3u1fbpfcp-zoom-1.image)

`mysql`支持三种将`redo log buffer`写入`redo log file`的时机，可以通过`innodb_flush_log_at_trx_commit`参数配置，各参数值含义如下：

| 参数值              | 含义                                                         |
| ------------------- | ------------------------------------------------------------ |
| 0（延迟写）         | 事务提交时不会将`redo log buffer`中日志写入到`os buffer`，而是每秒写入`os buffer`并调用`fsync()`写入到`redo log file`中。也就是说设置为0时是(大约)每秒刷新写入到磁盘中的，当系统崩溃，会丢失1秒钟的数据。 |
| 1（实时写，实时刷） | 事务每次提交都会将`redo log buffer`中的日志写入`os buffer`并调用`fsync()`刷到`redo log file`中。这种方式即使系统崩溃也不会丢失任何数据，但是因为每次提交都写入磁盘，IO的性能较差。 |
| 2（实时写，延迟刷） | 每次提交都仅写入到`os buffer`，然后是每秒调用`fsync()`将`os buffer`中的日志写入到`redo log file`。 |

### redo log记录形式

前面说过，`redo log`实际上记录数据页的变更，而这种变更记录是没必要全部保存，因此`redo log`实现上采用了大小固定，循环写入的方式，当写到结尾时，会回到开头循环写日志。如下图： ![img](https://p3-juejin.byteimg.com/tos-cn-i-k3u1fbpfcp/5bd580611e99442a8037c9b5f0b519bc~tplv-k3u1fbpfcp-zoom-1.image)

同时我们很容易得知，**在innodb中，既有`redo log`需要刷盘，还有`数据页`也需要刷盘，`redo log`存在的意义主要就是降低对`数据页`刷盘的要求**。在上图中，`write pos`表示`redo log`当前记录的`LSN`(逻辑序列号)位置，`check point`表示**数据页更改记录**刷盘后对应`redo log`所处的`LSN`(逻辑序列号)位置。`write pos`到`check point`之间的部分是`redo log`空着的部分，用于记录新的记录；`check point`到`write pos`之间是`redo log`待落盘的数据页更改记录。当`write pos`追上`check point`时，会先推动`check point`向前移动，空出位置再记录新的日志。

启动`innodb`的时候，不管上次是正常关闭还是异常关闭，总是会进行恢复操作。因为`redo log`记录的是数据页的物理变化，因此恢复的时候速度比逻辑日志(如`binlog`)要快很多。 重启`innodb`时，首先会检查磁盘中数据页的`LSN`，如果数据页的`LSN`小于日志中的`LSN`，则会从`checkpoint`开始恢复。 还有一种情况，在宕机前正处于`checkpoint`的刷盘过程，且数据页的刷盘进度超过了日志页的刷盘进度，此时会出现数据页中记录的`LSN`大于日志中的`LSN`，这时超出日志进度的部分将不会重做，因为这本身就表示已经做过的事情，无需再重做。

### redo log与binlog区别

|          | redo log                                                     | binlog                                                       |
| -------- | ------------------------------------------------------------ | ------------------------------------------------------------ |
| 文件大小 | `redo log`的大小是固定的。                                   | `binlog`可通过配置参数`max_binlog_size`设置每个`binlog`文件的大小。 |
| 实现方式 | `redo log`是`InnoDB`引擎层实现的，并不是所有引擎都有。       | `binlog`是`Server`层实现的，所有引擎都可以使用 `binlog`日志  |
| 记录方式 | redo log 采用循环写的方式记录，当写到结尾时，会回到开头循环写日志。 | binlog 通过追加的方式记录，当文件大小大于给定值后，后续的日志会记录到新的文件上 |
| 适用场景 | `redo log`适用于崩溃恢复(crash-safe)                         | `binlog`适用于主从复制和数据恢复                             |

由`binlog`和`redo log`的区别可知：`binlog`日志只用于归档，只依靠`binlog`是没有`crash-safe`能力的。但只有`redo log`也不行，因为`redo log`是`InnoDB`特有的，且日志上的记录落盘后会被覆盖掉。因此需要`binlog`和`redo log`二者同时记录，才能保证当数据库发生宕机重启时，数据不会丢失。

## undo log

数据库事务四大特性中有一个是**原子性**，具体来说就是 **原子性是指对数据库的一系列操作，要么全部成功，要么全部失败，不可能出现部分成功的情况**。实际上，**原子性**底层就是通过`undo log`实现的。`undo log`主要记录了数据的逻辑变化，比如一条`INSERT`语句，对应一条`DELETE`的`undo log`，对于每个`UPDATE`语句，对应一条相反的`UPDATE`的`undo log`，这样在发生错误时，就能回滚到事务之前的数据状态。同时，`undo log`也是`MVCC`(多版本并发控制)实现的关键，这部分内容在[面试中的老大难-mysql事务和锁，一次性讲清楚！](https://juejin.im/post/6855129007336521741)中有介绍，不再赘述。







# SQL语句优化



## 索引优化

对于唯一的字段, 必加唯一索引

索引列不能参与计算或函数,不能是表达式的一部分, 否则无法使用索引

联合索引比多个单索引要好



## 字段优化





# 表设计要注意什么

1. 要有一个主键, 尽量使用自增id
2. **主键为什么不推荐有业务含义?**
3. 货币类型用decimal或int, 不要用float, double
4. 时间字段用datetime或timestamp
5. 字段尽量定义为NOT null
6. 枚举字段不用enum, 用tinyint
7. 不建议使用 TEXT/BLOB：
8. 禁止 VARBINARY、BLOB 存储图片、文件等。
9. 能用数值型就不要用字符串型





# [数据库逻辑设计之三大范式通俗理解，一看就懂，书上说的太晦涩](https://segmentfault.com/a/1190000013695030)

记住6个字: 原子, 主键依赖, 非主键传递不能依赖

**第二范式**（2NF）是[数据库正规化](https://zh.wikipedia.org/wiki/数据库正规化)所使用的[正规形式](https://zh.wikipedia.org/w/index.php?title=正規形式&action=edit&redlink=1)。规则是要求资料表里的所有资料都要和该资料表的[键](https://zh.wikipedia.org/wiki/关系键)（主键与候选键）有[完全依赖关系](https://zh.wikipedia.org/w/index.php?title=依赖关系&action=edit&redlink=1)：每个非键属性必须独立于任意一个候选键的任意一部分属性。如果有哪些资料只和一个键的一部分有关的话，就得把它们独立出来变成另一个资料表。如果一个资料表的键只有单个字段的话，它就一定符合第二范式。

第三范式要求非主键字段之间不能有依赖关系(非主键属性之间应该是独立无关的)，显然本例中小计依赖于非主键字段“单价”和“数量”，不符合第三范式。