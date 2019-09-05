<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/5
// +----------------------------------------------------------------------


namespace AppBundle\Service;


class MeetingRoomService extends AbsService
{
    /**
     * 获取会议室列表
     * @return array
     */
    public function getRoomList()
    {
        $em = $this->getEm();
        $query = $em->createQueryBuilder();
        $query->select('a')
            ->from('AppBundle:PioneerparkMeetingroom', 'a')
            ->orderBy('a.sort', 'asc');

        return $query->getQuery()->getResult();
    }

    /**
     * 获取详情
     * @param $id
     * @return \AppBundle\Entity\PioneerparkMeetingroom|object|null
     */
    public function getDetailById($id)
    {
        return $this->getDoctrine()->getRepository('AppBundle:PioneerparkMeetingroom')
            ->find($id);
    }
}