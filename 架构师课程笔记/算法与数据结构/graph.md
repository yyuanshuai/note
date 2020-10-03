# 图





图的遍历方法有[深度优先搜索法](https://zh.wikipedia.org/wiki/深度优先搜索法)和[广度（宽度）优先搜索法](https://zh.wikipedia.org/wiki/广度优先搜索法)。

```java
	public static void bfs(Node node) {//广度
		if (node == null) {
			return;
		}
		Queue<Node> queue = new LinkedList<>();
		HashSet<Node> set = new HashSet<>();
		queue.add(node);
		set.add(node);
		while (!queue.isEmpty()) {
			Node cur = queue.poll();
			System.out.println(cur.value);
			for (Node next : cur.nexts) {
				if (!set.contains(next)) {
					set.add(next);
					queue.add(next);
				}
			}
		}
	}
```

```java
	public static void dfs(Node node) {//深度
		if (node == null) {
			return;
		}
		Stack<Node> stack = new Stack<>();
		HashSet<Node> set = new HashSet<>();
		stack.add(node);
		set.add(node);
		System.out.println(node.value);
		while (!stack.isEmpty()) {
			Node cur = stack.pop();
			for (Node next : cur.nexts) {
				if (!set.contains(next)) {
					stack.push(cur);
					stack.push(next);
					set.add(next);
					System.out.println(next.value);
					break;
				}
			}
		}
	}
```

拓扑排序

```java
	// directed graph and no loop
	public static List<Node> sortedTopology(Graph graph) {
		// key：某一个node
		// value：剩余的入度
		HashMap<Node, Integer> inMap = new HashMap<>();
		// 剩余入度为0的点，才能进这个队列
		Queue<Node> zeroInQueue = new LinkedList<>();
		
		for (Node node : graph.nodes.values()) {
			inMap.put(node, node.in);
			if (node.in == 0) {
				zeroInQueue.add(node);
			}
		}
		// 拓扑排序的结果，依次加入result
		List<Node> result = new ArrayList<>();
		while (!zeroInQueue.isEmpty()) {
			Node cur = zeroInQueue.poll();
			result.add(cur);
			for (Node next : cur.nexts) {
				inMap.put(next, inMap.get(next) - 1);
				if (inMap.get(next) == 0) {
					zeroInQueue.add(next);
				}
			}
		}
		return result;
	}
```



## Kruskal算法

**Kruskal算法**是一种用来查找[最小生成树](https://zh.wikipedia.org/wiki/最小生成树)的算法[[1\]](https://zh.wikipedia.org/wiki/克鲁斯克尔演算法#cite_note-:0-1)，由Joseph Kruskal在1956年发表[[2\]](https://zh.wikipedia.org/wiki/克鲁斯克尔演算法#cite_note-2)。用来解决同样问题的还有[Prim算法](https://zh.wikipedia.org/wiki/Prim演算法)和[Boruvka算法](https://zh.wikipedia.org/w/index.php?title=Boruvka演算法&action=edit&redlink=1)等。三种算法都是[贪心算法](https://zh.wikipedia.org/wiki/贪心法)的应用。和Boruvka算法不同的地方是，Kruskal算法在图中存在相同权值的边时也有效。

### 步骤

1. 新建图{\displaystyle G}![G](https://wikimedia.org/api/rest_v1/media/math/render/svg/f5f3c8921a3b352de45446a6789b104458c9f90b)，{\displaystyle G}![G](https://wikimedia.org/api/rest_v1/media/math/render/svg/f5f3c8921a3b352de45446a6789b104458c9f90b)中拥有原图中相同的节点，但没有边
2. 将原图中所有的边按权值从小到大排序
3. 从权值最小的边开始，如果这条边连接的两个节点于图{\displaystyle G}![G](https://wikimedia.org/api/rest_v1/media/math/render/svg/f5f3c8921a3b352de45446a6789b104458c9f90b)中不在同一个连通分量中，则添加这条边到图{\displaystyle G}![G](https://wikimedia.org/api/rest_v1/media/math/render/svg/f5f3c8921a3b352de45446a6789b104458c9f90b)中
4. 重复3，直至图{\displaystyle G}![G](https://wikimedia.org/api/rest_v1/media/math/render/svg/f5f3c8921a3b352de45446a6789b104458c9f90b)中所有的节点都在同一个连通分量中

### 证明

1. 这样的步骤保证了选取的每条边都是桥，因此图{\displaystyle G}![G](https://wikimedia.org/api/rest_v1/media/math/render/svg/f5f3c8921a3b352de45446a6789b104458c9f90b)构成一个树。
2. 为什么这一定是最小生成树呢？关键还是步骤3中对边的选取。算法中总共选取了{\displaystyle n-1}![n-1](https://wikimedia.org/api/rest_v1/media/math/render/svg/fbd0b0f32b28f51962943ee9ede4fb34198a2521)条边，每条边在选取的当时，都是连接两个不同的连通分量的权值最小的边
3. 要证明这条边一定属于最小生成树，可以用反证法：如果这条边不在最小生成树中，它连接的两个连通分量最终还是要连起来的，通过其他的连法，那么另一种连法与这条边一定构成了环，而环中一定有一条权值大于这条边的边，用这条边将其替换掉，图仍旧保持连通，但总权值减小了。也就是说，如果不选取这条边，最后构成的生成树的总权值一定不会是最小的。

### 时间复杂度

平均时间复杂度为

### 伪代码

```
KRUSKAL-FUNCTION(G, w)
1    F := 空集合
2    for each 图 G 中的顶点 v
3        do 将 v 加入森林 F
4    所有的边(u, v) ∈ E依权重 w 递增排序
5    for each 边(u, v) ∈ E
6        do if u 和 v 不在同一棵子树
7            then F := F ∪ {(u, v)}
8                将 u 和 v 所在的子树合并
```



## Prim

**普里姆算法**（Prim's algorithm），[图论](https://zh.wikipedia.org/wiki/图论)中的一种[算法](https://zh.wikipedia.org/wiki/算法)，可在加权连通图里搜索[最小生成树](https://zh.wikipedia.org/wiki/最小生成树)。意即由此算法搜索到的[边](https://zh.wikipedia.org/wiki/邊_(圖論))子集所构成的[树](https://zh.wikipedia.org/wiki/树_(图论))中，不但包括了连通图里的所有[顶点](https://zh.wikipedia.org/wiki/顶点_(图论))，且其所有边的权值之和亦为最小。该算法于1930年由[捷克](https://zh.wikipedia.org/wiki/捷克)[数学家](https://zh.wikipedia.org/wiki/数学家)[沃伊捷赫·亚尔尼克](https://zh.wikipedia.org/w/index.php?title=沃伊捷赫·亚尔尼克&action=edit&redlink=1)发现；并在1957年由[美国](https://zh.wikipedia.org/wiki/美国)[计算机科学家](https://zh.wikipedia.org/wiki/计算机科学家)[罗伯特·普里姆](https://zh.wikipedia.org/w/index.php?title=罗伯特·普里姆&action=edit&redlink=1)独立发现；1959年，[艾兹格·迪科斯彻](https://zh.wikipedia.org/wiki/艾兹格·迪科斯彻)再次发现了该算法。因此，在某些场合，普里姆算法又被称为**DJP算法**、**亚尔尼克算法**或**普里姆－亚尔尼克算法**。

### 描述

从单一[顶点](https://zh.wikipedia.org/wiki/顶点_(图论))开始，普里姆算法按照以下步骤逐步扩大树中所含顶点的数目，直到遍及连通图的所有顶点。

# 戴克斯特拉算法

**戴克斯特拉算法**（英语：Dijkstra's algorithm），又译**迪杰斯特拉算法**，亦可不音译而称为**Dijkstra算法**[[6\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-6)，是由荷兰计算机科学家[艾兹赫尔·戴克斯特拉](https://zh.wikipedia.org/wiki/艾兹赫尔·戴克斯特拉)在1956年发现的算法，并于3年后在[期刊](https://zh.wikipedia.org/wiki/学术期刊)上发表[[7\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-7)[[8\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-Dijkstra_Interview-8)[[9\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-Dijkstra1959-9)。戴克斯特拉算法使用类似[广度优先搜索](https://zh.wikipedia.org/wiki/广度优先搜索)的方法解决赋权图[[9\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-Dijkstra1959-9)的单源[最短路径问题](https://zh.wikipedia.org/wiki/最短路径问题)[[10\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-felner-10)[[1\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-IntroToAlgo-1)[[2\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-Discrete-2)。

该算法存在很多变体：戴克斯特拉的原始版本仅适用于找到两个顶点之间的最短路径[[9\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-Dijkstra1959-9)，后来更常见的变体固定了一个顶点作为源结点然后找到该顶点到图中所有其它结点的最短路径，产生一个[最短路径树](https://zh.wikipedia.org/wiki/最短路径树)[[1\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-IntroToAlgo-1)。

该算法解决了图 {\displaystyle G=\langle V,E\rangle }![{\displaystyle G=\langle V,E\rangle }](https://wikimedia.org/api/rest_v1/media/math/render/svg/63fb57aad2f16b4da437cf9dfeae8daee2a14407)上带权的单源最短路径问题[[1\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-IntroToAlgo-1)[[11\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-mehlhorn-11):196–206。具体来说，戴克斯特拉算法设置了一顶点集合{\displaystyle S}![S](https://wikimedia.org/api/rest_v1/media/math/render/svg/4611d85173cd3b508e67077d4a1252c9c05abca2)，在集合{\displaystyle S}![S](https://wikimedia.org/api/rest_v1/media/math/render/svg/4611d85173cd3b508e67077d4a1252c9c05abca2)中所有的顶点与[源点](https://zh.wikipedia.org/wiki/顶点_(图论)){\displaystyle s}![s](https://wikimedia.org/api/rest_v1/media/math/render/svg/01d131dfd7673938b947072a13a9744fe997e632)之间的最终最短路径权值均已确定[[1\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-IntroToAlgo-1)。算法反复选择最短路径估计最小的点{\displaystyle u\in {V-S}}![{\displaystyle u\in {V-S}}](https://wikimedia.org/api/rest_v1/media/math/render/svg/49f51d0c817fddffa56a98c1f754cc5fd0062ee4)并将{\displaystyle u}![u](https://wikimedia.org/api/rest_v1/media/math/render/svg/c3e6bb763d22c20916ed4f0bb6bd49d7470cffd8)加入{\displaystyle S}![S](https://wikimedia.org/api/rest_v1/media/math/render/svg/4611d85173cd3b508e67077d4a1252c9c05abca2)中[[1\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-IntroToAlgo-1)。该算法常用于[路由](https://zh.wikipedia.org/wiki/路由)算法或者作为其他图算法的一个子模块[[12\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-OSPF-12)。举例来说，如果图中的顶点表示城市，而边上的权重表示城市间开车行经的距离，该算法可以用来找到两个城市之间的最短路径[[8\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-Dijkstra_Interview-8)[[2\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-Discrete-2)。

应当注意，绝大多数的戴克斯特拉算法不能有效处理带有负权边的图[[1\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-IntroToAlgo-1)[[13\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-13)。

戴克斯特拉算法在计算机科学的[人工智能](https://zh.wikipedia.org/wiki/人工智能)等领域也被称为均一开销搜索，并被认为是[最良优先搜索](https://zh.wikipedia.org/w/index.php?title=最良优先搜索&action=edit&redlink=1)的一个特例[[10\]](https://zh.wikipedia.org/wiki/戴克斯特拉算法#cite_note-felner-10)。