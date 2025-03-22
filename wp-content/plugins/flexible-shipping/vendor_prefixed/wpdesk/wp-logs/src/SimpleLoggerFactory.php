<?php

declare (strict_types=1);
namespace FSVendor\WPDesk\Logger;

use FSVendor\Monolog\Handler\FingersCrossedHandler;
use FSVendor\Monolog\Handler\HandlerInterface;
use FSVendor\Monolog\Logger;
use FSVendor\Monolog\Handler\ErrorLogHandler;
use FSVendor\Monolog\Processor\PsrLogMessageProcessor;
use FSVendor\Monolog\Processor\UidProcessor;
use FSVendor\Psr\Log\LogLevel;
use FSVendor\WPDesk\Logger\WC\WooCommerceHandler;
final class SimpleLoggerFactory implements LoggerFactory
{
    /**
     * @var array{
     *   level?: string,
     *   action_level?: string|null,
     * }
     */
    private $options;
    /** @var string */
    private $channel;
    /** @var Logger */
    private $logger;
    /**
     * Valid options are:
     *   * level (default debug): Default logging level
     *   * action_level: If value is set, it will act as the minimum level at which logger will be triggered using FingersCrossedHandler {@see https://seldaek.github.io/monolog/doc/02-handlers-formatters-processors.html#wrappers--special-handlers}
     */
    public function __construct(string $channel, $options = null)
    {
        $this->channel = $channel;
        $options = $options ?? new Settings();
        if ($options instanceof Settings) {
            $options = $options->to_array();
        }
        $this->options = array_merge(['level' => LogLevel::DEBUG, 'action_level' => null], $options);
    }
    public function getLogger($name = null): Logger
    {
        if ($this->logger) {
            return $this->logger;
        }
        $this->logger = new Logger($this->channel, [], [new PsrLogMessageProcessor(null, \true), new UidProcessor()], wp_timezone());
        if (\function_exists('wc_get_logger') && \did_action('woocommerce_init')) {
            $this->set_wc_handler();
        } else {
            \add_action('woocommerce_init', [$this, 'set_wc_handler']);
        }
        // In the worst-case scenario, when WC logs are not available (yet, or at all),
        // fallback to WP logs, but only when enabled.
        if (empty($this->logger->getHandlers()) && defined('WP_DEBUG_LOG') && \WP_DEBUG_LOG) {
            $this->set_handler($this->logger, new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, $this->options['level']));
        }
        return $this->logger;
    }
    /**
     * @internal
     */
    public function set_wc_handler(): void
    {
        $this->set_handler($this->logger, new WooCommerceHandler(\wc_get_logger(), $this->channel));
    }
    private function set_handler(Logger $logger, HandlerInterface $handler): void
    {
        if (is_string($this->options['action_level'])) {
            $handler = new FingersCrossedHandler($handler, $this->options['action_level']);
        }
        // Purposefully replace all existing handlers.
        $logger->setHandlers([$handler]);
    }
}
