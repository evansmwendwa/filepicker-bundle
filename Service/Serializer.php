<?php
namespace Evans\FilepickerBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerBuilder;

class Serializer
{
    protected $serializer;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    public function encode($data, $format = 'json'){
        return $this->serializer->serialize($data, $format);
    }

    public function decode($jsonData, $namespace, $format = 'json'){
        return $serializer->deserialize($jsonData, $namespace, $format);
    }

    public function JsonResponse($data, $code = 200){
        return new response(
            $this->encode($data),
            $code,
            array(
                'Content-Type' => 'application/json'
            )
        );
    }
}
