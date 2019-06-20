<?php
/**
 * Ingredient controller.
 */

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use App\Repository\IngredientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\IngredientRepository        $repository Ingredient repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "/",
     *     name="ingredient_index",
     * )
     */
    public function index(Request $request, IngredientRepository $repository, PaginatorInterface $paginator): Response
    {
        if ($this->isGranted('ROLE_ADMIN')==false){
            $this->addFlash('warning', 'message.not_have_access');

            return $this->redirectToRoute('main_index');
        }

        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Ingredient::NUMBER_OF_ITEMS
        );

        return $this->render(
            'ingredient/index.html.twig',
            ['ingredients' => $pagination]
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
        if ($this->isGranted('ROLE_ADMIN')==false){
            $this->addFlash('warning', 'message.not_have_access');

            return $this->redirectToRoute('main_index');
        }

        $form = $this->createForm(IngredientType::class, $ingredient, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($ingredient);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('ingredient_index');
        }

        return $this->render(
            'ingredient/edit.html.twig',
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
        if ($this->isGranted('ROLE_ADMIN')==false){
            $this->addFlash('warning', 'message.not_have_access');

            return $this->redirectToRoute('main_index');
        }

        $form = $this->createForm(FormType::class, $ingredient, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE')) {
            //$form->submit($request->request->get($form->getName()));
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