**Spring Cloud技术点**

Eureka：服务注册与发现，用于服务管理。

Feign： web调用客户端，能够简化HTTP接口的调用。

Ribbon：基于客户端的负载均衡。

Hystrix：熔断降级，防止服务雪崩。

Zuul：网关路由，提供路由转发、请求过滤、限流降级等功能。

Config：配置中心，分布式配置管理。

Sleuth：服务链路追踪

Admin：健康管理



# 服务进化概述

1. 传统服务到微服务进化。

   > 《传统到分布式演进》

2. 单体应用-> SOA ->微服务（下面讲）

```
课外扩展：
持续集成，持续部署，持续交付。
集成:是指软件个人研发的部分向软件整体部分集成，以便尽早发现个人开发部分的问题；
部署: 是代码尽快向可运行的开发/测试节交付，以便尽早测试；
交付: 是指研发尽快向客户交付，以便尽早发现生产环境中存在的问题。
   如果说等到所有东西都完成了才向下个环节交付，导致所有的问题只能在最后才爆发出来，解决成本巨大甚至无法解决。而所谓的持续，就是说每完成一个完整的部分，就向下个环节交付，发现问题可以马上调整。使问题不会放大到其他部分和后面的环节。
   这种做法的核心思想在于：既然事实上难以做到事先完全了解完整的、正确的需求，那么就干脆一小块一小块的做，并且加快交付的速度和频率，使得交付物尽早在下个环节得到验证。早发现问题早返工。

上面的3个持续，也都随着微服务的发展而发展，当架构师的同学，可以参考这种方式。

持续集成的工具，向大家推荐：https://jenkins.io/doc/book/pipeline/
```



## 单体应用

1. 概念：所有功能全部打包在一起。应用大部分是一个war包或jar包。我参与网约车最开始架构是：一个乘客项目中有 用户、订单、消息、地图等功能。随着业务发展，功能增多，这个项目会越来越臃肿。

2. 好处：容易开发、测试、部署，适合项目初期试错。

3. 坏处：

   ​	随着项目越来越复杂，团队不断扩大。坏处就显现出来了。

   - 复杂性高：代码多，十万行，百万行级别。加一个小功能，会带来其他功能的隐患，因为它们在一起。
   - 技术债务：人员流动，不坏不修，因为不敢修。
   - 持续部署困难：由于是全量应用，改一个小功能，全部部署，会导致无关的功能暂停使用。编译部署上线耗时长，不敢随便部署，导致部署频率低，进而又导致两次部署之间 功能修改多，越不敢部署，恶性循环。
   - 可靠性差：某个小问题，比如小功能出现OOM，会导致整个应用崩溃。
   - 扩展受限：只能整体扩展，无法按照需要进行扩展，  不能根据计算密集型（派单系统）和IO密集型（文件服务） 进行合适的区分。
   - 阻碍创新：单体应用是以一种技术解决所有问题，不容易引入新技术。但在高速的互联网发展过程中，适应的潮流是：用合适的语言做合适的事情。比如在单体应用中，一个项目用spring MVC，想换成spring boot，切换成本很高，因为有可能10万，百万行代码都要改，而微服务可以轻松切换，因为每个服务，功能简单，代码少。

## SOA

   	对单体应用的改进：引入SOA（Service-Oriented Architecture）面向服务架构，拆分系统，用服务的流程化来实现业务的灵活性。服务间需要某些方法进行连接，面向接口等，它是一种设计方法，其中包含多个服务， 服务之间通过相互依赖最终提供一系列的功能。一个服务 通常以独立的形式存在于操作系统进程中。各个服务之间 通过网络调用。但是还是需要用些方法来进行服务组合，有可能还是个单体应用。



所以要引入微服务，是SOA思想的一种具体实践。

微服务架构 = 80%的SOA服务架构思想 + 100%的组件化架构思想

# 微服务

## 微服务概况

- 无严格定义。
- 微服务是一种架构风格，将单体应用划分为小型的服务单元。
- 微服务架构是一种使用一系列粒度较小的服务来开发单个应用的方式；每个服务运行在自己的进程中；服务间采用轻量级的方式进行通信(通常是HTTP API)；这些服务是基于业务逻辑和范围，通过自动化部署的机制来独立部署的，并且服务的集中管理应该是最低限度的，即每个服务可以采用不同的编程语言编写，使用不同的数据存储技术。
- 英文定义：

