# SCNUCPC 2020 代码打印队列

DOMjudge `print_command` 配置如下：

```
t=[teamname] && f=/var/www/scnuoj/domjudge_print && cp [file] $f && cd $f && mv [file] team[teamid]-T$(date +"%m%d%H%M%S").[language] && rm -rf php*
```
