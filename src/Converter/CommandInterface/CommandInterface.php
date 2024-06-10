<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter\Converter\CommandInterface;

use JakubLech\Converter\DestinationFormat\Null\NullFormat;
use JakubLech\Converter\Converter\ClassBuilderAbstract;
use JakubLech\Converter\ResponseBuilderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommandInterface extends ClassBuilderAbstract
{
    protected const string BUILDER_FOR_CLASSNAME = CommandInterface::class;
    public function __construct(ResponseBuilderInterface $responseBuilder)
    {
        parent::__construct($responseBuilder);

        $this->supportFormat('array', fn (object $class, array $context) => (new NullFormat())($class, $context));
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
