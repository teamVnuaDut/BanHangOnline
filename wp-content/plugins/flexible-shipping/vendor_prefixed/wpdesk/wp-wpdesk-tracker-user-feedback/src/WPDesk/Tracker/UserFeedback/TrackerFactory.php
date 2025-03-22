<?php

namespace FSVendor\WPDesk\Tracker\UserFeedback;

use FSVendor\WPDesk\Tracker\Sender\NullSender;
/**
 * Can create tracker.
 */
class TrackerFactory
{
    /**
     * Create custom tracker.
     *
     * @param PluginData $user_feedback_data .
     * @param Scripts|null $scripts If null given default scrips file is used.
     * @param Thickbox|null $thickbox If null given default thickbox file is used.
     * @param AjaxUserFeedbackDataHandler|null $ajax
     *
     * @return Tracker
     */
    public static function create_custom_tracker(UserFeedbackData $user_feedback_data, $scripts = null, $thickbox = null, $ajax = null)
    {
        if (empty($scripts)) {
            $scripts = new Scripts($user_feedback_data);
        }
        if (empty($thickbox)) {
            $thickbox = new Thickbox($user_feedback_data);
        }
        if (empty($ajax)) {
            $sender = new \FSVendor\WPDesk_Tracker_Sender_Wordpress_To_WPDesk();
            $sender = new \FSVendor\WPDesk_Tracker_Sender_Logged($sender);
            $ajax = new AjaxUserFeedbackDataHandler($user_feedback_data, $sender);
        }
        return new Tracker($user_feedback_data, $scripts, $thickbox, $ajax);
    }
    /**
     * Create custom tracker without sender.
     * Created tracker do not sends payload data to server.
     *
     * @param PluginData $user_feedback_data .
     * @param Scripts|null $scripts .
     * @param Thickbox|null $thickbox .
     * @param AjaxUserFeedbackDataHandler|null $ajax
     *
     * @return Tracker
     */
    public static function create_custom_tracker_with_null_sender(UserFeedbackData $user_feedback_data, $scripts = null, $thickbox = null, $ajax = null)
    {
        if (empty($scripts)) {
            $scripts = new Scripts($user_feedback_data);
        }
        if (empty($thickbox)) {
            $thickbox = new Thickbox($user_feedback_data);
        }
        if (empty($ajax)) {
            $ajax = new AjaxUserFeedbackDataHandler($user_feedback_data, new NullSender());
        }
        return new Tracker($user_feedback_data, $scripts, $thickbox, $ajax);
    }
}
