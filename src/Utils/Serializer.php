<?php

namespace App\Utils;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SerializerComponent;

/**
 * Class Serializer
 * @package App\Users
 */
class Serializer
{
    /**
     * @var SerializerComponent
     *
     * This Class helps to serialize and deserialize objects in multiple format and options.
     */
    private $serializer;

    /**
     * UserManager constructor.
     */
    public function __construct()
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        // $normalizers = [new ObjectNormalizer()];
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $objectNormalizer = new ObjectNormalizer($classMetadataFactory, null, null, new ReflectionExtractor());
        $normalizers = [
            new DateTimeNormalizer(),
            $objectNormalizer,
            new ArrayDenormalizer()
        ];
        $this->serializer = $serializer = new SerializerComponent($normalizers, $encoders);
    }

    /**
     * @param mixed $data An entity
     * @param string $format json, xml, etc
     * @param array $options
     * @return bool|float|int|string Return the serialized object.
     */
    public function serialize($data, string $format, array $options)
    {
        return $this->serializer->serialize($data, $format, $options);
    }

    /**
     * @param string $data Stringify data
     * @param string $type The class of the expected entity.
     * @param string $format the type of the given data : json, xml, etc
     * @param array $extra
     * @return object An entity populated with the given data.
     */
    public function deserialize(string $data, string $type, string $format, array $extra = []): object
    {
        return $this->serializer->deserialize($data, $type, $format, $extra);
    }
}
