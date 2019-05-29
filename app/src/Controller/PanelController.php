<?php
/**
 * Panel controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\UserTypeData;
use App\Form\UserTypePassword;

/**
 * Class PanelController.
 *
 * @Route("/panel")
 */
class PanelController extends AbstractController
{
    /**
     * ViewPanel action.
     *
     * @param \App\Entity\User $user User entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="user_panel",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function viewPanel(Request $request, RecipeRepository $recipeRepository, User $user, PaginatorInterface $paginator): Response
    {
        $LoggedId = $this->getUser()->getId();
        if($LoggedId === $user->getId() or $this->isGranted('ROLE_ADMIN')){

            $recipe_pagination = $paginator->paginate(
                $recipeRepository->findByAuthor($user->getId()),
                $request->query->getInt('page', 1),
                Recipe::NUMBER_OF_RECIPES
            );

            return $this->render(
                'user/viewPanel.html.twig',
                [
                    'user' => $user,
                    'loggedId' => $LoggedId,
                    'usersRecipes' => $recipe_pagination,
                ]
            );
        }
        else{
            $this->addFlash('warning', 'message.not_have_access');

            return $this->redirectToRoute('user_panel', ['id' => $LoggedId]);
        }
    }
    /**
     * EditDataPanel action.
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
     *     "/{id}/editDataPanel",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_editDataPanel",
     * )
     */
    public function editDataPanel(Request $request, User $user, UserRepository $repository): Response
    {
        $LoggedId = $this->getUser()->getId();
        if($LoggedId === $user->getId() or $this->isGranted('ROLE_ADMIN')) {

            $form = $this->createForm(UserTypeData::class, $user, ['method' => 'PUT']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $repository->save($user);

                $this->addFlash('success', 'message.updated_successfully');

                return $this->redirectToRoute('user_panel', ['id' => $user->getId()], 301);
            }

            return $this->render(
                'user/editDataPanel.html.twig',
                [
                    'form' => $form->createView(),
                    'user' => $user,
                ]
            );
        }
        else{
            $this->addFlash('warning', 'message.not_have_access');

            return $this->redirectToRoute('user_panel', ['id' => $LoggedId]);
        }
    }
    /**
     * EditPasswordPanel action.
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
     *     "/{id}/editPasswordPanel",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_editPasswordPanel",
     * )
     */
    public function editPasswordPanel(Request $request, User $user, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $LoggedId = $this->getUser()->getId();
        if($LoggedId === $user->getId() or $this->isGranted('ROLE_ADMIN')) {

            $form = $this->createForm(UserTypePassword::class, $user, ['method' => 'PUT']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
                $repository->save($user);

                $this->addFlash('success', 'message.updated_successfully');

                return $this->redirectToRoute('user_panel', ['id' => $user->getId()], 301);
            }

            return $this->render(
                'user/editPasswordPanel.html.twig',
                [
                    'form' => $form->createView(),
                    'user' => $user,
                ]
            );
        }
        else{
            $this->addFlash('warning', 'message.not_have_access');

            return $this->redirectToRoute('user_panel', ['id' => $LoggedId]);
        }
    }
}