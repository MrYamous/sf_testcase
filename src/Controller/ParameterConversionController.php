<?php

namespace App\Controller;

use App\Entity\Post;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class ParameterConversionController
 * @Route("/param_conversion", name="param_conversion_")
 */
class ParameterConversionController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/without/{id}", name="without")
     * @param int $id
     * @return Response
     */
    public function showWithoutConversion(int $id): Response
    {
        $post = $this->em->getRepository('App:Post')->find($id);
        if (!$post) return $this->json(['The Post id is incorrect'], 404);

        $result = [
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'date' => $post->getDate(),
            'active' => $post->getActive(),
        ];

        return $this->json($result);
    }

    /**
     * @Route("/with/{id}", name="with")
     * @param Post $post
     * @return Response
     */
    public function showWithConversion(Post $post): Response
    {
        return $this->json([
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'date' => $post->getDate(),
            'active' => $post->getActive()
        ]);

        // If Post doesn't exist, the converter uses the default 404 page
    }

    /**
     * @Route("/datetime/{dateTime}", name="dateTime")
     * @param DateTime $dateTime
     * @return Response
     */
    public function showDateTimeConversion(DateTime $dateTime): Response
    {
        // All Date and Time format are accepted as a parameter and converted to DateTime object
        // Converter seems to be permissive with Date and Time formats
        // Examples : 01-01-2020121214+2, 2021-01-0113:14:15, 15:15:1501-01-2021
        return $this->json($dateTime);
    }
}
