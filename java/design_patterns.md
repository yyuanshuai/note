# 六大原则

## 单一职责原则

对类来说的, 即一个类应该只负责一项职责. 如类A负责两个不同职责,当职责1需求变更而改变A是, 可能造成职责2执行错误, 所以需要将类A的粒度分解为A1, A2



## 接口隔离原则**（Interface Segregation Principle）**

如果B类和D类实现了Interface1接口, A类依赖(使用)B类的1,2,3方法.C类依赖(使用)B类的1,4,5方法. 则需要把Interface1接口拆开成3个接口, 这就是接口隔离原则.

```java
//这是不对的, 应该吧接口拆开成3个接口, (1)-(2,3)-(4,5)
public interface Interface1 {
    public void operation1();
    public void operation2();
    public void operation3();
    public void operation4();
    public void operation5();
}

class B implements Interface1{
    public void operation1(){
      System.out.printIn("B 实现了operation1");
    }
    public void operation2(){
      System.out.printIn("B 实现了operation2");
    }
    public void operation3(){
      System.out.printIn("B 实现了operation3");
    }
    public void operation4(){
      System.out.printIn("B 实现了operation4");
    }
    public void operation5(){
      System.out.printIn("B 实现了operation5");
    }
}

class D implements Interface1{
    public void operation1(){
      System.out.printIn("D 实现了operation1");
    }
    public void operation2(){
      System.out.printIn("D 实现了operation2");
    }
    public void operation3(){
      System.out.printIn("D 实现了operation3");
    }
    public void operation4(){
      System.out.printIn("D 实现了operation4");
    }
    public void operation5(){
      System.out.printIn("D 实现了operation5");
    }
}

class A {//A类通过接口Interface1依赖(使用)B类, 但是只会用到1,2,3方法
    public void depend1(Interface1 i){
      i.operation1();
    }
    public void depend2(Interface1 i){
      i.operation2();
    }
    public void depend3(Interface1 i){
      i.operation3();
    }
}
class C {//C类通过接口Interface1依赖(使用)D类, 但是只会用到1,4,5方法
    public void depend1(Interface1 i){
      i.operation1();
    }
    public void depend4(Interface1 i){
      i.operation4();
    }
    public void depend5(Interface1 i){
      i.operation5();
    }
}
```

```java
//拆开成3个接口, (1)-(2,3)-(4,5)
public interface Interface1 {
    public void operation1();
}
public interface Interface2 {
    public void operation2();
    public void operation3();
}
public interface Interface3 {
    public void operation4();
    public void operation5();
}

class B implements Interface1,Interface2 {
    public void operation1(){
      System.out.printIn("B 实现了operation1");
    }
    public void operation2(){
      System.out.printIn("B 实现了operation2");
    }
    public void operation3(){
      System.out.printIn("B 实现了operation3");
    }
}

class D implements Interface1,Interface3 {
    public void operation1(){
      System.out.printIn("D 实现了operation1");
    }
    public void operation4(){
      System.out.printIn("D 实现了operation4");
    }
    public void operation5(){
      System.out.printIn("D 实现了operation5");
    }
}

class A {//A类通过接口Interface1依赖(使用)B类, 但是只会用到1,2,3方法
    public void depend1(Interface1 i){
      i.operation1();
    }
    public void depend2(Interface1 i){
      i.operation2();
    }
    public void depend3(Interface1 i){
      i.operation3();
    }
}
class C {//C类通过接口Interface1依赖(使用)D类, 但是只会用到1,4,5方法
    public void depend1(Interface1 i){
      i.operation1();
    }
    public void depend4(Interface1 i){
      i.operation4();
    }
    public void depend5(Interface1 i){
      i.operation5();
    }
}
```



## 依赖倒转原则**（Dependence Inversion Principle）**

高层模块不应该依赖底层模块, 二者都应该依赖其抽象 

抽象不应该依赖细节, 细节应该依赖抽象 

依赖倒转的中心思想是面向接口编程

