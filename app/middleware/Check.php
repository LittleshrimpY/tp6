<?php

namespace app\middleware;

use app\api\service\Token;
use app\lib\exception\ScopeException;
use think\facade\Request;

class Check
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
        $scope = Token::getCurrentTokenVar('scope');
        if ($scope >= 16) {
            return $next($request);
        }else{
            throw new ScopeException();
        }
    }
}
