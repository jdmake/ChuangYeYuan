<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/26
// +----------------------------------------------------------------------


namespace AppBundle\Service;

use AppBundle\Entity\PioneerparkMember;
use AppBundle\Entity\PioneerparkMemberProfile;
use AppBundle\Util\HttpRequestUtil;

/**
 * 成员服务
 * Class MemberService
 * @package AppBundle\Service
 */
class MemberService extends AbsService
{
    /**
     * 从微信服务器换取 openId, sessionKey, unionId
     * @param $code
     */
    public function code2Session($code)
    {

        $config = $this->get('miniapp_config_service');

        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $config->getAppid() . '&secret=' . $config->getSecret() . '&js_code=' . $code . '&grant_type=authorization_code';
        $res = HttpRequestUtil::httpGet($url);
        if (isset($res['errcode'])) {
            if ($res['errcode'] == '-1') {
                $this->error = '系统繁忙，此时请开发者稍候再试	';
            }
            if ($res['errcode'] == '41008') {
                $this->error = 'code 无效';
            }
            if ($res['errcode'] == '40029') {
                $this->error = 'code 无效';
            }
            if ($res['errcode'] == '45011') {
                $this->error = '频率限制，每个用户每分钟100次';
            }
            return null;
        }

        return $res;
    }

    /**
     * 保存推送码
     * @param $uid
     * @param $formid
     */
    public function saveFormId($uid, $formid)
    {
       $entry = $this->getDoctrine()->getRepository('AppBundle:PioneerparkMember')
            ->find($uid);
       if($entry) {
           $entry->setFormid($formid);
           $this->getEm()->flush($entry);
       }
    }

    /**
     * 获取用户资料
     * @param $uid
     * @return PioneerparkMemberProfile|object|null
     */
    public function getProfile($uid)
    {
        $entry = $this->findByUid($uid);
        if ($entry) {
            $profileId = $entry->getProfileid();
            $entry = $this->getDoctrine()->getRepository('AppBundle:PioneerparkMemberProfile')
                ->find($profileId);
            $memberArr = $this->toArray($entry);
            $user = $this->getDoctrine()->getRepository('AppBundle:PioneerparkMember')->find($uid);
            $memberArr['mobile'] = $user->getMobile();
            return $memberArr;
        }
        return null;
    }

    /**
     * 用户是否存在
     */
    public function isUserExistByOpenId($openid)
    {
        $res = $this->getDoctrine()->getRepository('AppBundle:PioneerparkMember')
            ->findOneBy([
                'openid' => $openid
            ]);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 通过OPENID获取用户
     * @param $openid
     * @return PioneerparkMember|object|null
     */
    public function findByOpenid($openid)
    {
        return $this->getDoctrine()->getRepository('AppBundle:PioneerparkMember')
            ->findOneBy([
                'openid' => $openid
            ]);
    }

    /**
     * 通过UID获取用户
     * @param $uid
     * @return PioneerparkMember|object|null
     */
    public function findByUid($uid)
    {
        return $this->getDoctrine()->getRepository('AppBundle:PioneerparkMember')
            ->find($uid);
    }

    /**
     * 创建唯一的用户, 不重复
     */
    public function createUserOnly($openid, $profileArray = [])
    {
        if ($this->isUserExistByOpenId($openid)) {
            $res = $this->findByOpenid($openid);
            if ($res) {
                return $res->getUid();
            }
        }

        $entry = new PioneerparkMember();
        $entry->setOpenid($openid);
        $entry->setMobile('');
        $entry->setCredit(0);
        $entry->setIsenterprise(false);
        $entry->setLastloginip('');
        $entry->setLastlogintime(new \DateTime());
        $entry->setLevel(1);
        $entry->setParentid(0);
        $entry->setProfileid(0);
        $entry->setRegtime(new \DateTime());
        $entry->setEnable(true);
        $this->getDoctrine()->getManager()->persist($entry);
        $this->getDoctrine()->getManager()->flush();
        $uid = $entry->getUid();

        if ($uid > 0) {
            $res = $this->updateUserProfile($uid, $profileArray);
            if (!$res) {
                return 0;
            }
        }

        return $uid;
    }

    /**
     * 更新用户profile
     * @param $uid
     * @param array $profileArray = { avatarUrl, city, country, gender, language, nickName, province }
     *
     */
    public function updateUserProfile($uid, $profileArray = [])
    {
        $memberEntry = $this->getDoctrine()->getRepository('AppBundle:PioneerparkMember')
            ->find($uid);
        if (!$memberEntry) {
            return false;
        }

        if ($memberEntry->getProfileid() > 0) {
            return true;
        }

        $entry = new PioneerparkMemberProfile();
        $entry->setNickname($profileArray['nickName']);
        $entry->setAvatar($profileArray['avatarUrl']);
        $entry->setGender($profileArray['gender']);
        $entry->setCity('');
        $entry->setRealname('');
        $entry->setIdcard('');
        $entry->setBirthday(new \DateTime());
        $this->getDoctrine()->getManager()->persist($entry);
        $this->getDoctrine()->getManager()->flush();

        $profileId = $entry->getId();
        if ($profileId > 0) {
            $memberEntry->setProfileid($profileId);
            $this->getDoctrine()->getManager()->flush();
            return true;
        }

        return false;
    }

}