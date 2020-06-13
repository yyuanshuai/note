# 必会MYSQL语句

## 数据定义语句(DDL)

### 1. 数据库操作

* 登录数据库：
  > mysql -uroot -proot
* 创建数据库:
  > create database `databasename` charset=utf8mb4
* 查看所有数据库：
  > show databases
* 选择数据库：
  > use `databasename`;
* 显示你创建数据库时的信息
  > show create database `databasename`
* 删除数据库：
  > drop database `databasename`
* 显示当前所在库及其库表
  > show tables
* 查看当前mysql连接情况
  > show processlist

  
### 2. 表操作

* 创建表
    ```
    create table users(
        id int unsigned auto_increment not null comment 'id',
        name varchar(100) not null comment '真实姓名',
        wechat varchar(100) comment '微信号',
        phone varchar(25) not null comment '联系电话',
        address text comment '收件地址',
        primary key (id),
        unique wechat(wechat),
        index phone(phone)
    )charset=utf8-mb4 engine=innoDB;
    ```

* 查看表的定义：
  > desc 表名
* 查看表定义（详细）：
  > show create table users \G
* 删除表：
  > drop table users
* 修改表字段：
  > alter table users modify job varchart(255);
* 添加表字段：
  > alter table users add avatar varchart(255) after resume;
* 删除表字段：
  > alter table users drop sex;
* 修改列名
  > alter table users change name new_name varchart(255);
* 修改表名
  > alter table users rename to employee;

## 数据操纵语句(DML)

### 1. 插入记录
* 指定字段插入
  > INSERT INTO table_name ( field1, field2,...fieldN ) VALUES ( value1, value2,...valueN );
* 指定表插入表
  > INSERT INTO table_name ( field1, field2,...fieldN ) SELECT ( value1, value2,...valueN ) FROM TABLE_NAME;
* 批量插入数据
  > INSERT INTO table_name values(1,'dept1'),(2,'dept2')

### 2. 修改记录
> UPDATE table_name SET field1=new-value1, field2=new-value2 [WHERE Clause]

### 3. 删除记录
> DELETE FROM table_name [WHERE Clause]

### 4. 查询记录
* 查询所有记录
  > select * from users
* 查询不重复的记录：
  > select distinct deptno from emp
* 条件查询：
  > select * from emp where deptno=1 and sal<3000
* 排序和限制：
  > select * from emp order by deptno desc limit 2
* 分页查询(查询从第0条记录开始10条)：
  > select * from emp order by deptno desc limit 0,10
* 聚合(查询部门人数大于1的部门编号)：
  > select deptno,count(1) from emp group by deptno having count(1) > 1
* 连接查询：
  > select * from emp e left join dept d on e.deptno=d.deptno
* 子查询：
  > select * from emp where deptno in (select deptno from dept)
* 记录联合：
  > select deptno from emp union select deptno from dept

## 数据控制语句(DCL)
### 1. 权限相关
* 授予操作权限(将test数据库中所有表的select和insert权限授予test用户)：
  > grant select,insert on test.* to 'test'@'localhost' identified by '123'
* 查看账号权限：
  > show grants for 'test'@'localhost'
* 收回操作权限：
  > revoke insert on test.* from 'test'@'localhost'
* 授予所有数据库的所有权限：
  > grant all privileges on *.* to 'test'@'localhost'
* 授予所有数据库的所有权限(包括grant)：
  > grant all privileges on *.* to 'test'@'localhost' with grant option
* 授予SUPER PROCESS FILE权限（系统权限不能指定数据库）：
  > grant super,process,file on *.* to 'test'@'localhost'
* 只授予登录权限：
  > grant usage on *.* to 'test'@'localhost'

### 2. 账号相关
* 删除账号：
  > drop user 'test'@'localhost'
* 修改自己的密码：
  * > set password = password('123')
  * > SET PASSWORD FOR 'root'@'localhost' = PASSWORD('newpass');
  * > ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
* 管理员修改他人密码：
  > set password for 'test'@'localhost' = password('123')

## 事务

* 查看事务隔离级别
  > select @@tx_isolation;
* 设置事务隔离级别
  * > set session transaction isolation level read uncommitted;
  * > set session transaction isolation level read committed;
  * > set session transaction isolation level repeatable-read;
  * > set session transaction isolation level serializable;

## 其他
### 字符集相关
* 查看字符集：
  > show variables like 'character%'
* 创建数据库时指定字符集：
  > create database test2 character set utf8

### 时区相关
* 查看当前时区（UTC为世界统一时间，中国为UTC+8）：
  > show variables like "%time_zone%"
* 修改mysql全局时区为北京时间，即我们所在的东8区：
  > set global time_zone = '+8:00';
* 修改当前会话时区：
  > set time_zone = '+8:00'
