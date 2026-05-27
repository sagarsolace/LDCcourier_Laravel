<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContactFormController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'form_fields.name' => ['nullable', 'string', 'max:255'],
            'form_fields.email' => ['required', 'email:rfc,dns', 'max:255'],
            'form_fields.message' => ['nullable', 'string', 'max:5000'],
        ]);

        Log::info('Contact form POST request received.', [
            'ip' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'payload' => $request->except(['_token']),
        ]);

        $name = data_get($validated, 'form_fields.name', 'Website Visitor');
        $email = data_get($validated, 'form_fields.email');
        $message = data_get($validated, 'form_fields.message', '');

        try {
            $sent = $this->sendViaBrevo(
                (string) $name,
                (string) $email,
                (string) $message
            );
        } catch (Throwable $exception) {
            Log::error('Contact form POST exception.', [
                'email' => (string) $email,
                'error' => $exception->getMessage(),
            ]);

            return back()->withInput()->withErrors([
                'contact_form' => 'Unable to send your message right now. Please try again in a few minutes.',
            ]);
        }

        if (! $sent) {
            return back()->withInput()->withErrors([
                'contact_form' => 'Unable to send your message right now. Please try again in a few minutes.',
            ]);
        }

        return back()->with('contact_form_success', 'Your message has been sent successfully.');
    }

    private function sendViaBrevo(string $name, string $email, string $message): bool
    {
        $apiKey = (string) config('services.brevo.api_key');
        $toAddress = (string) config('services.brevo.contact_to');
        $fromAddress = (string) config('services.brevo.from_address', config('mail.from.address'));
        $fromName = (string) config('services.brevo.from_name', config('mail.from.name'));

        if ($apiKey === '' || $toAddress === '' || $fromAddress === '') {
            Log::warning('Brevo contact form config is incomplete.');
            return false;
        }

        $html = view('emails.contact-form', [
            'name' => $name,
            'email' => $email,
            'userMessage' => $message,
        ])->render();

        $response = Http::timeout(15)
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

        if ($response->successful()) {
            Log::info('Contact form POST email sent successfully.', [
                'email' => $email,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);
            return true;
        }

        Log::error('Brevo contact form send failed.', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return false;
    }
}
