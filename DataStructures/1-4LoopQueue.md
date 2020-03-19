* 循环队列时间复杂度: O(1)

* //ArrayQueue: 3.333271172

* //LoopQueue: 0.011785373

  

public class LoopQueue<E> implements Queue<E> {

    private E[] data;
    
    private int front;
    
    private int tail;
    
    public LoopQueue(int capacity) {
        data = (E[]) new Object[capacity + 1];
        front = 0;
        tail = 0;
    }
    
    public LoopQueue() {
        this(10);
    }
    
    public int getCapacity() {
        return data.length - 1;
    }
    
    @Override
    public boolean isEmpty() {
        return front == tail;
    }
    
    @Override
    public int getSize() {
        return tail >= front ? tail - front : tail - front + data.length;
    }
    
    @Override
    public void enqueue(E e) {
        if ((tail + 1) % data.length == front) {
            resize(getCapacity() * 2);
        }
        data[tail] = e;
        tail = (tail + 1) % data.length;
    }
    
    @Override
    public E dequeue() {
        if (isEmpty()) {
            throw new IllegalArgumentException("队列为空");
        }
        E ret = data[front];
        data[front] = null;
        front = (front + 1) % data.length;
        if (getSize() == getCapacity() / 4 && getCapacity() / 2 != 0) {
            resize(getCapacity() / 2);
        }
        return ret;
    }
    
    @Override
    public E getFront() {
        if (isEmpty()) {
            throw new IllegalArgumentException("队列为空");
        }
        return data[front];
    }
    
    private void resize(int newCapacity) {
        E[] newData = (E[]) new Object[newCapacity + 1];
        int size = getSize();
        for (int i = 0; i < size; i++) {
            newData[i] = data[(i + front) % data.length];
        }
        data = newData;
        front = 0;
        tail = size;
    }
    
    @Override
    public String toString() {
        StringBuilder res = new StringBuilder();
        res.append(String.format("Queue: size = %d, capacity = %d \n", getSize(), getCapacity()));
        res.append("front [");
    
        for (int i = front; i != tail; i = (i + 1) % data.length) {
            res.append(data[i]);
            if ((i + 1) % data.length != tail) {
                res.append(", ");
            }
        }
        res.append("] tail");
        return res.toString();
    }
}
