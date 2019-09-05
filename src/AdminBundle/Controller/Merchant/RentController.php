<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/18
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Merchant;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\PioneerparkRentRecord;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\DateTime;
use YuZhi\TableingBundle\Tableing\Components\Enable;
use YuZhi\TableingBundle\Tableing\Components\FormatNumber;
use YuZhi\TableingBundle\Tableing\Components\Image;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;

/**
 * @Route("/merchant/rent")
 */
class RentController extends AbsController
{
    /**
     * @Route("/", name="admin_merchant_rent_index")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);

        $pagination = $this->get('admin_merchant_service')->getPageList('AppBundle:PioneerparkRentRecord', $page, 15,
            function (QueryBuilder $query) {
                $query->select('a.id,a.uid,a.total,a.remarks,a.status,a.createAt,a.completion,b.name,b.contacts,b.tel,b.logopic');
                $query->innerJoin('AppBundle:PioneerparkMerchant', 'b', 'WITH', 'b.id=a.merchantId');
                $query->orderBy('a.createAt', 'desc');
                return $query;
            });

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("租金缴纳")
            ->addTopButton(new LinkButton([
                'title' => ' + 添加交租通知',
                'url' => '/admin/merchant/rent/edit',
                'popup' => true,
            ]))
            ->add('id', '编号')
            ->add('logopic', '商户logo', [
                'type' => new Image()
            ])
            ->add('name', '商户')
            ->add('total', '金额', [
                'type' => new FormatNumber(2, '元')
            ])
            ->add('remarks', '详情备注')
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
            ->add('completion', '完成时间', [
                'type' => new DateTime('Y-m-d H:i:s')
            ])
            ->addAction('操作', [
                new LinkButton([
                    'title' => '置为已支付',
                    'url' => '/admin/merchant/rent/setPayStatus?id={%id%}',
                    'confirm' => '真的操作吗？',
                    'class' => 'btn btn-info'
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/merchant/rent/delete?id={%id%}',
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
     * @Route("/setPayStatus", name="admin_merchant_rent_setPayStatus")
     */
    public function setPayStatusAction()
    {
        $id = $this->request()->get('id', 0);
        /** @var PioneerparkRentRecord $entry */
        $entry = $this->get('admin_merchant_service')->findById('AppBundle:PioneerparkRentRecord', $id);
        if($entry) {
            $entry->setStatus(1);
            $entry->setCompletion(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            $orderEntry = $this->getDoctrine()->getRepository('AppBundle:PioneerparkRentOrder')->findOneBy([
                'recordId' => $entry->getId()
            ]);
            $orderEntry->setStatus(1);
            $this->getDoctrine()->getManager()->flush();

            return $this->success('操作成功', 1, '/admin/merchant/rent');
        }else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/merchant/rent');
        }
    }

    /**
     * @Route("/edit", name="admin_merchant_rent_edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id', 0);

        /** @var PioneerparkRentRecord $entry */
        $entry = $this->get('admin_merchant_service')->findById('AppBundle:PioneerparkRentRecord', $id);

        // 获取商户选择数据
        $merchants = $this->get('admin_merchant_service')->getMerchantChoices();

        $form = $this->createFormBuilder($entry)
            ->add('merchantId', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'label' => '商户', 'choices' => $merchants
            ])
            ->add('total', 'Symfony\Component\Form\Extension\Core\Type\MoneyType', [
                'label' => '缴纳金额', 'currency' => 'RMB', 'invalid_message' => '请输入正确的人民币格式',  'constraints' => [
                    new NotBlank(['message' => '缴纳金额不能为空'])
                ]
            ])
            ->add('remarks', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
                'label' => '详情备注', 'attr' => [
                    'rows' => 5
                ], 'constraints' => [
                    new NotBlank(['message' => '详情备注不能为空'])
                ]
            ])
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType')
            ->getForm();

        if ($form->handleRequest($this->request())->isValid()) {
            $data = $form->getData();
            $entry = new PioneerparkRentRecord();
            $uid = $this->get('admin_merchant_service')->getUidByMid($data['merchantId']);
            $entry->setMerchantId($data['merchantId']);
            $entry->setUid($uid);
            $entry->setTotal($data['total']);
            $entry->setRemarks($data['remarks']);
            $entry->setCreateAt(new \DateTime());
            $entry->setCompletion(new \DateTime('0000-00-00 00:00:00'));
            $entry->setStatus(0);
            $this->getDoctrine()->getManager()->persist($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('添加成功', 1);
        }

        return $this->render('@Admin/form/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete", name="admin_merchant_rent_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id', 0);
        /** @var PioneerparkRentRecord $entry */
        $entry = $this->get('admin_merchant_service')->findById('AppBundle:PioneerparkRentRecord', $id);
        if($entry) {
            $this->getDoctrine()->getManager()->remove($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('删除成功', 1, '/admin/merchant/rent');
        }else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/merchant/rent');
        }
    }
}