# 注解

| 注解名         | 作用                                                         | 作用于        |
| -------------- | ------------------------------------------------------------ | ------------- |
| @Component     | 将该类注入到spring容器成为bean                               | 类            |
| @Autowired     | 自动注入一个定义好的 bean到变量或属性                        | 属性,方法参数 |
| **@Qualifier** | @Qualifier("student1")定义别名和使用别名, 消除bean混乱       | 属性,方法参数 |
| @Configuration | 指示类声明一个或多个@Bean方法，并且可以由Spring容器处理      | 类            |
| @Bean          | 一个带有 @Bean 的注解方法将返回一个对象，该对象应该被注册为在 Spring 应用程序上下文中的 bean | 方法          |
| @Scope         | 定义bean的生命周期, singleton或prototype                     | 类或方法      |
| @Resource      |                                                              |               |
|                |                                                              |               |