1. 通过方法传递(以下例子使用)//person.receive(new Email());
2. 通过构造方法传递//Person person = new Person(new Email());
3. 通过setter方法传递//person.setxxx(new Email())

```java
//违反依赖倒转原则
pulic class DependecyInversion(){
  public static void main(String[] args){
    Person person = new Person();
    person.receive(new Email());
  }
}

class Email {
  public String getInfo() {
    return "电子邮件信息: hello,world";
  }
}
class Person {
  public void receive (Email email){
    System.out.printIn(email.getInfo());
  }
}
```

```java
//符合依赖倒转原则
pulic class DependecyInversion(){
  public static void main(String[] args){
    Person person = new Person();
    person.receive(new Email());
    person.receive(new Weixin());
  }
}
interface IReceiver {
  public String getInfo();
}
class Email implements IReceiver {
  public String getInfo() {
    return "电子邮件信息: hello,world";
  }
}
class Weixin implements IReceiver {
  public String getInfo() {
    return "wx信息: hello,world";
  }
}
class Person {
  public void receive (IReceiver receiver){
    System.out.printIn(receiver.getInfo());
  }
}
```

## 里式替换原则**（Liskov Substitution Principle）**

定义:

里氏代换原则(Liskov Substitution Principle LSP)面向对象设计的基本原则之一。 里氏代换原则中说，任何基类可以出现的地方，子类一定可以出现。 LSP是继承复用的基石，只有当衍生类可以替换掉基类，软件单位的功能不受到影响时，基类才能真正被复用，而衍生类也能够在基类的基础上增加新的行为。里氏代换原则是对“开-闭”原则的补充。实现“开-闭”原则的关键步骤就是抽象化。而基类与子类的继承关系就是抽象化的具体实现，所以里氏代换原则是对实现抽象化的具体步骤的规范。

```java
public class Liskov{
	public static void main(String[] args){
    A a = new A();
    System.out.println("11-3="+a.func1(11,3));    
    System.out.println("1-8="+a.func1(1,8));

		System.out.println("---------------");
    
    B b = new B();
    System.out.println("11-3="+b.func1(11,3));//这里本意是求出11-3
    System.out.println("1-8="+b.func1(1,8));//1-8
    System.out.println("11+3+9="+b.func2(11,3));
    
  }
}
class A{
  public int func1(int num1 , int num2){
    return num1 - num2;
  }
}
class B extends A{
  //这里重写了A类的方法, 可能是无意识的
  public int func1(int a , int b){
    return a + b;
  }
  public int func2(int a , int b){
    return func1(a, b) + 9;
  }
}
```

**通用的做法是: 原来的父类和子类都继承一个更通俗的基类, 原有的继承关系去掉,采用依赖,聚合,组合灯关系代替**

````java
public class Liskov{
	public static void main(String[] args){
    A a = new A();
    System.out.println("11-3="+a.func1(11,3));    
    System.out.println("1-8="+a.func1(1,8));

		System.out.println("---------------");
    
    B b = new B();
    System.out.println("11+3="+b.func1(11,3));//这里本意是求出11+3
    System.out.println("1+8="+b.func1(1,8));//1+8
    System.out.println("11+3+9="+b.func2(11,3));
    //使用组合任然可以使用到A类相关方法
    System.out.println("11-3="+b.func3(11,3));//这里本意是求出11-3
  }
}
class Base{
  public int func1(int num1 , int num2);
}
class A extends Base{
  public int func1(int num1 , int num2){
    return num1 - num2;
  }
}
class B extends Base{
  private A a = new A();
  
  //这里重写了A类的方法, 可能是无意识的
  public int func1(int a , int b){
    return a + b;
  }
  
  public int func2(int a , int b){
    return func1(a, b) + 9;
  }
  
  public int func3(int a , int b){
    return this.a.func1(a, b);
  }
}
````

## 开闭原则**（Open Close Principle）**

