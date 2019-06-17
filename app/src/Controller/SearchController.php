<?php
/**
 * Search controller.
 */

namespace App\Controller;

use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController.
 * @Route("/search")
 */
class SearchController extends AbstractController
{
    /**
     * Index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "/",
     *     name="search_index",
     * )
     */
    public function index(Request $request): Response
    {
        $search = $request->get('search');
        if($search){
            if(preg_match('/^\w+[\w]*$/', $search)){
                return $this->redirectToRoute('search_action', ['search'=>$search]);
            }
            else{
                $this->addFlash('warning','message.search_error');
            }
        }

        return $this->render(
            'search/index.html.twig'
        );
    }

    /**
     * View action.
     *
     * @param string $search
     * @param IngredientRepository $ingredientRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(
     *     "/{search}",
     *     name="search_action",
     *     requirements={"search": "\w+[\w ]*"}
     * )
     *
     */
    public function view(string $search, IngredientRepository $ingredientRepository): Response
    {
        $ingredientResult = $ingredientRepository->findRecipeByIngredient($search)->getQuery()->getResult();
        $uniqueRecipe = [];

        foreach ($ingredientResult as $ingredient){
//            dump($ingredient);
            foreach ($ingredient->getRecipes() as $recipe) {
//                dump($recipe);
                if(!in_array($recipe, $uniqueRecipe)){
                    array_push($uniqueRecipe, $recipe);
                }
            }
        }

//        dump($ingredientResult);
//        dump($uniqueRecipe);
        return $this->render(
            'search/view.html.twig',
            [
                'search' => $search,
                'ingredients' =>$ingredientResult,
                'recipes' => $uniqueRecipe
            ]
        );

    }


}