<?php

namespace FSVendor;

use FSVendor\Psr\Log\LoggerInterface;
if (!\defined('ABSPATH')) {
    exit;
}
if (!\class_exists('FSVendor\WPDesk_Tracker_Sender_Logged')) {
    class WPDesk_Tracker_Sender_Logged implements \WPDesk_Tracker_Sender
    {
        const LOGGER_SOURCE = 'wpdesk-sender';
        /**
         * Decorated sender.
         *
         * @var WPDesk_Tracker_Sender
         */
        private $sender;
        /** @var ?LoggerInterface */
        private $logger;
        /**
         * WPDesk_Tracker_Sender_Logged constructor.
         *
         * @param WPDesk_Tracker_Sender $sender Sender to decorate.
         * @param ?LoggerInterface $logger
         */
        public function __construct(\WPDesk_Tracker_Sender $sender, ?LoggerInterface $logger = null)
        {
            $this->sender = $sender;
            $this->logger = $logger;
        }
        /**
         * Sends payload logging payload and the response.
         *
         * @param array $payload Payload to send.
         *
         * @throws WPDesk_Tracker_Sender_Exception_WpError Error if send failed.
         *
         * @return array If succeeded. Array containing 'headers', 'body', 'response', 'cookies', 'filename'.
         */
        public function send_payload(array $payload)
        {
            if ($this->logger instanceof LoggerInterface) {
                return $this->do_send($payload);
            }
            return $this->do_send_deprecated($payload);
        }
        private function do_send(array $payload): array
        {
            $this->logger->debug('Sender payload', ['payload' => $payload]);
            try {
                $response = $this->sender->send_payload($payload);
                $this->logger->debug('Sender response', ['response' => $response]);
                return $response;
            } catch (WPDesk_Tracker_Sender_Exception_WpError $e) {
                $this->logger->error('Sender error', ['error' => $e]);
                throw $e;
            }
        }
        /**
         * For backward compatibility this function uses static access on `wp-logs` library.
         */
        private function do_send_deprecated(array $payload): array
        {
            if (\class_exists('FSVendor\WPDesk_Logger_Factory')) {
                WPDesk_Logger_Factory::log_message('Sender payload: ' . \json_encode($payload), self::LOGGER_SOURCE, WPDesk_Logger::DEBUG);
                try {
                    $response = $this->sender->send_payload($payload);
                    WPDesk_Logger_Factory::log_message('Sender response: ' . \json_encode($response), self::LOGGER_SOURCE, WPDesk_Logger::DEBUG);
                    return $response;
                } catch (WPDesk_Tracker_Sender_Exception_WpError $exception) {
                    WPDesk_Logger_Factory::log_exception($exception);
                    throw $exception;
                }
            } else {
                return $this->sender->send_payload($payload);
            }
        }
    }
}
