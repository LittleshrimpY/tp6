<?php


namespace app\lib;


class Code
{
    public static $code = [
        'ok' => 200, //ok 一切正常。
        'created' => 201, //created 服务器已经创建了文档，location 头给出了他的URL。
        'accepted' => 202, //accepted 已经接收请求，但是尚未处理完成。
        'non_authoritative_information' => 203, //non-authoritative information 文档已经正常的返回，但一些应答头可能不正确，因为使用的是的文档的拷贝(HTTP 1.1新)。
        'no_content' => 204, //no content 没有新文档，游览器应该继续显示原来的文档，这个跟下面的304非常相似。
        'reset_content' => 205, //Reset content 没有新的内容，到那时游览器应该重置它所显示的内容，用来强制清楚表单输入内容（HTTP1.1 新）
        'partial_content' => 206, //partial content 客户发送了一个带有range头的GET请求，服务器完成了它（HTTP1.1  新）。注意 通过Range 可以实现断点续传。

        'bad_request' => 400, //Bad Request 请求出现语法错误。
        'unauthorized' => 401, //unauthorized 客户试图未经授权访问受密码保护的页面。应答中会包含-WWW-Authenticate头，浏览器据此显示用户名字和密码对话框，然后再填写合适的authorization头后再次发送请求。
        'forbidden' => 403, //Forbidden 资源不可用。服务器理解客户的需求，但是拒绝处理他通常由于服务器上文件或目录的权限设置问题。
        'no_found' => 404, //NO Found 无法找到指定位置的资源，也是一个常用的应答。
        'method_not_allowed' => 405, //Method not allowed 请求方法（GET、POST、HEAD、Delete、put、trace等）对指定的资源不适用。（HTTP 1.1新）
        'not_acceptable' => 406, //not acceptable 指定的资源已经找到

        'internal_server_error' => 500, //internal Server Error 服务器遇到了意料不到的情况，不能完成客户的请求
        'not_lmplemented' => 501, //Not lmplemented 服务器不支持请求所需要的功能。例如，客户发出来了一个服务器不支持的put请求。
        'bad_gateway' => 502, //Bad Gateway 服务器作为网关或者代理时，为了完成请求访问下一个服务器，但该服务器返回了非法的应答。
        'service_unavilable' => 503 //service unavilable 服务器由于维护或者负载过重未能应答。例如，servlet 可能在数据库连接池已满的情况下返回503.服务器返回503时可以提供一个retry-after头。
    ];

    public static $errorCode = [

    ];
}