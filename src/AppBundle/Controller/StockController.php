<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StockController extends Controller
{

    /**
     * @Route("/stock", name="stock")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/admin/stock", name="admin_stock_list")
     * @Template()
     */
    public function adminListAction()
    {
        return [];
    }
    /**
     * @Route("/admin/stock/add", name="admin_stock_add")
     * @Template()
     */
    public function adminAddAction()
    {
        return [];
    }

}
