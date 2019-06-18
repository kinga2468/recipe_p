<?php
/**
 * Recipe controller.
 */

namespace App\Controller;

use App\Repository\IngredientRepository;
use App\Repository\PhotoRepository;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\Recipe;
use App\Entity\Comment;
use App\Entity\Ingredient;
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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

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
    public function view(Recipe $recipe, PaginatorInterface $paginator, Request $request, RecipeRepository $repository, CommentRepository $commentRepository, $id): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new \DateTime());
            $comment->setUpdatedAt(new \DateTime());
            $comment->setRecipe($recipe);
            $comment->setAuthor($this->getUser());
            $commentRepository->save($comment);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('recipe_view', ['id' => $id]);
        }

        $comment_pagination = $paginator->paginate(
            $commentRepository->findByRecipe($recipe->getId()),
            $request->query->getInt('page', 1),
            Comment::NUMBER_OF_ITEMS
        );

        $ingredients = $repository->findRecipeIngredients($recipe->getId());

        return $this->render(
            'recipe/view.html.twig',
            [
                'id' => $id,
                'recipe' => $recipe,
                'ingredients' => $ingredients,
                'recipesComments' => $comment_pagination,
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
    public function new(Request $request, RecipeRepository $repository, IngredientRepository $ingredientRepository, PhotoRepository $photoRepository): Response
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
    public function edit($id, Request $request, Recipe $recipe, RecipeRepository $repository, EntityManagerInterface $entityManager): Response
    {
//        https://symfony.com/doc/current/form/form_collections.html - jeśli by coś nie działało to jeszcze ten ostatni kod zanalizuj
        if ($recipe->getAuthor() !== $this->getUser() and $this->isGranted('ROLE_ADMIN')==false){
            $this->addFlash('warning', 'message.item_not_found');

            return $this->redirectToRoute('recipe_index');
        }





//        if (null === $recipe = $entityManager->getRepository(Recipe::class)->find($id)) {
//            throw $this->createNotFoundException('No recipe found for id '.$id);
//        }
//        $originalIngredients = new ArrayCollection();
//        // Create an ArrayCollection of the current Tag objects in the database
//        foreach ($recipe->getIngredient() as $ingredient) {
//            $originalIngredients->add($ingredient);
//        }



        $form = $this->createForm(RecipeType::class, $recipe, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipePhoto = $form->get('photo')->getData();
            if($recipePhoto!=null){
                $recipe->setPhoto($recipePhoto);
            }


//            foreach ($originalIngredients as $ingredient) {
//                if (false === $recipe->getIngredient()->contains($ingredient)) {
//                    $ingredient->getRecipes()->removeElement($recipe);
//                    $entityManager->persist($ingredient);
//                }
//            }
//
//            $entityManager->persist($recipe);
//            $entityManager->flush();

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
        if ($recipe->getAuthor() !== $this->getUser() and $this->isGranted('ROLE_ADMIN')==false){
            $this->addFlash('warning', 'message.item_not_found');

            return $this->redirectToRoute('recipe_index');
        }

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