<?php
/**
 * Recipe controller.
 */

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\Comment;
use App\Repository\RecipeRepository;
use App\Repository\TagRepository;
use App\Repository\CommentRepository;
use App\Form\CommentType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RecipeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Class RecipeController.
 *
 * @Route("/recipe")
 */
class RecipeController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\RecipeRepository            $repository Repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="recipe_index",
     * )
     */
    public function index(Request $request, RecipeRepository $repository, TagRepository $tagRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->allRecipeByUpdateDate(),
            $request->query->getInt('page', 1),
            Recipe::NUMBER_OF_ITEMS
        );

        return $this->render(
            'recipe/index.html.twig',
            [
                'pagination' => $pagination,
                'mostPopularTag' => $repository -> findMostPopularTag()
//                'allNewestRecipe' => $repository -> allRecipeByUpdateDate()
            ]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\Recipe $recipe Recipe entity
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\RecipeRepository        $repository Recipe repository
     **
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="recipe_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Recipe $recipe, Request $request, RecipeRepository $repository, CommentRepository $commentRepository, $id): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new \DateTime());
            $comment->setUpdatedAt(new \DateTime());
            $comment->setRecipe($recipe);
            $commentRepository->save($comment);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('recipe_view', ['id' => $id]);
        }

        return $this->render(
            'recipe/view.html.twig',
            [
                'id' => $id,
                'recipe' => $recipe,
                'recipesComments' => $repository -> findRecipeComments($id),
                'form_comment' => $commentForm->createView(),
            ]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\RecipeRepository        $repository Recipe repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="recipe_new",
     * )
     */
    public function new(Request $request, RecipeRepository $repository): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setAuthor($this->getUser());
            $repository->save($recipe);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'recipe/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Recipe                      $recipe   Recipe entity
     * @param \App\Repository\RecipeRepository        $repository Recipe repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="recipe_edit",
     * )
     */
    public function edit(Request $request, Recipe $recipe, RecipeRepository $repository): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($recipe);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'recipe/edit.html.twig',
            [
                'form' => $form->createView(),
                'recipe' => $recipe,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Recipe                      $recipe   Recipe entity
     * @param \App\Repository\RecipeRepository        $repository Recipe repository
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
     *     name="recipe_delete",
     * )
     */
    public function delete(Request $request, Recipe $recipe, RecipeRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $recipe, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($recipe);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'recipe/delete.html.twig',
            [
                'form' => $form->createView(),
                'recipe' => $recipe,
            ]
        );
    }
}