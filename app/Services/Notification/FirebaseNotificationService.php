<?php

namespace App\Services\Notification;

use App\Models\User;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Exception\FirebaseException;

class FirebaseNotificationService
{
    public function __construct(
        private Messaging $messaging
    ) {}

    public function sendToUser(
        User $user,
        string $title,
        string $body,
        array $data = []
    ): array {
        $tokens = $user->deviceTokens()
            ->pluck('token')
            ->filter()
            ->values()
            ->all();

        if (empty($tokens)) {
            return [
                'success_count' => 0,
                'failure_count' => 0,
                'failures' => [],
                'message' => 'This user has no registered device tokens.',
            ];
        }

        return $this->sendToTokens($tokens, $title, $body, $data);
    }

    public function sendToTokens(
        array $tokens,
        string $title,
        string $body,
        array $data = []
    ): array {
        $tokens = array_values(array_filter($tokens));

        if (empty($tokens)) {
            return [
                'success_count' => 0,
                'failure_count' => 0,
                'failures' => [],
                'message' => 'No valid tokens were provided.',
            ];
        }

        $stringData = $this->normalizeData($data);

        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($stringData);

        try {
            $report = $this->messaging->sendMulticast($message, $tokens);

            $failures = [];

            foreach ($report->failures()->getItems() as $failure) {
                $target = method_exists($failure->target(), 'value')
                    ? $failure->target()->value()
                    : (string) $failure->target();

                $failures[] = [
                    'token' => $target,
                    'error' => $failure->error()->getMessage(),
                ];
            }

            return [
                'success_count' => $report->successes()->count(),
                'failure_count' => $report->failures()->count(),
                'failures' => $failures,
                'message' => 'Notification processed.',
            ];
        } catch (MessagingException|FirebaseException $e) {
            return [
                'success_count' => 0,
                'failure_count' => count($tokens),
                'failures' => [
                    [
                        'token' => null,
                        'error' => $e->getMessage(),
                    ]
                ],
                'message' => 'Firebase send failed.',
            ];
        }
    }

    private function normalizeData(array $data): array
    {
        $normalized = [];

        foreach ($data as $key => $value) {
            $normalized[(string) $key] = is_scalar($value) || $value === null
                ? (string) ($value ?? '')
                : json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        return $normalized;
    }
}