* 立即生效：
  > flush privileges

## 导入导出数据库

* mysqldump -uroot -p -B --all-databases >  ~/db.sql;
* mysqldump -uroot -p -B 数据库名1 数据库名2 >  数据存放路径;
* mysqldump -uroot -p -B 数据库名 表名1 表名2 >  数据存放路径;
> * 注意：如果我们在备份一个数据库时，也带上 -B参数，更好，在恢复数据库时，不需要再创建空数据库
> * 导出数据库或数据表到指定文件（需在MySQL文件夹bin目录）

* mysql>source 备份的全路径;
> 注意: 在使用source 恢复数据时，保证你use 对应的数据库.

```

该语句添加一个主键，这意味着索引值必须是唯一的，且不能为NULL。
ALTER TABLE tbl_name ADD PRIMARY KEY (column_list)

这条语句创建索引的值必须是唯一的（除了NULL外，NULL可能会出现多次）。
ALTER TABLE tbl_name ADD UNIQUE index_name (column_list): 

添加普通索引，索引值可出现多次。
ALTER TABLE tbl_name ADD INDEX index_name (column_list): 

该语句指定了索引为 FULLTEXT ，用于全文索引。
ALTER TABLE tbl_name ADD FULLTEXT index_name (column_list):

mysql> ALTER TABLE testalter_tbl MODIFY i INT NOT NULL;
mysql> ALTER TABLE testalter_tbl ADD PRIMARY KEY (i);


//增加字段
alter table table_name add fieldname int(1) default '1' comment 'aa';

//修改字段
alter table table_name modify fieldname int(1) default '1' comment 'aa';
ALTER TABLE 表名 CHANGE 原字段名 新字段名 字段类型 约束条件
ALTER TABLE user10 CHANGE test test1 CHAR(32) NOT NULL DEFAULT '123';

//移动字段顺序
alter table table_name modify field1 int(1) after test;
-- 将test放到第一个，保留原完整性约束条件
ALTER TABLE user10 MODIFY test CHAR(32) NOT NULL DEFAULT '123' FIRST;

//给字段添加默认值
alter table table_name alter field1 set default 'aa';
//删除默认值
alter table table_name alter field1 drop default ;

//若存在表则删除
DROP TABLE IF EXIST tablename; 


-- 删除主键
ALTER TABLE test12 DROP PRIMARY KEY;

//添加主键
ALTER TABLE tb_name ADD [CONSTRAINT [sysmbol]] PRIMARY KEY [index_type] (字段名称,...)
ALTER TABLE test12 ADD PRIMARY KEY(id);

-- 添加复合主键
ALTER TABLE test13 ADD PRIMARY KEY(id,card);

-- 添加唯一性约束
-- ALTER TABLE tb_name ADD [CONSTANT [symbol]] UNIQUE [INDEX | KEY] [索引名称](字段名称,...)

在删除主键时，有一种情况是需要注意的，我们知道具有自增长的属性的字段必须是主键，如果表里的主键是具有自增长属性的；那么直接删除是会报错的。如果想要删除主键的话，可以先去年自增长属性，再删除主键

-- 删除主键，这样会报错，因为自增长的必须是主键
ALTER TABLE test14 DROP PRIMARY KEY;

-- 先用MODIFY删除自增长属性，注意MODIFY不能去掉主键属性
ALTER TABLE test14 MODIFY id INT UNSIGNED;
-- 再来删除主键
ALTER TABLE test14 DROP PRIMARY KEY;

修改表的存储引擎：
-- 修改表的存储引擎
-- ALTER TABLE tb_name ENGINE=存储引擎名称
ALTER TABLE user12 ENGINE=MyISAM;
ALTER TABLE user12 ENGINE=INNODB;

修改自增长值：
-- 修改自增长的值
-- ALTER TABLE tb_name AUTO_INCREMENT=值
ALTER TABLE user12 AUTO_INCREMENT=100;


-- username添加唯一性约束，如果没有指定索引名称，系统会以字段名建立索引
ALTER TABLE user12 ADD UNIQUE(username);
-- car添加唯一性约束
ALTER TABLE user12 ADD CONSTRAINT symbol UNIQUE KEY uni_card(card);
-- 查看索引
SHOW CREATE TABLE user12;

-- test,test1添加联合unique
ALTER TABLE user12 ADD CONSTRAINT symbol UNIQUE INDEX mulUni_test_test1(test, test1);

-- 删除唯一
-- ALTER TABLE tb_name DROP {INDEX|KEY} index_name;
-- 删除刚刚添加的唯一索引
ALTER TABLE user12 DROP INDEX username;
ALTER TABLE user12 DROP KEY uni_card;
ALTER TABLE user12 DROP KEY mulUni_test_test1;

```