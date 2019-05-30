<?php
/**
 * SignUp controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class SignUpController.
 *
 * @Route("/register")
 */
class SignUpController extends AbstractController
{
    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\UserRepository        $repository User repository
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/",
     *     methods={"GET", "POST"},
     *     name="user_new",
     * )
     */
    public function new(Request $request, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));

            $repository->save($user);
            $this->addFlash('success', 'message.created_successfully');
            return $this->redirectToRoute('main_index');
        }
        return $this->render(
            'user/new.html.twig',
            ['form' => $form->createView()]
        );
    }
}