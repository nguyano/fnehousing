<?php
/**
 * The Admin Notification Manager class of the plugin.
 * Defines all Admin Notification Actions.
 *
 * @since 1.0.0
 * @package Fnehousing
 */

namespace Fnehousing;

use Fnehousing\Database\NotificationDBManager;
use DateTime;

defined('ABSPATH') || exit;

class NotificationActions extends NotificationDBManager {

    /**
     * Register actions and hooks.
     *
     * @since 1.0.0
     */
    public function register() {
        add_action('wp_ajax_fnehd_notifications', [$this, 'actionGetNotifications']);
        add_action('fnehd_notify', [$this, 'notify']);
    }

    /**
     * Handle notification retrieval and status update.
     *
     * @since 1.0.0
     */
    public function actionGetNotifications() {
        if (!empty($_POST['noty-action']) && $_POST['noty-action'] === 'viewed' ) {
            $this->updateNotyStatus(); //change status on viewing notification
        }

        //$this->updateNotyStatus();
        $output = '';
        $notifications = $this->getNotifications();
        $totalCount = $this->totalNotyCount();

        if ($totalCount > 0) {
            foreach ($notifications as $row) {
                $output .= $this->formatNotification($row);
            }
        } else {
            $output = '<li><a href="#" class="text-bold text-italic">' . __('No Notification Found', 'fnehousing') . '</a></li>';
        }

        $notyBgClass = fnehd_is_front_user() ? 'bg-secondary' : 'bg-primary';
        wp_send_json([
            'noty' => $output,
            'unseen_noty' => $this->unseenNotyCount(),
            'noty_bg_class' => $notyBgClass,
        ]);
		
    }

    /**
     * Format a single notification item.
     *
     * @param array $row Notification data row.
     * @return string Formatted notification HTML.
     */
    private function formatNotification(array $row): string {
        $timezone = defined('FNEHD_TIMEZONE') ? FNEHD_TIMEZONE : 'UTC';
        date_default_timezone_set($timezone);

        $currentTimestamp = (new DateTime())->getTimestamp();
        $notificationTimestamp = (new DateTime($row['date']))->getTimestamp();
        $timeDiffSecs = abs($currentTimestamp - $notificationTimestamp);

        $timeString = $this->formatTimeDifference($timeDiffSecs, $row['date']);
        $notifSubject = explode(',', $row['subject'])[0];
        $subjectId = $row['subject_id'] ?? '';

        $link = $this->getNotificationLink($notifSubject, $subjectId);
        $icon = $this->getNotificationIcon($notifSubject);

		return '
		<li>
			<a href="'.esc_url($link).'" style="display: inline-block !important" class="dropdown-item fnehd-rounded">
				<i style="display: inline-block !important" class="fa fa-'.esc_attr($icon).' sett-icon"></i>
				<strong>'.$notifSubject.'</strong>&nbsp;<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;<small><em>'.$row["message"].'</em></small><br/>
				&nbsp;&nbsp;&nbsp;&nbsp;<small class="noty-small">'.$timeString.'</small>
			<a/>
		</li>';

    }

    /**
     * Format time difference into a human-readable string.
     *
     * @param int $timeDiffSecs Time difference in seconds.
     * @param string $originalDate Original date of the notification.
     * @return string Human-readable time string.
     */
    private function formatTimeDifference(int $timeDiffSecs, string $originalDate): string {
        if ($timeDiffSecs <= 5) return __('just now', 'fnehousing');
        if ($timeDiffSecs < 60) return $timeDiffSecs . __(' seconds ago', 'fnehousing');
        if ($timeDiffSecs < 3600) return round($timeDiffSecs / 60) . __(' minutes ago', 'fnehousing');
        if ($timeDiffSecs < 86400) return round($timeDiffSecs / 3600) . __(' hours ago', 'fnehousing');
        if ($timeDiffSecs < 172800) return __('Yesterday at ', 'fnehousing') . date('h:i A', strtotime($originalDate));

        return date('M d', strtotime($originalDate)) . __(' at ', 'fnehousing') . date('h:i A', strtotime($originalDate));
    }

    /**
     * Get the link for a specific notification type.
     *
     * @param string $type Notification type.
     * @param string $id Reference ID.
     * @return string Notification link.
     */
    private function getNotificationLink(string $type, string $id): string {
        switch ($type) {
            case 'New User Signup, ID':
                return admin_url('admin.php?page=fnehousing-user-profile&user_id=' . $id);
			 case 'New Shelter Created, ID':
                return admin_url('admin.php?page=fnehousing-view-shelter&shelter_id=' . $id);	
            case 'New Dispute Opened, ID':
                if (fnehd_is_front_user() && isset($_POST['fnehd_dispute_url'])) {
                    return add_query_arg(['dispute_id' => $id], sanitize_url($_POST['fnehd_dispute_url']));
                }
                return admin_url('admin.php?page=fnehousing-view-dispute&dispute_id=' . $id);
            default:
                return '#';
        }
    }

    /**
     * Get the icon class for a specific notification type.
     *
     * @param string $type Notification type.
     * @return string Icon class.
     */
    private function getNotificationIcon(string $type): string {
        return ($type === 'New User Account Created, ID' || $type === 'New Dispute Opened, ID')
            ? 'fa fa-user-group'
            : 'fa fa-bell';
    }
}