```sh
看这篇文章：
http://www.martinfowler.com/articles/microservices.html
```

- 小类比

  合久必分。分开后通信，独立部署，独立存储。

```sh
分封制：
服从天子命令：服从服务管理。
有为天子镇守疆土的义务：各自完成各自的一块业务。
随从作战：服务调用。
交纳贡献：分担流量压力。
```

- 段子（中台战略）

```
Q：大师大师，服务拆多了怎么办？
A：那就再合起来。
Q：那太没面子了。
A：那就说跨过了微服务初级阶段，在做中台。
```



## 微服务特性

独立运行在自己进程中。

一系列独立服务共同构建起整个系统。

一个服务只关注自己的独立业务。

轻量的通信机制RESTful API

使用不同语言开发

全自动部署机制

## 微服务组件介绍

不局限与具体的微服务实现技术。

- 服务注册与发现：服务提供方将己方调用地址注册到服务注册中心，让服务调用方能够方便地找到自己；服务调用方从服务注册中心找到自己需要调用的服务的地址。

- 负载均衡：服务提供方一般以多实例的形式提供服务，负载均衡功能能够让服务调用方连接到合适的服务节点。并且，服务节点选择的过程对服务调用方来说是透明的。

- 服务网关：服务网关是服务调用的唯一入口，可以在这个组件中实现用户鉴权、动态路由、灰度发布、A/B测试、负载限流等功能。

  ```
  灰度发布（又名金丝雀发布）是指在黑与白之间，能够平滑过渡的一种发布方式。在其上可以进行A/B testing，即让一部分用户继续用产品特性A，一部分用户开始用产品特性B，如果用户对B没有什么反对意见，那么逐步扩大范围，把所有用户都迁移到B上面来。灰度发布可以保证整体系统的稳定，在初始灰度的时候就可以发现、调整问题，以保证其影响度。
  ```

- 配置中心：将本地化的配置信息(Properties、XML、YAML等形式)注册到配置中心，实现程序包在开发、测试、生产环境中的无差别性，方便程序包的迁移，也是无状态特性。

- 集成框架：微服务组件都以职责单一的程序包对外提供服务，集成框架以配置的形式将所有微服务组件(特别是管理端组件)集成到统一的界面框架下，让用户能够在统一的界面中使用系统。Spring Cloud就是一个集成框架。

- 调用链监控：记录完成一次请求的先后衔接和调用关系，并将这种串行或并行的调用关系展示出来。在系统出错时，可以方便地找到出错点。

- 支撑平台：系统微服务化后，各个业务模块经过拆分变得更加细化，系统的部署、运维、监控等都比单体应用架构更加复杂，这就需要将大部分的工作自动化。现在，Docker等工具可以给微服务架构的部署带来较多的便利，例如持续集成、蓝绿发布、健康检查、性能监控等等。如果没有合适的支撑平台或工具，微服务架构就无法发挥它最大的功效。

  ```
  1. 蓝绿部署是不停老版本，部署新版本然后进行测试，确认OK，将流量切到新版本，然后老版本同时也升级到新版本。
  2. 灰度是选择部分部署新版本，将部分流量引入到新版本，新老版本同时提供服务。等待灰度的版本OK，可全量覆盖老版本。
  
  灰度是不同版本共存，蓝绿是新旧版本切换，2种模式的出发点不一样。
  ```

  

## 微服务优点

1. 独立部署。不依赖其他服务，耦合性低，不用管其他服务的部署对自己的影响。
2. 易于开发和维护：关注特定业务，所以业务清晰，代码量少，模块变的易开发、易理解、易维护。
3. 启动块：功能少，代码少，所以启动快，有需要停机维护的服务，不会长时间暂停服务。
4. 局部修改容易：只需要部署 相应的服务即可，适合敏捷开发。
5. 技术栈不受限：java，node.js等
6. 按需伸缩：某个服务受限，可以按需增加内存，cpu等。
7. 职责专一。专门团队负责专门业务，有利于团队分工。
8. 代码复用。不需要重复写。底层实现通过接口方式提供。
9. 便于团队协作：每个团队只需要提供API就行，定义好API后，可以并行开发。

