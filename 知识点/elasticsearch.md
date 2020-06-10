## 一.基本概念

### 1.1 Node 与 Cluster

Elastic 本质上是一个分布式数据库，允许多台服务器协同工作，每台服务器可以运行多个 Elastic 实例。

单个 Elastic 实例称为一个节点（node）。一组节点构成一个集群（cluster）。

### 1.2 ndex

Elastic 会索引所有字段，经过处理后写入一个反向索引（Inverted Index）。查找数据的时候，直接查找该索引。

所以，Elastic 数据管理的顶层单位就叫做 Index（索引）。它是单个数据库的同义词。每个 Index （即数据库）的名字必须是小写。

下面的命令可以查看当前节点的所有 Index。

> ```bash
> $ curl -X GET 'http://localhost:9200/_cat/indices?v'
> ```

### 1.3 Document

Index 里面单条的记录称为 Document（文档）。许多条 Document 构成了一个 Index。

Document 使用 JSON 格式表示，下面是一个例子。

> ```javascript
> {
>   "user": "张三",
>   "title": "工程师",
>   "desc": "数据库管理"
> }
> ```

同一个 Index 里面的 Document，不要求有相同的结构（scheme），但是最好保持相同，这样有利于提高搜索效率。

### 1.4 Type

Document 可以分组，比如`weather`这个 Index 里面，可以按城市分组（北京和上海），也可以按气候分组（晴天和雨天）。这种分组就叫做 Type，它是虚拟的逻辑分组，用来过滤 Document。

不同的 Type 应该有相似的结构（schema），举例来说，`id`字段不能在这个组是字符串，在另一个组是数值。这是与关系型数据库的表的[一个区别](https://www.elastic.co/guide/en/elasticsearch/guide/current/mapping.html)。性质完全不同的数据（比如`products`和`logs`）应该存成两个 Index，而不是一个 Index 里面的两个 Type（虽然可以做到）。

下面的命令可以列出每个 Index 所包含的 Type。

> ```bash
> $ curl 'localhost:9200/_mapping?pretty=true'
> ```

根据[规划](https://www.elastic.co/blog/index-type-parent-child-join-now-future-in-elasticsearch)，Elastic 6.x 版只允许每个 Index 包含一个 Type，7.x 版将会彻底移除 Type。

## 新建和删除Index

 新建 Index，可以直接向 Elastic 服务器发出 PUT 请求。下面的例子是新建一个名叫`weather`的 Index。

> ```bash
> $ curl -X PUT 'localhost:9200/weather'
> ```

服务器返回一个 JSON 对象，里面的`acknowledged`字段表示操作成功。

> ```javascript
> {
>   "acknowledged":true,
>   "shards_acknowledged":true
> }
> ```

然后，我们发出 DELETE 请求，删除这个 Index。

> ```bash
> $ curl -X DELETE 'localhost:9200/weather'
> ```

## 新增Index, 中文分词

```bash
curl -H "Content-Type: application/json" -X PUT 'localhost:9200/accounts' -d '
{
  "mappings": {
    "person": {
      "properties": {
        "user": {
          "type": "text",
          "analyzer": "ik_max_word",
          "search_analyzer": "ik_max_word"
        },
        "title": {
          "type": "text",
          "analyzer": "ik_max_word",
          "search_analyzer": "ik_max_word"
        },
        "desc": {
          "type": "text",
          "analyzer": "ik_max_word",
          "search_analyzer": "ik_max_word"
        }
      }
    }
  }
}'
```

```bash

```

## 增删改查记录

```bash
curl 'localhost:9200/accounts/person/1?pretty=true'
curl -X DELETE 'localhost:9200/accounts/person/1'
//新增,此时ID是生成的随机字符串
curl -H "Content-Type: application/json" -X POST 'localhost:9200/accounts/person' -d '
{
  "user": "李四",
  "title": "工程师",
  "desc": "系统管理"
}'
{//返回
  "_index":"accounts",
  "_type":"person",
  "_id":"AV3qGfrC6jMbsbXb6k1p",
  "_version":1,
  "result":"created",
  "_shards":{"total":2,"successful":1,"failed":0},
  "created":true
}
//新增或修改,新增的话,ID为指定的值, 此处为1
curl -H "Content-Type: application/json" -X PUT 'localhost:9200/accounts/person/1' -d '
{
    "user" : "张三",
    "title" : "工程师",
    "desc" : "数据库管理，软件开发"
}' 
{//返回
  "_index":"accounts",
  "_type":"person",
  "_id":"1",
  "_version":1,//该数据操作次数, 1为新增
  "result":"created",//created为新增 updated为更新
  "_shards":{"total":2,"successful":1,"failed":0},
  "created":true//true为新增, false为更新
}
```

```bash
//查询所有记录
curl 'localhost:9200/accounts/person/_search'
//全文搜索查询
curl -H "Content-Type: application/json" 'localhost:9200/accounts/person/_search'  -d '
{
  "query" : { "match" : { "desc" : "软件 系统" }},//or
  "from": 1,//从位置1开始（默认是从位置0开始)
  "size": 1//默认10
}'
{
  "took":3,
  "timed_out":false,
  "_shards":{"total":5,"successful":5,"failed":0},
  "hits":{
    "total":1,
    "max_score":0.28582606,
    "hits":[
      {
        "_index":"accounts",
        "_type":"person",
        "_id":"1",
        "_score":0.28582606,
        "_source": {
          "user" : "张三",
          "title" : "工程师",
          "desc" : "数据库管理，软件开发"
        }
      }
    ]
  }
}
//全文搜索查询 desc字段必须包含`软件``系统`两个条件
curl 'localhost:9200/accounts/person/_search'  -d '
{
  "query": {
    "bool": {
      "must": [
        { "match": { "desc": "软件" } },
        { "match": { "desc": "系统" } }
      ]
    }
  }
}'
```