# Mysql 练习题

**我使用的Mysql版本是5.7.19。答案可能会因版本会有少许出入**。



## group by 注意事项

要选择主表(也就是left join 的左表, right join的右表)字段进行分组, 因为附表字段可能为null

```mysql
#要选择s.id 不要选score.s_id
SELECT s.*,sc.s_id FROM student s LEFT JOIN score sc ON s.id = sc.s_id GROUP BY s.id;
```





## 练习数据

数据表 --1.学生表 student(s_id,s_name,Sage,s_sex)

--s_id 学生编号,s_name 学生姓名,s_age出生年月,s_sex 学生性别

--2.课程表 course(c_id,c_name,TId) --c_id --课程编号,Cname 课程名称,TId 教师编号

--3.教师表 teacher(t_id,t_name) --t_id教师编号,Tname 教师姓名

--4.成绩表 SC(s_id,c_id,score) --s_id 学生编号,c_id 课程编号,score 分数

创建测试数据

学生表 student

```mysql
CREATE TABLE `student`(
	`id` VARCHAR(20),
	`s_name` VARCHAR(20) NOT NULL DEFAULT '',
	`s_birth` VARCHAR(20) NOT NULL DEFAULT '',
	`s_sex` VARCHAR(10) NOT NULL DEFAULT '',
	PRIMARY KEY(`s_id`)
);
insert into student values('01' , '赵雷' , '1990-01-01' , '男');
insert into student values('02' , '钱电' , '1990-12-21' , '男');
insert into student values('03' , '孙风' , '1990-05-20' , '男');
insert into student values('04' , '李云' , '1990-08-06' , '男');
insert into student values('05' , '周梅' , '1991-12-01' , '女');
insert into student values('06' , '吴兰' , '1992-03-01' , '女');
insert into student values('07' , '郑竹' , '1989-07-01' , '女');
insert into student values('09' , '张三' , '2017-12-20' , '女');
insert into student values('10' , '李四' , '2017-12-25' , '女');
insert into student values('11' , '李四' , '2017-12-30' , '女');
insert into student values('12' , '赵六' , '2017-01-01' , '女');
insert into student values('13' , '孙七' , '2018-01-01' , '女');
```

科目表 course

```mysql
CREATE TABLE `course`(
	`id`  VARCHAR(20),
	`c_name` VARCHAR(20) NOT NULL DEFAULT '',
	`t_id` VARCHAR(20) NOT NULL,
	PRIMARY KEY(`c_id`)
);
insert into course values('01' , '语文' , '02');
insert into course values('02' , '数学' , '01');
insert into course values('03' , '英语' , '03');
```

教师表 teacher

```mysql
CREATE TABLE `teacher`(
	`id` VARCHAR(20),
	`t_name` VARCHAR(20) NOT NULL DEFAULT '',
	PRIMARY KEY(`t_id`)
);
insert into teacher values('01' , '张三');
insert into teacher values('02' , '李四');
insert into teacher values('03' , '王五');
```

成绩表 score

```mysql
CREATE TABLE `score`(
	`s_id` VARCHAR(20),
	`c_id`  VARCHAR(20),
	`score` INT(3),
	PRIMARY KEY(`s_id`,`c_id`)
);
insert into score  values('01' , '01' , 80);
insert into score  values('01' , '02' , 90);
insert into score  values('01' , '03' , 99);
insert into score  values('02' , '01' , 70);
insert into score  values('02' , '02' , 60);
insert into score  values('02' , '03' , 80);
insert into score  values('03' , '01' , 80);
insert into score  values('03' , '02' , 80);
insert into score  values('03' , '03' , 80);
insert into score  values('04' , '01' , 50);
insert into score  values('04' , '02' , 30);
insert into score  values('04' , '03' , 20);
insert into score  values('05' , '01' , 76);
insert into score  values('05' , '02' , 87);
insert into score  values('06' , '01' , 31);
insert into score  values('06' , '03' , 34);
insert into score  values('07' , '02' , 89);
insert into score  values('07' , '03' , 98);
```

## 练习题目

1. 查询" 01 "课程比" 02 "课程成绩高的学生的信息及课程分数 

   1.1 查询同时存在" 01 "课程和" 02 "课程的情况

   1.2 查询存在" 01 "课程但可能不存在" 02 "课程的情况(不存在时显示为 null ) 

   1.3 查询不存在" 01 "课程但存在" 02 "课程的情况

2. 查询平均成绩大于等于 60 分的同学的学生编号和学生姓名和平均成绩

