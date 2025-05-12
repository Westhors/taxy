<?php

namespace App\Services;

use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\MulticastSendReport;
use Illuminate\Support\Facades\Log;
use App\Models\Notification as NotificationModel;

class FirebaseService
{
    protected Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    /**
     * Send a Firebase Cloud Message using a NotificationModel instance
     */
    public function sendNotificationModel(NotificationModel $notificationModel): bool
    {
        try {
            // Ensure the user has a valid fcm_token
            $fcmToken = $notificationModel->user ? $notificationModel->user->fcm_token : null;

            if (!$fcmToken) {
                Log::error('FCM send error: User does not have a valid FCM token.');
                return false;
            }

            // Create the CloudMessage instance
            $message = CloudMessage::new()
                ->withNotification(Notification::create($notificationModel->title, $notificationModel->body))
                ->withData([
                    'type' => $notificationModel->type,
                    'type_id' => $notificationModel->type_id,
                ])
                ->toToken($fcmToken);

            // Send the message via Firebase Messaging
            $this->messaging->send($message);

            return true;
        } catch (\Throwable $e) {
            Log::error('FCM send error: ' . $e->getMessage());
            return false;
        }
    }


    /**
     * Send a Firebase Cloud Message
     *
     * @param string $token Device token
     * @param string $title Notification title
     * @param string $body Notification body
     * @param array $data Optional custom data payload
     * @return bool Success or failure
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): bool
    {
        try {
            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($data)
                ->toToken($token);

            $this->messaging->send($message);

            return true;
        } catch (\Throwable $e) {
            Log::error('FCM send error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a Firebase Cloud Message to a topic
     */
    public function sendToTopic(string $topic, string $title, string $body, array $data = []): bool
    {
        try {
            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($data)
                ->toTopic($topic);

            $this->messaging->send($message);
            return true;
        } catch (\Throwable $e) {
            Log::error('FCM topic send error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a Firebase Cloud Message to multiple devices
     *
     * @param array $tokens Array of device tokens
     * @param string $title Notification title
     * @param string $body Notification body
     * @param array $data Optional custom data payload
     * @return bool Success or failure
     */
    public function sendToMultipleTokens(array $tokens, string $title, string $body, array $data = []): bool
    {
        try {
            // Create the CloudMessage instance
            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            // Send the message to the provided tokens
            /** @var MulticastSendReport $sendReport */
            $sendReport = $this->messaging->sendMulticast($message, $tokens);

            // Handle the result
            if ($sendReport->hasFailures()) {
                Log::error('FCM multicast send failed. Errors: ' . json_encode($sendReport->failures()));
                return false;
            }

            // Optionally log success
            Log::info('FCM multicast sent successfully. Successful messages: ' . json_encode($sendReport->successes()));
            return true;
        } catch (\Throwable $e) {
            Log::error('FCM multicast send error: ' . $e->getMessage());
            return false;
        }
    }
}