## 微服务缺点

1. 分布式固有的复杂性：容错（某个服务宕机），网络延时，调用关系、分布式事务等，都会带来复杂。

2. 分布式事务的挑战：每个服务有自己的数据库，有点在于不同服务可以选择适合自身业务的数据库。订单用MySQL，评论用Mongodb等。目前最理想解决方案是：柔性事务的最终一致性。

   ```sh
   刚性事务：遵循ACID原则，强一致性。
   柔性事务：遵循BASE理论，最终一致性；与刚性事务不同，柔性事务允许一定时间内，不同节点的数据不一致，但要求最终一致。
   
   BASE 是 Basically Available(基本可用)、Soft state(软状态)和 Eventually consistent (最终一致性)三个短语的缩写。BASE理论是对CAP中AP的一个扩展，通过牺牲强一致性来获得可用性，当出现故障允许部分不可用但要保证核心功能可用，允许数据在一段时间内是不一致的，但最终达到一致状态。满足BASE理论的事务，我们称之为“柔性事务”。
   ```

3. 接口调整成本高：改一个接口，调用方都要改。

4. 测试难度提升：一个接口改变，所有调用方都得测。自动化测试就变的重要了。API文档的管理也尤为重要。推荐：yapi。

5. 运维要求高：需要维护 几十 上百个服务。监控变的复杂。并且还要关注多个集群，不像原来单体，一个应用正常运行即可。

6. 重复工作：比如java的工具类可以在共享common.jar中，但在多语言下行不通，C++无法直接用java的jar包。



## 设计原则

单一职责原则：关注整个系统功能中单独，有界限的一部分。

服务自治原则：可以独立开发，测试，构建，部署，运行，与其他服务解耦。

轻量级通信原则：轻，跨平台，跨语言。REST,AMQP 等。

粒度把控：与自己实际相结合。 不要追求完美，随业务进化而调整。《淘宝技术这10年》。

# Spring Cloud

## 概念

Spring Cloud是实现微服务架构的一系列框架的有机集合。

是在Spring Boot基础上构建的，用于简化分布式系统构建的工具集。是拥有众多子项目的项目集合。利用Spring Boot的开发便利性，巧妙地简化了分布式系统基础设施（服务注册与发现、熔断机制、网关路由、配置中心、消息总线、负载均衡、链路追踪等）的开发。



## 版本演进

1. 版本过程：版本名.版本号。

2. 版本名：伦敦地铁字母顺序。

3. 版本号：M（milestone）：里程碑，

   ​				SR（Service Releases）：稳定版，

   ​				RC(Release Candidate)：稳定版的候选版，也就是稳定版的最后一个版本。

```
看官网：查询每个cloud版本下面的子模块的版本。
https://spring.io/projects/spring-cloud
此网页的最下面，目前最新的SpringCloud最新版本是：Greenwich.SR2
```



```sh
版本记录
https://github.com/spring-cloud/spring-cloud-release/releases
```



```sh
采用版本
Spring Boot 2.1.7.RELEASE
https://docs.spring.io/spring-boot/docs/2.1.7.RELEASE/reference/html/

Spring Cloud Greenwich.SR2

```





## [SpringCloud的版本关系](http://www.macrozheng.com/#/cloud/springcloud?id=springcloud的版本关系)

SpringCloud是一个由许多子项目组成的综合项目，各子项目有不同的发布节奏。 为了管理SpringCloud与各子项目的版本依赖关系，发布了一个清单，其中包括了某个SpringCloud版本对应的子项目版本。 为了避免SpringCloud版本号与子项目版本号混淆，SpringCloud版本采用了名称而非版本号的命名，这些版本的名字采用了伦敦地铁站的名字，根据字母表的顺序来对应版本时间顺序，例如Angel是第一个版本, Brixton是第二个版本。 当SpringCloud的发布内容积累到临界点或者一个重大BUG被解决后，会发布一个"service releases"版本，简称SRX版本，比如Greenwich.SR2就是SpringCloud发布的Greenwich版本的第2个SRX版本。

