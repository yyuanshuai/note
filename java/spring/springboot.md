
* application.yml配置文件
```
spring:
  profiles:
    active: prod//加载application-prod.yml的配置文件
    
server:
  port: 8081//设置端口
  servlet:
    context-path: /luckymoney //设置url前缀

limit://可以看成是个对象,以下可以看成属性
  minMoney: 0.1
  maxMoney: 9999
  description: 最少要发${limit.minMoney}元, 最多发${limit.maxMoney}
```

```
@Component//表示这个类是bean
@ConfigurationProperties(prefix = "student")//从yml配置文件中加载student数据
public class Student {
    private String name;
    private Integer id;

    public String getName() {
        return name;
    }

    public void setDescription(String name) {
        this.name = name;
    }

}
```

### controller注解
* @Controller
    * 处理http请求
* @RestController
    * Spring4之后加的,之前返回json需要@ResponseBody和@Controller
* @RequestMapping //GetMapping,PostMapping
    * 配置url映射
* @PathVariable("id") Integer id
* @RequestParam(value = "id", required = false, defaultValue = "0") Integer myId


### spring-data-jpa
