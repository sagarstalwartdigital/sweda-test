<?php

namespace Foggyline\Office\Controller\Test;

class Collection extends \Foggyline\Office\Controller\Test
{
    protected $employeeFactory;
    protected $departmentFactory;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Foggyline\Office\Model\EmployeeFactory $employeeFactory,
        \Foggyline\Office\Model\DepartmentFactory $departmentFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        $this->employeeFactory = $employeeFactory;
        $this->departmentFactory = $departmentFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        return parent::__construct($context);
    }

    public function execute()
    {

        $collection = $this->employeeFactory->create()
            ->getCollection();

        /** The same as before **/
        /*
        $collection = $this->_objectManager->create(
            'Foggyline\Office\Model\ResourceModel\Employee\Collection'
        );
        */

//        $collection
//            ->addAttributeToSelect('salary')
//            ->addAttributeToSelect('vat_number');

        $collection->addAttributeToSelect('*')
            ->setPageSize(10)
            ->setCurPage(1);


        $employees = [];

        foreach ($collection as $employee) {
            array_push($employees, $employee->toArray());
            //var_dump($employee->toArray(), '$employee');
        }

        $resultJson = $this->resultJsonFactory->create();
        $response = ['success' => 'true'];
        $resultJson->setData($employees);
        return $resultJson;

//
//        $collection = $this->departmentFactory->create()
//            ->getCollection();

//        foreach ($collection as $department) {
//            var_dump($department->toArray(), '$department');
//        }

        /**
         *
         * CHeck list of filters
         *
         * vendor/magento/framework/DB/Adapter/Pdo/Mysql prepareSqlCondition
         */

//        $collection = $this->_objectManager->create(
//            'Foggyline\Office\Model\ResourceModel\Employee\Collection'
//        );
//        $collection->addAttributeToSelect('*')
//            ->setPageSize(25)
//            ->setCurPage(1);
//        $collection->addAttributeToFilter('email',
//            array('like' => '%mail.loc%'))
//            ->addAttributeToFilter('vat_number',
//                array('like' => 'GB%'))
//            ->addAttributeToFilter('salary', array('gt' => 2400))
//            ->addAttributeToFilter('service_years',
//                array('lt' => 10));
//
//
//        /** try  catch should be used all the time...not how it is done here on test examples !!!! **/
//        echo 'Collection operations finalized';
//        exit;
    }
}
