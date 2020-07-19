1. 1.1



###### INSERT INTO table_name ( field1, field2,...fieldN ) VALUES ( value1, value2,...valueN );

###### INSERT INTO table_name ( field1, field2,...fieldN ) SELECT ( value1, value2,...valueN ) FROM TABLE_NAME;

###### SELECT column_name,column_name FROM table_name [WHERE Clause] [LIMIT N][ OFFSET M]

###### select * from a left join b on a.id = b.a_id left join c on c.id = b.c_id;

###### UPDATE table_name SET field1=new-value1, field2=new-value2 [WHERE Clause]

###### DELETE FROM table_name [WHERE Clause]

//修改密码
SET PASSWORD FOR 'root'@'localhost' = PASSWORD('newpass');
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';

创建数据库
create database `databasename` charset=utf8mb4
删除数据库
drop database `databasename`;
显示当前所有数据库
show databases;
显示你创建数据库时的信息
show create database 数据库名;
显示当前所在库及其库表
show tables;
显示表的信息
desc 表名；
查看当前mysql连接情况
show processlist;
修改 mysql数据文件夹的路径的方法
在mysql 安装目录 找到  my.ini
再指定要把数据放到哪个目录(事先这个目录要存在)

# Path to the database root
datadir=C:/ProgramData/MySQL/MySQL Server 5.7/Data

//导出数据库或数据表到指定文件（需在MySQL文件夹bin目录）

mysqldump -uroot -p -B --all-databases >  ~/db.sql;
mysqldump -uroot -p -B 数据库名1 数据库名2 >  数据存放路径;
mysqldump -uroot -p -B 数据库名 表名1 表名2 >  数据存放路径;
注意：如果我们在备份一个数据库时，也带上 -B参数，更好，在恢复数据库时，不需要再创建空数据库

//导入数据库
mysql>source 备份的全路径;
注意: 在使用source 恢复数据时，保证你use 对应的数据库.

//创建新表

create table main_contact(
	id int unsigned auto_increment not null comment 'id',
	userId int not null comment '用户ID',
	real_name varchar(100) not null comment '真实姓名',
	wechat varchar(100) comment '微信号',
	phone varchar(25) not null comment '联系电话',
	address text comment '收件地址',
	primary key (id),
 unique aa(aa),
	index userId(userId)
)charset=utf8-mb4 engine=innoDB;


修改表(重点)
//在resume字段后添加image字段
alter table users add image varchart(125) not null default '' comment '图片路径' after resume;
//修改job字段为varchart60长度
alter table users modify job varchart(60) not null comment '工作';
//修改job字段为varchart60长度
alter table users change job job varchart(60) not null comment '工作';
//删除字段,数据会被删除
alter table users drop sex;
//修改表名
alter table users rename to employee;
//修改表的字符集
alter table employee chartset=utf8;
//修改列名
alter table employee change name user_name varchart(60) not null default '';

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


//查看事务隔离级别
select @@tx_isolation;
//设置事务隔离级别
set session transaction isolation level read uncommitted;
set session transaction isolation level read committed;
set session transaction isolation level repeatable-read;
set session transaction isolation level serializable;





### 一些用法总结

* 获取分组后每组的count数

  > SELECT count(`category_id`) FROM `product` GROUP BY `category_id`;

* 获取分组后的组数

  > 


## 安全

避免使用熟知的端口,降低被初级扫描的风险
编辑/etc/my.cnf文件，mysqld 段落中配置新的端口参数，并重启mysql服务：
port=3506 

避免在主机名中只使用通配符，有助于限定可以连接数据库的客户端，否则服务就开放到了公网
执行SQL更新语句，为每个用户指定允许连接的host范围。 1. 登录数据库，执行use mysql; ； 2. 执行语句select user,Host from user where Host='%';查看HOST为通配符的用户; 3. 删除用户或者修改用户host字段，删除语句：DROP USER 'user_name'@'%'; 。更新语句：update user set host = <new_host> where host = '%';。 4. 执行SQL语句:
OPTIMIZE TABLE user;
flush privileges;

禁用local_infile选项会降低攻击者通过SQL注入漏洞器读取敏感文件的能力
编辑Mysql配置文件/etc/my.cnf，在mysqld 段落中配置local-infile参数为0，并重启mysql服务：
local-infile=0