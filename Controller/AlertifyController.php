<?php

namespace AppVentus\AlertifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * AdManager controller.
 * @Route("/alertify")
 */
class AlertifyController extends Controller
{
    /**
     * Confirm modal
     * @param Request $request An HTTP request.
     *
     * @return void
     *
     * @Route("/confirm", name="alertify_confirm", options={"expose"=true})
     * @Template("AvAlertifyBundle:Modal:confirm.html.twig")
     */
    public function confirmAction(Request $request)
    {
        $confirmCallback = $request->get('confirm_callback');
        if ($confirmCallback === '') {
            $confirmCallback = null;
        }

        return array(
            'title'                => $request->get('title'),
            'body'                 => $request->get('body'),
            'id'                   => $request->get('id').rand(1,100).'-modal',
            'cancel_button_class'  => $request->get('cancel_button_class', 'btn-cancel'),
            'confirm_button_class' => $request->get('confirm_button_class', 'btn-primary'),
            'modal_class'          => $request->get('modal_class'),
            'type'                 => $request->get('type'),
            'confirmCallback'      => $confirmCallback,
        );
    }

    /**
     * Ajax
     * @param Request $request An HTTP request.
     *
     * @return void
     *
     * @Route("/ajax", name="alertify_ajax", options={"expose"=true})
     * @Template("AvAlertifyBundle:Modal:ajax.html.twig")
     */
    public function ajaxAction(Request $request)
    {
        $options = array();
        if ($context = $request->request->get('context')) {

            $options = $this->container->getParameter('av_alertify.contexts.' . $context);
        }

        foreach ($request->request->all() as $name => $option) {
            $options[$name] = $option;
        }
        
        if ($request->get('main_type')) {
            $this->get('session')->getFlashBag()->add($request->get('main_type'), $options);
        }

        return array();
    }
}
