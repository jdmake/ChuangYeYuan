<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/18
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Appointment;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\PioneerparkMeetingroom;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\DateTime;
use YuZhi\TableingBundle\Tableing\Components\FormatNumber;
use YuZhi\TableingBundle\Tableing\Components\Image;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;
use YuZhi\TableingBundle\Tableing\Components\Remark;

/**
 * @Route("/appointment/room")
 */
class RoomController extends AbsController
{
    /**
     * @Route("/index", name="admin_appointment_room_index")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('search', '');

        $pagination = $this->get('admin_appointment_service')->getPageList('AppBundle:PioneerparkMeetingroom', $page, 15,
            function (QueryBuilder $query) use ($search) {
                if ($search != '') {
                    $search = trim($search);
                    $query->where('a.title like :title')->setParameter('title', "%{$search}%");
                }
                $query->orderBy('a.sort', 'asc');
                return $query;
            });

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("会议室列表")
            ->addTopSearch('search', '输入会议室名称进行搜索', '/admin/appointment/room/index')
            ->addTopButton(new LinkButton([
                'title' => ' + 添加会议室',
                'url' => '/admin/appointment/room/edit',
                'popup' => true,
            ]))
            ->add('id', '编号')
            ->add('picture', '缩略图', [
                'type' => new Image()
            ])
            ->add('title', '会议室名称')
            ->add('price', '押金', [
                'type' => new FormatNumber()
            ])
            ->add('number', '人数')
            ->add('content', '介绍', [
                'type' => new Remark(30)
            ])
            ->add('starttime', '开始时间')
            ->add('endtime', '结束时间')
            ->add('stepminute', '间隔/分钟')
            ->add('sort', '排序')
            ->addAction('操作', [
                new LinkButton([
                    'title' => '编辑',
                    'url' => '/admin/appointment/room/edit?id={%id%}',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/appointment/room/delete?id={%id%}',
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
     * @Route("/edit", name="admin_appointment_room_edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id', 0);

        /** @var PioneerparkMeetingroom $entry */
        $entry = $this->get('admin_appointment_service')->findById('AppBundle:PioneerparkMeetingroom', $id);
        if($entry) {
            $entry->setStarttime(new \DateTime($entry->getStarttime()));
            $entry->setEndtime(new \DateTime($entry->getEndtime()));
        }

        $form = $this->createFormBuilder($entry)
            ->add('picture', 'AdminBundle\Custom\Form\Type\AvatarType', [
                'label' => '缩略图', 'constraints' => [
                    new NotBlank(['message' => '缩略图不能为空'])
                ]
            ])
            ->add('title', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '会议室名称', 'constraints' => [
                    new NotBlank(['message' => '会议室名称不能为空'])
                ]
            ])
            ->add('price', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => '押金', 'constraints' => [
                    new NotBlank(['message' => '押金不能为空'])
                ]
            ])
            ->add('number', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => '人数', 'constraints' => [
                    new NotBlank(['message' => '人数不能为空'])
                ]
            ])
            ->add('content', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
                'label' => '介绍', 'constraints' => [
                    new NotBlank(['message' => '介绍不能为空'])
                ]
            ])
            ->add('starttime', 'Symfony\Component\Form\Extension\Core\Type\TimeType', [
                'label' => '开始时间', 'constraints' => [
                    new NotBlank(['message' => '开始时间不能为空'])
                ]
            ])
            ->add('endtime', 'Symfony\Component\Form\Extension\Core\Type\TimeType', [
                'label' => '结束时间', 'constraints' => [
                    new NotBlank(['message' => '结束时间不能为空'])
                ]
            ])
            ->add('stepminute', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => '间隔/分钟', 'constraints' => [
                    new NotBlank(['message' => '间隔分钟不能为空'])
                ]
            ])
            ->add('sort', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => '排序',  'constraints' => [
                    new NotBlank(['message' => '排序不能为空'])
                ]
            ])
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType')
            ->getForm();

        if($form->handleRequest($this->request())->isValid()) {
            $data = $form->getData();
            if($id > 0) {
                $entry->setPicture($data['picture']);
                $entry->setTitle($data['title']);
                $entry->setPrice($data['price']);
                $entry->setNumber($data['number']);
                $entry->setContent($data['content']);
                $entry->setSort($data['sort']);
                $entry->setStarttime($data['starttime']->format('H:i'));
                $entry->setEndtime($data['endtime']->format('H:i'));
                $entry->setStepminute($data['stepminute']);
                $this->getDoctrine()->getManager()->flush();
                return $this->success('修改成功', 1);
            }else {
                $entry = new PioneerparkMeetingroom();
                $entry->setPicture($data['picture']);
                $entry->setTitle($data['title']);
                $entry->setPrice($data['price']);
                $entry->setNumber($data['number']);
                $entry->setContent($data['content']);
                $entry->setSort($data['sort']);
                $entry->setStarttime($data['starttime']->format('H:i'));
                $entry->setEndtime($data['endtime']->format('H:i'));
                $entry->setStepminute($data['stepminute']);
                $this->getDoctrine()->getManager()->persist($entry);
                $this->getDoctrine()->getManager()->flush();
                return $this->success('添加成功', 1);
            }
        }

        return $this->render('@Admin/form/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete", name="admin_appointment_room_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id', 0);
        /** @var PioneerparkMeetingroom $entry */
        $entry = $this->get('admin_appointment_service')->findById('AppBundle:PioneerparkMeetingroom', $id);
        if($entry) {
            $this->getDoctrine()->getManager()->remove($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('删除成功', 1, '/admin/appointment/room/index');
        }else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/appointment/room/index');
        }
    }
}