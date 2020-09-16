# HeroStory游戏开发学习

靳海江讲师: 游戏开发的本质是完成业务逻辑向多线程的映射, 但是大部分的业务逻辑是在单线程上完成的, 因为这样比较简单





# 初识netty

```java
package org.tinygame.herostory;

import io.netty.bootstrap.ServerBootstrap;
import io.netty.channel.ChannelFuture;
import io.netty.channel.ChannelInitializer;
import io.netty.channel.EventLoopGroup;
import io.netty.channel.nio.NioEventLoopGroup;
import io.netty.channel.socket.SocketChannel;
import io.netty.channel.socket.nio.NioServerSocketChannel;
import io.netty.handler.codec.http.HttpObjectAggregator;
import io.netty.handler.codec.http.HttpServerCodec;
import io.netty.handler.codec.http.websocketx.WebSocketServerProtocolHandler;
import org.apache.log4j.PropertyConfigurator;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.tinygame.herostory.cmdhandler.CmdHandlerFactory;

/**
 * 服务器入口类
 */
public class ServerMain {
    static private final Logger LOGGER = LoggerFactory.getLogger(ServerMain.class);

    /**
     * 服务器端口号
     */
    static private final int SERVER_PORT = 12345;

    /**
     * 应用主函数
     *
     * @param argvArray 命令行参数数组
     */
    static public void main(String[] argvArray) {
        // 设置 log4j 属性文件
        PropertyConfigurator.configure(ServerMain.class.getClassLoader().getResourceAsStream("log4j.properties"));

        // 初始化命令处理器工厂
        CmdHandlerFactory.init();
        // 初始化消息识别器
        GameMsgRecognizer.init();
        // 初始化 MySql 会话工厂
        MySqlSessionFactory.init();

        EventLoopGroup bossGroup = new NioEventLoopGroup();   // 拉客的, 也就是故事中的美女
        EventLoopGroup workerGroup = new NioEventLoopGroup(); // 干活的, 也就是故事中的服务生

        ServerBootstrap b = new ServerBootstrap();
        b.group(bossGroup, workerGroup);
        b.channel(NioServerSocketChannel.class); // 服务器信道的处理方式
        b.childHandler(new ChannelInitializer<SocketChannel>() {
            @Override
            protected void initChannel(SocketChannel ch) throws Exception {
                ch.pipeline().addLast(
                    new HttpServerCodec(), // Http 服务器编解码器
                    new HttpObjectAggregator(65535), // 内容长度限制
                    new WebSocketServerProtocolHandler("/websocket"), // WebSocket 协议处理器, 在这里处理握手、ping、pong 等消息
                    new GameMsgDecoder(), // 自定义的消息解码器
                    new GameMsgEncoder(), // 自定义的消息编码器
                    new GameMsgHandler() // 自定义的消息处理器
                );
            }
        });

        try {
            // 绑定 12345 端口,
            // 注意: 实际项目中会使用 argvArray 中的参数来指定端口号
			//b.option(ChannelOption.SO_BACKLOG, 128);
			//b.childOption(ChannelOption.SO_KEEPALIVE, true);
            
            //绑定并开始接受传入连接
            ChannelFuture f = b.bind(SERVER_PORT).sync();

            if (f.isSuccess()) {
                LOGGER.info("服务器启动成功!");
            }

            // 等待服务器信道关闭,
            // 也就是不要立即退出应用程序, 让应用程序可以一直提供服务
            f.channel().closeFuture().sync();
        } catch (Exception ex) {
            // 如果遇到异常, 打印详细信息...
            LOGGER.error(ex.getMessage(), ex);
        } finally {
            // 关闭服务器, 大家都歇了吧
            workerGroup.shutdownGracefully();
            bossGroup.shutdownGracefully();
        }
    }
}
```

