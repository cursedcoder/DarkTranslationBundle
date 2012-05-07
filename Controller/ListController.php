<?php

namespace Dark\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ListController extends Controller
{
    /**
     * @Route("/")
     * @Route("/list.html", name="list")
     * @Template()
     */
    public function listDirAction()
    {
        $path = $this->getRequest()->get('path');
        $explorer = $this->get('dark_translation.explorer');

        $data = $explorer->locate($path);
        $breadcrumbs = $explorer->breadcrumbs($path);

        return array('dir' => $data, 'breadcrumbs' => $breadcrumbs);
    }

    /**
     * @Route("/")
     * @Route("/mkdir.html", name="mkdir")
     * @Template()
     */
    public function mkdirAction()
    {
        $request = $this->getRequest();
        $path = $request->get('path');

        $explorer = $this->get('dark_translation.explorer');
        $explorer->createDir($path);

        $referer = $request->headers->get('referer');
        $this->get('session')->setFlash('notice', '<h4>Folder: ' . $path . ' was created</h4>');

        return new RedirectResponse($referer);
    }
}
