

## PHP正则匹配
* '(?<=A)B' 匹配前面紧挨着 A 的 B，但不使 A 成为匹配的一部分。
```
$reg = '/(?<=LoginName=).*/';
preg_match($reg, $url, $res);
var_dump($res);
```
* 'A(?=B)' 匹配后面跟有 B 的 A，但不使 B 成为匹配的一部分。
* 'A(?!B)' 匹配后面未跟着 B 的 A。
* ('about york', '(?<!new\\s)york'); 会返回子串 york。
* 