开闭原则的意思是：**对扩展开放，对修改关闭**。在程序需要进行拓展的时候，不能去修改原有的代码，实现一个热插拔的效果。简言之，是为了使程序的扩展性好，易于维护和升级。想要达到这样的效果，我们需要使用接口和抽象类，后面的具体设计中我们会提到这点。

1. 是编程中最基础.最重要的设计原则
2. 一个软件实体如类,模块和函数对扩展开放(对提供方),对修改关闭(对使用方). 用抽象构建框架,用实现扩展细节.
3. 当软件需要变化时, 尽量通过扩展软件实体的行为来实现变化, 而不是通过修改已有的代码来实现变化.
4. **编程中遵循其他原则, 以及使用设计模式的目的就是遵循开闭原则**

```java
//违反开闭原则的例子
public class Ocp {
  public static void main (String[] args) {
    GraphicEditor graphicEditor = new GraphicEditor();
    graphicEditor.drawRectangle(new Rectangle());
    graphicEditor.drawCircla(new Circle());
    graphicEditor.drawTriangle(new Triangle());
  }
}

//这是一个用于绘图的类(使用方)
class GraphicEditor{
  public void drawShape(Shape s){
    if(s.m_type == 1){
      drawRectangle(s);
    }else if(s.m_type == 2){
      drawCircle(s);
    }else if(s.m_type == 3){
      drawTriangle(s);
    }
  }
  
  public void drawRectangle(Shape r){
    System.out.println("绘制矩形");
  }
  public void drawCircla(Shape r){
    System.out.println("绘制圆形");
  }
  public void drawTriangle(Shape r){
    System.out.println("绘制三角形");
  }
}

class Shape{
  int m_type;
}
class Rectangle extends Shape{
  Rectangle(){
    super.m_type = 1;
  }
}
class Circle extends Shape{
  Circle(){
    super.m_type = 2;
  }
}
class Triangle extends Shape{
  Triangle(){
    super.m_type = 3;
  }
}
```

改进思路: 把创建的Shape类做成抽象类看, 并提供一个抽象的draw方法, 让子类去实现即可, 这样我们有新的图形种类是,只需要让新的图形类继承Shape,并实现draw方法即可,使用方的代码就不需要修改->满足开闭原则

```java
//符合开闭原则的例子
public class Ocp {
  public static void main (String[] args) {
    GraphicEditor graphicEditor = new GraphicEditor();
    graphicEditor.drawShape(new Rectangle());
    graphicEditor.drawShape(new Circle());
    graphicEditor.drawShape(new Triangle());
    graphicEditor.drawShape(new OtherGraphic());
  }
}

//这是一个用于绘图的类
class GraphicEditor{
  public void drawShape(Shape s){
      s.draw();
  }
}

abstract class Shape{
  int m_type;
  public abstarct void draw();
}
class Rectangle extends Shape{
  Rectangle(){
    super.m_type = 1;
  }
  @Override
  public void draw(){
    System.out.println("绘制矩形");
  }
}
class Circle extends Shape{
  Circle(){
    super.m_type = 2;
  }
  @Override
  public void draw(){
    System.out.println("绘制圆形");
  }
}
class Triangle extends Shape{
  Triangle(){
    super.m_type = 3;
  }
  @Override
  public void draw(){
    System.out.println("绘制三角形");
  }
}
```



## 迪米特法则，**又称最少知道原则（Demeter Principle）**

最少知道原则是指：一个实体应当尽量少地与其他实体之间发生相互作用，使得系统功能模块相对独立。

## 合成复用原则**（Composite Reuse Principle）**

合成复用原则是指：尽量使用合成/聚合的方式，而不是使用继承。



# 23种设计模式

## 创建型

这些设计模式提供了一种在创建对象的同时隐藏创建逻辑的方式，而不是使用 new 运算符直接实例化对象。这使得程序在判断针对某个给定实例需要创建哪些对象时更加灵活。

### 单例模式

#### 饿汉式