![](https://img2020.cnblogs.com/blog/897500/202004/897500-20200407221807100-761820511.png)

# Protobuf消息应用

## 通过消息编号解析消息体:

```java
package org.tinygame.herostory;

import com.google.protobuf.GeneratedMessageV3;
import io.netty.buffer.ByteBuf;
import io.netty.channel.ChannelHandlerContext;
import io.netty.channel.ChannelInboundHandlerAdapter;
import io.netty.handler.codec.http.websocketx.BinaryWebSocketFrame;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.tinygame.herostory.msg.GameMsgProtocol;

/**
 * 自定义的消息解码器
 */
public class GameMsgDecoder extends ChannelInboundHandlerAdapter {
    /**
     * 日志对象
     */
    static private final Logger LOGGER = LoggerFactory.getLogger(GameMsgDecoder.class);

    @Override
    public void channelRead(ChannelHandlerContext ctx, Object msg) {
        if (null == ctx ||
            null == msg) {
            return;
        }
        if (!(msg instanceof BinaryWebSocketFrame)) {
            return;
        }
        try {
            BinaryWebSocketFrame inputFrame = (BinaryWebSocketFrame) msg;
            ByteBuf byteBuf = inputFrame.content();

            byteBuf.readShort(); // 读取消息的长度
            int msgCode = byteBuf.readShort(); // 读取消息编号

            // 拿到消息体
            byte[] msgBody = new byte[byteBuf.readableBytes()];
            byteBuf.readBytes(msgBody);

            GeneratedMessageV3 cmd = null;

            switch (msgCode) {
                case GameMsgProtocol.MsgCode.USER_ENTRY_CMD_VALUE:
                    cmd = GameMsgProtocol.UserEntryCmd.parseFrom(msgBody);
                    break;
                case GameMsgProtocol.MsgCode.WHO_ELSE_IS_HERE_CMD_VALUE:
                    cmd = GameMsgProtocol.WhoElseIsHereCmd.parseFrom(msgBody);
                    break;
                default:
                    break;
            }
            if (null != cmd) {
                ctx.fireChannelRead(cmd);
            }
        } catch (Exception ex) {
            // 记录错误日志
            LOGGER.error(ex.getMessage(), ex);
        }
    }
}
```

## 自定义的消息处理器

```java
package org.tinygame.herostory;

import io.netty.channel.ChannelHandlerContext;
import io.netty.channel.SimpleChannelInboundHandler;
import io.netty.channel.group.ChannelGroup;
import io.netty.channel.group.DefaultChannelGroup;
import io.netty.util.concurrent.GlobalEventExecutor;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.tinygame.herostory.msg.GameMsgProtocol;

import java.util.HashMap;
import java.util.Map;

/**
 * 自定义的消息处理器
 */
public class GameMsgHandler extends SimpleChannelInboundHandler<Object> {
    /**
     * 日志对象
     */
    static private final Logger LOGGER = LoggerFactory.getLogger(GameMsgHandler.class);

    /**
     * 信道组, 注意这里一定要用 static,
     * 否则无法实现群发
     */
    static private final ChannelGroup _channelGroup = new DefaultChannelGroup(GlobalEventExecutor.INSTANCE);

    /**
     * 用户字典
     */
    static private final Map<Integer, User> _userMap = new HashMap<>();

    @Override
    public void channelActive(ChannelHandlerContext ctx) {
        if (null == ctx) {
            return;
        }

        try {
            super.channelActive(ctx);
            _channelGroup.add(ctx.channel());
        } catch (Exception ex) {
            // 记录错误日志
            LOGGER.error(ex.getMessage(), ex);
        }
    }

    @Override
    protected void channelRead0(ChannelHandlerContext ctx, Object msg) {
        if (null == ctx ||
            null == msg) {
            return;
        }

        LOGGER.info(
            "收到客户端消息, msgClazz = {}, msgBody = {}",
            msg.getClass().getSimpleName(),
            msg
        );

        try {
            if (msg instanceof GameMsgProtocol.UserEntryCmd) {
                //
                // 用户入场消息
                //
                GameMsgProtocol.UserEntryCmd cmd = (GameMsgProtocol.UserEntryCmd) msg;
                int userId = cmd.getUserId();
                String heroAvatar = cmd.getHeroAvatar();

                User newUser = new User();
                newUser.userId = userId;
                newUser.heroAvatar = heroAvatar;
                _userMap.putIfAbsent(userId, newUser);

                GameMsgProtocol.UserEntryResult.Builder resultBuilder = GameMsgProtocol.UserEntryResult.newBuilder();
                resultBuilder.setUserId(userId);
                resultBuilder.setHeroAvatar(heroAvatar);

                // 构建结果并广播
                GameMsgProtocol.UserEntryResult newResult = resultBuilder.build();
                _channelGroup.writeAndFlush(newResult);
            } else if (msg instanceof GameMsgProtocol.WhoElseIsHereCmd) {
                //
                // 还有谁在场
                //
                GameMsgProtocol.WhoElseIsHereResult.Builder resultBuilder = GameMsgProtocol.WhoElseIsHereResult.newBuilder();

                for (User currUser : _userMap.values()) {
                    if (null == currUser) {
                        continue;
                    }

                    GameMsgProtocol.WhoElseIsHereResult.UserInfo.Builder userInfoBuilder = GameMsgProtocol.WhoElseIsHereResult.UserInfo.newBuilder();
                    userInfoBuilder.setUserId(currUser.userId);
                    userInfoBuilder.setHeroAvatar(currUser.heroAvatar);
                    resultBuilder.addUserInfo(userInfoBuilder);
                }

                GameMsgProtocol.WhoElseIsHereResult newResult = resultBuilder.build();
                ctx.writeAndFlush(newResult);
            }
        } catch (Exception ex) {
            // 记录错误日志
            LOGGER.error(ex.getMessage(), ex);
        }
    }
}

```

## 返回处理结果(编码后)

```java
package org.tinygame.herostory;

import com.google.protobuf.GeneratedMessageV3;
import io.netty.buffer.ByteBuf;
import io.netty.channel.ChannelHandlerContext;
import io.netty.channel.ChannelOutboundHandlerAdapter;
import io.netty.channel.ChannelPromise;
import io.netty.handler.codec.http.websocketx.BinaryWebSocketFrame;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.tinygame.herostory.msg.GameMsgProtocol;

/**
 * 游戏消息编码器
 */
public class GameMsgEncoder extends ChannelOutboundHandlerAdapter {
    /**
     * 日志对象
     */
    static private final Logger LOGGER = LoggerFactory.getLogger(GameMsgEncoder.class);

    @Override
    public void write(ChannelHandlerContext ctx, Object msg, ChannelPromise promise) {
        if (null == ctx ||
            null == msg) {
            return;
        }

        try {
            if (!(msg instanceof GeneratedMessageV3)) {
                super.write(ctx, msg, promise);
                return;
            }

            // 消息编码
            int msgCode = -1;

            if (msg instanceof GameMsgProtocol.UserEntryResult) {
                msgCode = GameMsgProtocol.MsgCode.USER_ENTRY_RESULT_VALUE;
            } else if (msg instanceof GameMsgProtocol.WhoElseIsHereResult) {
                msgCode = GameMsgProtocol.MsgCode.WHO_ELSE_IS_HERE_RESULT_VALUE;
            } else {
                LOGGER.error(
                    "无法识别的消息类型, msgClazz = {}",
                    msg.getClass().getSimpleName()
                );
                super.write(ctx, msg, promise);
                return;
            }

            // 消息体
            byte[] msgBody = ((GeneratedMessageV3) msg).toByteArray();

            ByteBuf byteBuf = ctx.alloc().buffer();
            byteBuf.writeShort((short) msgBody.length); // 消息的长度
            byteBuf.writeShort((short) msgCode); // 消息编号
            byteBuf.writeBytes(msgBody); // 消息体

            // 写出 ByteBuf
            BinaryWebSocketFrame outputFrame = new BinaryWebSocketFrame(byteBuf);
            super.write(ctx, outputFrame, promise);
        } catch (Exception ex) {
            // 记录错误日志
            LOGGER.error(ex.getMessage(), ex);
        }
    }
}

```



# 重构代码

**看上面代码, 每次加动作(比如攻击, 移动, 跑, 跳)都需要修改编码解码部分以及命令处理器部分, 违反了开闭原则, (对修改关闭, 对扩展开放)**

## 重构编码解码

1. 我们可以看到, 根据消息编号获取消息体, 也就是有一个映射的关系, 我们可以用map来做

   ```java
               switch (msgCode) {
                   case GameMsgProtocol.MsgCode.USER_ENTRY_CMD_VALUE:
                       cmd = GameMsgProtocol.UserEntryCmd.parseFrom(msgBody);
                       break;
                   case GameMsgProtocol.MsgCode.WHO_ELSE_IS_HERE_CMD_VALUE:
                       cmd = GameMsgProtocol.WhoElseIsHereCmd.parseFrom(msgBody);
                       break;
                   default:
                       break;
               }
   ```

2. 新建一个GameMsgRecognizer(消息识别器)

   1. 有消息编号 -> 消息对象字典的_msgCodeAndMsgObjMap
   2. 有消息类 -> 消息编号字典_clazzAndMsgCodeMap
   3. 有初识化以上两map的方法(以后加动作只需要在这里加上就可以了, 但是还是不够好, 进一步重构下面再讲(使用反射+javassist))
   4. 有根据消息编号获取消息构建器的方法getBuilderByMsgCode
   5. 有根据消息类获取消息编号getMsgCodeByClazz

   ```java
   package org.tinygame.herostory;
   
   import com.google.protobuf.GeneratedMessageV3;
   import com.google.protobuf.Message;
   import org.tinygame.herostory.msg.GameMsgProtocol;
   
   import java.util.HashMap;
   import java.util.Map;
   
   /**
    * 消息识别器
    */
   public final class GameMsgRecognizer {
       /**
        * 消息编号 -> 消息对象字典
        */
       static private final Map<Integer, GeneratedMessageV3> _msgCodeAndMsgObjMap = new HashMap<>();
   
       /**
        * 消息类 -> 消息编号字典
        */
       static private final Map<Class<?>, Integer> _clazzAndMsgCodeMap = new HashMap<>();
   
       /**
        * 私有化类默认构造器
        */
       private GameMsgRecognizer() {
       }
   
       /**
        * 初始化
        */
       static public void init() {
           _msgCodeAndMsgObjMap.put(GameMsgProtocol.MsgCode.USER_ENTRY_CMD_VALUE, GameMsgProtocol.UserEntryCmd.getDefaultInstance());
           _msgCodeAndMsgObjMap.put(GameMsgProtocol.MsgCode.WHO_ELSE_IS_HERE_CMD_VALUE, GameMsgProtocol.WhoElseIsHereCmd.getDefaultInstance());
           _msgCodeAndMsgObjMap.put(GameMsgProtocol.MsgCode.USER_MOVE_TO_CMD_VALUE, GameMsgProtocol.UserMoveToCmd.getDefaultInstance());
   
           _clazzAndMsgCodeMap.put(GameMsgProtocol.UserEntryResult.class, GameMsgProtocol.MsgCode.USER_ENTRY_RESULT_VALUE);
           _clazzAndMsgCodeMap.put(GameMsgProtocol.WhoElseIsHereResult.class, GameMsgProtocol.MsgCode.WHO_ELSE_IS_HERE_RESULT_VALUE);
           _clazzAndMsgCodeMap.put(GameMsgProtocol.UserMoveToResult.class, GameMsgProtocol.MsgCode.USER_MOVE_TO_RESULT_VALUE);
           _clazzAndMsgCodeMap.put(GameMsgProtocol.UserQuitResult.class, GameMsgProtocol.MsgCode.USER_QUIT_RESULT_VALUE);
       }
   
       /**
        * 根据消息编号获取消息构建器
        *
        * @param msgCode
        * @return
        */
       static public Message.Builder getBuilderByMsgCode(int msgCode) {
           if (msgCode < 0) {
               return null;
           }
   
           GeneratedMessageV3 defaultMsg = _msgCodeAndMsgObjMap.get(msgCode);
   
           if (null == defaultMsg) {
               return null;
           } else {
               return defaultMsg.newBuilderForType();
           }
       }
   
       /**
        * 根据消息类获取消息编号
        *
        * @param msgClazz
        * @return
        */
       static public int getMsgCodeByClazz(Class<?> msgClazz) {
           if (null == msgClazz) {
               return -1;
           }
   
           Integer msgCode = _clazzAndMsgCodeMap.get(msgClazz);
   
           if (null == msgCode) {
               return -1;
           } else {
               return msgCode.intValue();
           }
       }
   }
   ```

3. 解码时只需调用getBuilderByMsgCode, 就可以通过消息编号, 获取到消息类, 进而传给命令处理器进行相应的处理

4. 解码时调用getMsgCodeByClazz, 就可以通过消息类, 获取消息编号返回给前端

   ```java
               // 获取消息构建器
               Message.Builder msgBuilder = GameMsgRecognizer.getBuilderByMsgCode(msgCode);
               msgBuilder.clear();
               msgBuilder.mergeFrom(msgBody);
   
               // 消息编码
               int msgCode = GameMsgRecognizer.getMsgCodeByClazz(msg.getClass());
   ```

## 重构命令处理器

每次加动作, 都需要改这个类, 和上面一样的问题, 

我们可以建一个命令处理接口, 以后每新增一个动作就新增一个类实现这个接口就可以了, 

```java
            if (msg instanceof GameMsgProtocol.UserEntryCmd) {
                // 用户入场消息
            } else if (msg instanceof GameMsgProtocol.WhoElseIsHereCmd) {
                // 还有谁在场
            }
```

### 1. 新建命令处理器接口

```java
/**
 * 命令处理器接口
 * @param <TCmd>
 */
public interface ICmdHandler<TCmd extends GeneratedMessageV3> {
    /**
     * 处理命令
     * @param ctx
     * @param cmd
     */
    void handle(ChannelHandlerContext ctx, TCmd cmd);
}
```

### 2. 让每个动作处理器继承命令处理器接口

```java
public class UserEntryCmdHandler implements ICmdHandler<GameMsgProtocol.UserEntryCmd> {
    @Override
    public void handle(ChannelHandlerContext ctx, GameMsgProtocol.UserEntryCmd cmd) {
        if (null == ctx ||
            null == cmd) {
            return;
        }

        // 获取用户 Id 和英雄形象
        int userId = cmd.getUserId();
        String heroAvatar = cmd.getHeroAvatar();

        User newUser = new User();
        newUser.userId = userId;
        newUser.heroAvatar = heroAvatar;
        UserManager.addUser(newUser);

        // 将用户 Id 保存至 Session
        ctx.channel().attr(AttributeKey.valueOf("userId")).set(userId);

        GameMsgProtocol.UserEntryResult.Builder resultBuilder = GameMsgProtocol.UserEntryResult.newBuilder();
        resultBuilder.setUserId(userId);
        resultBuilder.setHeroAvatar(heroAvatar);

        // 构建结果并广播
        GameMsgProtocol.UserEntryResult newResult = resultBuilder.build();
        Broadcaster.broadcast(newResult);
    }
}
```

### 3. 创建消息类对象->各个处理器的映射工厂

每一个设计模式都不应该是单独使用的. 就像这里工厂模式里的类都继承了同一个接口

```java
package org.tinygame.herostory.cmdhandler;

import com.google.protobuf.GeneratedMessageV3;
import org.tinygame.herostory.msg.GameMsgProtocol;

import java.util.HashMap;
import java.util.Map;

/**
 * 命令处理器工厂类
 */
public final class CmdHandlerFactory {
    /**
     * 命令处理器字典
     */
    static private Map<Class<?>, ICmdHandler<? extends GeneratedMessageV3>> _handlerMap = new HashMap<>();

    /**
     * 私有化类默认构造器
     */
    private CmdHandlerFactory() {
    }

    /**
     * 初始化
     */
    static public void init() {
        _handlerMap.put(GameMsgProtocol.UserEntryCmd.class, new UserEntryCmdHandler());
        _handlerMap.put(GameMsgProtocol.WhoElseIsHereCmd.class, new WhoElseIsHereCmdHandler());
        _handlerMap.put(GameMsgProtocol.UserMoveToCmd.class, new UserMoveToCmdHandler());
    }

    /**
     * 创建命令处理器
     *
     * @param msgClazz
     * @return
     */
    static public ICmdHandler<? extends GeneratedMessageV3> create(Class<?> msgClazz) {
        if (null == msgClazz) {
            return null;
        }
        return _handlerMap.get(msgClazz);
    }
}
```

### 4. 使用工厂, 执行处理命令

```java
            ICmdHandler<? extends GeneratedMessageV3> cmdHandler = CmdHandlerFactory.create(msg.getClass());
            if (null != cmdHandler) {
                cmdHandler.handle(ctx, cast(msg));
            }
```

## 用户管理器

```java
/**
 * 用户管理器
 */
public final class UserManager {
    /**
     * 用户字典
     */
    static private final Map<Integer, User> _userMap = new ConcurrentHashMap<>();

    /**
     * 私有化类默认构造器
     */
    private UserManager() {
    }

    /**
     * 添加用户
     *
     * @param u
     */
    static public void addUser(User u) {
        if (null != u) {
            _userMap.putIfAbsent(u.userId, u);
        }
    }

    /**
     * 移除用户
     *
     * @param userId
     */
    static public void removeByUserId(int userId) {
        _userMap.remove(userId);
    }

    /**
     * 列表用户
     *
     * @return
     */
    static public Collection<User> listUser() {
        return _userMap.values();
    }
}
```

## 广播器

```java
/**
 * 广播员
 */
public final class Broadcaster {
    /**
     * 信道组, 注意这里一定要用 static,
     * 否则无法实现群发
     */
    static private final ChannelGroup _channelGroup = new DefaultChannelGroup(GlobalEventExecutor.INSTANCE);

    /**
     * 私有化类默认构造器
     */
    private Broadcaster() {
    }

    /**
     * 添加信道
     * @param ch
     */
    static public void addChannel(Channel ch) {
        if (null != ch) {
            _channelGroup.add(ch);
        }
    }

    /**
     * 移除信道
     * @param ch
     */
    static public void removeChannel(Channel ch) {
        if (null != ch) {
            _channelGroup.remove(ch);
        }
    }

    /**
     * 广播消息
     *
     * @param msg
     */
    static public void broadcast(Object msg) {
        if (null != msg) {
            _channelGroup.writeAndFlush(msg);
        }
    }
}
```

# 反射的终级实战

继续优化重构代码

每次添加动作还需要修改命令处理器工厂类和消息识别器(GameMsgRecognizer), 那么有没有初始化的时候就把所有的映射关系建立好得办法呢?//答案是肯定的!!

### GameMsgRecognizer的init方法

以前是手动添加映射关系

```java
    static public void init() {
        _msgCodeAndMsgObjMap.put(GameMsgProtocol.MsgCode.USER_ENTRY_CMD_VALUE, GameMsgProtocol.UserEntryCmd.getDefaultInstance());
        _msgCodeAndMsgObjMap.put(GameMsgProtocol.MsgCode.WHO_ELSE_IS_HERE_CMD_VALUE, GameMsgProtocol.WhoElseIsHereCmd.getDefaultInstance());
        _msgCodeAndMsgObjMap.put(GameMsgProtocol.MsgCode.USER_MOVE_TO_CMD_VALUE, GameMsgProtocol.UserMoveToCmd.getDefaultInstance());

        _clazzAndMsgCodeMap.put(GameMsgProtocol.UserEntryResult.class, GameMsgProtocol.MsgCode.USER_ENTRY_RESULT_VALUE);
        _clazzAndMsgCodeMap.put(GameMsgProtocol.WhoElseIsHereResult.class, GameMsgProtocol.MsgCode.WHO_ELSE_IS_HERE_RESULT_VALUE);
        _clazzAndMsgCodeMap.put(GameMsgProtocol.UserMoveToResult.class, GameMsgProtocol.MsgCode.USER_MOVE_TO_RESULT_VALUE);
        _clazzAndMsgCodeMap.put(GameMsgProtocol.UserQuitResult.class, GameMsgProtocol.MsgCode.USER_QUIT_RESULT_VALUE);
    }
```

修改为自动根据命名规则加载实例, 完成映射关系

```java
    static public void init() {
        LOGGER.info("==== 完成消息类与消息编号的映射 ====");

        // 获取内部类
        Class<?>[] innerClazzArray = GameMsgProtocol.class.getDeclaredClasses();

        for (Class<?> innerClazz : innerClazzArray) {
            if (null == innerClazz ||
                !GeneratedMessageV3.class.isAssignableFrom(innerClazz)) {
                // 如果不是消息类,
                continue;
            }

            // 获取类名称并小写
            String clazzName = innerClazz.getSimpleName();
            clazzName = clazzName.toLowerCase();

            for (GameMsgProtocol.MsgCode msgCode : GameMsgProtocol.MsgCode.values()) {
                if (null == msgCode) {
                    continue;
                }

                // 获取消息编码
                String strMsgCode = msgCode.name();
                strMsgCode = strMsgCode.replaceAll("_", "");
                strMsgCode = strMsgCode.toLowerCase();

                if (!strMsgCode.startsWith(clazzName)) {
                    continue;
                }

                try {
                    // 相当于调用 UserEntryCmd.getDefaultInstance();
                    Object returnObj = innerClazz.getDeclaredMethod("getDefaultInstance").invoke(innerClazz);

                    LOGGER.info(
                        "{} <==> {}",
                        innerClazz.getName(),
                        msgCode.getNumber()
                    );

                    _msgCodeAndMsgObjMap.put(
                        msgCode.getNumber(),
                        (GeneratedMessageV3) returnObj
                    );

                    _msgClazzAndMsgCodeMap.put(
                        innerClazz,
                        msgCode.getNumber()
                    );
                } catch (Exception ex) {
                    // 记录错误日志
                    LOGGER.error(ex.getMessage(), ex);
                }
            }
        }
    }
```



### CmdHandlerFactory的init方法

```java
    static public void init() {
        _handlerMap.put(GameMsgProtocol.UserEntryCmd.class, new UserEntryCmdHandler());
        _handlerMap.put(GameMsgProtocol.WhoElseIsHereCmd.class, new WhoElseIsHereCmdHandler());
        _handlerMap.put(GameMsgProtocol.UserMoveToCmd.class, new UserMoveToCmdHandler());
    }
```

一样是自动完成映射, 但是是通过包内的所有类来找对应的GameMsgProtocol处理命令

```java
 	static public void init() {
        LOGGER.info("==== 完成命令与处理器的关联 ====");

        // 获取包名称
        final String packageName = CmdHandlerFactory.class.getPackage().getName();
        // 获取 ICmdHandler 所有的实现类
        Set<Class<?>> clazzSet = PackageUtil.listSubClazz(
            packageName,
            true,
            ICmdHandler.class
        );

        for (Class<?> handlerClazz : clazzSet) {
            if (null == handlerClazz ||
                0 != (handlerClazz.getModifiers() & Modifier.ABSTRACT)) {
                continue;
            }

            // 获取方法数组
            Method[] methodArray = handlerClazz.getDeclaredMethods();
            // 消息类型
            Class<?> cmdClazz = null;

            for (Method currMethod : methodArray) {
                if (null == currMethod ||
                    !currMethod.getName().equals("handle")) {
                    continue;
                }

                // 获取函数参数类型数组
                Class<?>[] paramTypeArray = currMethod.getParameterTypes();

                if (paramTypeArray.length < 2 ||
                    paramTypeArray[1] == GeneratedMessageV3.class ||
                    !GeneratedMessageV3.class.isAssignableFrom(paramTypeArray[1])) {
                    continue;
                }

                cmdClazz = paramTypeArray[1];
                break;
            }

            if (null == cmdClazz) {
                continue;
            }

            try {
                // 创建命令处理器实例
                ICmdHandler<?> newHandler = (ICmdHandler<?>) handlerClazz.newInstance();

                LOGGER.info(
                    "{} <==> {}",
                    cmdClazz.getName(),
                    handlerClazz.getName()
                );

                _handlerMap.put(cmdClazz, newHandler);
            } catch (Exception ex) {
                // 记录错误日志
                LOGGER.error(ex.getMessage(), ex);
            }
        }
    }
```

### 反射终极实战

...看靳海江老师的网游课的小案例



# 将多线程改为单线程

将消息处理抽象为一个类

```java
package org.tinygame.herostory;

import com.google.protobuf.GeneratedMessageV3;
import io.netty.channel.ChannelHandlerContext;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.tinygame.herostory.cmdhandler.CmdHandlerFactory;
import org.tinygame.herostory.cmdhandler.ICmdHandler;

import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

/**
 * 主消息处理器
 */
public final class MainMsgProcessor {
    /**
     * 日志对象
     */
    static private final Logger LOGGER = LoggerFactory.getLogger(MainMsgProcessor.class);

    /**
     * 单例对象
     */
    static private final MainMsgProcessor _instance = new MainMsgProcessor();

    /**
     * 创建一个单线程的线程池
     */
    private final ExecutorService _es = Executors.newSingleThreadExecutor((newRunnable) -> {
        Thread newThread = new Thread(newRunnable);
        newThread.setName("MainMsgProcessor");
        return newThread;
    });
    
    private MainMsgProcessor() {
    }

    /**
     * 获取单例对象
     *
     * @return 单例对象
     */
    static public MainMsgProcessor getInstance() {
        return _instance;
    }

    /**
     * 处理消息
     *
     * @param ctx
     * @param msg
     */
    public void process(ChannelHandlerContext ctx, Object msg) {
        if (null == ctx ||
            null == msg) {
            return;
        }

        final Class<?> msgClazz = msg.getClass();

        LOGGER.info(
            "收到客户端消息, msgClazz = {}, msgObj = {}",
            msgClazz.getSimpleName(),
            msg
        );

        _es.submit(() -> {
            try {
                // 获取命令处理器
                ICmdHandler<? extends GeneratedMessageV3> cmdHandler = CmdHandlerFactory.create(msgClazz);

                if (null != cmdHandler) {
                    cmdHandler.handle(ctx, cast(msg));
                }
            } catch (Exception ex) {
                // 记录错误日志
                LOGGER.error(ex.getMessage(), ex);
            }
        });
    }

    @SuppressWarnings("unchecked")
    static private <TCmd extends GeneratedMessageV3> TCmd cast(Object msg) {
        if (!(msg instanceof GeneratedMessageV3)) {
            return null;
        } else {
            return (TCmd) msg;
        }
    }
}
```

# 多线程异步读写数据库

单线程读写数据库效率慢, 太多人一起登陆的话, 就很慢, 得优化变成多线程. 

由上一节可以知道, 我们单线程的实现是将命令处理放进单线程池内执行

```java
    private final ExecutorService _es = Executors.newSingleThreadExecutor((newRunnable)->{
        Thread newThread = new Thread(newRunnable);
        newThread.setName("MainMsgProcessor");
        return newThread;
    });
```

```java
        _es.submit(()->{
           // 单线程执行
            try {
                ICmdHandler<? extends GeneratedMessageV3> cmdHandler = CmdHandlerFactory.create(msg.getClass());
                if (null != cmdHandler) {
                    cmdHandler.handle(ctx, cast(msg));
                }
            } catch (Exception ex) {
                // 记录错误日志
                LOGGER.error(ex.getMessage(), ex);
            }
        });
```

我们只需要在需要异步的地方执行异步即可, 那么我们可以再新建一个异步多线程队列, 将需要异步多线程的任务放进该队列, 执行完异步多线程队列的任务后, 返回执行单线程的逻辑. 

**单线程队列里需要异步多线程的操作时, 将该操作提交到异步多线程的队列里, 执行完后将结果和余下单线程处理一并提交回单线程队列里**,这里通过callback和doFinish配合完成该效果

新建异步操作处理器

```java
package org.tinygame.herostory.async;

import org.tinygame.herostory.MainThreadProcessor;

import java.text.MessageFormat;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

/**
 * 异步操作处理器
 */
public final class AsyncOperationProcessor {
    /**
     * 单例对象
     */
    static private final AsyncOperationProcessor _instance = new AsyncOperationProcessor();

    /**
     * 单线程数组
     */
    private final ExecutorService[] _esArray = new ExecutorService[8];

    /**
     * 私有化类默认构造器
     */
    private AsyncOperationProcessor() {
        for (int i = 0; i < _esArray.length; i++) {
            // 线程名称
            final String threadName = MessageFormat.format("AsyncOperationProcessor[ {0} ]", i);
            // 创建单线程
            _esArray[i] = Executors.newSingleThreadExecutor((r) -> {
                Thread t = new Thread(r);
                t.setName(threadName);
                return t;
            });
        }
    }

    /**
     * 获取单例对象
     *
     * @return 单例对象
     */
    static public AsyncOperationProcessor getInstance() {
        return _instance;
    }

    /**
     * 执行异步操作
     *
     * @param op 操作对象
     */
    public void process(IAsyncOperation op) {
        if (null == op) {
            return;
        }

        int bindId = Math.abs(op.getBindId());
        int esIndex = bindId % _esArray.length;

        _esArray[esIndex].submit(() -> {
            // 执行异步操作
            op.doAsync();
            // 回到主线程执行完成逻辑
            MainThreadProcessor.getInstance().process(op::doFinish);
        });
    }
}
```

**将需要异步多线程的操作封装成类并继承IAsyncOperation接口**

该类有个单线程池数组, 有个执行异步操作方法, 执行异步操作方法时, 需传入异步操作接口(IAsyncOperation)的实现void doAsync();和default void doFinish(){};

该异步操作是将doAsync()和 MainThreadProcessor.getInstance().process(op::doFinish);一起提交到异步多线程队列, 意思就是 : 先执行异步操作doAsync, 再将doFinish提交到MainThreadProcessor, 这就一定会在doAsync执行完后在执行doFinish

```java
package org.tinygame.herostory.login;

import org.apache.ibatis.session.SqlSession;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.tinygame.herostory.MySqlSessionFactory;
import org.tinygame.herostory.async.AsyncOperationProcessor;
import org.tinygame.herostory.async.IAsyncOperation;
import org.tinygame.herostory.login.db.IUserDao;
import org.tinygame.herostory.login.db.UserEntity;

import java.util.function.Function;

public class LoginService {
    /**
     * 日志服务
     */
    static private final Logger LOGGER = LoggerFactory.getLogger(LoginService.class);

    /**
     * 单例对象
     */
    static private final LoginService _instance = new LoginService();
    /**
     * 私有化类默认构造器
     */
    private LoginService() {
    }

    /**
     * 获取单例对象
     *
     * @return
     */
    static public LoginService getInstance() {
        return _instance;
    }

    /**
     * 用户登陆
     *
     * @param userName
     * @param password
     * @return
     */
    public void userLogin(String userName, String password, Function<UserEntity, Void> callback){
        if(null == userName || null == password){
            return;
        }

        AsyncOperationProcessor.getInstance().process(new AsyncGetUserEntity(userName, password){
            @Override
            public void doFinish() {
                if (null != callback) {
                    callback.apply(this.getUserEntity());
                }
            }
        });
    }

    /**
     * 异步方式获取用户实体
     */
    static private class AsyncGetUserEntity implements IAsyncOperation {
        /**
         * 用户名称
         */
        private final String _userName;

        /**
         * 密码
         */
        private final String _password;

        /**
         * 用户实体
         */
        private UserEntity _userEntity;

        /**
         * 类参数构造器
         *
         * @param userName 用户名称
         * @param password 密码
         */
        AsyncGetUserEntity(String userName, String password) {
            _userName = userName;
            _password = password;
        }

        UserEntity getUserEntity() {
            return _userEntity;
        }

        @Override
        public int getBindId() {
            if (null == _userName) {
                return 0;
            } else {
                return _userName.charAt(_userName.length() - 1);
            }
        }

        @Override
        public void doAsync() {
            try (SqlSession mySqlSession = MySqlSessionFactory.openSession()) {
                IUserDao dao = mySqlSession.getMapper(IUserDao.class);
                UserEntity userEntity = dao.getByUserName(_userName);

                LOGGER.info("当前线程 = {}", Thread.currentThread().getName());

                if(null != userEntity){
                    if(!_password.equals(userEntity.password)){
                        throw new RuntimeException("密码错误");
                    }
                }else{
                    userEntity = new UserEntity();
                    userEntity.userName = _userName;
                    userEntity.password = _password;
                    userEntity.heroAvatar = "Hero_Shaman";

                    dao.insertInto(userEntity);
                }
                _userEntity = userEntity;
            }catch (Exception ex){
                LOGGER.error(ex.getMessage(), ex);
            }
        }

    }
}
```

```java
package org.tinygame.herostory.cmdhandle;

import io.netty.channel.ChannelHandlerContext;
import io.netty.util.AttributeKey;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.tinygame.herostory.async.AsyncOperationProcessor;
import org.tinygame.herostory.login.LoginService;
import org.tinygame.herostory.model.User;
import org.tinygame.herostory.model.UserManager;
import org.tinygame.herostory.msg.GameMsgProtocol;

/**
 * 用户登陆
 */
public class UserLoginCmdHandler implements ICmdHandler<GameMsgProtocol.UserLoginCmd> {

    static private final Logger LOGGER = LoggerFactory.getLogger(UserLoginCmdHandler.class);

    @Override
    public void handle(ChannelHandlerContext ctx, GameMsgProtocol.UserLoginCmd cmd) {
        if (null == ctx ||
                null == cmd) {
            return;
        }

        String userName = cmd.getUserName();
        String password = cmd.getPassword();

        if (null == userName || null == password) {
            return;
        }

        // 获取用户实体
        LoginService.getInstance().userLogin(userName, password, (userEntity)->{

            GameMsgProtocol.UserLoginResult.Builder resultBuilder = GameMsgProtocol.UserLoginResult.newBuilder();

            LOGGER.info("当前线程 = {}", Thread.currentThread().getName());

            if (null == userEntity) {
                resultBuilder.setUserId(-1);
                resultBuilder.setUserName("");
                resultBuilder.setHeroAvatar("");
            }  else {
                User newUser = new User();
                newUser.userId = userEntity.userId;
                newUser.userName = userEntity.userName;
                newUser.heroAvatar = userEntity.heroAvatar;
                newUser.currHp = 100;
                UserManager.addUser(newUser);

                // 将用户 Id 保存至 Session
                ctx.channel().attr(AttributeKey.valueOf("userId")).set(newUser.userId);

                resultBuilder.setUserId(userEntity.userId);
                resultBuilder.setUserName(userEntity.userName);
                resultBuilder.setHeroAvatar(userEntity.heroAvatar);
            }

            GameMsgProtocol.UserLoginResult newResult = resultBuilder.build();
            ctx.writeAndFlush(newResult);

            return null;
        });
    }
}
```

#### 一些解释:

**↓这里是为了同一个操作用户点两次的话, 让任务在同一个单线程进行,这样不会导致操作重复执行, 比如防止用户领两次礼包**

```java
        int bindId = Math.abs(op.getBindId());//getBindId自己定是什么
        int esIndex = bindId % _esArray.length;

        _esArray[esIndex].submit(()->{...
```

**↓保证了异步操作完成后才执行剩余的逻辑操作**

```java
        _esArray[esIndex].submit(() -> {
            // 执行异步操作
            op.doAsync();
            // 回到主线程执行完成逻辑(提交doFinish()到主线程(单)队列)
            MainThreadProcessor.getInstance().process(op::doFinish);
        });
```

**↓执行异步操作时传入一个callback来获取异步结果并添加到单线程执行执行.**  

```java
        AsyncOperationProcessor.getInstance().process(new AsyncGetUserEntity(userName, password){
            @Override
            public void doFinish() {
                if (null != callback) {
                    callback.apply(this.getUserEntity());
                }
            }
        });
```



# RocketMQ 实现简单的排行榜



# 游戏服务器的部署