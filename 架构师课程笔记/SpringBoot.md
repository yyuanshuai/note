

## 常用注解的使用

1. `@RestController` **将返回的对象数据直接以 JSON 或 XML 形式写入 HTTP 响应(Response)中。**绝大部分情况下都是直接以 JSON 形式返回给客户端，很少的情况下才会以 XML 形式返回。转换成 XML 形式还需要额为的工作，上面代码中演示的直接就是将对象数据直接以 JSON 形式写入 HTTP 响应(Response)中。关于`@Controller`和`@RestController` 的对比，我会在下一篇文章中单独介绍到（`@Controller` +`@ResponseBody`= `@RestController`）。
2. `@RequestMapping` :上面的示例中没有指定 GET 与 PUT、POST 等，因为**`@RequestMapping`默认映射所有HTTP Action**，你可以使用`@RequestMapping(method=ActionType)`来缩小这个映射。
3. `@PostMapping`实际上就等价于 `@RequestMapping(method = RequestMethod.POST)`，同样的 `@DeleteMapping` ,`@GetMapping`也都一样，常用的 HTTP Action 都有一个这种形式的注解所对应。
4. `@PathVariable` :取url地址中的参数。`@RequestParam `url的查询参数值。
5. `@RequestBody`:可以**将 \*HttpRequest\* body 中的 JSON 类型数据反序列化为合适的 Java 类型。**
6. `ResponseEntity`: **表示整个HTTP Response：状态码，标头和正文内容**。我们可以使用它来自定义HTTP Response 的内容。

```java
@RestController
@RequestMapping("/api")
public class BookController {

    private List<Book> books = new ArrayList<>();

    @PostMapping("/book")
    public ResponseEntity<List<Book>> addBook(@RequestBody Book book) {
        books.add(book);
        return ResponseEntity.ok(books);
    }

    @DeleteMapping("/book/{id}")
    public ResponseEntity deleteBookById(@PathVariable("id") int id) {
        books.remove(id);
        return ResponseEntity.ok(books);
    }

    @GetMapping("/book")
    public ResponseEntity getBookByName(@RequestParam("name") String name) {
        List<Book> results = books.stream().filter(book -> book.getName().equals(name)).collect(Collectors.toList());
        return ResponseEntity.ok(results);
    }
}
```

### Lombok注解说明

- `val`：用在局部变量前面，相当于将变量声明为final

- `@NonNull`：给方法参数增加这个注解会自动在方法内对该参数进行是否为空的校验，如果为空，则抛出NPE（NullPointerException）

- `@Cleanup`：自动管理资源，用在局部变量之前，在当前变量范围内即将执行完毕退出之前会自动清理资源，自动生成try-finally这样的代码来关闭流。虽然自JDK7以来，原生引入了try--with--resource结构，但还是不如@Cleanup来的简洁。

- `@Getter/@Setter`：用在属性上，再也不用自己手写setter和getter方法了，还可以指定访问范围

- `@ToString`：用在类上，可以自动覆写toString方法，当然还可以加其他参数，例如@ToString(exclude=”id”)排除id属性，或者@ToString(callSuper=true, includeFieldNames=true)调用父类的toString方法，包含所有属性

- `@EqualsAndHashCode`：用在类上，自动生成equals方法和hashCode方法

- `@NoArgsConstructor, @RequiredArgsConstructor and @AllArgsConstructor`：用在类上，自动生成无参构造和使用所有参数的构造函数以及把所有@NonNull属性作为参数的构造函数，如果指定staticName = “of”参数，同时还会生成一个返回类对象的静态工厂方法，比使用构造函数方便很多

- `@Data`：注解在类上，相当于同时使用了`@ToString`、`@EqualsAndHashCode`、`@Getter`、`@Setter`和`@RequiredArgsConstrutor`这些注解，对于`POJO类`十分有用

- `@Value`：用在类上，是@Data的不可变形式，相当于为属性添加final声明，只提供getter方法，而不提供setter方法

- `@Builder`：用在类、构造器、方法上，为你提供复杂的builder APIs，让你可以像如下方式一样调用`Person.builder().name("Adam Savage").city("San Francisco").job("Mythbusters").job("Unchained Reaction").build();`更多说明参考Builder

- `@SneakyThrows`：自动抛受检异常，而无需显式在方法上使用throws语句

