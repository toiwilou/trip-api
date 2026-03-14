<?php

namespace App\Helper;

use Symfony\Component\Serializer\SerializerInterface;

class AppHelper
{
    private $serializer;
    
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($object, string $key): string
    {
        return $this->serializer->serialize($object, 'json', [
            'groups' => [$key . ':read']
        ]);
    }
}
