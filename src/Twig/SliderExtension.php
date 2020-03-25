<?php

namespace App\Twig;

use App\Entity\Sliders\Slide;
use App\Entity\Sliders\SlideGallery;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SliderExtension extends AbstractExtension
{

    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var Environment
     */
    private $environment;

    public function __construct(EntityManagerInterface $manager, Environment $environment)
    {
        $this->manager = $manager;
        $this->environment = $environment;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_slider', [$this, 'renderSlider']),
            new TwigFunction('display_slide', [$this, 'displaySlide'])
        ];
    }

    public function displaySlide(Slide $slide)
    {
        $context = [
            'slide' => $slide,
        ];

        if ($slide->getType() == $slide::SLIDE_TYPE_HTML)
            $template = '_slide_html.html.twig';
        elseif ($slide->getType() == $slide::SLIDE_TYPE_IMAGE)
            $template = '_slide_image.html.twig';
        else
            return;

        return $this->environment->render('slider/types/' . $template, $context);

    }

    public function renderSlider($code)
    {
        $slideGallery = $this->manager->getRepository(SlideGallery::class)->findItemByCode($code);

        return $this->environment->render('slider/slide_gallery.html.twig', [
            'slider' => $slideGallery,
        ]);
    }
}
