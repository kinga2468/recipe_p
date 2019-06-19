<?php
/**
 * UserData controller.
 */

namespace App\Controller;

use App\Entity\UserData;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use App\Repository\UserDataRepository;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\UserTypeData;
use App\Form\UserTypeData2;
use App\Form\UserTypePassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class UserDataController.
 *
 * @Route("/userData")
 */
class UserDataController extends AbstractController
{
//    /**
//     * EditData action.
//     *
//     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
//     * @param \App\Entity\UserData                      $userData   UserData entity
//     * @param \App\Repository\UserDataRepository        $repository UserData repository
//     *
//     * @return \Symfony\Component\HttpFoundation\Response HTTP response
//     *
//     * @throws \Doctrine\ORM\ORMException
//     * @throws \Doctrine\ORM\OptimisticLockException
//     *
//     * @Route(
//     *     "/{id}/editData/{UserDataId}",
//     *     methods={"GET", "PUT"},
//     *     requirements={"id": "[1-9]\d*"},
//     *     requirements={"UserDataId": "[1-9]\d*"},
//     *     name="user_editUserData",
//     * )
//     */
//    public function editData(Request $request, UserData $userData, UserDataRepository $repository, $userId): Response
//    {
//        $form = $this->createForm(UserTypeData2::class, $userData, ['method' => 'PUT']);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $repository->save($userData);
//
//            $this->addFlash('success', 'message.updated_successfully');
//
//            return $this->redirectToRoute('user_view', ['id' => $userId]);
//        }
//
//        return $this->render(
//            'user/editData2.html.twig',
//            [
//                'form' => $form->createView(),
//                'userData' => $userData,
//            ]
//        );
//    }
}