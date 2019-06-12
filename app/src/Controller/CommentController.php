<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommentType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Class CommentController.
 *
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
//    /**
//     * Index action.
//     *
//     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
//     * @param \App\Repository\CommentRepository            $repository Repository
//     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
//     *
//     * @return \Symfony\Component\HttpFoundation\Response HTTP response
//     *
//     * @Route(
//     *     "/",
//     *     name="comment_index",
//     * )
//     */
//    public function index(Request $request, CommentRepository $repository, PaginatorInterface $paginator): Response
//    {
//        $pagination = $paginator->paginate(
//            $repository->queryAll(),
//            $request->query->getInt('page', 1),
//            Comment::NUMBER_OF_ITEMS
//        );
//
//        return $this->render(
//            'comment/index.html.twig',
//            ['pagination' => $pagination]
//        );
//    }
//
//    /**
//     * View action.
//     *
//     * @param \App\Entity\Comment $comment Comment entity
//     *
//     * @return \Symfony\Component\HttpFoundation\Response HTTP response
//     *
//     * @Route(
//     *     "/{id}",
//     *     name="comment_view",
//     *     requirements={"id": "[1-9]\d*"},
//     * )
//     */
//    public function view(Comment $comment): Response
//    {
//        return $this->render(
//            'comment/view.html.twig',
//            ['comment' => $comment]
//        );
//    }
//
//    /**
//     * New action.
//     *
//     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
//     * @param \App\Repository\CommentRepository        $repository Comment repository
//     *
//     * @return \Symfony\Component\HttpFoundation\Response HTTP response
//     *
//     * @throws \Doctrine\ORM\ORMException
//     * @throws \Doctrine\ORM\OptimisticLockException
//     *
//     * @Route(
//     *     "/new",
//     *     methods={"GET", "POST"},
//     *     name="comment_new",
//     * )
//     */
//    public function new(Request $request, CommentRepository $repository): Response
//    {
//        $comment = new Comment();
//        $form = $this->createForm(CommentType::class, $comment);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $repository->save($comment);
//
//            $this->addFlash('success', 'message.created_successfully');
//
//            return $this->redirectToRoute('comment_index');
//        }
//
//        return $this->render(
//            'comment/new.html.twig',
//            ['form' => $form->createView()]
//        );
//    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Comment                      $comment   Comment entity
     * @param \App\Repository\CommentRepository        $repository Comment repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{recipeId}/edit/{id}",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     requirements={"recipeId": "[1-9]\d*"},
     *     name="comment_edit",
     * )
     */
    public function edit(Request $request, Comment $comment, CommentRepository $repository, $recipeId): Response
    {
        if ($comment->getAuthor() !== $this->getUser() and $this->isGranted('ROLE_ADMIN') == false) {
            $this->addFlash('warning', 'message.item_not_found');

            return $this->redirectToRoute('recipe_view', ['id' => $recipeId]);
        }

        $form = $this->createForm(CommentType::class, $comment, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($comment);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('recipe_view', ['id' => $recipeId]);
        }

        return $this->render(
            'comment/edit.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Comment                      $comment   Comment entity
     * @param \App\Repository\CommentRepository        $repository Comment repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{recipeId}/delete/{id}",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     requirements={"recipeId": "[1-9]\d*"},
     *     name="comment_delete",
     * )
     */
    public function delete(Request $request, Comment $comment, CommentRepository $repository, $recipeId): Response
    {
        if ($comment->getAuthor() !== $this->getUser() and $this->isGranted('ROLE_ADMIN') == false) {
            $this->addFlash('warning', 'message.item_not_found');

            return $this->redirectToRoute('recipe_view', ['id' => $recipeId]);
        }

        $form = $this->createForm(FormType::class, $comment, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($comment);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('recipe_view', ['id' => $recipeId]);
        }

        return $this->render(
            'comment/delete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }
}