### [SpringCloud和SpringBoot版本对应关系](http://www.macrozheng.com/#/cloud/springcloud?id=springcloud和springboot版本对应关系)

| SpringCloud Version | SpringBoot Version |
| ------------------- | ------------------ |
| Hoxton              | 2.2.x              |
| Greenwich           | 2.1.x              |
| Finchley            | 2.0.x              |
| Edgware             | 1.5.x              |
| Dalston             | 1.5.x              |

### [SpringCloud和各子项目版本对应关系](http://www.macrozheng.com/#/cloud/springcloud?id=springcloud和各子项目版本对应关系)

| Component              | Edgware.SR6    | Greenwich.SR2 |
| ---------------------- | -------------- | ------------- |
| spring-cloud-bus       | 1.3.4.RELEASE  | 2.1.2.RELEASE |
| spring-cloud-commons   | 1.3.6.RELEASE  | 2.1.2.RELEASE |
| spring-cloud-config    | 1.4.7.RELEASE  | 2.1.3.RELEASE |
| spring-cloud-netflix   | 1.4.7.RELEASE  | 2.1.2.RELEASE |
| spring-cloud-security  | 1.2.4.RELEASE  | 2.1.3.RELEASE |
| spring-cloud-consul    | 1.3.6.RELEASE  | 2.1.2.RELEASE |
| spring-cloud-sleuth    | 1.3.6.RELEASE  | 2.1.1.RELEASE |
| spring-cloud-stream    | Ditmars.SR5    | Fishtown.SR3  |
| spring-cloud-zookeeper | 1.2.3.RELEASE  | 2.1.2.RELEASE |
| spring-boot            | 1.5.21.RELEASE | 2.1.5.RELEASE |
| spring-cloud-task      | 1.2.4.RELEASE  | 2.1.2.RELEASE |
| spring-cloud-gateway   | 1.0.3.RELEASE  | 2.1.2.RELEASE |
| spring-cloud-openfeign | 暂无           | 2.1.2.RELEASE |

**注意：Greenwich版本是基于SpringBoot 2.1.x版本构建的，不适用于1.5.x版本。随着2019年8月SpringBoot 1.5.x版本停止维护，Edgware版本也将停止维护。**

## [SpringCloud子项目简介](http://www.macrozheng.com/#/cloud/springcloud?id=springcloud子项目简介)

### [Spring Cloud Config](http://www.macrozheng.com/#/cloud/springcloud?id=spring-cloud-config)

集中配置管理工具，分布式系统中统一的外部配置管理，默认使用Git来存储配置，可以支持客户端配置的刷新及加密、解密操作。

### [Spring Cloud Netflix](http://www.macrozheng.com/#/cloud/springcloud?id=spring-cloud-netflix)

Netflix OSS 开源组件集成，包括Eureka、Hystrix、Ribbon、Feign、Zuul等核心组件。

- Eureka：服务治理组件，包括服务端的注册中心和客户端的服务发现机制；
- Ribbon：负载均衡的服务调用组件，具有多种负载均衡调用策略；
- Hystrix：服务容错组件，实现了断路器模式，为依赖服务的出错和延迟提供了容错能力；
- Feign：基于Ribbon和Hystrix的声明式服务调用组件；
- Zuul：API网关组件，对请求提供路由及过滤功能。

### [Spring Cloud Bus](http://www.macrozheng.com/#/cloud/springcloud?id=spring-cloud-bus)

用于传播集群状态变化的消息总线，使用轻量级消息代理链接分布式系统中的节点，可以用来动态刷新集群中的服务配置。

### [Spring Cloud Consul](http://www.macrozheng.com/#/cloud/springcloud?id=spring-cloud-consul)

基于Hashicorp Consul的服务治理组件。

### [Spring Cloud Security](http://www.macrozheng.com/#/cloud/springcloud?id=spring-cloud-security)

安全工具包，对Zuul代理中的负载均衡OAuth2客户端及登录认证进行支持。

