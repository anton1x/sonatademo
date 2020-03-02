<?php


namespace App\Application\Sonata\MediaBundle\Serializer;

use App\Application\Sonata\MediaBundle\Entity\Media;

use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Context;
use Sonata\MediaBundle\Provider\ImageProvider;
use Sonata\MediaBundle\Provider\FileProvider;

class LinkMediaSerializationHandler
{

    private $imageProvider;
    private $context;
    private $format;

    public function __construct(ImageProvider $imageProvider, string $context, string $format)
    {
        $this->imageProvider = $imageProvider;
        $this->context = $context;
        $this->format = $format;
    }

    public function serializeMedia(JsonSerializationVisitor $visitor, Media $media, array $type, Context $context)
    {
        switch ($media->getProviderName()) {
            case 'sonata.media.provider.image':
                $serialization = $this->serializeImage($media);
                break;

            default:
                throw new \RuntimeException("Serialization media provider not recognized");
        }

        return $serialization;
    }

    private function serializeImage(Media $media)
    {
         $url = $this->imageProvider->generatePublicUrl(
            $media,
            sprintf('%s_%s', $this->context, $this->format)
        );

        return $url;
    }


}