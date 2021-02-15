## IDEA 快捷键（摆脱鼠标）

来源 | blog.csdn.net/qq_37918553/article/details/83821856

- IDEA 快捷键（摆脱鼠标）

- - 一、跳转
    - 二、高效定位代码
    - 三、列操作
    - 四、Livetemplate
    - 五、Postfix
    - 六、Alter+enter
    - 七、重构
    - 八、抽取
    - 九、寻找修改轨迹
    - 十、关联
    - 十一、断点调试
    - 十二、文件操作
    - 十三、文本操作
    - 十四、结构图
    - 十五、其他快捷键



## IDEA 快捷键（摆脱鼠标）

### 一、跳转

1.ctrl+Alt+[] 项目之间跳转

2.ctrl+e 最近的文件

3.ctrl+shift+e 最近编辑的文件

4.ctrl+shift+backspace 浏览修改位置的跳转

5.ctrl+shift+左箭头（win10会改变屏幕方向） 最新浏览位置的修改

6.使用书签进行跳转

```
ctrl+shift+数字或字母 标记书签
ctrl+数字或者字母 跳转书签
shift+F11 总览书签
```

7.Ctrl+Up/Down 光标中转到当前界面第一行或最后一行下

8.Ctrl+[OR] 可以跑到大括号的开头与结尾

9.F2 1跳转到错误位置

### 二、高效定位代码

1.ctrl+n 定位类

2.ctrl+shift+n 定位文件

3.ctrl+shift+alt+n 定位函数或者属性

4.ctrl+shift+f 定位字符串

### 三、列操作

1.ctrl+shift+alt+j 列操作（多行逻辑操作、批量操作）

2.Ctrl+←(→) 移动到一个词的开始(结尾)

3.Ctrl+Shift+←(→) 从后到前（从前到后）选中一个词

4.Ctrl+Home 第一行代码

5.Ctrl+End 最后一行代码

6.ctrl+alt+l 代码格式化

### 四、Livetemplate

1.Livetemplate 自定义代码模块快捷键和内容

位置:ctrl+shift+a查找livetemplates,回车

```
$VAR1第一个值$VAR2第一个值$VAR2第二个值
$END$鼠标停留位置
回车跳转到到下个值
```

### 五、Postfix

1.postfix 参数后面点函数回车，生成代码 位置:ctrl+shift+a查找postfix,回车 name.field——可自动添加this.name=name以及private String name; 常用：

```
-foo.fori        for(int i = 0; i < foo; i++){}
-foo.return      return foo;
-foo.sout        System.out.println(foo);
-foo.field       private Foo foo;  this.foo = foo;
-foo.nn    if(user!=null){}
```

### 六、Alter+enter

1.Alter+enter 智能提示

```
-自动创建函数
-list replace 列表替换优化
-字符串format或者build 字符串格式化，build优化减小内存
-接口实现 在接口上回车可自动创建实现类
-单词拼写 波浪线为存在单词问题，可校对单词
-导包
-不知道怎么做就试试Alter+enter
```

### 七、重构

1.shift+F6 重构，将某个参数全部修改。

```
-重构变量
-重构方法
```

### 八、抽取

1.抽取，将常用数据抽取出来变成简单变量或函数。

```
-抽取变量 Ctrl+Alt+V
-抽取静态变量 Ctrl+Alt+C
-抽取成员变量 Ctrl+Alt+F
-抽取方法参数 Ctrl+Alt+P
-抽取函数 Ctrl+Alt+M
```

### 九、寻找修改轨迹

1.annotate

```
代码前右击，选中annotate，可以找到代码的所有者，更进一步点击，还可以找到该作者的修改记录
```

2.Ctrl+Alt+Shift+上下箭头 寻找改动的地方 3.Ctrl+Alt+Z 撤销，包括单个和项目改动之处 4.Local history idea本地历史记录

```
Put Label 本地存档说明
Put Label可以用Ctrl+Alt+A的Local History里找到
```

### 十、关联

1.spring关联

```
Ctrl+Alt+Shift+S 中的Facets配置。
可在代码前的行数中看到Spring的关系
```

