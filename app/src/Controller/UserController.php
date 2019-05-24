<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\UserRepository            $repository Repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="user_index",
     * )
     */
    public function index(Request $request, UserRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            User::NUMBER_OF_ITEMS
        );

        return $this->render(
            'user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\User $user User entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="user_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(User $user, Request $request, UserRepository $repository, PaginatorInterface $paginator): Response
    {
//        $pagination = $paginator->paginate(
//            $repository->findByUser($user->getId()),
//            $request->query->getInt('page', 1),
//            Photo::NUMBER_OF_ITEMS
//        );
//        dump($photo_pagination);
        return $this->render(
            'user/view.html.twig',
            [
                'user' => $user,
//                'pagination' => $pagination
            ]
        );
    }

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
     *     "/new",
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
            $user->setPassword($passwordEncoder->encodePassword(
                $user, $user->getPassword()
            ));
            dump($user);
            $repository->save($user);
            #dump($user);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('user_index');
        }
        return $this->render(
            'user/new.html.twig',
            ['form' => $form->createView()]
        );
    }
}