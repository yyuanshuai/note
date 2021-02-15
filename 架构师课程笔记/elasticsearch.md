## 一.基本概念

### 1.1 Node 与 Cluster

Elastic 本质上是一个分布式数据库，允许多台服务器协同工作，每台服务器可以运行多个 Elastic 实例。

单个 Elastic 实例称为一个节点（node）。一组节点构成一个集群（cluster）。

### 1.2 index

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

## 增删改查

```json
//查询
GET customer/external/1
//更新
POST customer/external/1/_update
{ 
	"doc":{ 
    	"name": "John Doew"
	}
}
或者
POST customer/external/1
{ 
    "name": "John Doe2"
}
或者
PUT customer/external/1
{ 
    "name": "John Doe"
}
/**
不同：POST 操作会对比源文档数据，如果相同不会有什么操作，文档 version 不增加
PUT 操作总会将数据重新保存并增加 version 版本；
带_update 对比元数据如果一样就不进行任何操作。
看场景；
对于大并发更新，不带 update；
对于大并发查询偶尔更新，带 update；对比更新，重新计算分配规则。
**/

//删除文档&索引
DELETE customer/external/1
DELETE customer

//bulk 批量 API
POST customer/external/_bulk
{"index":{"_id":"1"}}
{"name": "John Doe" }
{"index":{"_id":"2"}}
{"name": "Jane Doe" }

POST /_bulk
{ "delete": { "_index": "website", "_type": "blog", "_id": "123" }}
{ "create": { "_index": "website", "_type": "blog", "_id": "123" }}
{ "title": "My first blog post" }
{ "index": { "_index": "website", "_type": "blog" }}
{ "title": "My second blog post" }
{ "update": { "_index": "website", "_type": "blog", "_id": "123", "_retry_on_conflict" : 3} }
{ "doc" : {"title" : "My updated blog post"} }
```

## 进阶检索

```json
GET bank/_search
/**
响应结果解释：
took - Elasticsearch 执行搜索的时间（毫秒）
time_out - 告诉我们搜索是否超时
_shards - 告诉我们多少个分片被搜索了，以及统计了成功/失败的搜索分片
hits - 搜索结果
hits.total - 搜索结果
hits.hits - 实际的搜索结果数组（默认为前 10 的文档）
sort - 结果的排序 key（键）（没有则按 score 排序）
score 和 max_score –相关性得分和最高得分（全文检索用）
**/
```

### 1）、基本语法格式

```json
Elasticsearch 提供了一个可以执行查询的 Json 风格的 DSL（domain-specific language 领域特定语言）。这个被称为 Query DSL。该查询语言非常全面，并且刚开始的时候感觉有点复杂，真正学好它的方法是从一些基础的示例开始的。
一个查询语句 的典型结构
{
	QUERY_NAME: {
		ARGUMENT: VALUE, 
        ARGUMENT: VALUE,
        ... 
    }
}
如果是针对某个字段，那么它的结构如下：
{
	QUERY_NAME: {
		FIELD_NAME: {
			ARGUMENT: VALUE, 
    		ARGUMENT: VALUE,... 
		}
	}
}
GET bank/_search
{ 
	"query": { 
        "match_all": {}
	},
    "from": 0, 
    "size": 5, 
    "sort": [
        { 
            "account_number": { 
            	"order": "desc"
        	}
		}
    ]
}
 query 定义如何查询，
 match_all 查询类型【代表查询所有的所有】，es 中可以在 query 中组合非常多的查
询类型完成复杂查询
 除了 query 参数之外，我们也可以传递其它的参数以改变查询结果。如 sort，size
 from+size 限定，完成分页功能
 sort 排序，多字段排序，会在前序字段相等时后续字段内部排序，否则以前序为准
```

### 2）、返回部分字段

```json
GET bank/_search
{ 
    "query": {
		"match_all": {}
	},
    "from": 0, 
    "size": 5, 
    "_source": ["age","balance"]
}
```

### 3）、match【匹配查询】

```json
 基本类型（非字符串），精确匹配
GET bank/_search
{ 
	"query": { 
        "match": { 
            "account_number": "20"
		}
	}
}
match 返回 account_number=20 的字符串，全文检索
GET bank/_search
{ 
    "query": { 
        "match": { 
            "address": "mill"
		}
	}
}
最终查询出 address 中包含 mill 单词的所有记录
match 当搜索字符串类型的时候，会进行全文检索，并且每条记录有相关性得分。
 字符串，多个单词（分词+全文检索）
GET bank/_search
{ 
    "query": { 
        "match": { 
            "address": "mill road"
		}
	}
}
最终查询出 address 中包含 mill 或者 road 或者 mill road 的所有记录，并给出相关性得分
```

### 4）、match_phrase【短语匹配】

```
将需要匹配的值当成一个整体单词（不分词）进行检索
GET bank/_search
{ "query": { "match_phrase": { "address": "mill road"
}
}
}
查出 address 中包含 mill road 的所有记录，并给出相关性得分
```

### 5）、multi_match【多字段匹配】