### [Spring Cloud Sleuth](http://www.macrozheng.com/#/cloud/springcloud?id=spring-cloud-sleuth)

SpringCloud应用程序的分布式请求链路跟踪，支持使用Zipkin、HTrace和基于日志（例如ELK）的跟踪。

### [Spring Cloud Stream](http://www.macrozheng.com/#/cloud/springcloud?id=spring-cloud-stream)

轻量级事件驱动微服务框架，可以使用简单的声明式模型来发送及接收消息，主要实现为Apache Kafka及RabbitMQ。

### [Spring Cloud Task](http://www.macrozheng.com/#/cloud/springcloud?id=spring-cloud-task)

用于快速构建短暂、有限数据处理任务的微服务框架，用于向应用中添加功能性和非功能性的特性。

### [Spring Cloud Zookeeper](http://www.macrozheng.com/#/cloud/springcloud?id=spring-cloud-zookeeper)

基于Apache Zookeeper的服务治理组件。

### [Spring Cloud Gateway](http://www.macrozheng.com/#/cloud/springcloud?id=spring-cloud-gateway)

API网关组件，对请求提供路由及过滤功能。

### [Spring Cloud OpenFeign](http://www.macrozheng.com/#/cloud/springcloud?id=spring-cloud-openfeign)

基于Ribbon和Hystrix的声明式服务调用组件，可以动态创建基于Spring MVC注解的接口实现用于服务调用，在SpringCloud 2.0中已经取代Feign成为了一等公民。



## 整体架构

> 《Spring Cloud整体架构图》

组成：

1. 服务注册与发现组件：Eureka，Zookeeper，Consul, Nacos等。Eureka基于REST风格的。

2. 服务调用组件：Hystrix(熔断降级，在出现依赖服务失效的情况下，通过隔离 系统依赖服务  的方式，防止服务级联失败，同时提供失败回滚机制，使系统能够更快地从异常中恢复)，Ribbon（客户端负载均衡，用于提供客户端的软件负载均衡算法，提供了一系列完善的配置项：连接超时、重试等），OpenFeign（优雅的封装Ribbon，是一个声明式RESTful网络请求客户端，它使编写Web服务客户端变得更加方便和快捷）。

3. 网关：路由和过滤。Zuul，Gateway。

4. 配置中心：提供了配置集中管理，动态刷新配置的功能；配置通过Git或者其他方式来存储。

5. 消息组件：Spring Cloud Stream（对分布式消息进行抽象，包括发布订阅、分组消费等功能，实现了微服务之间的异步通信）和Spring Cloud Bus（主要提供服务间的事件通信，如刷新配置）

6. 安全控制组件：Spring Cloud Security 基于OAuth2.0开放网络的安全标准，提供了单点登录、资源授权和令牌管理等功能。

7. 链路追踪组件：Spring Cloud Sleuth（收集调用链路上的数据），Zipkin（对Sleuth收集的信息，进行存储，统计，展示）。



# Spring Cloud组件


## Eureka 服务发现框架

## Ribbon 进程内负载均衡器

## Open Feign 服务调用映射

Feign**是声明式的服务调用工具，我们只需创建一个接口并用注解的方式来配置它，就可以实现对某个服务接口的调用，简化了直接使用RestTemplate来调用服务接口的开发量**。Feign具备可插拔的注解支持，同时支持Feign注解、JAX-RS注解及SpringMvc注解。当使用Feign时，**Spring Cloud集成了Ribbon和Eureka以提供负载均衡的服务调用及基于Hystrix的服务容错保护功能**。

简单使用方法:

假设a服务调用b服务

1. @EnableFeignClients
2. 在a服务新建b服务控制器的接口

```java
@FeignClient(value = "b")
public interface UserService {
    @PostMapping("/user/create")
    CommonResult create(@RequestBody User user);

    @GetMapping("/user/{id}")
    CommonResult<User> getUser(@PathVariable Long id);
}
```

3. 注入到类调用即可

```java
@Autowired
private UserService userService;
```

还可以做降级, 日志打印功能

