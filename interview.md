[淘宝一面：“说一下 Spring Boot 自动装配原理呗？”](https://www.cnblogs.com/javaguide/p/springboot-auto-config.html)





# 面试题

### ==和equals的区别?

equals: Object默认采用==进行比较, 通常会重写



### List和Set的区别?

List是可重复的, 有序的, 按对象进入的顺序保存的, 允许多个null元素, 可以用lterator取出所有元素遍历还可以使用get(i)获取指定下标的元素

Set是不可重复的, 无序的, 最多允许1个null值, 只能用lterator取得所有元素遍历



### ArrayList和LinkedList区别?

arrayList: 基于动态数组, 连续内存存储, 适合下标访问(随机访问), 扩容机制: 因为数组长度固定, 超出长度存数据时需要新建数组, 然后将老数组的数据拷贝到新数组, 如果不是尾部插入数据还会涉及到元素的移动, 使用尾插法并指定初识容量可以极大的提升性能, 甚至超过LinkedList(需要创建大量node对象)

LinkedList: 基于链表, 可以存储在分散的内存中, 适合做数据插入和删除操作, 不适合查询, 需要逐一遍历, 遍历LinkedList必须使用lterator不能使用for循环, 因为每次for循环体内通过get(i)取得某一元素时都需要对list重新遍历

另外不要试图使用indexOf等返回元素索引, 并利用其进行遍历, 使用indexOf对list进行遍历, 当结果为空时会遍历整个链表





### hashcode和equals

hashCode() 的作用是获取哈希码，也称为散列码；它实际上是返回一个 int 整数。这个哈希码的作用是确定该对象在哈希表中的索引位置。hashCode()定义在 JDK 的 Object 类中，这就意味着 Java 中的任何类都包含有 hashCode() 函数。另外需要注意的是： Object 的 hashcode 方法是本地方法，也就是用 c 语言或 c++ 实现的，该方法通常用来将对象的 内存地址 转换为整数之后返回。

散列表存储的是键值对(key-value)，它的特点是：能根据“键”快速的检索出对应的“值”。这其中就利用到了散列码！（可以快速找到所需要的对象）

### **为什么要有 hashCode？**

我们以“`HashSet` 如何检查重复”为例子来说明为什么要有 hashCode？

当你把对象加入 `HashSet` 时，`HashSet` 会先计算对象的 hashcode 值来判断对象加入的位置，同时也会与其他已经加入的对象的 hashcode 值作比较，如果没有相符的 hashcode，`HashSet` 会假设对象没有重复出现。但是如果发现有相同 hashcode 值的对象，这时会调用 `equals()` 方法来检查 hashcode 相等的对象是否真的相同。如果两者相同，`HashSet` 就不会让其加入操作成功。如果不同的话，就会重新散列到其他位置。（摘自我的 Java 启蒙书《Head First Java》第二版）。这样我们就大大减少了 equals 的次数，相应就大大提高了执行速度。

[Java hashCode() 和 equals()的若干问题解答](https://www.cnblogs.com/skywang12345/p/3324958.html)



### ConcurrentHashMap原理, jdk7, jdk8的区别

jdk7: 

数据结构是: ReentrantLock+Segment+HashEntry, 一个Segment中包含一个HashEntry数组, 每个HashEntry又是一个链表结构

元素查询: 二次hash, 第一次hash定位到Segment, 第二次hash定位到元素所在的链表头部

锁: Segment分段锁 Segment继承了ReentrantLock, 锁定操作的Segment, 其他的S俄国梦不受影响, 并发度为Segment个数, 可以通过构造函数指定, 数组扩容不会影响其他的segment

get方法无需加锁, volatile保证

jdk8: 

数据结构: synchronized+cas+Node+红黑树, Node的val和next都用volatile修饰, 保证可见性

查找, 替换, 复制操作都是用cas

锁: 锁链表的head节点, 不影响其他元素的读写, 锁粒度更细, 效率更高, 扩容时, 阻塞所有的读写操作, 并发扩容

读操作无锁: 

Node的val和next使用volatile修饰,读写线程对该变量互相可见

数组用volatile修饰,保证扩容时被读线程感知



### 双亲委派机制

BootStrapClassLoader, ExtClassLoader, AppClassLoader, 自定义加载器

加载一个类时, 先去AppClassLoader里查找缓存(已加载过得类会缓存),没有, 就到ExtClassLoader缓存里查找, 有, 直接返回, 没有继续向上查找BootStrapClassLoader里的缓存, 都没有, 则到加载路径中查找有没有该类, 有则加载返回, 没有向下查找ExtClassLoader加载路径是否有该类, .....

好处: 

- 主要为了安全性, 避免用户自己编写的类动态替换java的一些核心类, 比如string
- 同时也避免类的重复加载, 应为jvm中区分不同类, 不仅仅是根据类名, 相同的class文件被不同的classloader加载就是不同的两个类



### java中的异常体系

顶级父类Throwable, 两个子类Exception和Error.

Error是程序无法处理的错误, 一旦出现这个错误, 则程序将被迫停止运行.

Exception是不会导致程序停止. 又分为两个部分RuntimeException运行时异常和CheckedException检查异常.



### 线程的生命周期

创建, 就绪, 运行, 阻塞, 死亡状态

1. 等待阻塞, 运行的线程执行wait方法, 该线程会释放占用的所有资源, jvm回吧该线程放入等待吃中. 进入这个状态后, 是不能自动唤醒的, 必须依靠其他线程调用notify或notifyall方法才能被唤醒, wait是object类的方法
2. 同步阻塞, 运行的线程在获取对象的同步锁时, 若该同步锁被别的线程占用, 则jvm会把该线程放入锁池中
3. 其他阻塞: 运行的线程执行sleep或join方法, 或者发出i/o请求时, jvm会把改线程置为阻塞状态, 当sleep状态超时, join等待线程终止或超时, 或者i/o处理完毕时, 线程重新转入就绪状态, sleep是Thread类的方法

 



### 缓存雪崩, 缓存穿透, 缓存击穿

缓存雪崩是指缓存同一时间大面积的失效, 所以, 后面的请求都会落到数据库上, 造成数据库短时间内承受大量请求而崩掉.

解决方案: 1. 缓存数据的过期时间设为随机, 防止同一时间大面积的缓存失效 2. 给每个缓存数据增加一个标记, 记录缓存是否已失效, 若失效则更新 3. 缓存预热 4. 互斥锁 

**缓存穿透**，用户不断的发起缓存和数据库均不存在的数据请求, 导致数据库压力过大. 即黑客故意去请求缓存中不存在的数据，导致所有的请求都怼到数据库上，从而数据库连接异常。

**前端后端进行初步参数校验**

**从网关层NGINX增加配置项, 对单个IP每秒访问次数超出阈值的IP都拉黑**

**解决方案**:

1. 提供一个能迅速判断请求是否有效的拦截机制，比如，布隆过滤器, 他的原理也很简单, 就是利用高效的数据结构和算法快速判断出你这个key是否在数据库中存在, 不存在你return就好了, 存在就去查DB刷新KV再return
2. 缓存空值, 设置过期时间



### 已知ArrayList是线程不安全的, 请编写一个不安全的案例并给出解决方案

```java
new Vector();
Collections.synchronizedList(new ArrayList());
new CopyOnWriteArrayList(); 

//扩展
new CopyOnWriteArraySet();//底层是使用 CopyOnWriteArrayList 来实现的
new ConcurrentHashMap();

```







### 负载均衡算法, 类型

1. 轮询
2. 随机
3. 加权轮询
4. 加权随机
5. 散列(哈希)
6. 一致性哈希
7. 最小连接数(最小活跃)

软件负载均衡: NGINX, HAproxy, LVS





### 分布式架构下, session共享有什么方案

1. 采用无状态

2. 存入cookie

3. 服务器之间进行session同步

4. ip绑定策略--失去了负载均衡的意义, 挂掉一台服务器时, 会影响一批用户的使用, 风险很大

5. 使用reids存储

    虽然架构上面变复杂了, 并且需要多访问一次redis, 但是这种方案也带来了好处

    - 实现了session共享
    - 可以水平扩展(增加redis机器)
    - 服务器重启session不丢失
    - 甚至可以跨平台



### 分布式id生成方案

1. uuid

2. 数据库自增序列

3. Leaf-segment

    > 

4. 基于redis, MongoDB, zk等中间件

5. 雪花算法



### 如何实现接口的幂等性

1. 唯一id, 每次操作, 都根据操作和内容生成唯一id, 在执行之前先判断id是否存在, 如果不存在则执行后续操作, 并且保存到数据库或redis中
2. 服务端提供发送token的接口, 业务调用接口前线获取token, 然后调用业务接口请求时, 把token携带过去,服务器判断token是否存在redis中, 存在表示第一次请求, 可以继续执行业务, 执行业务完成后,最后需要吧redis中的token删除
3. 建去重表, 将业务中有唯一标识的字段保存到去重表, 如果表中存在, 则表示已经处理过了
4. 版本控制, 增加版本号, 当版本号符合时, 才能更新数据
5. 状态控制, 例如订单有状态已支付, 未支付, 支付中, 支付失败, 当处于未支付的时候才允许修改为支付中等



### Spring Cloud和Dubbo的区别

底层协议: springcloud基于http协议, dubbo基于tcp, 决定了dubbo的性能相对较好

注册中心: springcloud使用的eureka, dubbo推荐使用zookeeper

模型定义: dubbo将一个接口定义为一个服务, springcloud则是将一个应用定义为一个服务springcloud是一个生态, 而dubbo是springcloud生态中关于服务调用的一种解决方案(服务治理)





# spring

### spring循环依赖



### spring事务失效

1. 發生自调用
2. 方法不是public
3. 数据库不支持事务
4. 异常被吃掉, 事务不会回滚(默认为RuntimeException,如果是其他异常想要回滚，需要在@Transactional注解上加rollbackFor属性)

 

### spring三级缓存知道吗?

spring内部是通过三级缓存来解决循环依赖问题的**

第一级缓存(也叫单例池) singletonObjects: 存放已经经历了完整生命周期的Bean对象. 这里的 bean 经历过 实例化->属性填充->初始化 以及各种后置处理

第二级缓存: earlySingletonObjects, 存放原始的 bean 对象（完成实例化但是尚未填充属性和初始化），仅仅能作为指针提前曝光，被其他 bean 所引用，用于解决循环依赖的

第三级缓存: Map<String, ObjectFactory<?>> singletonFactories, 在 bean 实例化完之后，属性填充以及初始化之前，如果允许提前曝光，Spring 会将实例化后的 bean 提前曝光，也就是把该 bean 转换成 beanFactory 并加入到 singletonFactories

>  A依赖B, B依赖A(都是单例)
>
> 1. 实例化A: 先去一级缓存中查找, 不存在再到二级缓存中找, 不存在再到三级缓存中找, 三级缓存不存在就创建Bean, 并将自己放到三级缓存里面, 然后populateBean,发现依赖B, 于是尝试去实例化B, 然后重复此步骤. 又发现需要依赖A, 但是此时可以再三级缓存中找到A
> 2. 找到了A然后把三级缓存里面的这个A放到二级缓存里面, 并删除三级缓存里面的A
> 3. B获得A的应用顺利初始化完毕, 将自己放到一级缓存里面(此时B里面的A依然是创建中状态) 然后回来接着创建A, 此时B已经创建结束, 直接从一级缓存里面拿到B, 然后完成创建, 并将A自己放到一级缓存里面



### @Autowired和@Resource的区别是什么？

@Autowired注解是按类型装配依赖对象，默认情况下它要求依赖对象必须存在，如果允许null值，可以设置它required属性为false。

@Resource注解和@Autowired一样，也可以标注在字段或属性的setter方法上，但它默认按名称装配。名称可以通过@Resource的name属性指定，如果没有指定name属性，当注解标注在字段上，即默认取字段的名称作为bean名称寻找依赖对象，当注解标注在属性的setter方法上，即默认取属性名作为bean名称寻找依赖对象。 

@Resources按名字，是ＪＤＫ的，@Autowired按类型，是Ｓｐｒｉｎｇ的。



# String常用方法

```
### length() 字符串的长度
### char charAt(int index)
###  getChars() 截取多个字符

void getChars(int sourceStart,int sourceEnd,char target[],int targetStart)
sourceStart指定了子串开始字符的下标，sourceEnd指定了子串结束后的下一个字符的下标。因此， 子串包含从sourceStart到sourceEnd-1的字符。接收字符的数组由target指定，target中开始复制子串的下标值是targetStart。
例：String s="this is a demo of the getChars method.";
char buf[]=new char[20];
s.getChars(10,14,buf,0);

### getBytes()
替代getChars()的一种方法是将字符存储在字节数组中，该方法即getBytes()。

### toCharArray() 
String转换成char数组

### equals()和equalsIgnoreCase() 
比较两个字符串

### regionMatches() 
用于比较一个字符串中特定区域与另一特定区域，它有一个重载的形式允许在比较中忽略大小写。

boolean regionMatches(int startIndex,String str2,int str2StartIndex,int numChars)
boolean regionMatches(boolean ignoreCase,int startIndex,String str2,int str2StartIndex,int numChars)

### startsWith()和endsWith() 
startsWith()方法决定是否以特定字符串开始，endWith()方法决定是否以特定字符串结束

### equals()和==
equals()方法比较字符串对象中的字符，==运算符比较两个对象是否引用同一实例。
例：String s1="Hello";
String s2=new String(s1);
s1.eauals(s2); //true
s1==s2;//false

### compareTo()和compareToIgnoreCase() 
比较字符串

### indexOf()和lastIndexOf()
indexOf() 查找字符或者子串第一次出现的地方。
lastIndexOf() 查找字符或者子串是后一次出现的地方。

### String[] split(String regex, int limit)
按照给定字符串进行分割成数组

### substring() 
它有两种形式，第一种是：String substring(int startIndex)

第二种是：String substring(int startIndex,int endIndex)

### concat() 
连接两个字符串

### replace() 替换
它有两种形式，第一种形式用一个字符在调用字符串中所有出现某个字符的地方进行替换，形式如下：
String replace(char original,char replacement)
例如：String s="Hello".replace('l','w');
第二种形式是用一个字符序列替换另一个字符序列，形式如下：
String replace(CharSequence original,CharSequence replacement)

### trim() 
去掉起始和结尾的空格

### valueOf()  static 
转换为字符串

### toLowerCase() // toUpperCase()
转换为大xiao写
```

### [如何正确的将数组转换为ArrayList?](https://snailclimb.gitee.io/javaguide/#/docs/java/basis/Java基础知识疑难点?id=_214-如何正确的将数组转换为arraylist)

```java
//最简便的方法(推荐)
List list = new ArrayList<>(Arrays.asList("a", "b", "c"))

// Java8 的Stream(推荐)
Integer [] myArray = { 1, 2, 3 };
List myList = Arrays.stream(myArray).collect(Collectors.toList());
//基本类型也可以实现转换（依赖boxed的装箱操作）
int [] myArray2 = { 1, 2, 3 };
List myList = Arrays.stream(myArray2).boxed().collect(Collectors.toList());

//Java9 的 List.of()方法/* 不支持基本数据类型 */
Integer[] array = {1, 2, 3};
List<Integer> list = List.of(array);
System.out.println(list); /* [1, 2, 3] */




```





# mysql



### *你一般怎么建索引的？*

(1)索引并非越多越好，大量的索引不仅占用磁盘空间，而且还会影响insert,delete,update等语句的性能

(2)避免对经常更新的表做更多的索引，并且索引中的列尽可能少；对经常用于查询的字段创建索引，避免添加不必要的索引

(3)数据量少的表尽量不要使用索引，由于数据较少，查询花费的时间可能比遍历索引的时间还要短，索引可能不会产生优化效果

(4)在条件表达式中经常用到不同值较多的列上创建索引，在不同值很少的列上不要建立索引。比如性别字段只有“男”“女”俩个值，就无需建立索引。如果建立了索引不但不会提升效率，反而严重减低数据的更新速度

(5)在频繁进行排序或者分组的列上建立索引，如果排序的列有多个，可以在这些列上建立联合索引。


### 讲讲索引的分类？你知道哪些？

从物理存储角度:
 聚簇索引和非聚簇索引
从数据结构角度:
 B+树索引、hash索引、FULLTEXT索引、R-Tree索引
从逻辑角度:

主键索引：主键索引是一种特殊的唯一索引，不允许有空值

普通索引或者单列索引

多列索引（复合索引）：复合索引指多个字段上创建的索引，只有在查询条件中使用了创建索引时的第一个字段，索引才会被使用。使用复合索引时遵循最左前缀集合

唯一索引或者非唯一索引

空间索引：空间索引是对空间数据类型的字段建立的索引，MYSQL中的空间数据类型有4种，分别是GEOMETRY、POINT、LINESTRING、POLYGON。




### 如何避免回表查询?什么是索引覆盖?

### 现在我有一个列，里头的数据都是唯一的，需要建一个索引，选唯一索引还是普通索引？

> **【强制】业务上具有唯一特性的字段，即使是多个字段的组合，也必须建成唯一索引**
>
> **说明：不要以为唯一索引影响了 insert 速度，这个速度损耗可以忽略，但提高查找速度是明显的；另外，即使在应用层做了非常完善的校验控制，只要没有唯一索引，根据墨菲定律，必然有脏数据产生。**//阿里巴巴JAVA开发规范

为什么唯一索引的插入速度比不上普通索引？为什么唯一索引的查找速度比普通索引快？

这个问题就要从Insert Buffer开始讲起了，在进行非聚簇索引的插入时，先判断插入的索引页是否在内存中。如果在，则直接插入；如果不在，则先放入Insert Buffer 中，然后再以一定频率和情况进行Insert Buffer和原数据页合并(merge)操作。

这么做的优点:能将多个插入合并到一个操作中，就大大提高了非聚簇索引的插入性能。

InnoDB 从 1.0.x 版本开始引入了 Change Buffer，可以算是对 Insert Buffer 的升级。从这个版本开始，InnoDB 存储引擎可以对 insert、delete、update 都进行缓存。

唯一速度的插入比普通索引慢的原因就是:

唯一索引无法利用Change Buffer

普通索引可以利用Change Buffer

于是乎下一问又来了！
为什么唯一索引的更新不使用 Change Buffer？
因为唯一索引为了保证唯一性，需要将数据页加载进内存才能判断是否违反唯一性约束。但是，既然数据页都加载到内存了，还不如直接更新内存中的数据页，没有必要再使用Change Buffer。

最后回答一下，唯一索引的搜索速度比普通索引快的原因就是:

普通索引在找到满足条件的第一条记录后，还需要判断下一条记录，直到第一个不满足条件的记录出现。

唯一索引在找到满足条件的第一条记录后，直接返回，不用判断下一条记录了。



### mysql索引是什么结构的？用红黑树可以么？

B+tree

和B-tree的区别?

为什么不用红黑树?

### mysql某表建了多个单索引，查询多个条件时如何走索引的？





