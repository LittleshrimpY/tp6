<?php


namespace app\middleware;


use app\lib\exception\SignException;
use think\facade\Config;
use think\facade\Request;

class CheckApiSign
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $apiUrl = Request::url();
        $apiUrl = substr($apiUrl, 1);
//        $requestTime = $_SERVER['REQUEST_TIME'];
        $requestTime = $this->msectime();
        $newApiSignData = [];
        $apiSignData = Request::header('apiSignData');
        $apiSignData = json_decode($apiSignData, true);
        if ($requestTime - $apiSignData['timeStamp']>10000) {
            throw new SignException([
                'message' => 'time out',
                'errorCode' => 10003,
            ]);
        }
        $apiSignData['url'] = $apiUrl;
        $apiSignData['app_id'] = Config::get("wx.app_id");
        $apiSignOrigin = $apiSignData['sign'];
        unset($apiSignData['sign']);
        $index = 0;
        foreach ($apiSignData as $value) {
            $newApiSignData[$index] = $value;
            $index++;
        }
        $ac = $this->checkApiSign($newApiSignData, $apiSignOrigin);
        if ($ac) {
            return $next($request);
        } else {
            throw new SignException([
                'message' => 'sign fail',
                'errorCode' => 10003,
            ]);
        }
    }

    private function msectime()
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }

    private function checkApiSign($apiSignData, $apiSignOrigin)
    {
        if (empty($apiSignData)) {
            throw new SignException();
        }
        $apiSign = $this->createApiSign($apiSignData);
        if ($apiSignOrigin == $apiSign) {
            return true;
        }
        return false;
    }

    private function createApiSign($apiSignData)
    {
        $signString = "";
        sort($apiSignData);
//        ksort($apiSignData);
//        foreach ($apiSignData as $value) {
//            if (empty($value) || $key == 'sign') {
//                continue;
//            }
//            $signString .= $apiSignData[$key] . "&";
//        }
        foreach ($apiSignData as $value) {
            if (empty($value)) {
                continue;
            }
            $signString .= $value . "&";
        }
        $signString = substr($signString, 0, -1);
//        $signString = Config::get("wx.app_id")."&".$signString;
        return md5($signString);
    }
}