3. 查询在 SC 表存在成绩的学生信息

4. 查询所有同学的学生编号、学生姓名、选课总数、所有课程的总成绩(没成绩的显示为 null ) 4.1 查有成绩的学生信息

5. 查询「李」姓老师的数量

6. 查询学过「张三」老师授课的同学的信息

7. 查询没有学全所有课程的同学的信息

8. 查询至少有一门课与学号为" 01 "的同学所学相同的同学的信息

9. 查询和" 01 "号的同学学习的课程 完全相同的其他同学的信息

10. 查询没学过"张三"老师讲授的任一门课程的学生姓名

11. 查询两门及其以上不及格课程的同学的学号，姓名及其平均成绩

12. 检索" 01 "课程分数小于 60，按分数降序排列的学生信息

13. 按平均成绩从高到低显示所有学生的所有课程的成绩以及平均成绩

14. 查询各科成绩最高分、最低分和平均分： 以如下形式显示：课程 ID，课程 name，最高分，最低分，平均分，及格率，中等率，优良率，优秀率 及格为>=60，中等为：70-80，优良为：80-90，优秀为：>=90 要求输出课程号和选修人数，查询结果按人数降序排列，若人数相同，按课程号升序排列

15. 按各科成绩进行排序，并显示排名， score 重复时保留名次空缺 15.1 按各科成绩进行排序，并显示排名， score 重复时合并名次

16. 查询学生的总成绩，并进行排名，总分重复时保留名次空缺 16.1 查询学生的总成绩，并进行排名，总分重复时不保留名次空缺

17. 统计各科成绩各分数段人数：课程编号，课程名称，[100-85]，[85-70]，[70-60]，[60-0] 及所占百分比

18. 查询各科成绩前三名的记录

19. 查询每门课程被选修的学生数

20. 查询出只选修两门课程的学生学号和姓名

21. 查询男生、女生人数

22. 查询名字中含有「风」字的学生信息

23. 查询同名同性学生名单，并统计同名人数

24. 查询 1990 年出生的学生名单

25. 查询每门课程的平均成绩，结果按平均成绩降序排列，平均成绩相同时，按课程编号升序排列

26. 查询平均成绩大于等于 85 的所有学生的学号、姓名和平均成绩

27. 查询课程名称为「数学」，且分数低于 60 的学生姓名和分数

28. 查询所有学生的课程及分数情况（存在学生没成绩，没选课的情况）

29. 查询任何一门课程成绩在 70 分以上的姓名、课程名称和分数

30. 查询不及格的课程

31. 查询课程编号为 01 且课程成绩在 80 分以上的学生的学号和姓名

32. 求每门课程的学生人数

33. 成绩不重复，查询选修「张三」老师所授课程的学生中，成绩最高的学生信息及其成绩

34. 成绩有重复的情况下，查询选修「张三」老师所授课程的学生中，成绩最高的学生信息及其成绩

35. 查询不同课程成绩相同的学生的学生编号、课程编号、学生成绩

36. 查询每门功成绩最好的前两名

37. 统计每门课程的学生选修人数（超过 5 人的课程才统计）。

38. 检索至少选修两门课程的学生学号

39. 查询选修了全部课程的学生信息

40. 查询各学生的年龄，只按年份来算

41. 按照出生日期来算，当前月日 < 出生年月的月日则，年龄减一

42. 查询本周过生日的学生

43. 查询下周过生日的学生

44. 查询本月过生日的学生

45. 查询下月过生日的学生

## 参考答案

1. 查询" 01 "课程比" 02 "课程成绩高的学生的信息及课程分数

```mysql
SELECT
	s.*,
	sc1.score course1,
	sc2.score course2 
FROM
	student AS s
	LEFT JOIN score sc1 ON s.id = sc1.s_id
	LEFT JOIN score sc2 ON s.id = sc2.s_id 
WHERE
	sc1.c_id = '01'
	AND sc2.c_id = '02'
	AND sc1.score > sc2.score;
	
SELECT
	s.*,
	sc1.score course1,
	sc2.score course2 
FROM
	(
	( SELECT s_id, score FROM score WHERE score.c_id = '01' ) AS sc1,
	( SELECT s_id, score FROM score WHERE score.c_id = '02' ) AS sc2 
	)
	RIGHT JOIN student s ON s.id = sc1.s_id 
WHERE
	sc1.s_id = sc2.s_id 
	AND sc1.score > sc2.score;
```

