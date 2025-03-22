<?php

namespace FSVendor\WPDesk\Logger\Processor;

use FSVendor\Monolog\Processor\ProcessorInterface;
/**
 * Can replace data in log.
 * Ie. sensitive data.
 *
 * @package WPDesk\Logger\Processor
 */
class SensitiveDataProcessor implements ProcessorInterface
{
    /**
     * Replace array.
     *
     * @var array
     */
    private array $replace;
    public function __construct(array $replace)
    {
        $this->replace = $replace;
    }
    public function __invoke(array $record): array
    {
        return $this->replace_array($record);
    }
    private function replace_array(array $value): array
    {
        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $value[$key] = $this->replace_array($item);
            }
            if (is_string($item)) {
                $value[$key] = $this->replace($item);
            }
        }
        return $value;
    }
    private function replace(string $value): string
    {
        foreach ($this->replace as $search => $replace) {
            $value = str_replace($search, $replace, $value);
        }
        return $value;
    }
}