这种方式基于 classloader机制避免了多线程的同步问题,不过, Instance在类装载时就实例化,在单例模式中大多数都是调用 getinstance方法,但是导致类装载的原因有很多种,因此不能确定有其他的方式(或者其他的静态方法)导致类装载,这时候初始化 Instance就没有达到 lazy loading的效果

**优点**：写法简单, 避免了线程同步问题

**缺点**：在类装载的时候就完成实例化, 没有达到懒加载的效果. 可能造成内存浪费

``` java
class Singleton {
  private final static Singleton instance = new Singleton();
  private Singleton (){};
  public static Singleton getInstance(){
      return instance;
  }
}
```

#### 懒汉式(线程不安全)

**描述：**这种方式是最基本的实现方式，这种实现最大的问题就是不支持多线程。因为没有加锁 synchronized，所以严格意义上它并不算单例模式。
这种方式 lazy loading 很明显，不要求线程安全，在多线程不能正常工作。

``` java
public class Singleton {  
    private static Singleton instance;  
    private Singleton (){}  
    public static Singleton getInstance() {  
      if (instance == null) {  
          instance = new Singleton();  
      }  
    	return instance;  
    }  
}
```

#### 懒汉式(线程安全)

**描述：**这种方式具备很好的 lazy loading，能够在多线程中很好的工作，但是，效率很低，99% 情况下不需要同步。
**优点**：第一次调用才初始化，避免内存浪费。
**缺点**：必须加锁 synchronized 才能保证单例，但加锁会影响效率。
getInstance() 的性能对应用程序不是很关键（该方法使用不太频繁）。

``` java
public class Singleton {  
    private static Singleton instance;
    private Singleton (){}
    public static synchronized Singleton getInstance() {  
      if (instance == null) {  
          instance = new Singleton();  
      }  
      return instance;  
    }  
}
```



#### 双检锁/双重校验锁（DCL，即 double-checked locking）

**JDK 版本：**JDK1.5 起

**是否 Lazy 初始化：**是

**是否多线程安全：**是

**实现难度：**较复杂

**描述：**这种方式采用双锁机制，安全且在多线程情况下能保持高性能。
getInstance() 的性能对应用程序很关键。

``` java
public class Singleton {
    private volatile static Singleton instance;
    private Singleton (){}  
    public static Singleton getInstance() {  
        if (singleton == null) {  
            synchronized (Singleton.class) {  
                if (instance == null) {  
                    instance = new Singleton();  
                }  
            }  
        }  
        return singleton;  
    }  
}
```



#### 静态内部类

**是否 Lazy 初始化：**是

**是否多线程安全：**是

**实现难度：**一般

**描述：**这种方式能达到双检锁方式一样的功效，但实现更简单。对静态域使用延迟初始化，应使用这种方式而不是双检锁方式。这种方式只适用于静态域的情况，双检锁方式可在实例域需要延迟初始化时使用。
这种方式同样利用了 classloader 机制来保证初始化 instance 时只有一个线程，它跟第 3 种方式不同的是：第 3 种方式只要 Singleton 类被装载了，那么 instance 就会被实例化（没有达到 lazy loading 效果），而这种方式是 Singleton 类被装载了，instance **不一定被初始化**。因为 SingletonHolder 类没有被主动使用，只有通过显式调用 getInstance 方法时，才会显式装载 SingletonHolder 类，从而实例化 instance。想象一下，如果实例化 instance 很消耗资源，所以想让它延迟加载，另外一方面，又不希望在 Singleton 类加载时就实例化，因为不能确保 Singleton 类还可能在其他的地方被主动使用从而被加载，那么这个时候实例化 instance 显然是不合适的。这个时候，这种方式相比第 3 种方式就显得很合理。应用程序很关键。

**这种方式同样利用了 classloader 机制来保证初始化 instance 时只有一个线程**

**静态内部类方式在Singleton类被装载时并不会立即实例化, 而是在需要实例化时, 调用getInstance方法,才会装载SingletonHolder类,从而完成Singleton的实例化**

**类的静态属性只会在第一次加载类的时候初始化,jvm帮助我们保证了线程的安全性**

