<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/22
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Appointment;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\PioneerparkAppointmentOrder;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\DateTime;
use YuZhi\TableingBundle\Tableing\Components\Enable;
use YuZhi\TableingBundle\Tableing\Components\FormatNumber;
use YuZhi\TableingBundle\Tableing\Components\Image;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;

/**
 * @Route("/appointment/TailMoney")
 */
class TailMoneyController extends AbsController
{
    /**
     * @Route("/", name="admin_appointment_TailMoney")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);

        $pagination = $this->get('admin_merchant_service')->getPageList('AppBundle:PioneerparkAppointmentOrder', $page, 15,
            function (QueryBuilder $query) {
                $query->select('a.id,a.uid,a.orderNo,a.total,a.status,a.createAt,c.nickname');
                $query->innerJoin('AppBundle:PioneerparkMember', 'b', 'WITH', 'b.uid=a.uid');
                $query->innerJoin('AppBundle:PioneerparkMemberProfile', 'c', 'WITH', 'c.id=b.profileid');
                $query->andWhere('a.recordId=:recordId');
                $query->setParameter('recordId', 0);
                $query->orderBy('a.createAt', 'desc');
                return $query;
            });

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("会议尾款")
            ->addTopButton(new LinkButton([
                'title' => ' + 添加尾款通知',
                'url' => '/admin/appointment/TailMoney/edit',
                'popup' => true,
            ]))
            ->add('id', '编号')
            ->add('nickname', '用户')
            ->add('total', '金额', [
                'type' => new FormatNumber(2, '元')
            ])
            ->add('status', '状态', [
                'type' => new Enable([
                    0 => ['title' => '待支付'],
                    1 => ['title' => '已支付', 'class' => 'badge badge-success'],
                    2 => ['title' => '已失效', 'class' => 'badge badge-red']
                ])
            ])
            ->add('createAt', '创建时间', [
                'type' => new DateTime('Y-m-d H:i:s')
            ])
            ->addAction('操作', [
                new LinkButton([
                    'title' => '置为已支付',
                    'url' => '/admin/appointment/TailMoney/setPayStatus?id={%id%}',
                    'confirm' => '真的操作吗？',
                    'class' => 'btn btn-info'
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/appointment/TailMoney/delete?id={%id%}',
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
     * @Route("/edit", name="admin_appointment_TailMoney_edit")
     */
    public function editAction()
    {
        $uid = $this->request()->get('uid');

        $form = $this->createFormBuilder()
            ->add('uid', 'AdminBundle\Custom\Form\Type\SelectMemberType', [
                'label' => '会员', 'url' => '/admin/member/findUser'
            ])
            ->add('total', 'Symfony\Component\Form\Extension\Core\Type\MoneyType', [
                'label' => '缴纳金额', 'currency' => 'RMB', 'invalid_message' => '请输入正确的人民币格式'
            ])
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType')
            ->getForm();

        if ($this->request()->getMethod() == 'POST') {
            $data = $this->request()->get('form');

            if(!isset($data['uid']) || empty($data['uid'])) {
                return $this->error('会员不能为空', 5, '/admin/appointment/TailMoney/edit?uid='.$uid);
            }
            if(!isset($data['total']) || empty($data['total'])) {
                return $this->error('金额不能为空', 5, '/admin/appointment/TailMoney/edit?uid='.$uid);
            }

            $entry = new PioneerparkAppointmentOrder();
            $uid = $data['uid'];
            $entry->setUid($uid);
            $entry->setOrderNo('TM'. date('YmdHis').time());
            $entry->setTotal($data['total']);
            $entry->setRecordId(0);
            $entry->setCreateAt(new \DateTime());
            $entry->setStatus(0);
            $entry->setPayStatus(0);
            $this->getDoctrine()->getManager()->persist($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('添加成功', 1, "javascript:window.parent.location.href='/admin/appointment/TailMoney/';");
        }

        return $this->render('@Admin/form/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/setPayStatus", name="admin_appointment_TailMoney_setPayStatus")
     */
    public function setPayStatusAction()
    {
        $id = $this->request()->get('id', 0);
        /** @var PioneerparkAppointmentOrder $entry */
        $entry = $this->get('admin_merchant_service')->findById('AppBundle:PioneerparkAppointmentOrder', $id);
        if($entry) {
            $entry->setStatus(1);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('操作成功', 1, '/admin/appointment/TailMoney');
        }else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/appointment/TailMoney');
        }
    }

    /**
     * @Route("/delete", name="admin_appointment_TailMoney_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id', 0);
        /** @var PioneerparkAppointmentOrder $entry */
        $entry = $this->get('admin_merchant_service')->findById('AppBundle:PioneerparkAppointmentOrder', $id);
        if($entry) {
            $this->getDoctrine()->getManager()->remove($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('删除成功', 1, '/admin/appointment/TailMoney');
        }else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/appointment/TailMoney');
        }
    }
}