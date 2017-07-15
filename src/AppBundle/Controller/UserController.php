<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
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
    public static $NUMBER_OF_USERS_PER_PAGE = 5;

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

    /**
     * @Route("/user-management", name="user_management")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userManagementAction(Request $request)
    {
        if (!$request->get('page')) {
            $request->request->set('page', 1);
        }

        $filters = [
            'page' => (int)$request->get('page'),
            'items_per_page' => self::$NUMBER_OF_USERS_PER_PAGE,
        ];

        $users = $this->get('user.finder')->setFilters($filters)->getUsers();
        $allUsersCount = $this->getDoctrine()->getRepository('AppBundle:User')->countAll();
        $nrOfPages = (int)($allUsersCount / self::$NUMBER_OF_USERS_PER_PAGE) + 1;
        if ($allUsersCount % self::$NUMBER_OF_USERS_PER_PAGE == 0) {
            $nrOfPages = $allUsersCount / self::$NUMBER_OF_USERS_PER_PAGE;
        }

        return $this->render('floathamburg/usermanagement.html.twig',[
            'users' => $users,
            'numberOfPages' => $nrOfPages,
            'currentPage' => $request->get('page'),
        ]);
    }

    /**
     * @Route("/user-management/create-user", name="create_user")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createUserAction(Request $request)
    {
        $newUser = new User();
        $form = $this->createForm(UserType::class, $newUser);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $newUser->setEnabled(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($newUser);
            $em->flush();

            return $this->render('floathamburg/createuser.html.twig', [
                'form' => null,
                'submitted' => true,
            ]);
        }

        return $this->render('floathamburg/createuser.html.twig', [
            'form' => $form->createView(),
            'submitted' => false,
        ]);
    }

    /**
     * @Route("/user-management/edit-user/{id}", name="edit_user")
     *
     * @param Request $request
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editUserAction(Request $request, User $user)
    {
        if ($user === null) {
            throw new \UnexpectedValueException("Cannot edit user. User is invalid!");
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->render('floathamburg/edituser.html.twig', [
                'form' => null,
                'submitted' => true,
            ]);
        }

        return $this->render('floathamburg/edituser.html.twig', [
            'form' => $form->createView(),
            'submitted' => false,
        ]);
    }

    /**
     * @Route("/user-management/suspend-user/{id}/{value}", name="suspend_user")
     *
     * @param User $user
     * @param bool $value
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function suspendUserAction(User $user, bool $value)
    {
        if ($user === null) {
            throw new \UnexpectedValueException("Cannot suspend user. User is invalid!");
        }

        $user->setEnabled($value);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('user_management');
    }
}
