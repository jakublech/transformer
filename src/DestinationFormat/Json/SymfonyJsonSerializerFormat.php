<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter\DestinationFormat\Json;

use Symfony\Component\Serializer\SerializerInterface;

class SymfonyJsonSerializerFormat implements JsonFormatInterface
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function __invoke(object $class, array $context = []): string
    {
        return $this->serializer->normalize($class, null, $context);
    }
}
