<?php
/**
 * Created by PhpStorm.
 * User: pkowalski
 * Date: 11/04/2017
 * Time: 00:15
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{

    /**
     * @Route("/admin", name="admin")
     * @Template()
     */
    public function indexAction()
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->createNotFoundException();
        }
        return [];
    }

}