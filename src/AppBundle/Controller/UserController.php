<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class VoucherController
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class UserController extends Controller
{
    /**
     * @Route("/user/profile", name="user_profile")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction(Request $request)
    {
        $form = $this->createForm(UserType::class, $this->getUser());

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($this->getUser());
            $em->flush();

            return $this->render('floathamburg/userprofile.html.twig', [
                'form' => null,
                'submitted' => true,
            ]);
        }

        return $this->render('floathamburg/userprofile.html.twig', [
            'form' => $form->createView(),
            'submitted' => false,
        ]);
    }
}
