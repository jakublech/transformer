<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter\Converter\Throwable;

use JakubLech\Converter\Builder;
use JakubLech\Converter\Converter\ConverterClassAbstract;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

final class RuntimeExceptionConverter extends ThrowableConverter
{
    protected const string BUILDER_FOR_CLASSNAME = Throwable::class;

    public function __construct(private Builder $convert)
    {
        parent::__construct($this->convert);
    }

    public function toJsonResponse(Throwable $exception, array $context = []): JsonResponse
    {
        return new JsonResponse(
            $this->convertClassToFormat($exception, 'json', $context),
            $context['_status'] ?? 503,
            $context['_header'] ?? ['Content-Type' => 'application/json']
        );
    }
}
