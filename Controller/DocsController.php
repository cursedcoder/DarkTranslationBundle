<?php

namespace Dark\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @author Evgeniy Guseletov <d46k16@gmail.com>
 */
class DocsController extends Controller
{
    /**
     * @Template()
     */
    public function showAction($path = 'index.html')
    {
        $data = $this->get('dark_translation.explorer')->show($path);

        return array('data' => $data);
    }
}