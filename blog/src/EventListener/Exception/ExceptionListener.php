<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\EventListener\Exception;

use App\Exception\BlogException;
use App\Normalizer\ExceptionNormalizerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\{
    EventDispatcher\EventSubscriberInterface,
    HttpFoundation\Response,
    HttpKernel\Event\ExceptionEvent,
    HttpKernel\KernelEvents,
    Serializer\Normalizer\AbstractObjectNormalizer,
    Serializer\SerializerInterface
};

/**
 * Class ExceptionListener
 */
class ExceptionListener implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var array
     */
    private array $exceptionNormalizers;

    /**
     * ExceptionListener constructor.
     *
     * @param LoggerInterface     $logger
     * @param SerializerInterface $serializer
     * @param iterable            $exceptionNormalizers
     */
    public function __construct(LoggerInterface $logger, SerializerInterface $serializer, iterable $exceptionNormalizers)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->exceptionNormalizers = $this->processExceptionNormalizers($exceptionNormalizers);
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['processException', 500],
                ['logException', 1000],
            ],
        ];
    }

    /**
     * @param ExceptionEvent $event
     */
    public function logException(ExceptionEvent $event): void
    {
        if (!$this->supports($event)) {
            return;
        }

        $throwable = $event->getThrowable();
        if ($throwable instanceof BlogException && !$throwable->isLogged()) {
            return;
        }

        $context = [
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => $throwable->getTraceAsString(),
        ];

        if ($throwable instanceof BlogException) {
            $context = array_merge([
                'trace_code' => $throwable->getCode(),
                'trace_data' => $throwable->getTraceAsString(),
            ], $context);
        }

        $this->logger->error($throwable->getMessage(), $context);
    }

    /**
     * @param ExceptionEvent $event
     */
    public function processException(ExceptionEvent $event): void
    {
        if (!$this->supports($event)) {
            return;
        }

        $throwable = $event->getThrowable();
        if (null === $exceptionNormalizer = $this->getNormalizer($throwable)) {
            return;
        }

        $code = $throwable->getCode();
        if (is_callable([$throwable, 'getStatusCode'])) {
            $code = $throwable->getStatusCode();
        }

        $statusCode = array_key_exists($code, Response::$statusTexts)
            ? $code
            : Response::HTTP_BAD_REQUEST
        ;
        $error = $exceptionNormalizer->normalize($throwable);
        $event->setResponse(new Response(
            $this->serializer->serialize($error, 'json', [AbstractObjectNormalizer::SKIP_NULL_VALUES => true]),
            $statusCode
        ));
    }

    /**
     * @param \Throwable $throwable
     *
     * @return ExceptionNormalizerInterface|null
     */
    private function getNormalizer(\Throwable $throwable): ?ExceptionNormalizerInterface
    {
        /** @var ExceptionNormalizerInterface $exceptionNormalizer */
        foreach ($this->exceptionNormalizers as $exceptionNormalizer) {
            if ($exceptionNormalizer->Support($throwable)) {
                return $exceptionNormalizer;
            }
        }

        return null;
    }

    /**
     * @param iterable $exceptionNormalizers
     *
     * @return array
     */
    private function processExceptionNormalizers(iterable $exceptionNormalizers): array
    {
        $exceptionNormalizersArray = [];
        foreach ($exceptionNormalizers as $exceptionNormalizer) {
            if (!$exceptionNormalizer instanceof ExceptionNormalizerInterface) {
                continue;
            }

            $exceptionNormalizersArray[] = $exceptionNormalizer;
        }

        usort($exceptionNormalizersArray, function (ExceptionNormalizerInterface $first, ExceptionNormalizerInterface $last) {
            if ($first->getPriority() === $last->getPriority()) {
                return 0;
            }

            return $first->getPriority() > $last->getPriority() ? -1 : 1;
        });

        return $exceptionNormalizersArray;
    }

    /**
     * @param ExceptionEvent $event
     *
     * @return bool
     */
    private function supports(ExceptionEvent $event): bool
    {
        return 'json' === $event->getRequest()->getRequestFormat();
    }
}
