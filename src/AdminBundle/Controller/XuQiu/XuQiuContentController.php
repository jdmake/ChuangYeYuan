<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/15
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\XuQiu;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\PioneerparkXuqiuWenzhang;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\Enable;
use YuZhi\TableingBundle\Tableing\Components\Image;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;
use YuZhi\TableingBundle\Tableing\Components\Remark;
use YuZhi\TableingBundle\Tableing\Components\SwitchButton;

/**
 * @Route("/xuqiuContent")
 */
class XuQiuContentController extends AbsController
{
    /**
     * @Route("/index", name="admin_xuqiu_content")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('title');
        $cate = $this->request()->get('cate');
        $status = $this->request()->get('status');

        $pagination = $this->get('admin_needs_service')->getPageList('AppBundle:PioneerparkXuqiuWenzhang', $page, 15,
            function (QueryBuilder $queryBuilder) use ($search, $cate, $status) {
                if ($search != '') {
                    $search = trim($search);
                    $queryBuilder->andwhere('a.title like :title')->setParameter('title', "%{$search}%");
                }
                if ($cate != '') {
                    $queryBuilder->andwhere('a.cate=:cate')->setParameter('cate', $cate);
                }
                if ($status != '') {
                    $queryBuilder->andwhere('a.status=:status')->setParameter('status', $status);
                }
                $queryBuilder->orderBy('a.createAt', 'desc');
                return $queryBuilder;
            }
        );
        $cates = $this->get('category_service')->findAllChoiceByXuQiu(false);

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("需求列表")
            ->addFilter('cate', '不限分类', $cates)
            ->addFilter('status', '不限状态', [0 => '待审核', 1 => '已通过', 2 => '未通过'])
            ->addTopSearch('title', '输入标题进行搜索', '/admin/xuqiuContent/index')
            ->addTopButton(new LinkButton([
                'title' => ' + 添加需求',
                'url' => '/admin/xuqiuContent/editContent',
                'popup' => true,
            ]))
            ->add('id', '编号')
            ->add('cate', '分类')
            ->add('thumbnail', '缩略图', [
                'type' => new Image()
            ])
            ->add('title', '标题')
            ->add('content', '摘要', [
                'type' => new Remark(50)
            ])
            ->add('status', '状态', [
                'type' => new Enable([
                    0 => ['title' => '待审核', 'class' => 'badge badge-default'],
                    1 => ['title' => '已通过', 'class' => 'badge badge-success'],
                    2 => ['title' => '未通过', 'class' => 'badge badge-pink'],
                ])
            ])
            ->add('isquality', '优质', [
                'type' => new SwitchButton('/admin/xuqiuContent/switchIsQuality')
            ])
            ->add('isrecommend', '推荐', [
                'type' => new SwitchButton('/admin/xuqiuContent/switchIsRecommend')
            ])
            ->addAction('操作', [
                new LinkButton([
                    'title' => '查看',
                    'url' => '/admin/xuqiuContent/edit?id={%id%}',
                    'target' => '_blank'
                ]),
                new LinkButton([
                    'title' => '编辑',
                    'url' => '/admin/xuqiuContent/editContent?id={%id%}',
                    'class' => 'btn btn-info',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/xuqiuContent/delete?id={%id%}',
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
     * @Route("/editContent", name="admin_xuqiu_editContent")
     */
    public function editContentAction()
    {
        $id = $this->request()->get('id', 0);

        /** @var PioneerparkXuqiuWenzhang $entry */
        $entry = $this->get('category_service')->findById('AppBundle:PioneerparkXuqiuWenzhang', $id);

        // 获取分类
        $cates = $this->get('category_service')->findAllChoiceByXuQiu(true);

        $form = $this->createFormBuilder($entry)
            ->add('cate', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'label' => '分类', 'choices' => $cates
            ])
            ->add('title', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '标题', 'constraints' => [
                    new NotBlank(['message' => '标题不能为空'])
                ]
            ])
            ->add('subtitle', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '副标题'
            ])
            ->add('thumbnail', 'AdminBundle\Custom\Form\Type\AvatarType', [
                'label' => '缩略图', 'constraints' => [
                    new NotBlank(['message' => '缩略图不能为空'])
                ]
            ])
            ->add('contacts', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '联系人', 'constraints' => [
                    new NotBlank(['message' => '联系人不能为空'])
                ]
            ])
            ->add('tel', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '联系电话', 'constraints' => [
                    new NotBlank(['message' => '联系电话不能为空'])
                ]
            ])
            ->add('content', 'AdminBundle\Custom\Form\Type\UeditorType', [
                'label' => '内容', 'constraints' => [
                    new NotBlank(['message' => '内容不能为空'])
                ]
            ])
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType')
            ->getForm();

        if ($form->handleRequest($this->request())->isValid()) {
            $data = $form->getData();
            if ($id > 0) {
                $entry->setCate($data['cate']);
                $entry->setTitle($data['title']);
                $entry->setSubtitle($data['subtitle']);
                $entry->setThumbnail($data['thumbnail']);
                $entry->setContent($data['content']);
                $entry->setContacts($data['contacts']);
                $entry->setTel($data['tel']);
                $this->getDoctrine()->getManager()->flush();
                return $this->success('修改成功', 1);
            } else {
                $entry = new PioneerparkXuqiuWenzhang();
                $entry->setUid(0);
                $entry->setMid(0);
                $entry->setCate($data['cate']);
                $entry->setTitle($data['title']);
                $entry->setSubtitle($data['subtitle']);
                $entry->setThumbnail($data['thumbnail']);
                $entry->setPicture('');
                $entry->setContacts($data['contacts']);
                $entry->setTel($data['tel']);
                $entry->setContent($data['content']);
                $entry->setCreateAt(new \DateTime());
                $entry->setIsquality(false);
                $entry->setIsrecommend(false);
                $entry->setStatus(1);

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
     * @Route("/edit", name="admin_xuqiu_content_edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id', 0);
        $entry = $this->get('admin_needs_service')->findById('AppBundle:PioneerparkXuqiuWenzhang', $id);
        $pictures = explode(',', $entry->getPicture());
        foreach ($pictures as &$picture) {
            $picture = "<div><img src='{$picture}'></div>";
        }
        $entry->setPicture($picture);

        // 企业名称
        $merchant = $this->get('merchant_service')->getDetailById($entry->getMid());
        // 需求分类
        $cate = $this->get('category_service')->findById('AppBundle:PioneerparkXuqiuCategory', $entry->getCate());

        return $this->render('@Admin/XuQiu/edit.html.twig', [
            'entry' => $entry,
            'merchant_name' => $merchant ? $merchant->getName() : '官方',
            'cate' => $cate->getTitle()
        ]);
    }

    /**
     * @Route("/delete", name="admin_xuqiu_content_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id', 0);
        /** @var PioneerparkXuqiuWenzhang $entry */
        $entry = $this->get('admin_needs_service')->findById('AppBundle:PioneerparkXuqiuWenzhang', $id);
        if ($entry) {
            $this->getDoctrine()->getManager()->remove($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('删除成功', 1, '/admin/xuqiuContent/index');
        } else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/xuqiuContent/index');
        }
    }

    /**
     * @Route("/pass", name="admin_xuqiu_content_pass")
     */
    public function passAction()
    {
        $id = $this->request()->get('id');
        /** @var PioneerparkXuqiuWenzhang $entry */
        $entry = $this->get('admin_needs_service')->findById('AppBundle:PioneerparkXuqiuWenzhang', $id);
        if ($entry) {
            $entry->setStatus(1);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('操作成功', 1, '/admin/xuqiuContent/edit?id=' . $id);
        } else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/xuqiuContent/edit?id=' . $id);
        }
    }

    /**
     * @Route("/refuse", name="admin_xuqiu_content_refuse")
     */
    public function refuseAction()
    {
        $id = $this->request()->get('id');
        /** @var PioneerparkXuqiuWenzhang $entry */
        $entry = $this->get('admin_needs_service')->findById('AppBundle:PioneerparkXuqiuWenzhang', $id);
        if ($entry) {
            $entry->setStatus(2);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('操作成功', 1, '/admin/xuqiuContent/edit?id=' . $id);
        } else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/xuqiuContent/edit?id=' . $id);
        }
    }

    /**
     * @Route("/switchIsQuality", name="admin_xuqiu_content_switchIsQuality")
     */
    public function switchIsQualityAction()
    {
        $id = $this->request()->get('id');
        /** @var PioneerparkXuqiuWenzhang $entry */
        $entry = $this->get('admin_needs_service')->findById('AppBundle:PioneerparkXuqiuWenzhang', $id);
        if ($entry) {
            $entry->setIsquality(!$entry->getIsquality());
            $this->getDoctrine()->getManager()->flush();
            return $this->json([
                'error' => 0,
                'msg' => '操作成功'
            ]);
        } else {
            return $this->json([
                'error' => 1,
                'msg' => '操作失败'
            ]);
        }
    }

    /**
     * @Route("/switchIsRecommend", name="admin_xuqiu_content_switchIsRecommend")
     */
    public function switchIsRecommendAction()
    {
        $id = $this->request()->get('id');
        /** @var PioneerparkXuqiuWenzhang $entry */
        $entry = $this->get('admin_needs_service')->findById('AppBundle:PioneerparkXuqiuWenzhang', $id);
        if ($entry) {
            $entry->setIsrecommend(!$entry->getIsrecommend());
            $this->getDoctrine()->getManager()->flush();
            return $this->json([
                'error' => 0,
                'msg' => '操作成功'
            ]);
        } else {
            return $this->json([
                'error' => 1,
                'msg' => '操作失败'
            ]);
        }
    }
}