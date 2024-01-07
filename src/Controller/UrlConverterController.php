<?php

namespace App\Controller;


use App\Entity\UrlCodeEntity;
use App\Form\UrlConverterFormType;
use App\Service\UrlConverterRepository;
use App\Shortener\Interfaces\UrlConverter\InterfaceUrlDecoder;
use App\Shortener\Interfaces\UrlConverter\InterfaceUrlEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UrlConverterController extends AbstractController
{
    #[Route('/encode/{url}', name: 'encode', requirements: ['url' => '.+'])]
    public function encode(string $url, InterfaceUrlEncoder $converter): Response
    {
        $code = $converter->encode($url);
        $urlCode = ['url' => $url, 'code' => $code];
        $this->addFlash('url_code', $urlCode);

        return $this->redirectToRoute('converter_form');
    }

    #[Route('/decode/{code}', name: 'decode', requirements: ['code' => '.+'])]
    public function decode(string $code, InterfaceUrlDecoder $converter, SessionInterface $session, UrlConverterRepository $urlConverterRepository): Response
    {
        $url = $converter->decode($code);
        $urlConverterRepository->increaseTransitionCount($url, $session->get('user_data')['user_id']);
        return $this->redirect($url);
    }

    #[Route('/converter_form', name: 'converter_form')]
    public function converterForm(Request $request, SessionInterface $session, UrlConverterRepository $urlConverterRepository): Response
    {
        $urlCode = new UrlCodeEntity();

        $form = $this->createForm(UrlConverterFormType::class, $urlCode);
        $form->handleRequest($request);

        $allUrlCodes =$urlConverterRepository->getAllUrlCode($session->get('user_data')['user_id']);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            return $this->redirectToRoute('encode', [
                'url' => $formData->getUrl()
            ]);
        }

        return $this->render('url_converter/url_converter.html.twig', [
            'converterForm' => $form->createView(),
            'nickname' => $session->get('user_data')['nickname'],
            'allUrlCodes' => $allUrlCodes
        ]);
    }
}