1.1 查询同时存在" 01 "课程和" 02 "课程的情况

```mysql
SELECT
	sc1.s_id,
	sc1.score course1,
	sc2.score course2 
FROM
	score sc1
	LEFT JOIN score sc2 ON sc1.s_id = sc2.s_id 
WHERE
	sc1.c_id = '01' 
	AND sc2.c_id = '02';
	
SELECT
	* 
FROM
	( SELECT s_id, score FROM score WHERE score.c_id = '01' ) AS sc1,
	( SELECT s_id, score FROM score WHERE score.c_id = '02' ) AS sc2 
WHERE
	sc1.s_id = sc2.s_id;
```

1.2 查询存在" 01 "课程但可能不存在" 02 "课程的情况(不存在时显示为 null )

```mysql
SELECT
	* 
FROM
	score sc1
	LEFT JOIN ( SELECT s_id, score FROM score WHERE score.c_id = '02' ) AS sc2 ON sc1.s_id = sc2.s_id 
WHERE
	sc1.c_id = '01';
```

1.3 查询不存在" 01 "课程但存在" 02 "课程的情况*****

```mysql
SELECT
	* 
FROM
	score
WHERE
	s_id NOT IN ( SELECT s_id FROM score WHERE c_id = '01' ) 
	AND c_id = '02'
#方法二这种快很多
SELECT
	sc2.* 
FROM
	score sc2
	LEFT JOIN ( SELECT * FROM score WHERE c_id = '01' ) sc1 ON sc1.s_id = sc2.s_id 
WHERE
	sc2.c_id = '02' 
	AND sc1.s_id IS NULL;
####方法三没理解,最快
SELECT
	* 
FROM
	A 
WHERE
	( SELECT COUNT( 1 ) AS num FROM B WHERE A.a_id = B.b_id ) = 0;
```

2. 查询平均成绩大于等于 60 分的同学的学生编号和学生姓名和平均成绩

```mysql
SELECT
	student.*,
	t1.avgscore 
FROM
	student
	INNER JOIN (
SELECT
	sc.s_id,
	AVG( sc.score ) AS avgscore 
FROM
	score sc 
GROUP BY
	sc.s_id 
HAVING
	AVG( sc.score ) >= 60 
	) AS t1 ON student.id = t1.s_id;
```

3. 查询在 SC 表存在成绩的学生信息

```mysql
SELECT
	* 
FROM
	student s
	INNER JOIN ( SELECT s_id FROM score GROUP BY s_id ) sc ON sc.s_id = s.id;
#########
SELECT
	* 
FROM
	student 
WHERE
	EXISTS ( SELECT * FROM score WHERE student.id = score.s_id );
#########
SELECT DISTINCT
	student.* 
FROM
	student,
	score 
WHERE
	student.id = score.s_id;
```

4. 查询所有同学的学生编号、学生姓名、选课总数、所有课程的总成绩(没成绩的显示为null)

```mysql
SELECT
	s.id,
	s.s_name,
	SUM( sc.score ) AS sumscore,
	COUNT( sc.c_id ) AS coursecount 
FROM
	student s
	LEFT JOIN score sc ON s.id = sc.s_id 
GROUP BY
	s.id;
#####
SELECT
	s.id,
	s.s_name,
	sc1.* 
FROM
	student s
	LEFT JOIN (
SELECT
	sc.s_id,
	SUM( sc.score ) AS sumscore,
	COUNT( sc.c_id ) AS coursecount 
FROM
	score sc 
GROUP BY
	sc.s_id 
	) sc1 ON sc1.s_id = s.id;
```

5. 查询「李」姓老师的数量

```mysql
SELECT
	COUNT( * ) 
FROM
	teacher 
WHERE
	t_name LIKE '李%';
```

6. 查询学过「张三」老师授课的同学的信息

```mysql
SELECT
	student.* 
FROM
	student,
	score,
	course,
	teacher 
WHERE
	teacher.t_name = '张三' 
	AND student.id = score.s_id 
	AND score.c_id = course.id 
	AND course.t_id = teacher.id;
```

7. 查询没有学全所有课程的同学的信息

```mysql
SELECT
	s.*,
	COUNT( sc.c_id ) AS c_num 
FROM
	student s
	LEFT JOIN score sc ON s.id = sc.s_id 
GROUP BY
	s.id 
HAVING
	( SELECT COUNT( id ) FROM course ) > COUNT( * );
```

8. 查询至少有一门课与学号为" 01 "的同学所学相同的同学的信息

