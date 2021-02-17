# Trie

[![img](https://upload.wikimedia.org/wikipedia/commons/thumb/b/be/Trie_example.svg/250px-Trie_example.svg.png)](https://zh.wikipedia.org/wiki/File:Trie_example.svg)

一个保存了8个键的trie结构，"A", "to", "tea", "ted", "ten", "i", "in", "inn".

在[计算机科学](https://zh.wikipedia.org/wiki/计算机科学)中，**trie**，又称**前缀树**或**字典树**，是一种有序[树](https://zh.wikipedia.org/wiki/树_(数据结构))，用于保存[关联数组](https://zh.wikipedia.org/wiki/关联数组)，其中的键通常是[字符串](https://zh.wikipedia.org/wiki/字符串)。与[二叉查找树](https://zh.wikipedia.org/wiki/二叉查找树)不同，键不是直接保存在节点中，而是由节点在树中的位置决定。一个节点的所有子孙都有相同的[前缀](https://zh.wikipedia.org/wiki/前缀)，也就是这个节点对应的字符串，而根节点对应[空字符串](https://zh.wikipedia.org/wiki/空字符串)。一般情况下，不是所有的节点都有对应的值，只有叶子节点和部分内部节点所对应的键才有相关的值。

Trie这个术语来自于re**trie**val。根据[词源学](https://zh.wikipedia.org/wiki/词源学)，trie的发明者Edward Fredkin把它读作[/ˈtriː/](https://zh.wikipedia.org/wiki/Help:英語國際音標) "tree"。[[1\]](https://zh.wikipedia.org/wiki/Trie#cite_note-DADS-1)[[2\]](https://zh.wikipedia.org/wiki/Trie#cite_note-Liang1983-2)但是，其他作者把它读作[/ˈtraɪ/](https://zh.wikipedia.org/wiki/Help:英語國際音標) "try"。[[1\]](https://zh.wikipedia.org/wiki/Trie#cite_note-DADS-1)[[2\]](https://zh.wikipedia.org/wiki/Trie#cite_note-Liang1983-2)[[3\]](https://zh.wikipedia.org/wiki/Trie#cite_note-KnuthVol3-3)

在图示中，键标注在节点中，值标注在节点之下。每一个完整的英文单词对应一个特定的整数。Trie可以看作是一个[确定有限状态自动机](https://zh.wikipedia.org/wiki/确定有限状态自动机)，尽管边上的符号一般是隐含在分支的顺序中的。

键不需要被显式地保存在节点中。图示中标注出完整的单词，只是为了演示trie的原理。

trie中的键通常是字符串，但也可以是其它的结构。trie的算法可以很容易地修改为处理其它结构的有序序列，比如一串数字或者形状的排列。比如，**bitwise trie**中的键是一串比特，可以用于表示整数或者内存地址。

## 目录



- [1应用](https://zh.wikipedia.org/wiki/Trie#应用)
- 2实现方式
  - [2.1三数组Trie](https://zh.wikipedia.org/wiki/Trie#三数组Trie)
  - [2.2二数组Trie](https://zh.wikipedia.org/wiki/Trie#二数组Trie)
- [3实例](https://zh.wikipedia.org/wiki/Trie#实例)
- [4参考资料](https://zh.wikipedia.org/wiki/Trie#参考资料)

## 应用[[编辑](https://zh.wikipedia.org/w/index.php?title=Trie&action=edit&section=1)]

trie树常用于搜索提示。如当输入一个网址，可以自动搜索出可能的选择。当没有完全匹配的搜索结果，可以返回前缀最相似的可能。[[4\]](https://zh.wikipedia.org/wiki/Trie#cite_note-4)

## 实现方式[[编辑](https://zh.wikipedia.org/w/index.php?title=Trie&action=edit&section=2)]

trie树实际上是一个[确定有限状态自动机](https://zh.wikipedia.org/wiki/确定有限状态自动机)(DFA)，通常用[转移矩阵](https://zh.wikipedia.org/wiki/轉移矩陣)表示。行表示状态，列表示输入字符，（行，列）位置表示转移状态。这种方式的查询效率很高，但由于稀疏的现象严重，空间利用效率很低。也可以采用压缩的存储方式即链表来表示状态转移，但由于要线性查询，会造成效率低下。

于是人们提出了下面两种结构。[[5\]](https://zh.wikipedia.org/wiki/Trie#cite_note-datrie-5)

### 三数组Trie[[编辑](https://zh.wikipedia.org/w/index.php?title=Trie&action=edit&section=3)]

三数组Trie（Triple-Array Trie）结构包括三个数组：base,next和check.

### 二数组Trie[[编辑](https://zh.wikipedia.org/w/index.php?title=Trie&action=edit&section=4)]

二数组Trie（Double-Array Trie）包含base和check两个数组。base数组的每个元素表示一个Trie节点，即一个状态；check数组表示某个状态的前驱状态。



```java
package class05;

import java.util.HashMap;

// 该程序完全正确
public class Code02_TrieTree {

	public static class Node1 {
		public int pass;
		public int end;
		public Node1[] nexts;

		public Node1() {
			pass = 0;
			end = 0;
			// 0    a
			// 1    b
			// 2    c
			// ..   ..
			// 25   z
			// nexts[i] == null   i方向的路不存在
			// nexts[i] != null   i方向的路存在
			nexts = new Node1[26];
		}
	}

	public static class Trie1 {
		private Node1 root;

		public Trie1() {
			root = new Node1();
		}

		public void insert(String word) {
			if (word == null) {
				return;
			}
			char[] str = word.toCharArray();
			Node1 node = root;
			node.pass++;
			int path = 0;
			for (int i = 0; i < str.length; i++) { // 从左往右遍历字符
				path = str[i] - 'a'; // 由字符，对应成走向哪条路
				if (node.nexts[path] == null) {
					node.nexts[path] = new Node1();
				}
				node = node.nexts[path];
				node.pass++;
			}
			node.end++;
		}

		public void delete(String word) {
			if (search(word) != 0) {
				char[] chs = word.toCharArray();
				Node1 node = root;
				node.pass--;
				int path = 0;
				for (int i = 0; i < chs.length; i++) {
					path = chs[i] - 'a';
					if (--node.nexts[path].pass == 0) {
						node.nexts[path] = null;
						return;
					}
					node = node.nexts[path];
				}
				node.end--;
			}
		}

		// word这个单词之前加入过几次
		public int search(String word) {
			if (word == null) {
				return 0;
			}
			char[] chs = word.toCharArray();
			Node1 node = root;
			int index = 0;
			for (int i = 0; i < chs.length; i++) {
				index = chs[i] - 'a';
				if (node.nexts[index] == null) {
					return 0;
				}
				node = node.nexts[index];
			}
			return node.end;
		}

		// 所有加入的字符串中，有几个是以pre这个字符串作为前缀的
		public int prefixNumber(String pre) {
			if (pre == null) {
				return 0;
			}
			char[] chs = pre.toCharArray();
			Node1 node = root;
			int index = 0;
			for (int i = 0; i < chs.length; i++) {
				index = chs[i] - 'a';
				if (node.nexts[index] == null) {
					return 0;
				}
				node = node.nexts[index];
			}
			return node.pass;
		}
	}

	public static class Node2 {
		public int pass;
		public int end;
		public HashMap<Integer, Node2> nexts;

		public Node2() {
			pass = 0;
			end = 0;
			nexts = new HashMap<>();
		}
	}

	public static class Trie2 {
		private Node2 root;

		public Trie2() {
			root = new Node2();
		}

		public void insert(String word) {
			if (word == null) {
				return;
			}
			char[] chs = word.toCharArray();
			Node2 node = root;
			node.pass++;
			int index = 0;
			for (int i = 0; i < chs.length; i++) {
				index = (int) chs[i];
				if (!node.nexts.containsKey(index)) {
					node.nexts.put(index, new Node2());
				}
				node = node.nexts.get(index);
				node.pass++;
			}
			node.end++;
		}

		public void delete(String word) {
			if (search(word) != 0) {
				char[] chs = word.toCharArray();
				Node2 node = root;
				node.pass--;
				int index = 0;
				for (int i = 0; i < chs.length; i++) {
					index = (int) chs[i];
					if (--node.nexts.get(index).pass == 0) {
						node.nexts.remove(index);
						return;
					}
					node = node.nexts.get(index);
				}
				node.end--;
			}
		}

		// word这个单词之前加入过几次
		public int search(String word) {
			if (word == null) {
				return 0;
			}
			char[] chs = word.toCharArray();
			Node2 node = root;
			int index = 0;
			for (int i = 0; i < chs.length; i++) {
				index = (int) chs[i];
				if (!node.nexts.containsKey(index)) {
					return 0;
				}
				node = node.nexts.get(index);
			}
			return node.end;
		}

		// 所有加入的字符串中，有几个是以pre这个字符串作为前缀的
		public int prefixNumber(String pre) {
			if (pre == null) {
				return 0;
			}
			char[] chs = pre.toCharArray();
			Node2 node = root;
			int index = 0;
			for (int i = 0; i < chs.length; i++) {
				index = (int) chs[i];
				if (!node.nexts.containsKey(index)) {
					return 0;
				}
				node = node.nexts.get(index);
			}
			return node.pass;
		}
	}

	public static class Right {

		private HashMap<String, Integer> box;

		public Right() {
			box = new HashMap<>();
		}

		public void insert(String word) {
			if (!box.containsKey(word)) {
				box.put(word, 1);
			} else {
				box.put(word, box.get(word) + 1);
			}
		}

		public void delete(String word) {
			if (box.containsKey(word)) {
				if (box.get(word) == 1) {
					box.remove(word);
				} else {
					box.put(word, box.get(word) - 1);
				}
			}
		}

		public int search(String word) {
			if (!box.containsKey(word)) {
				return 0;
			} else {
				return box.get(word);
			}
		}

		public int prefixNumber(String pre) {
			int count = 0;
			for (String cur : box.keySet()) {
				if (cur.startsWith(pre)) {
					count += box.get(cur);
				}
			}
			return count;
		}
	}

	// for test
	public static String generateRandomString(int strLen) {
		char[] ans = new char[(int) (Math.random() * strLen) + 1];
		for (int i = 0; i < ans.length; i++) {
			int value = (int) (Math.random() * 6);
			ans[i] = (char) (97 + value);
		}
		return String.valueOf(ans);
	}

	// for test
	public static String[] generateRandomStringArray(int arrLen, int strLen) {
		String[] ans = new String[(int) (Math.random() * arrLen) + 1];
		for (int i = 0; i < ans.length; i++) {
			ans[i] = generateRandomString(strLen);
		}
		return ans;
	}

	public static void main(String[] args) {
		int arrLen = 100;
		int strLen = 20;
		int testTimes = 100000;
		for (int i = 0; i < testTimes; i++) {
			String[] arr = generateRandomStringArray(arrLen, strLen);
			Trie1 trie1 = new Trie1();
			Trie2 trie2 = new Trie2();
			Right right = new Right();
			for (int j = 0; j < arr.length; j++) {
				double decide = Math.random();
				if (decide < 0.25) {
					trie1.insert(arr[j]);
					trie2.insert(arr[j]);
					right.insert(arr[j]);
				} else if (decide < 0.5) {
					trie1.delete(arr[j]);
					trie2.delete(arr[j]);
					right.delete(arr[j]);
				} else if (decide < 0.75) {
					int ans1 = trie1.search(arr[j]);
					int ans2 = trie2.search(arr[j]);
					int ans3 = right.search(arr[j]);
					if (ans1 != ans2 || ans2 != ans3) {
						System.out.println("Oops!");
					}
				} else {
					int ans1 = trie1.prefixNumber(arr[j]);
					int ans2 = trie2.prefixNumber(arr[j]);
					int ans3 = right.prefixNumber(arr[j]);
					if (ans1 != ans2 || ans2 != ans3) {
						System.out.println("Oops!");
					}
				}
			}
		}
		System.out.println("finish!");

	}

}

```





