<?php

namespace FSVendor\WPDesk\Logger;

use FSVendor\Psr\Log\LogLevel;
/**
 * @deprecated
 */
final class Settings
{
    /** @var string */
    public $level = LogLevel::DEBUG;
    /** @var bool */
    public $use_wc_log = \true;
    /** @var bool */
    public $use_wp_log = \true;
    /**
     * @param string $level
     * @param bool   $use_wc_log
     * @param bool   $use_wp_log
     */
    public function __construct(string $level = LogLevel::DEBUG, bool $use_wc_log = \true, bool $use_wp_log = \true)
    {
        $this->level = $level;
        $this->use_wc_log = $use_wc_log;
        $this->use_wp_log = $use_wp_log;
    }
    public function to_array(): array
    {
        return ['level' => $this->level, 'use_wc_log' => $this->use_wc_log, 'use_wp_log' => $this->use_wp_log];
    }
}