```yml
#常用配置
feign:
  hystrix:
    enabled: true #在Feign中开启Hystrix
  compression:
    request:
      enabled: false #是否对请求进行GZIP压缩
      mime-types: text/xml,application/xml,application/json #指定压缩的请求数据类型
      min-request-size: 2048 #超过该大小的请求会被压缩
    response:
      enabled: false #是否对响应进行GZIP压缩
logging:
  level: #修改日志级别
    com.macro.cloud.service.UserService: debug
```





## Hystrix 服务降级熔断器

在微服务架构中，服务与服务之间通过远程调用的方式进行通信，一旦某个被调用的服务发生了故障，其依赖服务也会发生故障，此时就会发生故障的蔓延，最终导致系统瘫痪。Hystrix实现了断路器模式，当某个服务发生故障时，通过断路器的监控，给调用方返回一个错误响应，而不是长时间的等待，这样就不会使得调用方由于长时间得不到响应而占用线程，从而防止故障的蔓延。Hystrix具备**服务降级、服务熔断、线程隔离、请求缓存、请求合并及服务监控**等强大功能。

## Zuul 微服务网关

API网关为微服务架构中的服务提供了统一的访问入口，客户端通过API网关访问相关服务。API网关的定义类似于设计模式中的门面模式，它相当于整个微服务架构中的门面，所有客户端的访问都通过它来进行路由及过滤。它**实现了请求路由、负载均衡、校验过滤、服务容错、服务聚合等功能。**

> 由于Zuul自动集成了Ribbon和Hystrix，所以Zuul天生就有负载均衡和服务容错能力，我们可以通过Ribbon和Hystrix的配置来配置Zuul中的相应功能。

## Config 微服务统一配置中心

Spring Cloud Config 分为服务端和客户端两个部分。服务端被称为分布式配置中心，它是个独立的应用，可以从配置仓库获取配置信息并提供给客户端使用。客户端可以通过配置中心来获取配置信息，在启动时加载配置。Spring Cloud Config 的配置中心默认采用Git来存储配置信息，所以天然就支持配置信息的版本管理，并且可以使用Git客户端来方便地管理和访问配置信息。

## Bus 消息总线

我们通常会使用消息代理来构建一个主题，然后把微服务架构中的所有服务都连接到这个主题上去，当我们向该主题发送消息时，所有订阅该主题的服务都会收到消息并进行消费。使用 Spring Cloud Bus 可以方便地构建起这套机制，所以 Spring Cloud Bus 又被称为消息总线。**Spring Cloud Bus 配合 Spring Cloud Config 使用可以实现配置的动态刷新。**目前 Spring Cloud Bus 支持两种消息代理：RabbitMQ 和 Kafka，下面以 RabbitMQ 为例来演示下使用Spring Cloud Bus 动态刷新配置的功能。

## [Spring Cloud Sleuth：分布式请求链路跟踪](http://www.macrozheng.com/#/cloud/sleuth?id=spring-cloud-sleuth：分布式请求链路跟踪)

随着我们的系统越来越庞大，各个服务间的调用关系也变得越来越复杂。当客户端发起一个请求时，这个请求经过多个服务后，最终返回了结果，经过的每一个服务都有可能发生延迟或错误，从而导致请求失败。这时候我们就需要请求链路跟踪工具来帮助我们，理清请求调用的服务链路，解决问题。

## [Consul 简介](http://www.macrozheng.com/#/cloud/consul?id=consul-简介)

Consul是HashiCorp公司推出的开源软件，提供了微服务系统中的服务治理、配置中心、控制总线等功能。这些功能中的每一个都可以根据需要单独使用，也可以一起使用以构建全方位的服务网格，总之Consul提供了一种完整的服务网格解决方案。

Spring Cloud Consul 具有如下特性：

- 支持服务治理：Consul作为注册中心时，微服务中的应用可以向Consul注册自己，并且可以从Consul获取其他应用信息；
- 支持客户端负责均衡：包括Ribbon和Spring Cloud LoadBalancer；
- 支持Zuul：当Zuul作为网关时，可以从Consul中注册和发现应用；
- 支持分布式配置管理：Consul作为配置中心时，使用键值对来存储配置信息；
- 支持控制总线：可以在整个微服务系统中通过 Control Bus 分发事件消息。

