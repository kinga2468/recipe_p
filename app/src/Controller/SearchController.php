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
        $uniqueRecipes = [];
//        dump($searches);
        if($search){
            foreach($searches as $ingredients){
//                dump($ingredients);
                $ingredientResult = $ingredientRepository->findRecipeByIngredient($ingredients)->getQuery()->getResult();
//                dump($ingredientResult);
                foreach ($ingredientResult as $ingredient){
                    $uniqueRecipe = [];
                    foreach ($ingredient->getRecipes() as $recipe) {
                        if(!in_array($recipe, $uniqueRecipe)){
                            array_push($uniqueRecipe, $recipe);
                        }
                    }
                    array_push($uniqueRecipes, $uniqueRecipe);
                }
            }
//            dump($uniqueRecipes);
        }
        else{
            $this->addFlash('warning','message.search_error');
            $ingredientResult=[];
            $uniqueRecipe=[];
        }




//        if($search){
//            if(preg_match('/^\w+[\w]*$/', $search)){
//                return $this->redirectToRoute('search_action', ['search'=>$search]);
//            }
//            else{
//                $this->addFlash('warning','message.search_error');
//            }
//        }

        return $this->render(
            'search/view.html.twig',
            [
                'search' => $search,
                'ingredients' =>$ingredientResult,
                'recipes' => $uniqueRecipe
            ]
        );
    }
//    public function index(Request $request) {
//        if ($request->getMethod() == 'GET') {
//            $title = $request->get('search');
//            $em = $this->getDoctrine()->getManager();
//            $qb = $em->getRepository('YCRYcrBundle:Ingredient')
//                ->createQueryBuilder('i');
//            $searches= explode(' ', $title);
//
//            foreach ($searches as $sk => $sv) {
//                $cqb[]=$qb->expr()->like("CONCAT($sv, '')", "'%$sv%'");
//            }
//
//            $qb->andWhere(call_user_func_array(array($qb->expr(),"orx"),$cqb));
//
//            $recipes = $qb->getResult();
//        }else{
//            $recipes=[];
//        }
//        dump($recipes);
//        return $this->render(
//            'search/index.html.twig',
//            [
//                'recipes' => $recipes
//            ]
//        );
//    }

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
//        dump($search);
//        die();
        $ingredientResult = $ingredientRepository->findRecipeByIngredient($search)->getQuery()->getResult();
        $uniqueRecipe = [];
//        dump($ingredientResult);
//        die();

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