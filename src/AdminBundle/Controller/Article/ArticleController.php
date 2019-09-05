<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/9/5
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Article;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\PioneerparkArticle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\Enumeration;
use YuZhi\TableingBundle\Tableing\Components\Image;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;
use YuZhi\TableingBundle\Tableing\Components\Remark;

/**
 * @Route("/article")
 */
class ArticleController extends AbsController
{
    /**
     * @Route("/", name="admin_article")
     */
    public function indexAction()
    {
        $type = $this->request()->get('type');
        $cate = $this->request()->get('cate');
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('search', '');

        $pagination = $this->get('article_service')->getPageListByAdmin($type, $cate, $search, $page, 15);

        $cates = array_column($this->get('article_service')->getCates($type), 'title', 'id');

        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("内容列表")
            ->addFilter('cate', '不限分类', $cates)
            ->addTopSearch('username', '输入标题进行搜索', '/admin/article/')
            ->addTopButton(new LinkButton([
                'title' => ' + 添加',
                'url' => '/admin/article/edit?type=' . $type,
                'popup' => true,
            ]))
            ->add('id', '编号')
            ->add('cate', '分类', [
                'type' => new Enumeration($cates)
            ])
            ->add('thumbnail', '缩略图', [
                'type' => new Image()
            ])
            ->add('title', '标题')
            ->add('content', '摘要', [
                'type' => new Remark(30)
            ])
            ->addAction('操作', [
                new LinkButton([
                    'title' => '编辑',
                    'url' => '/admin/article/edit?type=' . $type . '&id={%id%}',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/article/delete?type=' . $type . '&id={%id%}',
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
     * @Route("/edit", name="admin_article_edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id', 0);
        $type = $this->request()->get('type');

        /** @var PioneerparkArticle $entry */
        $entry = $this->get('category_service')->findById('AppBundle:PioneerparkArticle', $id);

        // 获取分类
        $cates = array_column($this->get('article_service')->getCates($type), 'id', 'title');

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

                $this->getDoctrine()->getManager()->flush();

                return $this->success('修改成功', 1);
            } else {

                $entry = new PioneerparkArticle();
                $entry->setType($type);
                $entry->setCate($data['cate']);
                $entry->setTitle($data['title']);
                $entry->setSubtitle($data['subtitle']);
                $entry->setThumbnail($data['thumbnail']);
                $entry->setContent($data['content']);
                $entry->setIsdisplay(true);
                $entry->setVisit(0);
                $entry->setCreateAt(new \DateTime());

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
     * @Route("/delete", name="admin_article_delete")
     */
    public function deleteAction()
    {
        $type = $this->request()->get('type');

        $id = $this->request()->get('id', 0);
        /** @var PioneerparkArticle $entry */
        $entry = $this->get('admin_needs_service')->findById('AppBundle:PioneerparkArticle', $id);
        if ($entry) {
            $this->getDoctrine()->getManager()->remove($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('删除成功', 1, '/admin/article?type=' . $type);
        } else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/article?type=' . $type);
        }
    }

}