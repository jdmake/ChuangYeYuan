<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/18
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Appointment;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\PioneerparkAppointmentRecord;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use YuZhi\TableingBundle\Tableing\Components\DateTime;
use YuZhi\TableingBundle\Tableing\Components\Enable;
use YuZhi\TableingBundle\Tableing\Components\Image;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;

/**
 * @Route("/appointment/meeting")
 */
class MeetingController extends AbsController
{
    /**
     * @Route("/index", name="admin_appointment_meeting_index")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $status = $this->request()->get('status');

        $pagination = $this->get('admin_appointment_service')->getRecordPageList($status, $page, 15);

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("预约记录")
            ->addFilter('status', '不限状态', [
                0 => '待支付',
                1 => '已支付',
                2 => '已失效',
            ])
            ->add('uid', 'UID')
            ->add('avatar', '会员头像', [
                'type' => new Image()
            ])
            ->add('nickname', '昵称')
            ->add('date', '日期')
            ->add('time', '时段')
            ->add('title', '会议室')
            ->add('mobile', '联系电话')
            ->add('status', '状态', [
                'type' => new Enable([
                    0 => ['title' => '待支付'],
                    1 => ['title' => '已支付', 'class' => 'badge badge-success'],
                    2 => ['title' => '已失效', 'class' => 'badge badge-red'],
                ])
            ])
            ->addAction('操作', [
                new LinkButton([
                    'title' => '置为已支付',
                    'url' => '/admin/appointment/meeting/setPay?id={%id%}',
                    'confirm' => '真的要设置吗？',
                ]),
                function ($e) {
                    $children = $e->getChildren();
                    $uid = $children[0]['value'];
                    return '<a popup="true" class="btn btn-success" target="" 
                            href="/admin/appointment/TailMoney/edit?uid=' . $uid . '">收尾款</a>';
                },
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/appointment/meeting/delete?id={%id%}',
                    'confirm' => '真的要删除吗？',
                    'class' => 'btn btn-pink'
                ]),
            ])
            ->buildView();

        return $this->render('@Admin/Table/base.html.twig', [
            'tableView' => $tableView,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/setPay", name="admin_appointment_meeting_setPay")
     */
    public function setPayAction()
    {
        $id = $this->request()->get('id', 0);
        /** @var PioneerparkAppointmentRecord $entry */
        $entry = $this->get('admin_appointment_service')->findById('AppBundle:PioneerparkAppointmentRecord', $id);
        if ($entry) {
            $entry->setStatus(1);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('设置成功', 1, '/admin/appointment/meeting/index');
        } else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/appointment/meeting/index');
        }
    }

    /**
     * @Route("/delete", name="admin_appointment_meeting_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id', 0);
        $res = $this->get('admin_appointment_service')->removeMeetingAppointmentRecord($id);
        if ($res) {
            return $this->success('删除成功', 1, '/admin/appointment/meeting/index');
        } else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/appointment/meeting/index');
        }
    }
}