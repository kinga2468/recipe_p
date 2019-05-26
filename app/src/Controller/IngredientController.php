<?php
/**
 * Ingredient controller.
 */

namespace App\Controller;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\IngredientType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Class IngredientController.
 *
 * @Route("/ingredient")
 */
class IngredientController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \App\Repository\IngredientRepository $repository Ingredient repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="ingredient_index",
     * )
     */
    public function index(IngredientRepository $repository): Response
    {
        return $this->render(
            'ingredient/index.html.twig',
            ['ingredients' => $repository->findAll()]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\Ingredient $ingredient Ingredient entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="ingredient_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Ingredient $ingredient): Response
    {
        return $this->render(
            'ingredient/view.html.twig',
            ['ingredient' => $ingredient]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\IngredientRepository        $repository Ingredient repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="ingredient_new",
     * )
     */
    public function new(Request $request, IngredientRepository $repository): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($ingredient);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('ingredient_index');
        }

        return $this->render(
            'ingredient/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Ingredient                      $ingredient   Ingredient entity
     * @param \App\Repository\IngredientRepository        $repository Ingredient repository
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
     *     name="ingredient_edit",
     * )
     */
    public function edit(Request $request, Ingredient $ingredient, IngredientRepository $repository): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($ingredient);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('ingredient_index');
        }

        return $this->render(
            'ingredient/editData.html.twig',
            [
                'form' => $form->createView(),
                'ingredient' => $ingredient,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Ingredient                      $ingredient   Ingredient entity
     * @param \App\Repository\IngredientRepository        $repository Ingredient repository
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
     *     name="ingredient_delete",
     * )
     */
    public function delete(Request $request, Ingredient $ingredient, IngredientRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $ingredient, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($ingredient);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('ingredient_index');
        }

        return $this->render(
            'ingredient/delete.html.twig',
            [
                'form' => $form->createView(),
                'ingredient' => $ingredient,
            ]
        );
    }
}