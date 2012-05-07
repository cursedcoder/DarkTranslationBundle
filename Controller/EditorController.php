<?php

namespace Dark\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class EditorController extends Controller
{
    /**
     * @Route("/edit.html", name="edit")
     * @Template()
     */
    public function editAction()
    {
        $editor = $this->get('dark_translation.editor');

        $path = $this->getRequest()->get('path');

        $info = $editor->info($path);

        return array('editor' => $info);
    }

    /**
     * @Route("/save.html", name="save")
     * @Template()
     */
    public function saveAction()
    {
        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $editor = $this->get('dark_translation.editor');

            $path = $this->getRequest()->get('path');
            $data = $request->get('data');
            $editor->save($path, $data);

            $this->get('session')->setFlash('notice', '<h4>File: ' . $path . '</h4> Your changes were saved! ');

            return $this->redirect($this->generateUrl('list'));
        } else {
            throw $this->createNotFoundException('Page is not exist');
        }
    }
}