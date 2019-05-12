<?php
/**
 * Main controller.
 */

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Class MainController.
 *
 * @Route("/main")
 */
class MainController extends AbstractController
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
     *     name="main_index",
     * )
     */
    public function index(Request $request, RecipeRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Recipe::NUMBER_OF_ITEMS
        );

        return $this->render(
            'main/index.html.twig',
            [
                'pagination' => $pagination,
                'newestRecipe' => $repository -> findRecipeByUpdateDate()
            ]
        );
    }
}