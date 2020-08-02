int[] myList = new int[10];

E[] data = (E[]) new Objecj[capacity];

### Socket

客户端获取服务端socket信息

```
import java.io.*
import java.net.*

Socket s = new Socket("127.0.0.1" , 4242);

InputStreamReade streamReader - new InputStreamReader(s.getInputStream());
BufferedReader reader = new BufferedReader(streamReader);
String advice = reader.readLine();
reader.close();

```