外部类的装载不会导致静态内部类的装载

``` java
public class Singleton {  
  	private Singleton (){}
    private static class SingletonHolder {
      	//装载时并不会马上被初始化,只有通过显式调用 getInstance 方法时，才会显式装载 SingletonHolder 类，从而实例化 instance
    		private static final Singleton INSTANCE = new Singleton();  
    }
    public static final Singleton getInstance() {  
    		return SingletonHolder.INSTANCE;  
    }  
}
```



#### 枚举

**JDK 版本：**JDK1.5 起

**是否 Lazy 初始化：**否

**是否多线程安全：**是

**实现难度：**易

**描述：**这种实现方式还没有被广泛采用，但这是实现单例模式的最佳方法。它更简洁，自动支持序列化机制，绝对防止多次实例化。
这种方式是 Effective Java 作者 Josh Bloch 提倡的方式，它不仅能避免多线程同步问题，而且还自动支持序列化机制，防止反序列化重新创建新的对象，绝对防止多次实例化。不过，由于 JDK1.5 之后才加入 enum 特性，用这种方式写不免让人感觉生疏，在实际工作中，也很少用。
不能通过 reflection attack 来调用私有构造方法。

``` java
public enum Singleton {  
    INSTANCE;  
    public void whateverMethod() {  
    }  
}
```



### 工厂模式

**意图：**定义一个创建对象的接口，让其子类自己决定实例化哪一个工厂类，工厂模式使其创建过程延迟到子类进行。

**主要解决：**主要解决接口选择的问题。

**何时使用：**我们明确地计划不同条件下创建不同实例时。

**如何解决：**让其子类实现工厂接口，返回的也是一个抽象的产品。

**关键代码：**创建过程在其子类执行。

**应用实例：** 1、您需要一辆汽车，可以直接从工厂里面提货，而不用去管这辆汽车是怎么做出来的，以及这个汽车里面的具体实现。 2、Hibernate 换数据库只需换方言和驱动就可以。

**优点：** 1、一个调用者想创建一个对象，只要知道其名称就可以了。 2、扩展性高，如果想增加一个产品，只要扩展一个工厂类就可以。 3、屏蔽产品的具体实现，调用者只关心产品的接口。

**缺点：**每次增加一个产品时，都需要增加一个具体类和对象实现工厂，使得系统中类的个数成倍增加，在一定程度上增加了系统的复杂度，同时也增加了系统具体类的依赖。这并不是什么好事。

**使用场景：** 1、日志记录器：记录可能记录到本地硬盘、系统事件、远程服务器等，用户可以选择记录日志到什么地方。 2、数据库访问，当用户不知道最后系统采用哪一类数据库，以及数据库可能有变化时。 3、设计一个连接服务器的框架，需要三个协议，"POP3"、"IMAP"、"HTTP"，可以把这三个作为产品类，共同实现一个接口。

**注意事项：**作为一种创建类模式，在任何需要生成复杂对象的地方，都可以使用工厂方法模式。有一点需要注意的地方就是复杂对象适合使用工厂模式，而简单对象，特别是只需要通过 new 就可以完成创建的对象，无需使用工厂模式。如果使用工厂模式，就需要引入一个工厂类，会增加系统的复杂度。

