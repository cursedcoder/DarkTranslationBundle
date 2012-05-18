<?php

namespace Dark\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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

        $files = $explorer->locate($path);
        $breadcrumbs = $explorer->breadcrumbs($path);

        return array('files' => $files, 'breadcrumbs' => $breadcrumbs);
    }

    /**
     * @Route("/{path}/mkdir.html", name="mkdir", requirements={"path" = ".+"})
     */
    public function mkdirAction($path)
    {
        $request = $this->getRequest();

        $explorer = $this->get('dark_translation.explorer');
        $explorer->createDir($path);

        $this->get('session')->setFlash('notice', '<h4>Folder: ' . $path . ' was created</h4>');

        return $this->redirect($this->generateUrl('list', array('path' => $path)));
    }

    /**
     * @Route("/edit/{path}", name="edit", requirements={"path" = ".+"})
     * @Template()
     */
    public function editAction($path)
    {
        $explorer = $this->get('dark_translation.explorer');

        $info = $explorer->info($path);

        return array('info' => $info);
    }

    /**
     * @Route("/save/{path}", name="save", requirements={"path" = ".+"})
     * @Template()
     */
    public function saveAction($path)
    {
        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $explorer = $this->get('dark_translation.explorer');

            $data = $request->get('data');
            $explorer->save($path, $data);

            $this->get('session')->setFlash('notice', '<h4>File: ' . $path . '</h4> Your changes were saved!');

            $dir = explode('/', $path);
            array_pop($dir);
            $dir = implode('/', $dir);

            $route = $dir
                ? $this->generateUrl('list', array('path' => $dir))
                : $this->generateUrl('explorer')
            ;

            return $this->redirect($route);
        } else {
            throw $this->createNotFoundException('Page is not exist');
        }
    }
}