2.与数据库关联

```
添加Database在mapper输入时可以自动提示Database字段。
Shinf+f6重构改表名mapper也可以改。或者直接改。
```

### 十一、断点调试

1.Ctrl+F8 添加删除断点

2.Shift +f9 dubug运行

3.F8 单步运行

4.F9 跳到下一个断点

5.Ctrl+Shift+F8 查看所有断点位置（在有断点的位置为设置条件断点）

6.Alt+F8 查看当前变量值和表达式求值

7.Alt+F9 运行到光标位置

8.setValue 在debug页面按F2动态设置传递的值

9.Ctrl+Shift+f9 运行光标最小上下文

1. Shift+Alt+F9 最近运行的历史列表选择运行

### 十二、文件操作

1.Ctrl+Alt+Insert 新建文件

2.F5 复制文件

3.F6 移动文件

### 十三、文本操作

1.Ctrl+C 复制文件名

2.Ctrl+Shift+C 复制文件路径

3.Ctrl+Shift+V 剪切板（历史复制）

### 十四、结构图

1.Ctrl+F12 查看当前field,method大纲

2.Ctrl+alt+Shift+U查看maven依赖，类图

3.Ctrl+H，查看类的继承关系

4.Ctrl+Alt+H，查看方法的调用和被调用关系

### 十五、其他快捷键

1.Alt+Q 可以看到当前方法的声明

2.Alt+Insert 可以生成构造器/Getter/Setter等

3.Ctrl+/或Ctrl+Shift+/ 注释（//或者/**/）

4.Ctrl+J 自动代码（例如：serr）

5.Ctrl+Shift+J 整合两行

6.Ctrl+Shift+U 大小写转化

7.Ctrl+Y 删除当前行

8.Ctrl+D 复制当前行

9.Shift+Enter 向下插入新行

10.Ctrl+”+/-”，当前方法展开、折叠

11.Ctrl+Shift+”+/-”，全部展开、折叠





### WINDOWS

```
Ctrl + F	在当前文件进行文本查找 （必备）
Ctrl + R	在当前文件进行文本替换 （必备）
Ctrl + Y	删除光标所在行 或 删除选中的行 （必备）
Ctrl + W	递进式选择代码块。可选中光标所在的单词或段落，连续按会在原有选中的基础上再扩展选中范围 （必备）
Ctrl + E	显示最近打开的文件记录列表 （必备）
Ctrl + N	根据输入的 类名 查找类文件 （必备）
Ctrl + P	方法参数提示显示 （必备）
Ctrl + U	前往当前光标所在的方法的父类的方法 / 接口定义 （必备）
Ctrl + B	进入光标所在的方法/变量的接口或是定义处，等效于 Ctrl + 左键单击 （必备）
Ctrl + F1	在光标所在的错误代码处显示错误信息 （必备）
Ctrl + F3	调转到所选中的词的下一个引用位置 （必备）
Ctrl + F11	选中文件 / 文件夹，使用助记符设定 / 取消书签 （必备）
Ctrl + Space	基础代码补全，默认在 Windows 系统上被输入法占用，需要进行修改，建议修改为 Ctrl + 逗号 （必备）
Ctrl + Delete	删除光标后面的单词或是中文句 （必备）
Ctrl + BackSpace	删除光标前面的单词或是中文句 （必备）
Ctrl + 1,2,3...9	定位到对应数值的书签位置 （必备）
Ctrl + 左键单击	在打开的文件标题上，弹出该文件路径 （必备）
Ctrl + 左方向键	光标跳转到当前单词 / 中文句的左侧开头位置 （必备）
Ctrl + 右方向键	光标跳转到当前单词 / 中文句的右侧开头位置 （必备）
Ctrl + 前方向键	等效于鼠标滚轮向前效果 （必备）
Ctrl + 后方向键	等效于鼠标滚轮向后效果 （必备）

Alt + ` 显示版本控制常用操作菜单弹出层 （必备）
Alt + Enter	IntelliJ IDEA 根据光标所在问题，提供快速修复选择，光标放在的位置不同提示的结果也不同 （必备）
Alt + Insert	代码自动生成，如生成对象的 set / get 方法，构造函数，toString() 等 （必备）
Alt + 左方向键	切换当前已打开的窗口中的子视图，比如Debug窗口中有Output、Debugger等子视图，用此快捷键就可以在子视图中切换 （必备）
Alt + 右方向键	按切换当前已打开的窗口中的子视图，比如Debug窗口中有Output、Debugger等子视图，用此快捷键就可以在子视图中切换 （必备）
Alt + 前方向键	当前光标跳转到当前文件的前一个方法名位置 （必备）
Alt + 后方向键	当前光标跳转到当前文件的后一个方法名位置 （必备）
Alt + 1,2,3...9	显示对应数值的选项卡，其中 1 是 Project 用得最多 （必备）