- `@Synchronized`：用在方法上，将方法声明为同步的，并自动加锁，而锁对象是一个私有的属性`$lock`或`$LOCK`，而java中的synchronized关键字锁对象是this，锁在this或者自己的类对象上存在副作用，就是你不能阻止非受控代码去锁this或者类对象，这可能会导致竞争条件或者其它线程错误

- `@Getter(lazy=true)`：可以替代经典的Double Check Lock样板代码

- `@Log`：根据不同的注解生成不同类型的log对象，但是实例名称都是log，有六种可选实现类

- - `@CommonsLog` Creates log = org.apache.commons.logging.LogFactory.getLog(LogExample.class);
  - `@Log` Creates log = java.util.logging.Logger.getLogger(LogExample.class.getName());
  - `@Log4j` Creates log = org.apache.log4j.Logger.getLogger(LogExample.class);
  - `@Log4j2` Creates log = org.apache.logging.log4j.LogManager.getLogger(LogExample.class);
  - `@Slf4j` Creates log = org.slf4j.LoggerFactory.getLogger(LogExample.class);
  - `@XSlf4j` Creates log = org.slf4j.ext.XLoggerFactory.getXLogger(LogExample.class);

# 常用文件夹说明解释

**entity层**

别名： model层 ，domain层, PO

**mapper层**

别名： dao层

config

component

validator

repository

util

filter

authorization

handler

constant

api

exception

**1.PO(persistant object)** **持久对象** 

PO 就是对应数据库中某个表中的一条记录，多个记录可以用 PO 的集合。 PO 中应该不包 

含任何对数据库的操作。 

**2.DO****（****Domain Object****）领域对象** 

就是从现实世界中抽象出来的有形或无形的业务实体。 

**3.TO(Transfer Object)** **，数据传输对象** 

不同的应用程序之间传输的对象 

**4.DTO****（****Data Transfer Object****）数据传输对象** 

这个概念来源于 J2EE 的设计模式，原来的目的是为了 EJB 的分布式应用提供粗粒度的 

数据实体，以减少分布式调用的次数，从而提高分布式调用的性能和降低网络负载，但在这 

里，泛指用于展示层与服务层之间的数据传输对象。 

**5.VO(value object)** **值对象** 

通常用于业务层之间的数据传递，和 PO 一样也是仅仅包含数据而已。但应是抽象出 

的业务对象 , 可以和表对应 , 也可以不 , 这根据业务的需要 。用 new 关键字创建，由 

GC 回收的。 

View object：视图对象； 

接受页面传递来的数据，封装对象 

将业务处理完成的对象，封装成页面要用的数据 

**6.BO(business object)** **业务对象** 

从业务模型的角度看 , 见 UML 元件领域模型中的领域对象。封装业务逻辑的 java 对 

象 , 通过调用 DAO 方法 , 结合 PO,VO 进行业务操作。business object: 业务对象 主要作 

用是把业务逻辑封装为一个对象。这个对象可以包括一个或多个其它的对象。 比如一个简 

历，有教育经历、工作经历、社会关系等等。 我们可以把教育经历对应一个 PO ，工作经 

历对应一个 PO ，社会关系对应一个 PO 。 建立一个对应简历的 BO 对象处理简历，每 

个 BO 包含这些 PO 。 这样处理业务逻辑时，我们就可以针对 BO 去处理。

**7.POJO(plain ordinary java object)** **简单无规则** **java** **对象** 

传统意义的 java 对象。就是说在一些 Object/Relation Mapping 工具中，能够做到维护 

数据库表记录的 persisent object 完全是一个符合 Java Bean 规范的纯 Java 对象，没有增 

加别的属性和方法。我的理解就是最基本的 java Bean ，只有属性字段及 setter 和 getter 

方法！。

POJO 是 DO/DTO/BO/VO 的统称。 

**8.DAO(data access object)** **数据访问对象** 

是一个 sun 的一个标准 j2ee 设计模式， 这个模式中有个接口就是 DAO ，它负持久 

层的操作。为业务层提供接口。此对象用于访问数据库。通常和 PO 结合使用， DAO 中包 

含了各种数据库的操作方法。通过它的方法 , 结合 PO 对数据库进行相关的操作。夹在业 

务逻辑与数据库资源中间。配合 VO, 提供数据库的 CRUD 操作



# springboot引入依赖时,会自动配置