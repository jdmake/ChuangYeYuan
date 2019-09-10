<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/7/31
// +----------------------------------------------------------------------


namespace AppBundle\Service;


use AppBundle\Entity\PioneerparkMerchant;

class MerchantService extends AbsService
{
    /**
     * 通过用户UID获取商户ID
     * @param $uid
     * @return int
     */
    public function getIdByUid($uid) {
        $res = $this->getDetailByUid($uid);
        if($res) {
            return intval($res->getId());
        }
        return 0;
    }

    /**
     * 通过ID获取商户
     * @param $id
     * @return PioneerparkMerchant|object|null
     */
    public function getDetailById($id) {
        $res = $this->getDoctrine()->getRepository('AppBundle:PioneerparkMerchant')
            ->find($id);
        return $res;
    }

    /**
     * 获取商户详情
     * @param $uid
     * @return array
     */
    public function getDetailByUid($uid)
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $res = $query->select('a')
            ->from('AppBundle:PioneerparkMerchant', 'a')
            ->where('a.uid=:uid')
            ->setParameter('uid', $uid)
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->eq('a.status', 0),
                    $query->expr()->eq('a.status', 1)
                )
            )
            ->getQuery()->getResult();
        if($res) {
            return $res[0];
        }
        return null;
    }

    /**
     * 创建商户入驻申请记录
     * @throws \Exception
     */
    public function createJoinRecord($uid, array $data)
    {
        $entry = new PioneerparkMerchant();
        $entry->setContacts($data['contacts']);
        $entry->setCreditCode($data['creditCode']);
        $entry->setLicensePic($data['licensePic']);
        $entry->setLogoPic($data['logoPic']);
        $entry->setMonthlyRent($data['monthlyRent']);
        $entry->setName($data['name']);
        $entry->setScope($data['scope']);
        $entry->setStartingTime(new \DateTime($data['startingTime']));
        $entry->setTel($data['tel']);
        $entry->setUid($uid);

        $entry->setJointime($data['jointime']);
        $entry->setCapital($data['capital']);
        $entry->setLegal($data['legal']);
        $entry->setStaffcount($data['staffcount']);
        $entry->setNeedsarea($data['needsarea']);

        $entry->setStatus(0);
        $entry->setCreateAt(new \DateTime());

        $this->getEm()->persist($entry);
        $this->getEm()->flush();

        return $entry->getId();
    }


}