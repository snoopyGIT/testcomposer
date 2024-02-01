### 制作 composer 包

**0x01 初始化composer.json**

`composer init`

> 填写包的各类信息，包括name、description、keywords、type、version、autoload、authors、license、require等。

composer.json 架构可参考 [点击](https://docs.phpcomposer.com/04-schema.html)

**0x02 编写代码文件**

以本项目为例，在`src`目录下，创建子目录`Decrypt/Lib`，放核心功能类库代码。

以 `src/Decrypt/Signature.php` 为例，代码如下：

```
<?php

namespace Snoopy\TestSignature\Decrypt\Lib;

class Signature
{
    public static function getSignature($params, $secret)
    {
        $signature = '';
        ksort($params);
        foreach ($params as $k => $v) {
            if ($v instanceof \SplFileInfo) {
                $v = md5_file($v->getPathname());
            } else if ($v instanceof \CURLFile) {
                $v = md5_file($v->name);
            }
            $signature .= sprintf('%s=%s&', $k, $v);
        }
        $signature .= $secret;

        return md5($signature);
    }

    public static function getToken($app, $ticket, $time)
    {
        return md5(sprintf("%s%d%s", $app, $time, $ticket));
    }
}

```

注意到`namespace`为`Snoopy\TestSignature\Decrypt\Lib`，这是因为`composer.json`里的`authoload.psr-4`，有设置命名空间对应的代码路径映射关系：`"Snoopy\\TestSignature\\": "src/"`。

**0x03 推送到github仓库**

```
git init
git remote add origin git@github.com:snoopyGIT/testcomposer.git
git commit -am "first commit"
git push -u origin main
```

**0x04 提交到packagist.org**

首先需要注册一个账号，邮箱激活以后，登录账号，在这个地址 `https://packagist.org/packages/submit` 把github仓库地址复制进去，点击提交。不出意外，就能提交成功了。如有意外，比如名字和别已有的包重复啦，类似啦，那就改一下，更新到github，再提交到packagist。


**0x05 使用**

在你的项目里： `composer require snoopyebo/test_signature`、
然后在你的代码里这样引用：(注意是用https://packagist.org源)

```
use Snoopy\TestSignature\Decrypt\Lib\Signature;

$params = [
    'id' => 1,
];
$secret = '123456';
$signature = Signature::getSignature($params, $secret);

echo $signature;
```