## [Gateway 简介](http://www.macrozheng.com/#/cloud/gateway?id=gateway-简介)

Gateway是在Spring生态系统之上构建的API网关服务，基于Spring 5，Spring Boot 2和 Project Reactor等技术。Gateway旨在提供一种简单而有效的方式来对API进行路由，以及提供一些强大的过滤器功能， 例如：熔断、限流、重试等。

Spring Cloud Gateway 具有如下特性：

- 基于Spring Framework 5, Project Reactor 和 Spring Boot 2.0 进行构建；
- 动态路由：能够匹配任何请求属性；
- 可以对路由指定 Predicate（断言）和 Filter（过滤器）；
- 集成Hystrix的断路器功能；
- 集成 Spring Cloud 服务发现功能；
- 易于编写的 Predicate（断言）和 Filter（过滤器）；
- 请求限流功能；
- 支持路径重写。



## [Spring Boot Admin 简介](http://www.macrozheng.com/#/cloud/admin?id=spring-boot-admin-简介)

SpringBoot应用可以通过Actuator来暴露应用运行过程中的各项指标，Spring Boot Admin通过这些指标来监控SpringBoot应用，然后通过图形化界面呈现出来。Spring Boot Admin不仅可以监控单体应用，还可以和Spring Cloud的注册中心相结合来监控微服务应用。

Spring Boot Admin 可以提供应用的以下监控信息：

- 监控应用运行过程中的概览信息；
- 度量指标信息，比如JVM、Tomcat及进程信息；
- 环境变量信息，比如系统属性、系统环境变量以及应用配置信息；
- 查看所有创建的Bean信息；
- 查看应用中的所有配置信息；
- 查看应用运行日志信息；
- 查看JVM信息；
- 查看可以访问的Web端点；
- 查看HTTP跟踪信息。



# [Nacos简介](http://www.macrozheng.com/#/cloud/nacos?id=nacos简介)

Nacos 致力于帮助您发现、配置和管理微服务。Nacos 提供了一组简单易用的特性集，帮助您快速实现动态服务发现、服务配置、服务元数据及流量管理。

Nacos 具有如下特性:

- 服务发现和服务健康监测：支持基于DNS和基于RPC的服务发现，支持对服务的实时的健康检查，阻止向不健康的主机或服务实例发送请求；
- 动态配置服务：动态配置服务可以让您以中心化、外部化和动态化的方式管理所有环境的应用配置和服务配置；
- 动态 DNS 服务：动态 DNS 服务支持权重路由，让您更容易地实现中间层负载均衡、更灵活的路由策略、流量控制以及数据中心内网的简单DNS解析服务；
- 服务及其元数据管理：支持从微服务平台建设的视角管理数据中心的所有服务及元数据。

[Spring Cloud Alibaba：Sentinel实现熔断与限流](http://www.macrozheng.com/#/cloud/sentinel?id=spring-cloud-alibaba：sentinel实现熔断与限流)

> Spring Cloud Alibaba 致力于提供微服务开发的一站式解决方案，Sentinel 作为其核心组件之一，具有熔断与限流等一系列服务保护功能，本文将对其用法进行详细介绍。



# [Sentinel简介](http://www.macrozheng.com/#/cloud/sentinel?id=sentinel简介)

随着微服务的流行，服务和服务之间的稳定性变得越来越重要。 Sentinel 以流量为切入点，从流量控制、熔断降级、系统负载保护等多个维度保护服务的稳定性。

Sentinel具有如下特性:

- 丰富的应用场景：承接了阿里巴巴近 10 年的双十一大促流量的核心场景，例如秒杀，可以实时熔断下游不可用应用；
- 完备的实时监控：同时提供实时的监控功能。可以在控制台中看到接入应用的单台机器秒级数据，甚至 500 台以下规模的集群的汇总运行情况；
- 广泛的开源生态：提供开箱即用的与其它开源框架/库的整合模块，例如与 Spring Cloud、Dubbo、gRPC 的整合；
- 完善的 SPI 扩展点：提供简单易用、完善的 SPI 扩展点。您可以通过实现扩展点，快速的定制逻辑。