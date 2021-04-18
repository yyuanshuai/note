https://www.liaoxuefeng.com/wiki/1252599548343744/1309301178105890

解决依赖管理。我们声明了自己的项目需要`abc`，Maven会自动导入`abc`的jar包，再判断出`abc`需要`xyz`，又会自动导入`xyz`的jar包，这样，最终我们的项目会依赖`abc`和`xyz`两个jar包。





Maven定义了几种依赖关系，分别是`compile`、`test`、`runtime`和`provided`：

| scope    | 说明                                          | 示例            |
| :------- | :-------------------------------------------- | :-------------- |
| compile  | 编译时需要用到该jar包（默认）                 | commons-logging |
| test     | 编译Test时需要用到该jar包                     | junit           |
| runtime  | 编译时不需要，但运行时需要用到                | mysql           |
| provided | 编译时需要用到，但运行时由JDK或某个服务器提供 | servlet-api     |

某个jar包一旦被Maven下载过，即可永久地安全缓存在本地。

注：只有以`-SNAPSHOT`结尾的版本号会被Maven视为开发版本，开发版本每次都会重复下载，这种SNAPSHOT版本只能用于内部私有的Maven repo，公开发布的版本不允许出现SNAPSHOT。

在用户主目录下进入`.m2`目录，创建一个`settings.xml`配置文件，内容如下：

```xml
<settings>
    <mirrors>
        <mirror>
            <id>aliyun</id>
            <name>aliyun</name>
            <mirrorOf>central</mirrorOf>
            <!-- 国内推荐阿里云的Maven镜像 -->
            <url>https://maven.aliyun.com/repository/central</url>
        </mirror>
    </mirrors>
</settings>
```

在命令中，进入到`pom.xml`所在目录，输入以下命令：

```
$ mvn clean package
```

如果一切顺利，即可在`target`目录下获得编译后自动打包的jar。

```shell
mvn package#Maven就会执行default生命周期，它会从开始一直运行到package这个phase为止：
mvn compile#Maven也会执行default生命周期，但这次它只会运行到compile，即以下几个phase：
mvn clean

mvn clean#清理所有生成的class和jar；

mvn clean compile#先清理，再执行到compile；

mvn clean test#先清理，再执行到test，因为执行test前必须执行compile，所以这里不必指定compile；

mvn clean package#先清理，再执行到package。
```

经常用到的phase其实只有几个：

- clean：清理
- compile：编译
- test：运行测试
- package：打包