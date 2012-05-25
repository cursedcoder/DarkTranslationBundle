<?php

namespace Dark\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @author Evgeniy Guseletov <d46k16@gmail.com>
 */
class BrowserController extends Controller
{
    /**
     * @Template()
     */
    public function listDirAction($path = null)
    {
        $browser = $this->get('dark_translation.browser');

        $files = $browser->locate($path);

        $breadcrumbs = $browser->breadcrumbs($path);

        return array('files' => $files, 'breadcrumbs' => $breadcrumbs);
    }

    /**
     * @Template()
     */
    public function editAction($path)
    {
        $browser = $this->get('dark_translation.browser');

        $info = $browser->info($path);

        return array('info' => $info);
    }

    /**
     * @Template()
     */
    public function saveAction($path)
    {
        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $browser = $this->get('dark_translation.browser');

            $data = $request->get('data');

            $browser->save($path, $data);

            $this->get('session')->setFlash('notice', '<h4>File: ' . $path . '</h4> Your changes were saved!');

            $dir = explode('/', $path);
            array_pop($dir);
            $dir = implode('/', $dir);

            $route = $dir
                ? $this->generateUrl('list', array('path' => $dir))
                : $this->generateUrl('browser')
            ;

            return $this->redirect($route);
        } else {
            throw $this->createNotFoundException('Page is not exist');
        }
    }

    public function mkdirAction($path)
    {
        $request = $this->getRequest();

        $browser = $this->get('dark_translation.browser');

        $browser->createDir($path);

        $this->get('session')->setFlash('notice', '<h4>Folder: ' . $path . ' was created</h4>');

        return $this->redirect($this->generateUrl('list', array('path' => $path)));
    }
}
