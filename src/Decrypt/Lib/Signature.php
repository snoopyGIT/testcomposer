<?php

namespace Snoopyebo\TestSignature\Decrypt\Lib;

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
