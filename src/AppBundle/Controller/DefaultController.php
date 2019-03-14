<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Feedback;
use AppBundle\Form\FeedbackType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {

        $a = 'Mike';

        $entityManager = $this->get('doctrine')->getManager();
        //$entityManager = $this->getDoctrine()->getManager();

        $feedback = (new Feedback())
            ->setName('Mike')
            ->setEmail('dsdf@hgfh.ry')
            ->setMessage('Hello');

        $entityManager->persist($feedback);
        //$entityManager->flush();

        return ['name' => $a];
    }


    /**
     * @Route("/contact-us", name="contact")
     * @Template()
     */
    public function contactAction(Request $request)
    {
        //$feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class);
        // $form = $this->createForm(FeedbackType::class, $feedback);
        $form->add('submit', SubmitType::class);

        $form->handleRequest($request); // отлавливает данные из формы

        // Проверяем на валидность
        if ($form->isSubmitted() && $form->isValid()) {
            //$feedback->setIpAdress($request->getClientIp());

            $feedback = $form->getData(); // отдает объект из формы

            // сохраняем данные из формы в БД
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($feedback); // ставим под контроль
            $entityManager->flush(); // сохраняем

            // добавлям Flash сообщение
            $this->addFlash('success', 'Saved');
            // редирект
            return $this->redirectToRoute('contact');
        }

        return ['feedback_form' => $form->createView()];
    }


}