```java
//创建一个接口:
public interface Shape {
   void draw();
}
//创建实现接口的实体类。
public class Rectangle implements Shape {
 
   @Override
   public void draw() {
      System.out.println("Inside Rectangle::draw() method.");
   }
}
public class Square implements Shape {
 
   @Override
   public void draw() {
      System.out.println("Inside Square::draw() method.");
   }
}
public class Circle implements Shape {
 
   @Override
   public void draw() {
      System.out.println("Inside Circle::draw() method.");
   }
}
//创建一个工厂，生成基于给定信息的实体类的对象。
public class ShapeFactory {
    
   //使用 getShape 方法获取形状类型的对象
   public Shape getShape(String shapeType){
      if(shapeType == null){
         return null;
      }        
      if(shapeType.equalsIgnoreCase("CIRCLE")){
         return new Circle();
      } else if(shapeType.equalsIgnoreCase("RECTANGLE")){
         return new Rectangle();
      } else if(shapeType.equalsIgnoreCase("SQUARE")){
         return new Square();
      }
      return null;
   }
}
//使用该工厂，通过传递类型信息来获取实体类的对象。
public class FactoryPatternDemo {
 
   public static void main(String[] args) {
      ShapeFactory shapeFactory = new ShapeFactory();
 
      //获取 Circle 的对象，并调用它的 draw 方法
      Shape shape1 = shapeFactory.getShape("CIRCLE");
 
      //调用 Circle 的 draw 方法
      shape1.draw();
 
      //获取 Rectangle 的对象，并调用它的 draw 方法
      Shape shape2 = shapeFactory.getShape("RECTANGLE");
 
      //调用 Rectangle 的 draw 方法
      shape2.draw();
 
      //获取 Square 的对象，并调用它的 draw 方法
      Shape shape3 = shapeFactory.getShape("SQUARE");
 
      //调用 Square 的 draw 方法
      shape3.draw();
   }
}
```



### 抽象工厂模式

**意图：**提供一个创建一系列相关或相互依赖对象的接口，而无需指定它们具体的类。

**主要解决：**主要解决接口选择的问题。

**何时使用：**系统的产品有多于一个的产品族，而系统只消费其中某一族的产品。

**如何解决：**在一个产品族里面，定义多个产品。

**关键代码：**在一个工厂里聚合多个同类产品。

**应用实例：**工作了，为了参加一些聚会，肯定有两套或多套衣服吧，比如说有商务装（成套，一系列具体产品）、时尚装（成套，一系列具体产品），甚至对于一个家庭来说，可能有商务女装、商务男装、时尚女装、时尚男装，这些也都是成套的，即一系列具体产品。假设一种情况（现实中是不存在的，要不然，没法进入共产主义了，但有利于说明抽象工厂模式），在您的家中，某一个衣柜（具体工厂）只能存放某一种这样的衣服（成套，一系列具体产品），每次拿这种成套的衣服时也自然要从这个衣柜中取出了。用 OOP 的思想去理解，所有的衣柜（具体工厂）都是衣柜类的（抽象工厂）某一个，而每一件成套的衣服又包括具体的上衣（某一具体产品），裤子（某一具体产品），这些具体的上衣其实也都是上衣（抽象产品），具体的裤子也都是裤子（另一个抽象产品）。

**优点：**当一个产品族中的多个对象被设计成一起工作时，它能保证客户端始终只使用同一个产品族中的对象。

**缺点：**产品族扩展非常困难，要增加一个系列的某一产品，既要在抽象的 Creator 里加代码，又要在具体的里面加代码。

**使用场景：** 1、QQ 换皮肤，一整套一起换。 2、生成不同操作系统的程序。

**注意事项：**产品族难扩展，产品等级易扩展。

