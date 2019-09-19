* FIFO(队列)
* 数组尾进 O(1)
* 数组头出 O(n)
* 这种方式数组头出时间复杂度高,所以使用循环队列LoopQueue

public class ArrayQueue<E> implements Queue<E> {

    private Array<E> array;

    public ArrayQueue(int capacity) {
        array = new Array(capacity);
    }

    public ArrayQueue() {
        array = new Array();
    }

    @Override
    public boolean isEmpty() {
        return array.isEmpty();
    }

    @Override
    public int getSize() {
        return array.getSize();
    }

    public int getCapacity() {
        return array.getCapacity();
    }

    @Override
    public void enqueue(E e) {
        array.addLast(e);
    }

    @Override
    public E dequeue() {
        return array.removeFirst();
    }

    @Override
    public E getFront() {
        return array.getFirst();
    }

    @Override
    public String toString() {
        StringBuilder str = new StringBuilder();
        str.append("Queue: ");
        str.append("front [");
        for (int i = 0; i < array.getSize(); i++) {
            str.append(array.get(i));
            if (i != array.getSize() - 1) {
                str.append(", ");
            }
        }
        str.append("] tail");
        return str.toString();
    }
}
