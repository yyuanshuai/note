

## 连接redis
redis-cli
redis-cli -h host -p port -a password

//查看是否设置了密码验证

CONFIG get requirepass

//设置密码验证

CONFIG set requirepass "runoob"

//验证密码

auth password





set key value

get key

del key

exists key 

EXPIRE key seconds//expire key 3600

KEYS pattern  查找所有符合给定模式( pattern)的 key 

