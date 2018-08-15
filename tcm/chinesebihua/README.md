#查询汉字笔画

原理参照了网上的列举法，我觉得思路应该差不多吧，最多是算法上的优化

**函数方法：**

1. count_bihua($str) //查询汉字笔画，不是汉字返回0

2. query($str) //统计各类型字符，和汉字笔画之和，还有每个汉字对应的拼音

3. sortBihua($array,$order) //对汉字进行排序

**使用方法：**

require_once('chinese.php');

$instance = new bihua();

$instance->query($queryStr);

**返回格式**

返回格式均为json，因为当时用于自己写的一个小App《文字大师》的接口，App已经上架：

https://itunes.apple.com/us/app/wen-zi-da-shi-han-zi-bi-hua/id1100111751?l=zh&ls=1&mt=8

![输入图片说明](https://is1-ssl.mzstatic.com/image/thumb/Purple49/v4/bb/86/e5/bb86e52d-9cd6-7aef-55a6-82e4426b63c2/pr_source.jpg/500x500bb.jpg "在这里输入图片标题")

![输入图片说明](https://is1-ssl.mzstatic.com/image/thumb/Purple49/v4/3e/01/c9/3e01c904-27b6-9edb-eccc-135e8d1e1655/pr_source.jpg/500x500bb.jpg "在这里输入图片标题")

