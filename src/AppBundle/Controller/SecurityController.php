<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\ForgotPasswordType;
use AppBundle\Form\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SecurityController
 *
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class SecurityController extends Controller
{
    /**
     * @Route("/forgot", name="forgot_pass")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forgotPasswordAction(Request $request)
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $userEmail = $form->getData()['email'];
            $matchedUsers = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->findBy(['email' => $userEmail]);

            if (count($matchedUsers) == 0) {
                return $this->render('floathamburg/forgotpassword.html.twig', [
                    'form' => null,
                    'submitted' => true,
                    'emailNotFound' => true,
                ]);
            }

            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $matchedUsers[0]->setConfirmationToken($tokenGenerator->generateToken());
            $em = $this->getDoctrine()->getManager();
            $em->persist($matchedUsers[0]);
            $em->flush();

            $message = \Swift_Message::newInstance(null)
                ->setSubject("Password Reset")
                ->setTo($userEmail)
                ->setFrom('mydummytestingemail@gmail.com')
                ->setBody("
                    \"In order to reset your password, please follow this link:\"
                ")
            ;

            $this->get('mailer')->send($message);
            return $this->render('floathamburg/forgotpassword.html.twig', [
                'form' => null,
                'submitted' => true,
                'emailNotFound' => false,
            ]);
        }

        return $this->render('floathamburg/forgotpassword.html.twig', [
            'form' => $form->createView(),
            'submitted' => false,
            'emailNotFound' => null,
        ]);
    }
}