<?php
//
//namespace App\Resolver;
//
//use InvalidArgumentException;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
//use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
//use Symfony\Component\Serializer\SerializerInterface;
//use Symfony\Component\Validator\Validator\ValidatorInterface;
//
//class DtoValueResolver implements ValueResolverInterface
//{
//    private SerializerInterface $serializer;
//    private ValidatorInterface $validator;
//
//    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
//    {
//        $this->serializer = $serializer;
//        $this->validator = $validator;
//    }
//
//    public function resolve(Request $request, ArgumentMetadata $argument): iterable
//    {
//        $json = $request->getContent();
//        $dto = $this->serializer->deserialize($json, $argument->getType(), 'json');
//        $errors = $this->validator->validate($dto);
//        if (count($errors) > 0) {
//            throw new InvalidArgumentException('Validation failed: ' . (string)$errors);
//        }
//
//        yield $dto;
//    }
//}
