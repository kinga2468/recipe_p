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