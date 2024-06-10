<?php
/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace JakubLech\Converter\Converter;

use JakubLech\Converter\BuilderInterface;
use RuntimeException;

class ConverterClassAbstract
{
    protected const string CONVERTER_FOR_CLASSNAME = '';
    private array $formatHandlers = [];
    public function __construct(private BuilderInterface $convert)
    {
        if ('' === static::converterForClassname()) {
            throw new RuntimeException('CONVERTER_FOR_CLASSNAME must be set to classname which builder is designed for');
        }
        $this->convert->supportClassnameWithConverter(static::converterForClassname(), $this);
    }

    public static function converterForClassname(): string
    {
        return static::CONVERTER_FOR_CLASSNAME;
    }

    public function supportFormat(string $format, callable $handler): void
    {
        $this->formatHandlers[$format] = $handler;
    }

    public function isFormatSupported(string $format): bool
    {
        return isset($this->formatHandlers[$format]);
    }

    public function convertClassToFormat(object $class, string $format, array $context = []): mixed
    {
        if (false === $this->isFormatSupported($format)) {
            throw new RuntimeException('Format ' . $format . ' is not supported for className '.static::CONVERTER_FOR_CLASSNAME);
        }

        return $this->formatHandlers[$format]($class, $context);
    }

}
