<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class WpAdminAjaxController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $action = (string) $request->input('action', '');

        Log::info('Contact form ajax request received.', [
            'action' => $action,
            'ip' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'payload' => $this->safePayloadForLog($request),
        ]);

        return match ($action) {
            'wpr_form_builder_submissions' => $this->handleSubmission(),
            'wpr_form_builder_email' => $this->handleEmail($request),
            'wpr_update_form_action_meta' => $this->handleMetaUpdate($request),
            default => response()->json([
                'success' => false,
                'data' => [
                    'message' => 'Unsupported AJAX action.',
                    'action' => $action,
                ],
            ], 400),
        };
    }

    private function handleSubmission(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'action' => 'wpr_form_builder_submissions',
                'status' => 'success',
                'message' => 'Submission successful',
                'post_id' => random_int(100000, 999999),
                'submission_secret' => Str::random(32),
            ],
        ]);
    }

    private function handleMetaUpdate(Request $request): JsonResponse
    {
        Log::info('Contact form action meta updated.', [
            'post_id' => $request->input('post_id'),
            'action_name' => $request->input('action_name'),
            'status' => $request->input('status'),
            'message' => $request->input('message'),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'action' => 'wpr_update_form_action_meta',
                'status' => 'success',
                'message' => 'Action meta stored.',
            ],
        ]);
    }

    private function handleEmail(Request $request): JsonResponse
    {
        $name = $this->extractFormValue($request, 'name') ?: 'Website Visitor';
        $email = $this->extractFormValue($request, 'email');
        $message = $this->extractFormValue($request, 'message');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::warning('Contact form email validation failed.', [
                'email' => $email,
                'payload' => $this->safePayloadForLog($request),
            ]);

            return response()->json([
                'success' => false,
                'data' => [
                    'action' => 'wpr_form_builder_email',
                        'status' => 'error',
                    'message' => 'Invalid email address.',
                ],
            ], 422);
        }

        try {
            $response = $this->sendViaBrevo($name, (string) $email, (string) $message);

            if ($response->successful()) {
                Log::info('Contact form email sent successfully.', [
                    'email' => $email,
                    'brevo_status' => $response->status(),
                    'brevo_response' => $response->json(),
                ]);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'action' => 'wpr_form_builder_email',
                        'status' => 'success',
                        'message' => 'Submission successful',
                    ],
                ]);
            }

            Log::error('Contact form email send failed.', [
                'email' => $email,
                'brevo_status' => $response->status(),
                'brevo_response' => $response->body(),
            ]);

            return response()->json([
                'success' => false,
                'data' => [
                    'action' => 'wpr_form_builder_email',
                    'status' => 'error',
                    'message' => 'Submission failed',
                ],
            ], 500);
        } catch (Throwable $exception) {
            Log::error('Contact form email exception.', [
                'email' => $email,
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'data' => [
                    'action' => 'wpr_form_builder_email',
                    'status' => 'error',
                    'message' => 'Submission failed',
                ],
            ], 500);
        }
    }

    private function sendViaBrevo(string $name, string $email, string $message)
    {
        $apiKey = (string) config('services.brevo.api_key');
        $toAddress = (string) config('services.brevo.contact_to');
        $fromAddress = (string) config('services.brevo.from_address', config('mail.from.address'));
        $fromName = (string) config('services.brevo.from_name', config('mail.from.name'));

        if ($apiKey === '' || $toAddress === '' || $fromAddress === '') {
            throw new \RuntimeException('Brevo configuration is incomplete.');
        }

        $html = view('emails.contact-form', [
            'name' => $name,
            'email' => $email,
            'userMessage' => $message,
        ])->render();

        return Http::timeout(15)
            ->withHeaders([
                'accept' => 'application/json',
                'api-key' => $apiKey,
                'content-type' => 'application/json',
            ])
            ->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'email' => $fromAddress,
                    'name' => $fromName ?: 'LDC Courier',
                ],
                'to' => [
                    ['email' => $toAddress, 'name' => 'Orders'],
                ],
                'replyTo' => [
                    'email' => $email,
                    'name' => $name,
                ],
                'subject' => 'New Contact Form Submission - LDC Courier',
                'htmlContent' => $html,
            ]);
    }

    private function extractFormValue(Request $request, string $field): ?string
    {
        $formContent = $request->input('form_content');

        if (is_array($formContent)) {
            if (array_key_exists($field, $formContent)) {
                $fieldValue = $formContent[$field];
                if (is_array($fieldValue) && array_key_exists(1, $fieldValue)) {
                    return is_scalar($fieldValue[1]) ? (string) $fieldValue[1] : null;
                }
                return is_scalar($fieldValue) ? (string) $fieldValue : null;
            }

            // Royal Elementor form payload often uses keys like "form_field-email".
            $candidateKeys = [
                'form_field-' . $field,
                'form-field-' . $field,
                'form_fields[' . $field . ']',
            ];

            foreach ($candidateKeys as $candidateKey) {
                if (! array_key_exists($candidateKey, $formContent)) {
                    continue;
                }

                $fieldValue = $formContent[$candidateKey];
                if (is_array($fieldValue) && array_key_exists(1, $fieldValue)) {
                    return is_scalar($fieldValue[1]) ? (string) $fieldValue[1] : null;
                }
                return is_scalar($fieldValue) ? (string) $fieldValue : null;
            }
        }

        $fallback = $request->input('form_fields.' . $field);
        return is_scalar($fallback) ? (string) $fallback : null;
    }

    private function safePayloadForLog(Request $request): array
    {
        $payload = $request->except(['nonce']);
        return is_array($payload) ? $payload : [];
    }
}
