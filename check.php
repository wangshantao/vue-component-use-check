<?php
const PATH = '你的vue项目的src目录';

getPath(my_dir(PATH), PATH);
echo '------------------------end------------------------';

// 遍历目录下所有文件夹和文件
function my_dir($dir)
{
    $files = array();
    if (@$handle = opendir($dir)) { //注意这里要加一个@，不然会有warning错误提示：）
        while (($file = readdir($handle)) !== false) {
            if ($file != ".." && $file != ".") { //排除根目录；
                if (is_dir($dir . "/" . $file)) { //如果是子文件夹，就进行递归
                    $files[$file] = my_dir($dir . "/" . $file);
                } else { //不然就将文件的名字存入数组；
                    $files[] = $file;
                }

            }
        }
        closedir($handle);
        return $files;
    } else {
        echo '文件夹路径有误，请检查路径';
        exit(0);
    }
}

// 根据遍历的内容找出路径 如果是vue文件就遍历他
function getPath($t, $path = '')
{
    if (is_array($t)) {
        foreach ($t as $k => $v) {
            if (is_array($v)) {
                getPath($v, $path . '/' . $k);
            } else if (is_string($v) && strpos($v, '.vue') !== false) {
                searchNoUseComponents($path . '/' . $v);
            }
        }
    }
}

// 把驼峰改成短横线分隔命名
function humpToLine($str)
{
    $str = lcfirst($str);
    $str = preg_replace_callback('/(([A-Z]|[0-9]){1})/', function ($matches) {
        return '-' . strtolower($matches[0]);
    }, $str);
    return $str;
}

// 寻找vue内导入却未使用的组件
function searchNoUseComponents($path)
{
    if (file_exists($path)) {
        $flag = 0;
        $myFile = fopen($path, 'r');
        $components = [];
        $originComponents = [];
        while (!feof($myFile)) {
            $line = fgets($myFile);
            if (strpos($line, 'components: {}') !== false) {
                break;
            } else if (strpos($line, 'components: {') !== false) {
                $flag = 1;
            } else if ($flag == 1 && strpos($line, '}') === false) {
                $components[] = humpToLine(trim(trim($line), ','));
                $originComponents[] = trim(trim($line), ',');
            } else if ($flag == 1 && strpos($line, '}') !== false) {
                break;
            }
        }
        fclose($myFile);
        $res = fopen($path, 'r');
        $vue = fread($res, filesize($path));
        foreach ($components as $k => $v) {
            if (strpos($vue, '<' . $v) === false) {
                echo ltrim($path, PATH) . ' 内组件 ' . $originComponents[$k] . ' 导入但是未使用' . "<br />";
            }
        }
    }
}


