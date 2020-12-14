# SCNUCPC 2020 代码打印队列

DOMjudge `print_command` 配置如下：

```
t=[teamname] && f=/var/www/scnuoj/domjudge_print && [ $t ] && cp [file] $f && cd $f && mv [file] $(date +"%m%d-%H-%M-%S")-[location]-[teamname].[language] && rm -rf php*
```
