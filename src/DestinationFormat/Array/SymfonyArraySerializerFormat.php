<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter\DestinationFormat\Array;

use Symfony\Component\Serializer\SerializerInterface;

class SymfonyArraySerializerFormat implements ArrayFormatInterface
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function __invoke(object $class, array $context = []): ?array
    {
        $result = $this->serializer->normalize($class, null, $context);
        return $result === null ? null : (array) $this->serializer->normalize($class, null, $context);
    }
}
