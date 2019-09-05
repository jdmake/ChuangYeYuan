<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/15
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Dynamic;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\PioneerparkDongtaiCategory;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;

/**
 * @Route("/dynamic")
 */
class DynamicCategoryController extends AbsController
{
    /**
     * @Route("/index", name="admin_dynamic_index")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('username', '');

        $pagination = $this->get('category_service')->getPageList('AppBundle:PioneerparkDongtaiCategory', $page, 15,
            function (QueryBuilder $query) use ($search) {
                if ($search != '') {
                    $search = trim($search);
                    $query->andWhere('a.title like :title')
                        ->setParameter('title', "%{$search}%");
                }
                $query->orderBy('a.sort', 'asc');
                return $query;
            });

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("动态分类")
            ->addTopSearch('username', '输入分类名称进行搜索', '/admin/dynamic/index')
            ->addTopButton(new LinkButton([
                'title' => ' + 添加分类',
                'url' => '/admin/dynamic/edit',
                'popup' => true,
            ]))
            ->add('id', '编号')
            ->add('title', '分类名称')
            ->add('sort', '排序')
            ->addAction('操作', [
                new LinkButton([
                    'title' => '编辑',
                    'url' => '/admin/dynamic/edit?id={%id%}',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/dynamic/delete?id={%id%}',
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
     * @Route("/edit", name="admin_dynamic_edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id', 0);

        /** @var PioneerparkDongtaiCategory $entry */
        $entry = $this->get('category_service')->findById('AppBundle:PioneerparkDongtaiCategory', $id);

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
                $entry->setSort($data['sort']);
                $this->getDoctrine()->getManager()->flush();
                return $this->success('修改成功', 1);
            }else {
                $entry = new PioneerparkDongtaiCategory();
                $entry->setTitle($data['title']);
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
     * @Route("/delete", name="admin_dynamic_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id', 0);
        /** @var PioneerparkDongtaiCategory $entry */
        $entry = $this->get('category_service')->findById('AppBundle:PioneerparkDongtaiCategory', $id);
        if($entry) {
            $this->getDoctrine()->getManager()->remove($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('删除成功', 1, '/admin/dynamic/index');
        }else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/dynamic/index');
        }
    }
}