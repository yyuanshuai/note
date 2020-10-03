**堆**（英语：Heap）是[计算机科学](https://zh.wikipedia.org/wiki/计算机科学)中的一种特别的[完全二叉树](https://zh.wikipedia.org/wiki/完全二叉树)。若是满足以下特性，即可称为堆：“给定堆中任意[节点](https://zh.wikipedia.org/wiki/節點)P和C，若P是C的母节点，那么P的值会小于等于（或大于等于）C的值”。若母节点的值恒**小于等于**子节点的值，此堆称为**最小堆**（min heap）；反之，若母节点的值恒**大于等于**子节点的值，此堆称为**最大堆**（max heap）。在堆中最顶端的那一个节点，称作**根节点**（root node），根节点本身没有**母节点**（parent node）。

堆始于[J. W. J. Williams](https://zh.wikipedia.org/w/index.php?title=J._W._J._Williams&action=edit&redlink=1)在1964年发表的**堆排序**（heap sort），当时他提出了二叉堆树作为此算法的数据结构。堆在[戴克斯特拉算法](https://zh.wikipedia.org/wiki/戴克斯特拉算法)（英语：Dijkstra's algorithm）中亦为重要的关键。

在[队列](https://zh.wikipedia.org/wiki/队列)中，调度程序反复提取队列中第一个作业并运行，因为实际情况中某些时间较短的任务将等待很长时间才能结束，或者某些不短小，但具有重要性的作业，同样应当具有优先权。堆即为解决此类问题设计的一种数据结构。[[1\]](https://zh.wikipedia.org/wiki/堆積#cite_note-定义-1)

## 性质[[编辑](https://zh.wikipedia.org/w/index.php?title=堆積&action=edit&section=1)]

堆的实现通过构造**二叉堆**（binary heap），实为[二叉树](https://zh.wikipedia.org/wiki/二叉树)的一种；由于其应用的普遍性，当不加限定时，均指该数据结构的这种实现。这种数据结构具有以下性质。

- 任意节点小于（或大于）它的所有后裔，最小元（或最大元）在堆的根上（**堆序性**）。
- 堆总是一棵[完全树](https://zh.wikipedia.org/wiki/完全二叉树)。即除了最底层，其他层的节点都被元素填满，且最底层尽可能地从左到右填入。

将根节点最大的堆叫做**最大堆**或**大根堆**，根节点最小的堆叫做**最小堆**或**小根堆**。常见的堆有[二叉堆](https://zh.wikipedia.org/wiki/二叉堆)、[斐波那契堆](https://zh.wikipedia.org/wiki/斐波那契堆)等。



i位置的

左子节点位置: i * 2  +1

右子节点位置: i * 2 + 2 

父节点位置: (i - 1) / 2



若不使用数组0位置的话

i位置的

左子节点位置:i * 2 等于 ( i << 1)

右子节点位置: i * 2 +1 等于 ( i<<1 |1)

父节点位置: i / 2 等于 ( i>>1)



|  操作   | 描述                         | [时间复杂度](https://zh.wikipedia.org/wiki/时间复杂度) |
| :-----: | ---------------------------- | :----------------------------------------------------: |
|  build  | 创建一个空堆                 |                          O(n)                          |
| insert  | 向堆中插入一个新元素         |                        O(log n)                        |
| update  | 将新元素提升使其符合堆的性质 |                                                        |
|   get   | 获取当前堆顶元素的值         |                          O(1)                          |
| delete  | 删除堆顶元素                 |                        O(log n)                        |
| heapify | 使删除堆顶元素的堆再次成为堆 |                                                        |

将数组变成堆结构的两种方法

```java
//相当于一个一个加进堆里, 从上往下构建堆结构
for(int i = 0; i < arr.length; i++){//O(N)
	heapInsert(arr, i, arr.length);//O(logN)
}
//
for(int = arr.length; i >= 0; i--){
	heapify(arr, i, arr.length);//O(N)
}
```

```java
//从index位置, 往上看, 上浮
private void heapInsert(int[] arr, int index) {
  while (arr[index] > arr[(index - 1) / 2]) {
    swap(arr, index, (index - 1) / 2);
    index = (index - 1) / 2;
  }
}
```

```java
// 从index位置，往下看，不断的下沉
private void heapify(int[] arr, int index, int heapSize) {
  int left = index * 2 + 1;
  while (left < heapSize) {
    int largest = left + 1 < heapSize && arr[left + 1] > arr[left] ? left + 1 : left;
    largest = arr[largest] > arr[index] ? largest : index;
    if (largest == index) {
      break;
    }
    swap(arr, largest, index);
    index = largest;
    left = index * 2 + 1;
  }
}
```



与堆有关的题目

1. 已知一个几乎有序的数组. 几乎有序是指, 如果把数组排好顺序的话, 每个元素移动的距离不超过k,并且k相对于数组长度来说是比较小的. 

   请选择一个合适的排序策略, 对这个数组进行排序.

   ```java
   //已知每个元素移动的距离不超过k, 那么可以将前k个元素放入小根堆中, 然后弹出最小值, 再将k+1个元素放入该堆中, 弹出最小值, 如此往复, 便可得到从小到大的数组
   	public static void sortedArrDistanceLessK(int[] arr, int k) {
   		if (k == 0) {
   			return;
   		}
   		// 默认小根堆
   		PriorityQueue<Integer> heap = new PriorityQueue<>();
   		int index = 0;
   		// 0...K-1
   		for (; index <= Math.min(arr.length - 1, k - 1); index++) {
   			heap.add(arr[index]);
   		}
   		int i = 0;
   		for (; index < arr.length; i++, index++) {
   			heap.add(arr[index]);
   			arr[i] = heap.poll();
   		}
   		while (!heap.isEmpty()) {
   			arr[i++] = heap.poll();
   		}
   	}
   ```

   > 复杂度为N\*logk, 比原来的N\*logN要好




语言提供的堆结构 VS 手写的堆结构

取决于, 你有没有动态改信息的需求 

语言提供的堆结构, 如果你动态改数据, 不保证依然有序

手写堆结构, 因为增加了对象的位置表, 所以能够保证动态改信息的需求

```java
package class04;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.HashMap;
import java.util.PriorityQueue;

public class Code03_Heap02 {

	// 堆
	public static class MyHeap<T> {
		private ArrayList<T> heap;
		private HashMap<T, Integer> indexMap;
		private int heapSize;
		private Comparator<? super T> comparator;

		public MyHeap(Comparator<? super T> com) {
			heap = new ArrayList<>();
			indexMap = new HashMap<>();
			heapSize = 0;
			comparator = com;
		}

		public boolean isEmpty() {
			return heapSize == 0;
		}

		public int size() {
			return heapSize;
		}

		public boolean contains(T key) {
			return indexMap.containsKey(key);
		}

		public void push(T value) {
			heap.add(value);
			indexMap.put(value, heapSize);
			heapInsert(heapSize++);
		}

		public T pop() {
			T ans = heap.get(0);
			int end = heapSize - 1;
			swap(0, end);
			heap.remove(end);
			indexMap.remove(ans);
			heapify(0, --heapSize);
			return ans;
		}

		public void resign(T value) {
			int valueIndex = indexMap.get(value);
			heapInsert(valueIndex);
			heapify(valueIndex, heapSize);
		}

		private void heapInsert(int index) {
			while (comparator.compare(heap.get(index), heap.get((index - 1) / 2)) < 0) {
				swap(index, (index - 1) / 2);
				index = (index - 1) / 2;
			}
		}

		private void heapify(int index, int heapSize) {
			int left = index * 2 + 1;
			while (left < heapSize) {
				int largest = left + 1 < heapSize && (comparator.compare(heap.get(left + 1), heap.get(left)) < 0)
						? left + 1
						: left;
				largest = comparator.compare(heap.get(largest), heap.get(index)) < 0 ? largest : index;
				if (largest == index) {
					break;
				}
				swap(largest, index);
				index = largest;
				left = index * 2 + 1;
			}
		}

		private void swap(int i, int j) {
			T o1 = heap.get(i);
			T o2 = heap.get(j);
			heap.set(i, o2);
			heap.set(j, o1);
			indexMap.put(o1, j);
			indexMap.put(o2, i);
		}

	}

	public static class Student {
		public int classNo;
		public int age;
		public int id;

		public Student(int c, int a, int i) {
			classNo = c;
			age = a;
			id = i;
		}

	}

	public static class StudentComparator implements Comparator<Student> {

		@Override
		public int compare(Student o1, Student o2) {
			return o1.age - o2.age;
		}

	}

	public static void main(String[] args) {
		Student s1 = null;
		Student s2 = null;
		Student s3 = null;
		Student s4 = null;
		Student s5 = null;
		Student s6 = null;

		s1 = new Student(2, 50, 11111);
		s2 = new Student(1, 60, 22222);
		s3 = new Student(6, 10, 33333);
		s4 = new Student(3, 20, 44444);
		s5 = new Student(7, 72, 55555);
		s6 = new Student(1, 14, 66666);

		PriorityQueue<Student> heap = new PriorityQueue<>(new StudentComparator());
		heap.add(s1);
		heap.add(s2);
		heap.add(s3);
		heap.add(s4);
		heap.add(s5);
		heap.add(s6);
		while (!heap.isEmpty()) {
			Student cur = heap.poll();
			System.out.println(cur.classNo + "," + cur.age + "," + cur.id);
		}

		System.out.println("===============");

		MyHeap<Student> myHeap = new MyHeap<>(new StudentComparator());
		myHeap.push(s1);
		myHeap.push(s2);
		myHeap.push(s3);
		myHeap.push(s4);
		myHeap.push(s5);
		myHeap.push(s6);
		while (!myHeap.isEmpty()) {
			Student cur = myHeap.pop();
			System.out.println(cur.classNo + "," + cur.age + "," + cur.id);
		}

		System.out.println("===============");

		s1 = new Student(2, 50, 11111);
		s2 = new Student(1, 60, 22222);
		s3 = new Student(6, 10, 33333);
		s4 = new Student(3, 20, 44444);
		s5 = new Student(7, 72, 55555);
		s6 = new Student(1, 14, 66666);

		heap = new PriorityQueue<>(new StudentComparator());

		heap.add(s1);
		heap.add(s2);
		heap.add(s3);
		heap.add(s4);
		heap.add(s5);
		heap.add(s6);

		s2.age = 6;
		s4.age = 12;
		s5.age = 10;
		s6.age = 84;

		while (!heap.isEmpty()) {
			Student cur = heap.poll();
			System.out.println(cur.classNo + "," + cur.age + "," + cur.id);
		}

		System.out.println("===============");

		s1 = new Student(2, 50, 11111);
		s2 = new Student(1, 60, 22222);
		s3 = new Student(6, 10, 33333);
		s4 = new Student(3, 20, 44444);
		s5 = new Student(7, 72, 55555);
		s6 = new Student(1, 14, 66666);

		myHeap = new MyHeap<>(new StudentComparator());

		myHeap.push(s1);
		myHeap.push(s2);
		myHeap.push(s3);
		myHeap.push(s4);
		myHeap.push(s5);
		myHeap.push(s6);

		s2.age = 6;
		myHeap.resign(s2);
		s4.age = 12;
		myHeap.resign(s4);
		s5.age = 10;
		myHeap.resign(s5);
		s6.age = 84;
		myHeap.resign(s6);

		while (!myHeap.isEmpty()) {
			Student cur = myHeap.pop();
			System.out.println(cur.classNo + "," + cur.age + "," + cur.id);
		}
		
		
		
		// 对数器
		System.out.println("test begin");
		int maxValue = 100000;
		int pushTime = 1000000;
		int resignTime = 100;
		MyHeap<Student> test = new MyHeap<>(new StudentComparator());
		ArrayList<Student> list = new ArrayList<>();
		for(int i = 0 ; i < pushTime; i++) {
			Student cur = new Student(1,(int) (Math.random() * maxValue), 1000);
			list.add(cur);
			test.push(cur);
		}
		for(int i = 0 ; i < resignTime; i++) {
			int index = (int)(Math.random() * pushTime);
			list.get(index).age = (int) (Math.random() * maxValue);
			test.resign(list.get(index));
		}
		int preAge = Integer.MIN_VALUE;
		while(test.isEmpty()) {
			Student cur = test.pop();
			if(cur.age < preAge) {
				System.out.println("Oops!");
			}
			preAge = cur.age;
		}
		System.out.println("test finish");
		
		
		
		
		
		
		
		
		
		

	}

}

```

