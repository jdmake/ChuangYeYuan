<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/5/1
// +----------------------------------------------------------------------


namespace AppBundle\Controller\Filter;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


class SessionFilter
{
    public static function doFilter(Request $request)
    {
        $allows = [
            '/v1/user/login',
            '/v1/user/register',
            '/v1/swiper/index',
            '/v1/notice/getNewNotice',
            '/v1/notice/getPageList',
            '/v1/notice/getDetail',
            '/v1/needs/getNewNeeds',
            '/v1/dynamic/getNewDynamic',
            '/v1/notice/getDetail',
            '/v1/needs/getZhaoPinDetail',
            '/v1/dynamic/getDetail',
            '/v1/comment/getPageList',
            '/v1/order/getOrderPageList',
            '/v1/appointment/getMeetingRooms',
            '/v1/needs/getCates',
            '/v1/needs/getPageList',
            '/v1/needs/getWenZhangDetail',
            '/v1/dynamic/getCates',
            '/v1/dynamic/getPageList',
            '/v1/member/getUserInfo',
            '/v1/task/expiredHandle',
            '/v1/wxpay/notify_url',
            '/v1/merchant/getShopPageList',
            '/v1/article/getCates',
            '/v1/article/getDetail',
            '/v1/article/getPageList',
        ];
        if(!in_array($request->getPathInfo(), $allows)) {
            if (!(new Session())->get('user')) {
                $res = new Response(json_encode([
                    'error' => 1000,
                    'msg' => '登录后继续操作'
                ]), 203);
                $res->send();
                exit();
            }
        }
    }
}