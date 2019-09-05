<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/15
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\XuQiu;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\PioneerparkXuqiuCategory;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;

/**
 * @Route("/xuqiuCategory")
 */
class XuQiuCategoryController extends AbsController
{
    /**
     * @Route("/index", name="admin_xuqiu_category_index")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('username', '');

        $pagination = $this->get('category_service')->getPageList('AppBundle:PioneerparkXuqiuCategory', $page, 15,
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
            ->setPageTitle("需求分类")
            ->addTopSearch('username', '输入分类名称进行搜索', '/admin/xuqiuCategory/index')
            ->addTopButton(new LinkButton([
                'title' => ' + 添加分类',
                'url' => '/admin/xuqiuCategory/edit',
                'popup' => true,
            ]))
            ->add('id', '编号')
            ->add('title', '分类名称')
            ->add('sort', '排序')
            ->addAction('操作', [
                new LinkButton([
                    'title' => '编辑',
                    'url' => '/admin/xuqiuCategory/edit?id={%id%}',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/xuqiuCategory/delete?id={%id%}',
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
     * @Route("/edit", name="admin_xuqiu_category_edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id', 0);

        /** @var PioneerparkXuqiuCategory $entry */
        $entry = $this->get('category_service')->findById('AppBundle:PioneerparkXuqiuCategory', $id);

        $form = $this->createFormBuilder($entry)
            ->add('title', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '分类名称', 'constraints' => [
                    new NotBlank(['message' => '分类名称不能为空'])
                ]
            ])
            ->add('sort', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => '排序', 'constraints' => [
                    new NotBlank(['message' => '排序不能为空'])
                ]
            ])
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType')
            ->getForm();

        if($form->handleRequest($this->request())->isValid()) {
            $data = $form->getData();
            if($id > 0) {
                $entry->setTitle($data['title']);
                $entry->setModel(2);
                $entry->setSort($data['sort']);
                $this->getDoctrine()->getManager()->flush();
                return $this->success('修改成功', 1);
            }else {
                $entry = new PioneerparkXuqiuCategory();
                $entry->setTitle($data['title']);
                $entry->setModel(2);
                $entry->setSort($data['sort']);
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
     * @Route("/delete", name="admin_xuqiu_category_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id', 0);
        /** @var PioneerparkXuqiuCategory $entry */
        $entry = $this->get('category_service')->findById('AppBundle:PioneerparkXuqiuCategory', $id);
        if($entry) {
            $this->getDoctrine()->getManager()->remove($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('删除成功', 1, '/admin/xuqiuCategory/index');
        }else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/xuqiuCategory/index');
        }
    }
}