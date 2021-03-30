## [深究Spring中Bean的生命周期](https://www.cnblogs.com/javazhiyin/p/10905294.html)

**spring bean 生命周期**

1. 解析类得到BeanDefinition
2. 如果有多个构造方法, 则要推断构造方法
3. 确定好构造方法后, 进行实例化得到一个对象
4. 对对象中的加了@Autowired注解的属性进行属性填充
5. 回调aware方法, 比如BeanNameAware, BeanFactoryAware
6. 回调BeanPostProcessor的初始化前的方法
7. 调用初始化方法
8. 调用BeanPostProcessor的初始化后的方法, 在这里会进行AOP
9. 如果当前创建的Bean是单例的则会把bean放入单例池
10. 使用bean
11. spring容器关闭时调用DisposableBean中的destory()方法



#### spring4的aop打印顺序

```
环绕通知之前
@Before前置通知
方法调用
环绕通知之后
@After后置通知
@AfterReturning返回通知

方法异常情况: 
环绕通知之前
@Before前置通知
@After后置通知
@AfterThrowing异常通知
```

#### spring5的aop打印顺序(springboot 2.*)

```
环绕通知之前
@Before前置通知
方法调用
@AfterReturning返回通知
@After后置通知
环绕通知之后

方法异常情况: 
环绕通知之前
@Before前置通知
@AfterThrowing异常通知
@After后置通知
```



#### spring循环依赖

a依赖b,b依赖a

首先实例化a,查找一级缓存,二级缓存,三级缓存,都没有就创建Bean, 并放入三级缓存, 然后populateBean, 发现依赖b, 于是实例化b,查找一级缓存,二级缓存,三级缓存,都没有就创建Bean, 然后populateBean, 发现依赖a, 查找一级缓存,二级缓存,三级缓存中存在, 将a放入二级缓存, 并将a注入给b, b完成初始化放入一级缓存, 回来a继续populateBean, 将b注入a中



# 源码分析

```
ApplicationContext context = new ClassPathXmlApplicationContext("bean.xml");
```

分析上面这行语句做了什么?

```java
    public void refresh() throws BeansException, IllegalStateException {
        synchronized(this.startupShutdownMonitor) {
            this.prepareRefresh();//准备工作
            /**该方法做了哪些事
                记录开始刷新springioc容器的时间
                设置关闭和活跃标志位
                initPropertySources()
                获取环境对象校验xml配置文件
                初始化监听器以及需要发布事件的集合
            */
            ConfigurableListableBeanFactory beanFactory = this.obtainFreshBeanFactory();
            /**
            	
            */
            this.prepareBeanFactory(beanFactory);

            try {
                this.postProcessBeanFactory(beanFactory);
                this.invokeBeanFactoryPostProcessors(beanFactory);
                this.registerBeanPostProcessors(beanFactory);
                this.initMessageSource();
                this.initApplicationEventMulticaster();
                this.onRefresh();
                this.registerListeners();
                this.finishBeanFactoryInitialization(beanFactory);
                this.finishRefresh();
            } catch (BeansException var9) {
                if (this.logger.isWarnEnabled()) {
                    this.logger.warn("Exception encountered during context initialization - cancelling refresh attempt: " + var9);
                }

                this.destroyBeans();
                this.cancelRefresh(var9);
                throw var9;
            } finally {
                this.resetCommonCaches();
            }

        }
    }
```





### sping ioc原理

ioc容器启动流程

1. 读取Bean定义的信息, 定义Bean信息有XML, 注解两种方式, 以后可能会增加其他方式, 所以就可以定义一个BeanDefinitionReader接口, 使用实现这个接口的不同类, 来读取不同方式的Bean定义信息. 
2. 解析成BeanDefinition, 此时的BeanDefinition不是最终的BD, 会经过BeanFactoryPostProcessor处理成你想要的BeanDefinition, 比如修改属性值
3. 根据BeanDefinition进行实例化(反射), 此时属性还是默认值, 进行属性填充, populateBean(), 中间会设置一系列的Aware接口的属性, 以帮助自定义的Bean方便的获取容器对象, 比如给User实现ApplicationContextAware接口, 就可以给User这个Bean实现setApplicationContext()方法. 然后接下来还会对BeanDefinition进行BeanPostProcessor, postProcessBeforeInitialization(), postProcessAfterInitialization()两个方法增强(AOP), 比如@AutoWired注解, AutowiredAnnotationBeanPostProcessor, 比如@PostConstruct, @PreDestroy注解, CommonAnnotationBeanPostProcessor
4. 初始化完成
5. 放入容器(单例放入单例池也就是一级缓存)
6. bean随着容器销毁而销毁



#### spring aop原理





