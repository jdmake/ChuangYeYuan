<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/5
// +----------------------------------------------------------------------


namespace AppBundle\Controller\v1;


use AppBundle\Controller\Common\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/v1/appointment")
 */
class AppointmentController extends CommonController
{
    /**
     * @Route("/getMeetingRooms", name="appointment_getMeetingRooms")
     */
    public function getMeetingRoomsAction()
    {
        $meetingroom_service = $this->get('meetingroom_service');
        $list = $meetingroom_service->getRoomList();

        return $this->jsonSuccess('获取全部会议室列表', $list);
    }

    /**
     * @Route("/getOptions", name="appointment_getOptions")
     */
    public function getOptionsAction()
    {
        $rid = $this->request()->get('rid');

        $meetingroom_service = $this->get('meetingroom_service');

        // 获取全部会议室
        $room = [];
        $roomObject = $meetingroom_service->getRoomList();
        $index = 0;
        foreach ($roomObject as $item) {
            if ($item->getId() == $rid) {
                $checkedIndex = $index;
            }
            $room[] = $item->getTitle();
            $index++;
        }

        // 获取预约日期列表
        $calendar = $this->_getCalendar();

        $room = [
            'checkedIndex' => $checkedIndex,
            'room' => $room,
            'roomObject' => $roomObject,
            'calendar' => $calendar,
        ];

        return $this->jsonSuccess('获取会议室预约参数', $room);
    }

    /**
     * @Route("/getTimes", name="appointment_getTimes")
     */
    public function getTimes()
    {
        $rid = $this->request()->get('rid');
        $date = $this->request()->get('date');

        $meetingroom_service = $this->get('meetingroom_service');
        // 获取会议室详情
        $meetingRoom = $meetingroom_service->getDetailById($rid);
        if (!$meetingRoom) {
            return $this->jsonError(1, '会议室不存在');
        }
        $startTime = $meetingRoom->getStarttime();
        $endTime = $meetingRoom->getEndtime();
        $price = $meetingRoom->getPrice();
        $stepMinute = $meetingRoom->getStepminute();

        // 获取可预约时段
        $time = $this->_getTime($startTime, $endTime, $price, $stepMinute);
        $this->_checkStatus($date, $rid, $time);

        return $this->jsonSuccess('获取时段', $time);
    }

    /**
     * 获取预约日期列表
     */
    private function _getCalendar()
    {
        $now = new \DateTime();
        // 今天
        $calendar[] = [
            'date' => $now->format('Y-m-d'),
            'title' => '今天',
            'checked' => true,
            'status' => 1,
        ];
        // 向后推进6天
        $weekArr = array('Sunday' => "星期日", 'Monday' => "星期一", 'Tuesday' => "星期二", 'Wednesday' => "星期三", 'Thursday' => "星期四", 'Friday' => "星期五", 'Saturday' => "星期六");
        for ($i = 0; $i < 6; $i++) {
            $calendar[] = [
                'date' => $now->add(new \DateInterval('P1D'))->format('Y-m-d'),
                'title' => ($i == 0) ? '明天' : $weekArr[date('l', $now->getTimestamp())],
                'checked' => false,
                'status' => 1,
            ];
        }
        return $calendar;
    }

    /**
     * 获取可预约时段
     */
    private function _getTime($startTime, $endTime, $price, $stepMinute)
    {
        $timeArray = [];
        $s = $startTime;
        $k = 0;
        while (true) {
            $now = new \DateTime($s);
            $now->modify('+' . $stepMinute . ' minutes');
            $time = date('H:i', $now->getTimestamp());
            $timeArray[] = [
                'range' => $s . '~' . $time,
                'status' => 1,
                'selected' => false,
                'price' => ($stepMinute / 60) * $price
            ];
            $s = $time;
            if (strtotime($now->format('H:i')) >= strtotime((new \DateTime($endTime))->format('H:i'))
            ) {
                break;
            }
            $k++;
            if ($k > 100) {
                break;
            }
        }
        return $timeArray;
    }

    /**
     * 检查设置时段是否可预约状态
     * @param $rid
     * @param array $time
     */
    private function _checkStatus($date, $rid, array &$time)
    {
        foreach ($time as &$value) {
            // 是否已经预约
            $appointmentrecord_service = $this->get('appointmentrecord_service');
            if ($appointmentrecord_service->isExist($date, $value['range'], $rid)) {
                $value['status'] = 2;
            }
        }

    }

    /**
     * 预约会议
     * @Route("/submit", name="appointment_submit")
     */
    public function submitAction()
    {

        $rid = $this->getJsonParameter('rid');
        $date = $this->getJsonParameter('date');
        $time = $this->getJsonParameter('time');

        // 是否已经绑定手机号
        $member_service = $this->get('member_service');
        $memberEntry = $member_service->findByUid($this->getUserSession('uid'));
        if(empty(@$memberEntry['mobile'])) {
            return $this->jsonError(4010, '请先绑定手机号');
        }

        if (empty($date)) {
            return $this->jsonError(1, '预约日期不能为空');
        }
        if (empty($time)) {
            return $this->jsonError(1, '预约时段不能为空');
        }

        $uid = $this->getUserSession('uid');

        // 获取会议室信息
        $room = $this->get('meetingroom_service')->getDetailById($rid);

        // 预约记录服务
        $appointmentrecord_service = $this->get('appointmentrecord_service');

        // 预约时段
        $id = $room->getId();
        $price = $room->getPrice();
        $stepminute = $room->getStepminute();

        // 创建预约记录
        $times = explode(',', $time);
        foreach ($times as $item) {
            if ($appointmentrecord_service->isExist($date, $item, $rid)) {
                return $this->jsonError(1, '预约失败, 已满');
            }
        }
        $record_id = $appointmentrecord_service->createRecord($id, $uid, $date, $time);
        if (!$record_id) {
            return $this->jsonError(1, '预约失败');
        }

        // 创建预约订单
        $appointment_order_service = $this->get('appointment_order_service');
        $order_no = $appointment_order_service->createOrder($uid, $record_id, $price, count($times), $stepminute);

        return $this->jsonSuccess('预约会议', [
            'order_no' => $order_no
        ]);
    }

    /**
     * 获取我的预约记录
     * @Route("/getRecord", name="appointment_getRecord")
     */
    public function getRecordAction()
    {
        $status = $this->request()->get('status');

        $appointmentrecord_service = $this->get('appointmentrecord_service');
        $records = $appointmentrecord_service->getAllByUid($status, $this->getUserSession('uid'));

        return $this->jsonSuccess('获取我的预约记录', $records);
    }
}