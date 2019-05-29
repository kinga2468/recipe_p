<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\Recipe;
use App\Form\UserType;
use App\Repository\RecipeRepository;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\UserTypeData;
use App\Form\UserTypePassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\UserRepository        $repository User repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "/",
     *     name="user_index",
     * )
     */
    public function index(Request $request, UserRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            User::NUMBER_OF_ITEMS
        );

        return $this->render(
            'user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\User $user User entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="user_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Request $request, UserRepository $userRepository, RecipeRepository $recipeRepository, User $user, $id,  PaginatorInterface $paginator): Response
    {
        $recipe_pagination = $paginator->paginate(
            $recipeRepository->findByAuthor($user->getId()),
            $request->query->getInt('page', 1),
            Recipe::NUMBER_OF_RECIPES
        );

        return $this->render(
            'user/view.html.twig',
            [
                'user' => $user,
                'usersRecipes' => $recipe_pagination,
            ]
        );
    }
    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\UserRepository        $repository User repository
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="user_new",
     * )
     */
    public function new(Request $request, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));

            $repository->save($user);
            $this->addFlash('success', 'message.created_successfully');
            return $this->redirectToRoute('main_index');
        }
        return $this->render(
            'user/new.html.twig',
            ['form' => $form->createView()]
        );
    }
    /**
     * EditData action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\User                      $user   User entity
     * @param \App\Repository\UserRepository        $repository User repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/editData",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_editData",
     * )
     */
    public function editData(Request $request, User $user, UserRepository $repository): Response
    {
        $form = $this->createForm(UserTypeData::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($user);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/editData.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
    /**
     * EditPassword action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\User                      $user   User entity
     * @param \App\Repository\UserRepository        $repository User repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/editPassword",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_editPassword",
     * )
     */
    public function editPassword(Request $request, User $user, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserTypePassword::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $repository->save($user);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/editPassword.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
    /**
     * EditRole action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\User                      $user   User entity
     * @param \App\Repository\UserRepository        $repository User repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/editRole",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_editRole",
     * )
     */
    public function editRole(Request $request, User $user, UserRepository $repository): Response
    {
        $role = $user->getRoles();
        $form = $this->createForm(FormType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

//        if ($request->isMethod('PUT')) {
        if ($form->isSubmitted() && $form->isValid()) {
            if ($role == ['ROLE_USER']){
                $user->setRoles(['ROLE_USER','ROLE_ADMIN']);
            }
            if ($role == ['ROLE_USER','ROLE_ADMIN']){
                $user->setRoles(['ROLE_USER']);
            }

            $repository->save($user);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/editRole.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\User                      $user   User entity
     * @param \App\Repository\UserRepository        $repository User repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_delete",
     * )
     */
    public function delete(Request $request, User $user, UserRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $user, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE')) {
            $repository->delete($user);

            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/delete.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}