# 多线程与高并发

## 基础介绍

线程指的是程序执行路径, 多线程就是多个执行路径, CPU在多个路径里来回切换.

```java
public class Main {
    public static void main(String[] args) {
        Runnable1 r = new Runnable1();
        Thread t = new Thread(r);
        t.start();
        for (int i = 0; i < 100; i++){
            System.out.printf("嘿嘿嘿");
        }
    }
}
class Runnable1 implements Runnable//还有继承Thread类的方法,一般使用接口方式实现, 方便灵活
{
    @Override
    public void run() {
        for (int i = 0; i < 100; i++){
            System.out.printf("啦啦啦");
        }
    }
}
输出: 
嘿嘿嘿
啦啦啦
嘿嘿嘿
```

thread的一些重要方法: 

```
start()#是线程进入就绪状态,以供CPU调用
run()#CPU调用方法执行任务
setName()#设置线程名
setPriority()#更改线程的优先级。
setDaemon()#将该线程标记为守护线程或用户线程。
join()#线程执行完后才执行当前线程
interrupt()#中断线程。
isAlive()//测试线程是否处于活动状态
静态
yield()#暂停当前正在执行的线程对象，并执行其他线程
sleep()#在指定的毫秒数内让当前正在执行的线程休眠（暂停执行），此操作受到系统计时器和调度程序精度和准确性的影响。
holdsLock()#当且仅当当前线程在指定的对象上保持监视器锁时，才返回 true。
currentThread()#返回对当前正在执行的线程对象的引用。
dumpStack()#将当前线程的堆栈跟踪打印至标准错误流。
```

