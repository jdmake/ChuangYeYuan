<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/10
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;


use AppBundle\Service\WxPhoneNumberService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/v1/member")
 */
class MemberController extends CommentController
{
    /**
     * @Route("/getUserInfo")
     */
    public function getUserInfoAction()
    {
        $uid = $this->getUserSession('uid');
        if (empty($uid)) {
            return $this->jsonError(2000, '需要登录');
        }
        $member_service = $this->get('member_service');
        $userinfo = $member_service->getProfile($uid);
        return $this->jsonSuccess('获取用户信息', $userinfo);
    }

    /**
     * @Route("/getMyReleaseRecord")
     */
    public function getMyReleaseRecordAction()
    {
        $cate = $this->request()->get('cate');
        $page = $this->request()->get('page');

        $recordList = [];

        if ($cate == 1) {
            // 获取需求
            $needs_service = $this->get('needs_service');
            $recordList = $needs_service->getNeedsListByUid($this->getUserSession('uid'), $page, 10);
            foreach ($recordList['list'] as &$re) {
                $re = $needs_service->toArray($re);
                $re['content'] = $this->_cutstr_html($re['content']);
            }

        } else if ($cate == 2) {
            // 获取动态
            $dynamic_service = $this->get('dynamic_service');
            $recordList = $dynamic_service->getDynamicListByUid($this->getUserSession('uid'), $page, 10);
            foreach ($recordList['list'] as &$re) {
                $re = $dynamic_service->toArray($re);
                $re['content'] = $this->_cutstr_html($re['content']);
            }
        }

        return $this->jsonSuccess('获取我的发布记录', $recordList);
    }
    private function _cutstr_html($string)
    {
        $string = strip_tags($string);
        $string = trim($string);
        $string = str_replace("\t", "", $string);
        $string = str_replace("\r\n", "", $string);
        $string = str_replace("\r", "", $string);
        $string = str_replace("\n", "", $string);
        $string = str_replace(" ", "", $string);
        return trim($string);
    }

    /**
     * @Route("/removeMyRelease")
     */
    public function removeMyReleaseAction()
    {
        $cate = $this->getJsonParameter('cate');
        $id = $this->getJsonParameter('id');
        if ($cate == 1) {
            // 删除需求
            $needs_service = $this->get('needs_service');
            $entry = $needs_service->getWenZhangDetail($id);
            if (!$entry) {
                return $this->jsonError(1, '数据不存在，或已被删除');
            }
            $this->getDoctrine()->getManager()->remove($entry);
            $this->getDoctrine()->getManager()->flush();
        } else if ($cate == 2) {
            // 删除动态
            $dynamic_service = $this->get('dynamic_service');
            $entry = $dynamic_service->getDetailObject($id);
            if (!$entry) {
                return $this->jsonError(1, '数据不存在，或已被删除');
            }
            $this->getDoctrine()->getManager()->remove($entry);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->jsonSuccess('删除成功');
    }

    /**
     * 是否是商户绑定用户
     * @Route("/isMerchantRoot")
     */
    public function isMerchantRootAction()
    {
        $uid = $this->getUserSession('uid');

        $res = $this->get('merchant_service')->getDetailByUid($uid);

        return $this->jsonSuccess('是否是商户绑定用户', [
            'isMerchantRoot' => ($res ? true : false)
        ]);
    }

    /**
     * @Route("/getUserPhoneNumber")
     */
    public function getUserPhoneNumberAction()
    {
        $encryptedData = $this->getJsonParameter('encryptedData');
        $iv = $this->getJsonParameter('iv');
        $sessionKey = $this->getJsonParameter('sessionKey');

        $wxPhoneNumberService = new WxPhoneNumberService($sessionKey);
        $wxPhoneNumberService->decryptData($encryptedData, $iv, $data);

        $phone = json_decode($data, true)['phoneNumber'];

        return $this->jsonSuccess('获取微信用户手机号码', [
            'phone' => $phone
        ]);
    }

    /**
     * @Route("/bindMobile")
     */
    public function bindMobileAction()
    {
        $phone = $this->getJsonParameter('phone');
        $uid = $this->getUserSession('uid');
        $entry = $this->get('member_service')->findByUid($uid);
        $entry->setMobile($phone);
        $this->getDoctrine()->getManager()->flush();

        return $this->jsonSuccess('绑定手机号码', [
            'phone' => $phone
        ]);
    }
}