```java
public interface Shape {
   void draw();
}
public class Rectangle implements Shape {
 
   @Override
   public void draw() {
      System.out.println("Inside Rectangle::draw() method.");
   }
}
public class Square implements Shape {
 
   @Override
   public void draw() {
      System.out.println("Inside Square::draw() method.");
   }
}
public class Circle implements Shape {
 
   @Override
   public void draw() {
      System.out.println("Inside Circle::draw() method.");
   }
}
public interface Color {
   void fill();
}
public class Red implements Color {
 
   @Override
   public void fill() {
      System.out.println("Inside Red::fill() method.");
   }
}
public class Green implements Color {
 
   @Override
   public void fill() {
      System.out.println("Inside Green::fill() method.");
   }
}
public class Blue implements Color {
 
   @Override
   public void fill() {
      System.out.println("Inside Blue::fill() method.");
   }
}
public abstract class AbstractFactory {
   public abstract Color getColor(String color);
   public abstract Shape getShape(String shape) ;
}
public class ShapeFactory extends AbstractFactory {
    
   @Override
   public Shape getShape(String shapeType){
      if(shapeType == null){
         return null;
      }        
      if(shapeType.equalsIgnoreCase("CIRCLE")){
         return new Circle();
      } else if(shapeType.equalsIgnoreCase("RECTANGLE")){
         return new Rectangle();
      } else if(shapeType.equalsIgnoreCase("SQUARE")){
         return new Square();
      }
      return null;
   }
   
   @Override
   public Color getColor(String color) {
      return null;
   }
}
public class ColorFactory extends AbstractFactory {
    
   @Override
   public Shape getShape(String shapeType){
      return null;
   }
   
   @Override
   public Color getColor(String color) {
      if(color == null){
         return null;
      }        
      if(color.equalsIgnoreCase("RED")){
         return new Red();
      } else if(color.equalsIgnoreCase("GREEN")){
         return new Green();
      } else if(color.equalsIgnoreCase("BLUE")){
         return new Blue();
      }
      return null;
   }
}
public class FactoryProducer {
   public static AbstractFactory getFactory(String choice){
      if(choice.equalsIgnoreCase("SHAPE")){
         return new ShapeFactory();
      } else if(choice.equalsIgnoreCase("COLOR")){
         return new ColorFactory();
      }
      return null;
   }
}
public class AbstractFactoryPatternDemo {
   public static void main(String[] args) {
 
      //获取形状工厂
      AbstractFactory shapeFactory = FactoryProducer.getFactory("SHAPE");
 
      //获取形状为 Circle 的对象
      Shape shape1 = shapeFactory.getShape("CIRCLE");
 
      //调用 Circle 的 draw 方法
      shape1.draw();
 
      //获取形状为 Rectangle 的对象
      Shape shape2 = shapeFactory.getShape("RECTANGLE");
 
      //调用 Rectangle 的 draw 方法
      shape2.draw();
      
      //获取形状为 Square 的对象
      Shape shape3 = shapeFactory.getShape("SQUARE");
 
      //调用 Square 的 draw 方法
      shape3.draw();
 
      //获取颜色工厂
      AbstractFactory colorFactory = FactoryProducer.getFactory("COLOR");
 
      //获取颜色为 Red 的对象
      Color color1 = colorFactory.getColor("RED");
 
      //调用 Red 的 fill 方法
      color1.fill();
 
      //获取颜色为 Green 的对象
      Color color2 = colorFactory.getColor("Green");
 
      //调用 Green 的 fill 方法
      color2.fill();
 
      //获取颜色为 Blue 的对象
      Color color3 = colorFactory.getColor("BLUE");
 
      //调用 Blue 的 fill 方法
      color3.fill();
   }
}
```



### 原型模式

**意图：**用原型实例指定创建对象的种类，并且通过拷贝这些原型创建新的对象。

**主要解决：**在运行期建立和删除原型。

**何时使用：** 1、当一个系统应该独立于它的产品创建，构成和表示时。 2、当要实例化的类是在运行时刻指定时，例如，通过动态装载。 3、为了避免创建一个与产品类层次平行的工厂类层次时。 4、当一个类的实例只能有几个不同状态组合中的一种时。建立相应数目的原型并克隆它们可能比每次用合适的状态手工实例化该类更方便一些。

**如何解决：**利用已有的一个原型对象，快速地生成和原型对象一样的实例。

**关键代码：** 1、实现克隆操作，在 JAVA 继承 Cloneable，重写 clone()，在 .NET 中可以使用 Object 类的 MemberwiseClone() 方法来实现对象的浅拷贝或通过序列化的方式来实现深拷贝。 2、原型模式同样用于隔离类对象的使用者和具体类型（易变类）之间的耦合关系，它同样要求这些"易变类"拥有稳定的接口。

**应用实例：** 1、细胞分裂。 2、JAVA 中的 Object clone() 方法。

**优点：** 1、性能提高。 2、逃避构造函数的约束。

