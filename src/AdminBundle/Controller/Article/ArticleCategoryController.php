<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/5
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Article;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\PioneerparkArticleCategory;
use AppBundle\Service\ArticleService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;

/**
 * @Route("/article/category")
 */
class ArticleCategoryController extends AbsController
{
    /**
     * @Route("/", name="admin_article_category")
     */
    public function indexAction()
    {
        $type = $this->request()->get('type');
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('search', '');

        $pagination = $this->get('article_service')->getCategoryPageListByAdmin($type, $search, $page, 15);

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("分类列表")
            ->addTopSearch('username', '输入分类名称进行搜索', '/admin/article/category')
            ->addTopButton(new LinkButton([
                'title' => ' + 添加分类',
                'url' => '/admin/article/category/edit?type=' . $type,
                'popup' => true,
            ]))
            ->add('id', '编号')
            ->add('title', '分类名称')
            ->add('sort', '排序')
            ->addAction('操作', [
                new LinkButton([
                    'title' => '编辑',
                    'url' => '/admin/article/category/edit?id={%id%}',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/article/category/delete?type='. $type .'&id={%id%}',
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
     * @Route("/edit", name="admin_article_category_edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id', 0);
        $type = $this->request()->get('type');

        /** @var PioneerparkArticleCategory $entry */
        $entry = $this->get('category_service')->findById('AppBundle:PioneerparkArticleCategory', $id);

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
                $entry = new PioneerparkArticleCategory();
                $entry->setTitle($data['title']);
                $entry->setSort($data['sort']);
                $entry->setType($type);
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
     * @Route("/delete", name="admin_article_category_delete")
     */
    public function deleteAction()
    {
        $type = $this->request()->get('type');

        $id = $this->request()->get('id', 0);
        /** @var PioneerparkArticleCategory $entry */
        $entry = $this->get('category_service')->findById('AppBundle:PioneerparkArticleCategory', $id);

        if($entry) {

            $this->getDoctrine()->getManager()->remove($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('删除成功', 1, '/admin/article/category?type=' . $type);

        }else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/article/category?type=' . $type);
        }
    }
}