```json
GET bank/_search
{ 
	"query": { 
		"multi_match": { 
            "query": "mill",
            "fields": ["state","address"]
		}
	}
}
#state 或者 address 包含 mill
```

### 6）、bool【复合查询】

```json
#bool 用来做复合查询：
#复合语句可以合并 任何 其它查询语句，包括复合语句，了解这一点是很重要的。这就意味着，复合语句之间可以互相嵌套，可以表达非常复杂的逻辑。
#must：必须达到 must 列举的所有条件
GET bank/_search
{ 
	"query": { 
        "bool": { 
            "must": [
				{ "match": { "address": "mill" } },
				{ "match": { "gender": "M" } }
            ]
		}
	}
}
#should：应该达到 should 列举的条件，如果达到会增加相关文档的评分，并不会改变查询的结果。如果 query 中只有 should 且只有一种匹配规则，那么 should 的条件就会被作为默认匹配条件而去改变查询结果
GET bank/_search
{ 
    "query": { 
        "bool": { 
            "must": [
				{ "match": { "address": "mill" } }, 
                { "match": { "gender": "M" } }
            ],
            "should": [
                {"match": { "address": "lane" }}
            ]
		}
	}
}
#must_not 必须不是指定的情况
GET bank/_search
{ 
    "query": { 
        "bool": { 
            "must": [
				{ "match": { "address": "mill" } }, 
                { "match": { "gender": "M" } }
			],
            "should": [
				{"match": { "address": "lane" }}
			],
            "must_not": [
				{"match": { "email": "baluba.com" }}
			]
		}
	}
}
#address 包含 mill，并且 gender 是 M，如果 address 里面有 lane 最好不过，但是 email 必须不包含 baluba.com
```

### 7）、filter【结果过滤】

```json
#并不是所有的查询都需要产生分数，特别是那些仅用于 “filtering”（过滤）的文档。为了不计算分数 Elasticsearch 会自动检查场景并且优化查询的执行。
GET bank/_search
{ 
    "query": { 
        "bool": {
            "must": [
				{"match": { "address": "mill"}}
			],
            "filter": { 
                "range": { 
                    "balance": {
                        "gte": 10000, 
                        "lte": 20000
                    }
                }
            }
        }
    }
}
```

### 8）、term

```json
#和 match 一样。匹配某个属性的值。全文检索字段用 match，其他非 text 字段匹配用 term。GET bank/_search
{ 
    "query": { 
        "bool": { 
            "must": [{	
                "term": { 
    				"age": { 
        				"value": "28"
					}
				}
            }, {
            	"match": { 
    				"address": "990 Mill Road"
				}
         	}]
		}
	}
}
```

### 9）、aggregations（执行聚合）

```json
聚合提供了从数据中分组和提取数据的能力。最简单的聚合方法大致等于 SQL GROUP
BY 和 SQL 聚合函数。在 Elasticsearch 中，您有执行搜索返回 hits（命中结果），并且同时返
回聚合结果，把一个响应中的所有 hits（命中结果）分隔开的能力。这是非常强大且有效的，
您可以执行查询和多个聚合，并且在一次使用中得到各自的（任何一个的）返回结果，使用
一次简洁和简化的 API 来避免网络往返。
#搜索 address 中包含 mill 的所有人的年龄分布以及平均年龄，但不显示这些人的详情。
GET bank/_search
{ 
    "query": { 
        "match": { "address": "mill"}
    },
    "aggs": { 
        "group_by_state": { 
            "terms": { 
                "field": "age"
            }
		},
        "avg_age": { 
            "avg": {
				"field": "age"
            }
		}
	},
    "size": 0
}
size：0 不显示搜索数据
aggs：执行聚合。聚合语法如下
"aggs": { 
    "aggs_name 这次聚合的名字，方便展示在结果集中": { 
        "AGG_TYPE 聚合的类型（avg,term,terms）": {}
	}
}, 
#复杂：按照年龄聚合，并且请求这些年龄段的这些人的平均薪资
GET bank/account/_search
{ 
    "query": { 
        "match_all": {}
	},
    "aggs": { 
        "age_avg": { 
            "terms": { 
                "field": "age", 
                "size": 1000
			},
            "aggs": { 
                "banlances_avg": { 
                    "avg": { 
                        "field": "balance"
					}
				}
			}
		}
	},
    "size": 1000
}
#复杂：查出所有年龄分布，并且这些年龄段中 M 的平均薪资和 F 的平均薪资以及这个年龄段的总体平均薪资
GET bank/account/_search
{ 
    "query": { 
        "match_all": {}
	},
    "aggs": { 
        "age_agg": { 
            "terms": { 
                "field": "age", 
                "size": 100
			},
            "aggs": { 
                "gender_agg": { 
                    "terms": { 
                        "field": "gender.keyword", 
                        "size": 100
					},
                    "aggs": { 
                        "balance_avg": { 
                            "avg": { 
                                "field": "balance"
							}
						}
					}
				},
                "balance_avg":{
                    "avg": { 
                        "field": "balance"
					}
				}
			}
		}
	},
    "size": 1000
}
```

