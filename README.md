# 这是什么
在使用vue的时候，有时候由于种种原因会使我们导入一些组件，最终却没有使用它。
于是我就编写了这个php文件来找出已经导入但是未使用的组件。
# 为什么是php
JavaScript不能访问本地文件，node.js我不会。
如果你没用过php，但是想使用。可以自己搭建一个php环境，Windows下个wamp可以一键安装。
# 怎么使用
编辑check.php文件的第一行，替换''里的内容为你的src路径
```const PATH = '你的vue项目的src路径';```
把check.php放到www目录下，然后游览器访问http://localhost/check.php

