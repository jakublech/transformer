<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Transformer\Transformers;

use JakubLech\Transformer\Exception\TransformException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ArrayToSymfonyJsonResponse implements TransformerInterface
{
    /**
     * @param array $input
     *
     * @throws TransformException
     */
    public function __invoke(mixed $input, array $context = []): JsonResponse
    {
        $status = $context['_status'] ?? 200;
        $headers = $context['_headers'] ?? ['Content-Type' => 'application/json'];

        return new JsonResponse(
            (new ArrayToJsonTransformer())($input, $context),
            $status,
            $headers,
            true,
        );
    }

    public static function inputType(): string
    {
        return 'array';
    }

    public static function returnType(): string
    {
        return JsonResponse::class;
    }

    public static function priority(): int
    {
        return -1000;
    }
}
