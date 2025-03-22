<?php

/**
 * Array logger.
 *
 * @package WPDesk\FS\TableRate\Logger
 */
namespace FSVendor\WPDesk\FS\TableRate\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
/**
 * Can log to array.
 */
if (defined('FLEXIBLE_SHIPPING_PSR_NOT_PREFIXED') && FLEXIBLE_SHIPPING_PSR_NOT_PREFIXED) {
    class ArrayLogger implements LoggerInterface
    {
        use LoggerTrait;
        /**
         * @var array
         */
        private $messages = array();
        /**
         * @param mixed $level .
         * @param string $message .
         * @param array $context .
         */
        public function log($level, $message, array $context = array()): void
        {
            $this->messages[] = array('level' => $level, 'message' => $message, 'context' => $context);
        }
        /**
         * @return array
         */
        public function get_messages()
        {
            return $this->messages;
        }
    }
} else {
    class ArrayLogger implements \FSVendor\Psr\Log\LoggerInterface
    {
        use \FSVendor\Psr\Log\LoggerTrait;
        /**
         * @var array
         */
        private $messages = array();
        /**
         * @param mixed $level .
         * @param string $message .
         * @param array $context .
         */
        public function log($level, $message, array $context = array()): void
        {
            $this->messages[] = array('level' => $level, 'message' => $message, 'context' => $context);
        }
        /**
         * @return array
         */
        public function get_messages()
        {
            return $this->messages;
        }
    }
}
