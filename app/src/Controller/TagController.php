<?php
/**
 * Tag controller.
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TagController.
 *
 * @Route("/tag")
 */
class TagController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\TagRepository        $repository Tag repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "/",
     *     name="tag_index",
     * )
     */
    public function index(Request $request, TagRepository $repository, PaginatorInterface $paginator): Response
    {
        if ($this->isGranted('ROLE_ADMIN')==false){
            $this->addFlash('warning', 'message.not_have_access');

            return $this->redirectToRoute('main_index');
        }

        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Tag::NUMBER_OF_ITEMS
        );

        return $this->render(
            'tag/index.html.twig',
            ['tags' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\Tag $tag Tag entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="tag_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Request $request, TagRepository $tagRepository, Tag $tag, $id,  PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $tagRepository->findRecipeWithThisTag($id),
            $request->query->getInt('page', 1),
            Tag::NUMBER_OF_ITEMS
        );

        return $this->render(
            'tag/view.html.twig',
            [
                'pagination' => $pagination,
                'tag' => $tag,
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Tag                      $tag   Tag entity
     * @param \App\Repository\TagRepository        $repository Tag repository
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
     *     name="tag_edit",
     * )
     */
    public function edit(Request $request, Tag $tag, TagRepository $repository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')==false){
            $this->addFlash('warning', 'message.not_have_access');

            return $this->redirectToRoute('main_index');
        }

        $form = $this->createForm(TagType::class, $tag, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($tag);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render(
            'tag/edit.html.twig',
            [
                'form' => $form->createView(),
                'tag' => $tag,
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Tag                      $tag   Tag entity
     * @param \App\Repository\TagRepository        $repository Tag repository
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
     *     name="tag_delete",
     * )
     */
    public function delete(Request $request, Tag $tag, TagRepository $repository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')==false){
            $this->addFlash('warning', 'message.not_have_access');

            return $this->redirectToRoute('main_index');
        }

        $form = $this->createForm(FormType::class, $tag, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE')) {
            //$form->submit($request->request->get($form->getName()));
            $repository->delete($tag);

            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render(
            'tag/delete.html.twig',
            [
                'form' => $form->createView(),
                'tag' => $tag,
            ]
        );
    }
}