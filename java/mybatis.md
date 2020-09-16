1: 配置文件-> 配置数据库
2: mapper-> 相当于sql语句, interface,xml
3: dto -> model->POJO, 数据的对象表现形式





## 基本使用

### 引入pom

```xml
        <dependency>
            <groupId>org.mybatis</groupId>
            <artifactId>mybatis</artifactId>
            <version>3.5.3</version>
        </dependency>
        <dependency>
            <groupId>mysql</groupId>
            <artifactId>mysql-connector-java</artifactId>
            <version>8.0.18</version>
        </dependency>
```

### resources配置

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE configuration
        PUBLIC "-//mybatis.org//DTD Config 3.0//EN"
        "http://mybatis.org/dtd/mybatis-3-config.dtd">
<configuration>
    <environments default="dev">
        <environment id="dev">
            <transactionManager type="JDBC"/>
            <dataSource type="POOLED">
                <property name="driver" value="com.mysql.cj.jdbc.Driver"/>
                <property name="url"
                          value="jdbc:mysql://127.0.0.1:3306/hero_story?useSSL=false&amp;useUnicode=true&amp;characterEncoding=UTF-8&amp;zeroDateTimeBehavior=convertToNull&amp;autoReconnect=true&amp;autoReconnectForPools=true"/>
                <property name="username" value="root"/>
                <property name="password" value="root"/>
            </dataSource>
        </environment>
    </environments>
    <mappers>
        <mapper resource="org/tinygame/herostory/login/db/IUserDao.xml"/>
    </mappers>
</configuration>
```

### IUserDao.xml(--mapper)

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE mapper PUBLIC "-//mybatis.org//DTD Mapper 3.0//EN" "http://mybatis.org/dtd/mybatis-3-mapper.dtd">
<mapper namespace="org.tinygame.herostory.login.db.IUserDao">
    <resultMap id="userEntity" type="org.tinygame.herostory.login.db.UserEntity">
        <id property="userId" column="user_id"/>
        <result property="userName" column="user_name"/>
        <result property="password" column="password"/>
        <result property="heroAvatar" column="hero_avatar"/>
    </resultMap>

    <select id="getByUserName" resultMap="userEntity">
        SELECT user_id, user_name, `password`, hero_avatar FROM t_user WHERE user_name = #{userName};
    </select>

    <insert id="insertInto">
        <selectKey resultType="java.lang.Integer" order="AFTER" keyProperty="userId">
            SELECT last_insert_id() AS user_id
        </selectKey>
        INSERT INTO t_user ( user_name, `password`, hero_avatar ) VALUE ( #{userName}, #{password}, #{heroAvatar} );
    </insert>
</mapper>
```

### IUserDao.java

```java
package org.tinygame.herostory.login.db;

public interface IUserDao {
    /**
     * 根据用户名称获取实体
     *
     * @param userName 用户名称
     * @return
     */
    UserEntity getByUserName(String userName);

    /**
     * 添加用户实体
     *
     * @param newEntity 用户实体
     */
    void insertInto(UserEntity newEntity);
}
```

### UserEntity.java

```java
package org.tinygame.herostory.login.db;
public class UserEntity {
    public int userId;
    public String userName;
    public String password;
    public String heroAvatar;
}
```

