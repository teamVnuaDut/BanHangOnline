<?php

namespace FSVendor\Octolize\Tracker;

/**
 * Can send tracked data to Octolize.
 */
class SenderToOctolize implements \WPDesk_Tracker_Sender
{
    /**
     * URL to the WP Desk Tracker API endpoint.
     * @var string
     */
    private $api_url = 'https://data.octolize.org/?track=1';
    private $test_api_url = 'https://testdata.octolize.org/?track=1';
    /**
     * Sends payload to predefined receiver.
     *
     * @param array $payload Payload to send.
     *
     * @return array If succeeded. Array containing 'headers', 'body', 'response', 'cookies', 'filename'.
     * @throws \WPDesk_Tracker_Sender_Exception_WpError Error if send failed.
     *
     */
    public function send_payload(array $payload)
    {
        \FSVendor\WPDesk_Logger_Factory::log_message("Target URL: " . $this->get_api_url(), 'octolize-sender', \FSVendor\WPDesk_Logger::DEBUG);
        $response = \wp_remote_post($this->get_api_url(), array('method' => 'POST', 'timeout' => 5, 'redirection' => 5, 'httpversion' => '1.0', 'blocking' => \false, 'headers' => array('user-agent' => 'OctolizeSender'), 'body' => json_encode($payload), 'cookies' => array()));
        if ($response instanceof \WP_Error) {
            throw new \FSVendor\WPDesk_Tracker_Sender_Exception_WpError('Payload send error', $response);
        } else {
            return $response;
        }
    }
    /**
     * @return string
     */
    private function get_api_url()
    {
        $api_url = $this->api_url;
        if (apply_filters('wpdesk_tracker_use_testdata', \false)) {
            $api_url = $this->test_api_url;
        }
        return $api_url;
    }
}
