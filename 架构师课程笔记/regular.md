

## PHP正则匹配
* (pattern)
* (?:pattern)
* 'A(?=B)'  匹配后面跟有 B 的 A。正向肯定预查
* 'A(?!B)'  匹配后面未跟着 B 的 A。正向否定预查
* '(?<=A)B' 匹配前面紧挨着 A 的 B。反向肯定预查
* '(?<!A)B' 反向否定预查

### 字符
> .