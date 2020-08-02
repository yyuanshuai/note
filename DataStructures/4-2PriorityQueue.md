### 优先队列
操作 | 入队 | 出队
--:|:--:|:--
普通线性结构|O(1)|O(n)|
顺序线性结构|O(n)|O(1)|
堆|O(logn)|O(logn)|



```java

public class PriorityQueue<E extends Comparable<E>> implements Queue<E> {

    private MaxHeap<E> maxHeap;

    public PriorityQueue(){
        maxHeap = new MaxHeap<>();
    }

    @Override
    public int getSize(){
        return maxHeap.size();
    }

    @Override
    public boolean isEmpty(){
        return maxHeap.isEmpty();
    }

    @Override
    public E getFront(){
        return maxHeap.findMax();
    }

    @Override
    public void enqueue(E e){
        maxHeap.add(e);
    }

    @Override
    public E dequeue(){
        return maxHeap.extractMax();
    }
}
```