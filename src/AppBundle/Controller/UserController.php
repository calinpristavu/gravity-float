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
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user, [
            'isPasswordRequired' => false,
            'createOnlineVouchersLabel' => false,
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $userManager = $this->container->get('fos_user.user_manager');
            $userManager->updateUser($user, true);

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
            'items_per_page' => $this->getParameter('users_per_page'),
        ];

        $users = $this->get('user.finder')->setFilters($filters)->getUsers();
        $allUsersCount = $this->getDoctrine()->getRepository('AppBundle:User')->countAll();
        $nrOfPages = (int)($allUsersCount / $this->getParameter('users_per_page')) + 1;
        if ($allUsersCount % $this->getParameter('users_per_page') == 0) {
            $nrOfPages = $allUsersCount / $this->getParameter('users_per_page');
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
        $userManager = $this->container->get('fos_user.user_manager');
        $newUser = $userManager->createUser();

        $form = $this->createForm(UserType::class, $newUser, [
            'isPasswordRequired' => true,
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $newUser->setEnabled(true);
            $userManager->updateUser($newUser, true);

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

        $form = $this->createForm(UserType::class, $user, [
            'isPasswordRequired' => false,
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $userManager = $this->container->get('fos_user.user_manager');
            $userManager->updateUser($user, true);

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
     * @Route("/user-management/suspend-user/{id}/{value}/{page}", name="suspend_user")
     *
     * @param User $user
     * @param bool $value
     * @param string $page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function suspendUserAction(User $user, bool $value, string $page = '1')
    {
        if ($user === null) {
            throw new \UnexpectedValueException("Cannot suspend user. User is invalid!");
        }

        $user->setEnabled($value);
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->updateUser($user, true);

        return $this->redirectToRoute('user_management', [
            'page' => $page,
        ]);
    }
}
