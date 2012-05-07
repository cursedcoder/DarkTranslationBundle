<?php

namespace Dark\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DocsController extends Controller
{
    /**
     * @Route("/docs/", name="docs")
     * @Route("/docs/{path}", name="show", requirements={"path" = ".+"})
     * @Template()
     */
    public function showAction($path = 'index.html')
    {
        $data = $this->get('dark_translation.explorer')->show($path);

        return array('data' => $data);
    }
}