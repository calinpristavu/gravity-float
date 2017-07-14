<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class VoucherController
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class UserController extends Controller
{
    /**
     * @Route("/user/profile", name="user_profile")
     */
    public function profileAction()
    {
        return $this->render('floathamburg/userprofile.html.twig');
    }
}