```mysql
SELECT DISTINCT
	s.* 
FROM
	student s
	LEFT JOIN score sc ON s.id = sc.s_id 
WHERE
	sc.c_id IN ( SELECT c_id FROM score WHERE score.s_id = '01' ) 
	AND s.id != '01';
#####
SELECT
	* 
FROM
	student s 
WHERE
	s.id IN ( SELECT DISTINCT s_id FROM score WHERE c_id IN ( SELECT c_id FROM score WHERE s_id = '01' ) ) 
	AND s.id != '01';
#####
SELECT DISTINCT
	student.* 
FROM
	score,
	student 
WHERE
	score.c_id IN ( SELECT c_id FROM score WHERE score.s_id = '01' ) 
	AND score.s_id = student.id
	AND student.id != '01';
```

9. 查询和" 01 "号的同学学习的课程完全相同的其他同学的信息

```mysql
##别人的解法, 但是为考虑到其他同学学习的课程覆盖'01'号同学的情况
SELECT
	score.s_id 
FROM
	score 
WHERE
	c_id IN ( SELECT c_id FROM score WHERE s_id = '01' ) 
GROUP BY
	score.s_id 
HAVING
	count( c_id ) = ( SELECT count( c_id ) FROM score WHERE s_id = '01' ) 
	AND score.s_id != '01';
```

10. 查询没学过"张三"老师讲授的任一门课程的学生姓名

```mysql
SELECT*FROM student s WHERE s.id NOT IN (
SELECT s_id FROM score WHERE score.c_id IN (
SELECT c.id FROM teacher t LEFT JOIN course c ON c.t_id=t.id WHERE t_name='张三'))
```

11. 查询两门及其以上不及格课程的同学的学号，姓名及其平均成绩

```mysql
SELECT
	s.id,
	s.s_name,
	AVG( score.score ) 
FROM
	student s
	LEFT JOIN score ON s_id = s.id 
WHERE
	s.id IN ( SELECT s_id FROM score WHERE score < 60 GROUP BY s_id HAVING COUNT( c_id ) > 1 ) 
GROUP BY
	s.id;
```

12. 检索" 01 "课程分数小于 60，按分数降序排列的学生信息

```mysql
SELECT
	student.* 
FROM
	student
	LEFT JOIN score ON student.id = score.s_id 
WHERE
	score.c_id = '01' 
	AND score < 60 
ORDER BY
	score desc;
SELECT
	student.* 
FROM
	student,
	score 
WHERE
	score.c_id = '01' 
	and score.score < 60 
	and student.id = score.s_id
ORDER BY
	score desc;
```

13. 按平均成绩从高到低显示所有学生的所有课程的成绩以及平均成绩

```mysql
SELECT
	score.s_id,
	score.c_id,
	score.score,
	avg_score 
FROM
	score
	LEFT JOIN ( SELECT score.s_id, avg( score.score ) as avg_score FROM score GROUP BY score.s_id ) as a USING ( s_id ) 
ORDER BY
	avg_score desc;
```

14. 查询各科成绩最高分、最低分和平均分： 以如下形式显示：课程 ID，课程 name，最高分，最低分，平均分，及格率，中等率，优良率，优秀率 及格为>=60，中等为：70-80，优良为：80-90，优秀为：>=90 要求输出课程号和选修人数，查询结果按人数降序排列，若人数相同，按课程号升序排列

```mysql
SELECT
	score.c_id,
	max( score.score ) as 最高分,
	min( score.score ) as 最低分,
	avg( score.score ) as 平均分,
	count( * ) as 选修人数,
	sum( case when score.score >= 60 then 1 else 0 end ) / count( * ) as 及格率,
	sum( case when score.score >= 70 and score.score < 80 then 1 else 0 end ) / count( * ) as 中等率,
	sum( case when score.score >= 80 and score.score < 90 then 1 else 0 end ) / count( * ) as 优良率,
	sum( case when score.score >= 90 then 1 else 0 end ) / count( * ) as 优秀率 
FROM
	score 
GROUP BY
	score.c_id 
ORDER BY
	count( * ) DESC,
	score.c_id asc;
```

15. 按各科成绩进行排序，并显示排名， score 重复时保留名次空缺

```mysql
select
	score.c_id,
	@curRank := @curRank + 1 as rank,
	score.score 
from
	( select @curRank := 0 ) as t,
	score 
ORDER BY
	score.score desc
```

15.1 按各科成绩进行排序，并显示排名， score 重复时合并名次

