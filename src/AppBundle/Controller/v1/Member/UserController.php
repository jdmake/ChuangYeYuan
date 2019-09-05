<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/26
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1\Member;

use AppBundle\Controller\Common\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * 会员控制器
 * @Route("/v1/user")
 */
class UserController extends CommonController
{
    /**
     * 会员微信登录
     * @Route("/login", name="user_login")
     */
    public function loginAction()
    {

        $code = $this->getJsonParameter('code');

        $member_service = $this->get('member_service');

        // 获取 OPENID
        $res = $member_service->code2Session($code);
        if (!$res) {
            return $this->jsonError(1, $member_service->getError());
        }

        if (!isset($res['openid'])) {
            return $this->jsonError(1, '获取openid失败');
        }

        $userEntry = $member_service->findByOpenid($res['openid']);
        if ($userEntry) {
            // 保存用户到 session 中
            $this->get('session')->set('user', $userEntry);
        }

        return $this->jsonSuccess('会员登录', $res);
    }

    /**
     * 用户注册
     * @Route("/register", name="user_register")
     */
    public function registerAction()
    {
        $openid = $this->getJsonParameter('openid');
        $avatarUrl = $this->getJsonParameter('avatarUrl');
        $city = $this->getJsonParameter('city');
        $country = $this->getJsonParameter('country');
        $gender = $this->getJsonParameter('gender');
        $language = $this->getJsonParameter('language');
        $nickName = $this->getJsonParameter('nickName');
        $province = $this->getJsonParameter('province');

        if (empty($openid)) {
            return $this->jsonError(1, 'openid 不能为空');
        }

        $member_service = $this->get('member_service');
        $uid = $member_service->createUserOnly($openid, [
            'avatarUrl' => $avatarUrl,
            'city' => $city,
            'country' => $country,
            'gender' => $gender,
            'language' => $language,
            'nickName' => $nickName,
            'province' => $province,
        ]);

        if ($uid <= 0) {
            return $this->jsonError(1, '创建用户信息失败');
        }

        $userEntry = $member_service->findByUid($uid);
        if ($userEntry) {
            // 保存用户到 session 中
            $this->get('session')->set('user', $userEntry);
        }

        return $this->jsonSuccess('创建用户', [
            'uid' => $uid
        ]);
    }

}