**缺点：** 1、配备克隆方法需要对类的功能进行通盘考虑，这对于全新的类不是很难，但对于已有的类不一定很容易，特别当一个类引用不支持串行化的间接对象，或者引用含有循环结构的时候。 2、必须实现 Cloneable 接口。

**使用场景：** 1、资源优化场景。 2、类初始化需要消化非常多的资源，这个资源包括数据、硬件资源等。 3、性能和安全要求的场景。 4、通过 new 产生一个对象需要非常繁琐的数据准备或访问权限，则可以使用原型模式。 5、一个对象多个修改者的场景。 6、一个对象需要提供给其他对象访问，而且各个调用者可能都需要修改其值时，可以考虑使用原型模式拷贝多个对象供调用者使用。 7、在实际项目中，原型模式很少单独出现，一般是和工厂方法模式一起出现，通过 clone 的方法创建一个对象，然后由工厂方法提供给调用者。原型模式已经与 Java 融为浑然一体，大家可以随手拿来使用。

**注意事项：**与通过对一个类进行实例化来构造新对象不同的是，原型模式是通过拷贝一个现有对象生成新对象的。浅拷贝实现 Cloneable，重写，深拷贝是通过实现 Serializable 读取二进制流。

## 行为型

### 策略模式

**意图：**定义一系列的算法,把它们一个个封装起来, 并且使它们可相互替换。

**主要解决：**在有多种算法相似的情况下，使用 if...else 所带来的复杂和难以维护。

**何时使用：**一个系统有许多许多类，而区分它们的只是他们直接的行为。

**如何解决：**将这些算法封装成一个一个的类，任意地替换。

**关键代码：**实现同一个接口。

**应用实例：** 1、诸葛亮的锦囊妙计，每一个锦囊就是一个策略。 2、旅行的出游方式，选择骑自行车、坐汽车，每一种旅行方式都是一个策略。 3、JAVA AWT 中的 LayoutManager。

**优点：** 1、算法可以自由切换。 2、避免使用多重条件判断。 3、扩展性良好。

**缺点：** 1、策略类会增多。 2、所有策略类都需要对外暴露。

**使用场景：** 1、如果在一个系统里面有许多类，它们之间的区别仅在于它们的行为，那么使用策略模式可以动态地让一个对象在许多行为中选择一种行为。 2、一个系统需要动态地在几种算法中选择一种。 3、如果一个对象有很多的行为，如果不用恰当的模式，这些行为就只好使用多重的条件选择语句来实现。

**注意事项：**如果一个系统的策略多于四个，就需要考虑使用混合模式，解决策略类膨胀的问题。

```java
//创建一个策略接口。
public interface Strategy {
   public int doOperation(int num1, int num2);
}
//创建实现接口的实体类。
public class OperationAdd implements Strategy{
   @Override
   public int doOperation(int num1, int num2) {
      return num1 + num2;
   }
}
public class OperationSubtract implements Strategy{
   @Override
   public int doOperation(int num1, int num2) {
      return num1 - num2;
   }
}
public class OperationMultiply implements Strategy{
   @Override
   public int doOperation(int num1, int num2) {
      return num1 * num2;
   }
}
//创建 Context 类。
public class Context {
   private Strategy strategy;
 
   public Context(Strategy strategy){
      this.strategy = strategy;
   }
 
   public int executeStrategy(int num1, int num2){
      return strategy.doOperation(num1, num2);
   }
}
//使用 Context 来查看当它改变策略 Strategy 时的行为变化。
public class StrategyPatternDemo {
   public static void main(String[] args) {
      Context context = new Context(new OperationAdd());    
      System.out.println("10 + 5 = " + context.executeStrategy(10, 5));
 
      context = new Context(new OperationSubtract());      
      System.out.println("10 - 5 = " + context.executeStrategy(10, 5));
 
      context = new Context(new OperationMultiply());    
      System.out.println("10 * 5 = " + context.executeStrategy(10, 5));
   }
}
/**
10 + 5 = 15
10 - 5 = 5
10 * 5 = 50
**/
```











