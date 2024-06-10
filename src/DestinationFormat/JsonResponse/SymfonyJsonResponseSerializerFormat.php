<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter\DestinationFormat\JsonResponse;

use JakubLech\Converter\DestinationFormat\Json\JsonFormatInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class SymfonyJsonResponseSerializerFormat implements JsonResponseFormatInterface
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function __invoke(object $class, array $context = []): JsonResponse
    {
        $status = $context['_status'] ?? Response::HTTP_OK;
        $headers = $context['_headers'] ?? ['Content-Type' => 'application/json'];
        unset($context['_status'], $context['_headers']);

        return new JsonResponse(
            $this->serializer->serialize($class, 'json', $context),
            $status,
            $headers,
            true
        );
    }
}