```mysql
select
	score.c_id,
case
	when @fontscore = score then
	@curRank 
	when @fontscore := score then
	@curRank := @curRank + 1 
	end as rank,
	score.score 
from
	( select @curRank := 0, @fontage := null ) as t,
	score 
ORDER BY
score.score desc
```

16. 查询学生的总成绩，并进行排名，总分重复时保留名次空缺

```mysql
select
	t1.*,
	@currank := @currank + 1 as rank 
from
	( select score.s_id, sum( score ) from score GROUP BY score.s_id ORDER BY sum( score ) desc ) as t1,
	( select @currank := 0 ) as t
```

16.1 查询学生的总成绩，并进行排名，总分重复时不保留名次空缺

```mysql
select
	t1.*,
case
	when @fontscore = t1.sumscore then
	@currank 
	when @fontscore := t1.sumscore then
	@currank := @currank + 1 
	end as rank 
from
	( select score.s_id, sum( score ) as sumscore from score GROUP BY score.s_id ORDER BY sum( score ) desc ) as t1,
( select @currank := 0, @fontscore := null ) as t
```

17. 统计各科成绩各分数段人数：课程编号，课程名称，[100-85]，[85-70]，[70-60]，[60-0] 及所占百分比

```mysql
select course.id,course.Cname,t1.*
from course LEFT JOIN (
select score.c_id,CONCAT(sum(case when score.score>=85 and score.score<=100 then 1 else 0 end )/count(*)*100,'%') as '[85-100]',
CONCAT(sum(case when score.score>=70 and score.score<85 then 1 else 0 end )/count(*)*100,'%') as '[70-85)',
CONCAT(sum(case when score.score>=60 and score.score<70 then 1 else 0 end )/count(*)*100,'%') as '[60-70)',
CONCAT(sum(case when score.score>=0 and score.score<60 then 1 else 0 end )/count(*)*100,'%') as '[0-60)'
from sc
GROUP BY score.c_id) as t1 on course.id=t1.c_id
```

18. 查询各科成绩前三名的记录

思路：前三名转化为若大于此成绩的数量少于3即为前三名。

```mysql
select
	* 
from
	score 
where
	( select count( * ) from score as a where score.c_id = a.c_id and score.score < a.score ) < 3 
ORDER BY
	c_id asc,
	score.score desc
```

19. 查询每门课程被选修的学生数

```mysql
SELECT c_id,count(s_id) FROM score GROUP BY c_id;
```

20. 查询出只选修两门课程的学生学号和姓名

```mysql
SELECT id,s_name FROM student LEFT JOIN score ON score.s_id=student.id GROUP BY student.id HAVING COUNT(c_id)=2;
```

21. 查询男生、女生人数

```mysql
SELECT s_sex,COUNT(s_sex) as 人数 FROM student GROUP BY s_sex;
```

22. 查询名字中含有「风」字的学生信息

```mysql
select * from student where student.s_name like '%风%';
```

23. 查询同名同姓学生名单，并统计同名人数

```mysql
select
	* 
from
	student
	LEFT JOIN ( select s_name, s_sex, COUNT( * ) 同名人数 from student group by s_name, s_sex ) as t1 on student.s_name = t1.s_name 
	and student.s_sex = t1.s_sex 
where
	t1.同名人数 >1
```

24. 查询 1990 年出生的学生名单

```mysql
select * from student where YEAR(student.s_birth)=1990;
```

25. 查询每门课程的平均成绩，结果按平均成绩降序排列，平均成绩相同时，按课程编号升序排列

```mysql
select score.c_id,AVG(score.score) from score GROUP BY score.c_id ORDER BY AVG(score.score) desc,score.c_id asc;
```

26. 查询平均成绩大于等于 85 的所有学生的学号、姓名和平均成绩

```mysql
select student.id,student.s_name,t1.avgscore from student INNER JOIN (select score.s_id,AVG(score.score) as avgscore from score GROUP BY score.s_id HAVING AVG(score.score)> 85) as t1 on student.id=t1.s_id
```

27. 查询课程名称为「数学」，且分数低于 60 的学生姓名和分数

```mysql
SELECT s_name,score FROM student INNER JOIN (
SELECT s_id,c_id,score FROM score LEFT JOIN course ON c_id=course.id WHERE c_name='数学' AND score< 60) AS sc ON student.id=sc.s_id;
```

28. 查询所有学生的课程及分数情况（存在学生没成绩，没选课的情况）

```mysql
SELECT id,c_id,score FROM student LEFT JOIN score ON student.id=score.s_id;
```

