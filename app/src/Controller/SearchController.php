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
    public function index(Request $request, IngredientRepository $ingredientRepository): Response
    {
        $search = $request->get('search');
        $searches = explode(', ', $search);

        $listId = array();
//        $list = array();
        if($search){
            foreach($searches as $ingredients){
                $ingredientResult = $ingredientRepository->findRecipeByIngredient($ingredients)->getQuery()->getResult();
                foreach ($ingredientResult as $ingredient){
                    $uniqueRecipeId = [];
                    foreach ($ingredient->getRecipes() as $recipe) {
                        $recipeId = $recipe->getId();
                        if(!in_array($recipeId, $uniqueRecipeId)){
                            array_push($uniqueRecipeId, $recipeId);
                        }
                    }
                    $listId[] = $uniqueRecipeId;
                }
            }
            $uniqueRecipe = [];
            foreach($searches as $ingredients){
                $ingredientResult = $ingredientRepository->findRecipeByIngredient($ingredients)->getQuery()->getResult();
                foreach ($ingredientResult as $ingredient){
                    foreach ($ingredient->getRecipes() as $recipe) {
                        if(!in_array($recipe, $uniqueRecipe)){
                            array_push($uniqueRecipe, $recipe);
                        }
                    }
                }
            }

            $unique = [];
            if(count($listId)>=2){
                $intersect = call_user_func_array('array_intersect',$listId);
                foreach ($intersect as $recipeId_1){
                    foreach ($uniqueRecipe as $recipe_1){
                        if($recipeId_1 == $recipe_1->getId()){
                            if(!in_array($recipe_1, $unique)){
                                array_push($unique, $recipe_1);
                            }
                        }
                    }
                }
            }
            else{
                $unique = $uniqueRecipe;
            }
        }
        else{
            $this->addFlash('warning','message.search_error');
            $unique=[];
        }

        return $this->render(
            'search/view.html.twig',
            [
                'search' => $search,
                'recipes'=> $unique,
            ]
        );
    }


}