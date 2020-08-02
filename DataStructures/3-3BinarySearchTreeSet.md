操作 | LinkedListSet | BinarySearchTreeSet
--:|:--:|:--
add |    O(n) | O(h) 平均: O(logn) 最差: O(n)
contains|O(n) | O(h) 平均: O(logn) 最差: O(n)
remove  |O(n) | O(h) 平均: O(logn) 最差: O(n)

```java
public class BinarySearchTreeSet<E extends Comparable<E>> implements Set<E> {

    private BinarySearchTree<E> bst;

    public BinarySearchTreeSet(){
        bst = new BinarySearchTree<>();
    }

    @Override
    public int getSize(){
        return bst.getSize();
    }

    @Override
    public boolean isEmpty(){
        return bst.isEmpty();
    }

    @Override
    public void add(E e){
        bst.add(e);
    }

    @Override
    public boolean contains(E e){
        return bst.contains(e);
    }

    @Override
    public void remove(E e){
        bst.remove(e);
    }
}
```