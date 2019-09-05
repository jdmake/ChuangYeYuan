<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/17
// +----------------------------------------------------------------------


namespace AdminBundle\Controller\Merchant;


use AdminBundle\Controller\Common\AbsController;
use AppBundle\Entity\PioneerparkMerchant;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use YuZhi\TableingBundle\Tableing\Components\DateTime;
use YuZhi\TableingBundle\Tableing\Components\Enable;
use YuZhi\TableingBundle\Tableing\Components\Image;
use YuZhi\TableingBundle\Tableing\Components\LinkButton;

/**
 * @Route("/merchant")
 */
class MerchantController extends AbsController
{
    /**
     * @Route("/index", name="admin_merchant_index")
     */
    public function indexAction()
    {
        $page = $this->request()->get('page', 1);
        $search = $this->request()->get('search');
        $status = $this->request()->get('status');

        $pagination = $this->get('admin_merchant_service')->getPageList('AppBundle:PioneerparkMerchant', $page, 15,
            function (QueryBuilder $query) use ($search, $status) {
                if ($search != '') {
                    $search = trim($search);
                    $query->where('a.name like :name')->setParameter('name', "%{$search}%");
                }
                if ($status != '') {
                    $query->where('a.status=:status')->setParameter('status', $status);
                }
                $query->orderBy('a.createAt', 'desc');
                return $query;
            });
        $tableBuilder = $this->get('yuzhi_tableing.table_builder');
        $tableView = $tableBuilder->createTable($pagination)
            ->setPageTitle("商户列表")
            ->addFilter('status', '不限状态', [
                0 => '待审核',
                1 => '已通过',
                2 => '未通过',
            ])
            ->addTopSearch('search', '输入商户名称进行搜索', '/admin/merchant/index')
            ->addTopButton(new LinkButton([
                'title' => ' + 添加商户',
                'url' => '/admin/merchant/edit',
                'popup' => true,
            ]))
            ->addTopButton(new LinkButton([
                'title' => '导出商户',
                'url' => '/admin/merchant/export',
                'class' => 'btn btn-info',
            ]))
            ->add('id', '编号')
            ->add('logopic', '公司logo', [
                'type' => new Image()
            ])
            ->add('name', '商户名称')
            ->add('creditcode', '信用代码')
            ->add('scope', '经营范围')
            ->add('monthlyrent', '月租')
            ->add('startingtime', '起租时间', [
                'type' => new DateTime('Y-m-d')
            ])
            ->add('licensepic', '营业执照', [
                'type' => new Image()
            ])
            ->add('contacts', '联系人')
            ->add('tel', '电话')
            ->add('status', '状态', [
                'type' => new Enable([
                    0 => ['title' => '待审核'],
                    1 => ['title' => '已通过', 'class' => 'badge badge-success'],
                    2 => ['title' => '未通过', 'class' => 'badge badge-pink'],
                ])
            ])
            ->add('createAt', '入驻时间', [
                'type' => new DateTime('Y-m-d H:i:s')
            ])
            ->addAction('操作', [
                new LinkButton([
                    'title' => '通过',
                    'url' => '/admin/merchant/pass?id={%id%}&status=' . $status,
                    'class' => 'btn btn-info',
                    'confirm' => '真的要操作吗？',
                    'show' => ['field' => 'status', 'value' => 0]
                ]),
                new LinkButton([
                    'title' => '不通过',
                    'url' => '/admin/merchant/nopass?id={%id%}&status=' . $status,
                    'class' => 'btn btn-red',
                    'confirm' => '真的要操作吗？',
                    'show' => ['field' => 'status', 'value' => 1]
                ]),
                new LinkButton([
                    'title' => '编辑',
                    'url' => '/admin/merchant/edit?id={%id%}',
                    'popup' => true
                ]),
                new LinkButton([
                    'title' => '删除',
                    'url' => '/admin/merchant/delete?id={%id%}',
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
     * @Route("/edit", name="admin_merchant_edit")
     */
    public function editAction()
    {
        $id = $this->request()->get('id', 0);

        /** @var PioneerparkMerchant $entry */
        $entry = $this->get('admin_merchant_service')->findById('AppBundle:PioneerparkMerchant', $id);

        $form = $this->createFormBuilder($entry)
            ->add('uid', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '会员UID', 'constraints' => [
                    new NotBlank(['message' => '会员UID不能为空'])
                ]
            ])
            ->add('name', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '企业名称', 'constraints' => [
                    new NotBlank(['message' => '企业名称不能为空'])
                ]
            ])
            ->add('creditCode', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '信用代码', 'constraints' => [
                    new NotBlank(['message' => '信用代码不能为空'])
                ]
            ])
            ->add('scope', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '经营范围', 'constraints' => [
                    new NotBlank(['message' => '经营范围不能为空'])
                ]
            ])
            ->add('contacts', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '联系人', 'constraints' => [
                    new NotBlank(['message' => '联系人不能为空'])
                ]
            ])
            ->add('tel', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => '电话', 'constraints' => [
                    new NotBlank(['message' => '电话不能为空'])
                ]
            ])
            ->add('monthlyRent', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => '月租', 'constraints' => [
                    new NotBlank(['message' => '月租不能为空'])
                ]
            ])
            ->add('startingTime', 'Symfony\Component\Form\Extension\Core\Type\DateType', [
                'label' => '起租时间	', 'constraints' => [
                    new NotBlank(['message' => '起租时间不能为空'])
                ]
            ])
            ->add('licensePic', 'AdminBundle\Custom\Form\Type\AvatarType', [
                'label' => '营业执照', 'constraints' => [
                    new NotBlank(['message' => '营业执照不能为空'])
                ]
            ])
            ->add('logoPic', 'AdminBundle\Custom\Form\Type\AvatarType', [
                'label' => '公司logo', 'constraints' => [
                    new NotBlank(['message' => '公司logo不能为空'])
                ]
            ])
            ->add('status', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'label' => '审核状态', 'choices' => [
                    '待审核' => 0,
                    '通过' => 1,
                    '未通过' => 2,
                ]
            ])
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType')
            ->getForm();

        if ($form->handleRequest($this->request())->isValid()) {
            $data = $form->getData();
            if ($id > 0) {
                $entry->setName($data['name']);
                $entry->setCreditcode($data['creditcode']);
                $entry->setContacts($data['contacts']);
                $entry->setTel($data['tel']);
                $entry->setScope($data['scope']);
                $entry->setMonthlyrent($data['monthlyrent']);
                $entry->setStartingtime($data['startingtime']);
                $entry->setLicensepic($data['licensepic']);
                $entry->setLogopic($data['logopic']);
                $entry->setStatus($data['status']);
                $this->getDoctrine()->getManager()->flush();
                return $this->success('修改成功', 1);
            } else {
                $entry = new PioneerparkMerchant();
                $entry->setName($data['name']);
                $entry->setCreditcode($data['creditcode']);
                $entry->setContacts($data['contacts']);
                $entry->setTel($data['tel']);
                $entry->setScope($data['scope']);
                $entry->setMonthlyrent($data['monthlyrent']);
                $entry->setStartingtime($data['startingtime']);
                $entry->setLicensepic($data['licensepic']);
                $entry->setLogopic($data['logopic']);
                $entry->setStatus($data['status']);
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
     * @Route("/pass", name="admin_merchant_pass")
     */
    public function passAction()
    {
        $id = $this->request()->get('id', 0);
        $status = $this->request()->get('status');

        /** @var PioneerparkMerchant $entry */
        $entry = $this->get('admin_merchant_service')->findById('AppBundle:PioneerparkMerchant', $id);
        if ($entry) {
            $entry->setStatus(1);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('操作成功', 1, '/admin/merchant/index?status=' . $status);
        } else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/merchant/index?status=' . $status);
        }
    }

    /**
     * @Route("/nopass", name="admin_merchant_nopass")
     */
    public function nopassAction()
    {
        $id = $this->request()->get('id', 0);
        $status = $this->request()->get('status');

        /** @var PioneerparkMerchant $entry */
        $entry = $this->get('admin_merchant_service')->findById('AppBundle:PioneerparkMerchant', $id);
        if ($entry) {
            $entry->setStatus(2);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('操作成功', 1, '/admin/merchant/index?status=' . $status);
        } else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/merchant/index?status=' . $status);
        }
    }

    /**
     * @Route("/delete", name="admin_merchant_delete")
     */
    public function deleteAction()
    {
        $id = $this->request()->get('id', 0);
        $status = $this->request()->get('status');

        /** @var PioneerparkMerchant $entry */
        $entry = $this->get('admin_merchant_service')->findById('AppBundle:PioneerparkMerchant', $id);
        if ($entry) {
            $this->getDoctrine()->getManager()->remove($entry);
            $this->getDoctrine()->getManager()->flush();
            return $this->success('删除成功', 1, '/admin/merchant/index?status=' . $status);
        } else {
            return $this->error('数据不存在，或已被删除', 5, '/admin/merchant/index?status=' . $status);
        }
    }

    /**
     * @Route("/export", name="admin_merchant_export")
     */
    public function exportAction()
    {
        // 获取全部商户
        $merchantEntryList = $this->getDoctrine()->getRepository('AppBundle:PioneerparkMerchant')->findBy([
            'status' => 1
        ]);

        include __DIR__ . '/../../Service/PHPExceling/PHPExcel.php';
        include __DIR__ . '/../../Service/PHPExceling/PHPExcel/Writer/Excel2007.php';

        $objPHPExcel = new \PHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
        $objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', '商户名称');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', '信用代码');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', '经营范围');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', '月租');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', '起租时间');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', '联系人');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', '电话');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', '入驻时间');

        $index = 1;
        foreach ($merchantEntryList as $item) {
            $index++;
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $index, $item->getName());
            $objPHPExcel->getActiveSheet()->getStyle('B' . $index)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $index, $item->getCreditcode());
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $index, $item->getScope());
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $index, $item->getMonthlyrent());
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $index, $item->getStartingtime());
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $index, $item->getContacts());
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $index, $item->getTel());
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $index, $item->getCreateAt()->format('Y-m-d H:i:s'));
        }

        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        //创建Excel输入对象
        ob_clean();      //清除缓冲区
        Header('content-Type:application/vnd.ms-excel;charset=utf-8');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="商户汇总报表(' . date('Ymd') . ').xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }
}