29. 查询任何一门课程成绩在 70 分以上的姓名、课程名称和分数

```mysql
SELECT
	s_name,
	c_name,
	score 
FROM
	student
	INNER JOIN ( SELECT s_id, c_name, score FROM score LEFT JOIN course ON score.c_id = course.id WHERE score > 70 ) a ON student.id = a.s_id;
	
select
	student.s_name,
	course.c_name,
	score.score 
from
	student,
	score,
	course 
where
	score.score > 70 
	and student.id = score.s_id 
	and score.c_id = course.id
```

30.查询存在不及格的课程

```mysql
select DISTINCT score.c_id from score where score.score< 60
```

31.查询课程编号为 01 且课程成绩在 80 分以上的学生的学号和姓名

```mysql
select student.id,student.s_name from student LEFT JOIN score ON student.id=score.s_id where score.c_id='01' and score.score> 80
```

32. 求每门课程的学生人数

```mysql
select score.c_id,count(*) as 学生人数 from score GROUP BY score.c_id
```

33. 成绩不重复，查询选修「张三」老师所授课程的学生中，成绩最高的学生信息及其成绩

```mysql
select
	student.*,
	score.score 
from
	student,
	course,
	teacher,
	score 
where
	course.id = score.c_id 
	and course.t_id = teacher.id 
	and teacher.t_name = '张三' 
	and student.id = score.s_id 
	LIMIT 1
```

34. 成绩有重复的情况下，查询选修「张三」老师所授课程的学生中，成绩最高的学生信息及其成绩

```mysql
select student.*,t1.score
from student INNER JOIN (select score.s_id,score.score, case when @fontage=score.score then @rank when @fontage:=score.score then @rank:=@rank+1 end  as rank
from course ,teacher ,sc,(select @fontage:=null,@rank:=0) as t
where course.id=score.c_id
and course.TId=teacher.TId
and teacher.Tname='张三'
ORDER BY score.score DESC) as t1 on student.s_id=t1.s_id
where t1.rank=1
```

35. 查询不同课程成绩相同的学生的学生编号、课程编号、学生成绩

```mysql
select *
from sc as t1
where exists(select * from sc as t2 where t1.s_id=t2.s_id and t1.c_id!=t2.c_id and t1.score =t2.score )
```

36.查询每门功成绩最好的前两名

```mysql
select *
from sc as t1
where (select count(*) from sc as t2 where t1.c_id=t2.c_id and t2.score >t1.score)<2
ORDER BY t1.c_id
```

37.统计每门课程的学生选修人数（超过 5 人的课程才统计）

```mysql
select score.c_id as 课程编号,count(*) as 选修人数
from sc 
GROUP BY score.c_id
HAVING count(*)>5
```

38.检索至少选修两门课程的学生学号

```mysql
select DISTINCT t1.s_id
from sc as t1 
where (select count(* )from sc where t1.s_id=score.s_id)>=3
```

39. 查询选修了全部课程的学生信息

```mysql
select student.*
from sc ,student 
where score.s_id=student.s_id
GROUP BY score.s_id
HAVING count(*) = (select DISTINCT count(*) from course )
```

40.查询各学生的年龄，只按年份来算

```mysql
select student.s_id as 学生编号,student.s_name  as  学生姓名,TIMESTAMPDIFF(YEAR,student.Sage,CURDATE()) as 学生年龄
from student
```

41. 按照出生日期来算，当前月日 < 出生年月的月日则，年龄减一

```mysql
select student.s_id as 学生编号,student.s_name  as  学生姓名,TIMESTAMPDIFF(YEAR,student.Sage,CURDATE()) as 学生年龄
from student
```

42.查询本周过生日的学生

```mysql
select *
from student 
where YEARWEEK(student.Sage)=YEARWEEK(CURDATE())
```

43. 查询下周过生日的学生

```mysql
select *
from student 
where YEARWEEK(student.Sage)=CONCAT(YEAR(CURDATE()),week(CURDATE())+1)
```

44.查询本月过生日的学生

```mysql
select *
from student 
where EXTRACT(YEAR_MONTH FROM student.Sage)=EXTRACT(YEAR_MONTH FROM CURDATE())
```

45. 查询下月过生日的学生

```mysql
select *
from student 
where EXTRACT(YEAR_MONTH FROM student.Sage)=EXTRACT(YEAR_MONTH FROM DATE_ADD(CURDATE(),INTERVAL 1 MONTH))
```