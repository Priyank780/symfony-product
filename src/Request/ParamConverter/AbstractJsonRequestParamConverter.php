<?php

namespace App\Request\ParamConverter;

use App\Exception\ValidatorExceptionInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

abstract class AbstractJsonRequestParamConverter implements ParamConverterInterface
{
    public function __construct(protected readonly SerializerInterface $serializer, protected readonly LoggerInterface $logger)
    {
    }

    /**
     * Stores the object in the request.
     *
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @throws ValidatorException
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $name = $configuration->getName();

        try {
            $object = $this->serializer->deserialize($request->getContent(), $configuration->getClass(), 'json');
        } catch (Exception $e) {
            $this->logger->info($e->getMessage(), [$e, $request->getContent(), $configuration->getClass()]);
            throw new ValidatorException(ValidatorExceptionInterface::INVALID_JSON_CODE);
        }

        $request->attributes->set($name, $object);

        return true;
    }

    /**
     * Checks if the object is supported.
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $this->getProperty() === $configuration->getName();
    }

    abstract public function getProperty(): string;
}
