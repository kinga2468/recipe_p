<?php
/**
 * Measure controller.
 */

namespace App\Controller;

use App\Entity\Measure;
use App\Form\MeasureType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use App\Repository\MeasureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MeasureController.
 *
 * @Route("/measure")
 */
class MeasureController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\MeasureRepository        $repository Measure repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "/",
     *     name="measure_index",
     * )
     */
    public function index(Request $request, MeasureRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Measure::NUMBER_OF_ITEMS
        );

        return $this->render(
            'measure/index.html.twig',
            ['measures' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\Measure $measure Measure entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="measure_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Measure $measure): Response
    {
        return $this->render(
            'measure/view.html.twig',
            ['measure' => $measure]
        );
    }
    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\MeasureRepository        $repository Measure repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="measure_new",
     * )
     */
    public function new(Request $request, MeasureRepository $repository): Response
    {
        $measure = new Measure();
        $form = $this->createForm(MeasureType::class, $measure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($measure);

            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('measure_index');
        }

        return $this->render(
            'measure/new.html.twig',
            ['form' => $form->createView()]
        );
    }
    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Measure                      $measure   Measure entity
     * @param \App\Repository\MeasureRepository        $repository Measure repository
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
     *     name="measure_edit",
     * )
     */
    public function edit(Request $request, Measure $measure, MeasureRepository $repository): Response
    {
        $form = $this->createForm(MeasureType::class, $measure, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($measure);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('measure_index');
        }

        return $this->render(
            'measure/edit.html.twig',
            [
                'form' => $form->createView(),
                'measure' => $measure,
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Measure                      $measure   Measure entity
     * @param \App\Repository\MeasureRepository        $repository Measure repository
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
     *     name="measure_delete",
     * )
     */
    public function delete(Request $request, Measure $measure, MeasureRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $measure, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE')) {
            $repository->delete($measure);

            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('measure_index');
        }

        return $this->render(
            'measure/delete.html.twig',
            [
                'form' => $form->createView(),
                'measure' => $measure,
            ]
        );
    }
}