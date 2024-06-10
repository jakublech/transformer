<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter\Converter\QueryResponseInterface;

use JakubLech\Converter\DestinationFormat\Array\SymfonyArraySerializerFormat;
use JakubLech\Converter\Converter\ClassBuilderAbstract;
use JakubLech\Converter\ResponseBuilderInterface;
use Shared\Application\Port\QueryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class QueryResponseInterface extends ClassBuilderAbstract
{
    protected const string BUILDER_FOR_CLASSNAME = QueryInterface::class;

    public function __construct(ResponseBuilderInterface $responseBuilder, SymfonyArraySerializerFormat $symfonyArraySerializerFormat)
    {
        parent::__construct($responseBuilder);

        $this->supportFormat('array', fn (QueryInterface $query, array $context = []) => $symfonyArraySerializerFormat($query, $context));
        $this->supportFormat('json', fn (object $class, array $context = []) => json_encode($this->build($class, 'array', $context)));
        $this->supportFormat('jsonResponse', fn (object $class, array $context = []) =>
        new JsonResponse(
            $this->build($class, 'json', $context),
            $context['_status'] ?? 200,
            $context['_header'] ?? ['Content-Type' => 'application/json']
        )
        );
    }
}
