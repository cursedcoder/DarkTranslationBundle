<?php

namespace Dark\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ExplorerController extends Controller
{
    /**
     * @Route("/", name="explorer")
     * @Route("/{path}/list.html", name="list", requirements={"path" = ".+"})
     * @Template()
     */
    public function listDirAction($path = null)
    {
        $explorer = $this->get('dark_translation.explorer');

        $data = $explorer->locate($path);
        $breadcrumbs = $explorer->breadcrumbs($path);

        return array('dir' => $data, 'breadcrumbs' => $breadcrumbs);
    }

    /**
     * @Route("/{path}/mkdir.html", name="mkdir", requirements={"path" = ".+"})
     */
    public function mkdirAction($path)
    {
        $request = $this->getRequest();

        $explorer = $this->get('dark_translation.explorer');
        $explorer->createDir($path);

        $referer = $request->headers->get('referer');
        $this->get('session')->setFlash('notice', '<h4>Folder: ' . $path . ' was created</h4>');

        return new RedirectResponse($referer);
    }
}
