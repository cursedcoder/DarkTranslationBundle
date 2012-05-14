<?php

namespace Dark\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class EditorController extends Controller
{
    /**
     * @Route("/edit/{path}", name="edit", requirements={"path" = ".+"})
     * @Template()
     */
    public function editAction($path)
    {
        $editor = $this->get('dark_translation.editor');

        $info = $editor->info($path);

        return array('editor' => $info);
    }

    /**
     * @Route("/save/{path}", name="save", requirements={"path" = ".+"})
     * @Template()
     */
    public function saveAction($path)
    {
        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $editor = $this->get('dark_translation.editor');

            $data = $request->get('data');
            $editor->save($path, $data);

            $this->get('session')->setFlash('notice', '<h4>File: ' . $path . '</h4> Your changes were saved! ');

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