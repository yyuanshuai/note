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





负载均衡的几种方式

1. 轮询
2. 随机
3. 加权
4. 散列
5. 一致性哈希
6. 最小活跃数