Shift + Enter	开始新一行。光标所在行下空出一行，光标定位到新行位置 （必备）
Shift + 左键单击	在打开的文件名上按此快捷键，可以关闭当前打开文件 （必备）
Shift + 滚轮前后滚动	当前文件的横向滚动轴滚动 （必备）

Ctrl + Alt + L	格式化代码，可以对当前文件和整个包目录使用 （必备）
Ctrl + Alt + O	优化导入的类，可以对当前文件和整个包目录使用 （必备）
Ctrl + Alt + I	光标所在行 或 选中部分进行自动代码缩进，有点类似格式化
Ctrl + Alt + T	对选中的代码弹出环绕选项弹出层 （必备）
Ctrl + Alt + J	弹出模板选择窗口，将选定的代码加入动态模板中
Ctrl + Alt + H	调用层次
Ctrl + Alt + B	在某个调用的方法名上使用会跳到具体的实现处，可以跳过接口
Ctrl + Alt + C	重构-快速提取常量
Ctrl + Alt + F	重构-快速提取成员变量
Ctrl + Alt + V	重构-快速提取变量
Ctrl + Alt + Y	同步、刷新
Ctrl + Alt + S	打开 IntelliJ IDEA 系统设置 （必备）
Ctrl + Alt + F7	显示使用的地方。寻找被该类或是变量被调用的地方，用弹出框的方式找出来
Ctrl + Alt + F11	切换全屏模式
Ctrl + Alt + Enter	光标所在行上空出一行，光标定位到新行 （必备）
Ctrl + Alt + Home	弹出跟当前文件有关联的文件弹出层
Ctrl + Alt + Space	类名自动完成
Ctrl + Alt + 左方向键	退回到上一个操作的地方 （必备）
Ctrl + Alt + 右方向键	前进到上一个操作的地方 （必备）
Ctrl + Alt + 前方向键	在查找模式下，跳到上个查找的文件
Ctrl + Alt + 后方向键	在查找模式下，跳到下个查找的文件
Ctrl + Alt + 右括号（]）	在打开多个项目的情况下，切换下一个项目窗口
Ctrl + Alt + 左括号（[）	在打开多个项目的情况下，切换上一个项目窗口

Ctrl + Shift + F	根据输入内容查找整个项目 或 指定目录内文件 （必备）
Ctrl + Shift + R	根据输入内容替换对应内容，范围为整个项目 或 指定目录内文件 （必备）
Ctrl + Shift + J	自动将下一行合并到当前行末尾 （必备）
Ctrl + Shift + Z	取消撤销 （必备）
Ctrl + Shift + W	递进式取消选择代码块。可选中光标所在的单词或段落，连续按会在原有选中的基础上再扩展取消选中范围 （必备）
Ctrl + Shift + N	通过文件名定位 / 打开文件 / 目录，打开目录需要在输入的内容后面多加一个正斜杠 （必备）
Ctrl + Shift + U	对选中的代码进行大 / 小写轮流转换 （必备）
Ctrl + Shift + T	对当前类生成单元测试类，如果已经存在的单元测试类则可以进行选择 （必备）
Ctrl + Shift + C	复制当前文件磁盘路径到剪贴板 （必备）
Ctrl + Shift + V	弹出缓存的最近拷贝的内容管理器弹出层
Ctrl + Shift + E	显示最近修改的文件列表的弹出层
Ctrl + Shift + H	显示方法层次结构
Ctrl + Shift + B	跳转到类型声明处 （必备）
Ctrl + Shift + I	快速查看光标所在的方法 或 类的定义
Ctrl + Shift + A	查找动作 / 设置
Ctrl + Shift + /	代码块注释 （必备）
Ctrl + Shift + [	选中从光标所在位置到它的顶部中括号位置 （必备）
Ctrl + Shift + ]	选中从光标所在位置到它的底部中括号位置 （必备）
Ctrl + Shift + +	展开所有代码 （必备）
Ctrl + Shift + -	折叠所有代码 （必备）
Ctrl + Shift + F7	高亮显示所有该选中文本，按Esc高亮消失 （必备）
Ctrl + Shift + F8	在 Debug 模式下，指定断点进入条件
Ctrl + Shift + F9	编译选中的文件 / 包 / Module
Ctrl + Shift + F12	编辑器最大化 （必备）
Ctrl + Shift + Space	智能代码提示
Ctrl + Shift + Enter	自动结束代码，行末自动添加分号 （必备）
Ctrl + Shift + Backspace	退回到上次修改的地方 （必备）
Ctrl + Shift + 1,2,3...9	快速添加指定数值的书签 （必备）
Ctrl + Shift + 左键单击	把光标放在某个类变量上，按此快捷键可以直接定位到该类中 （必备）
Ctrl + Shift + 左方向键	在代码文件上，光标跳转到当前单词 / 中文句的左侧开头位置，同时选中该单词 / 中文句 （必备）
Ctrl + Shift + 右方向键	在代码文件上，光标跳转到当前单词 / 中文句的右侧开头位置，同时选中该单词 / 中文句 （必备）
Ctrl + Shift + 前方向键	光标放在方法名上，将方法移动到上一个方法前面，调整方法排序 （必备）
Ctrl + Shift + 后方向键	光标放在方法名上，将方法移动到下一个方法前面，调整方法排序 （必备）

Alt + Shift + N	选择 / 添加 task （必备）
Alt + Shift + F	显示添加到收藏夹弹出层 / 添加到收藏夹
Alt + Shift + C	查看最近操作项目的变化情况列表
Alt + Shift + I	查看项目当前文件
Alt + Shift + F7	在 Debug 模式下，下一步，进入当前方法体内，如果方法体还有方法，则会进入该内嵌的方法中，依此循环进入
Alt + Shift + F9	弹出 Debug 的可选择菜单
Alt + Shift + F10	弹出 Run 的可选择菜单
Alt + Shift + 左键双击	选择被双击的单词 / 中文句，按住不放，可以同时选择其他单词 / 中文句 （必备）
Alt + Shift + 前方向键	移动光标所在行向上移动 （必备）
Alt + Shift + 后方向键	移动光标所在行向下移动 （必备）

Ctrl + Shift + Alt + V	无格式黏贴 （必备）
Ctrl + Shift + Alt + N	前往指定的变量 / 方法
Ctrl + Shift + Alt + S	打开当前项目设置 （必备）
Ctrl + Shift + Alt + C	复制参考信息

F2	跳转到下一个高亮错误 或 警告位置 （必备）
F3	在查找模式下，定位到下一个匹配处
F4	编辑源 （必备）
F7	在 Debug 模式下，进入下一步，如果当前行断点是一个方法，则进入当前方法体内，如果该方法体还有方法，则不会进入该内嵌的方法中
F8	在 Debug 模式下，进入下一步，如果当前行断点是一个方法，则不进入当前方法体内
F9	在 Debug 模式下，恢复程序运行，但是如果该断点下面代码还有断点则停在下一个断点上
F11	添加书签 （必备）
F12	回到前一个工具窗口 （必备）
Tab	缩进 （必备）
ESC	从工具窗口进入代码文件窗口 （必备）
连按两次Shift	弹出 Search Everywhere 弹出层

```





### Mac

```
ctrl + g//选中下一个当前选中项
alt + enter//引入类包
Ctrl + alt + o//引入全部类包并移除全部无效引用包

```

