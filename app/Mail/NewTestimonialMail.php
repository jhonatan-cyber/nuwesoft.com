<?php

namespace App\Mail;

use App\Models\Testimonial;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewTestimonialMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Testimonial $testimonial,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Nueva reseña de {$this->testimonial->client_name} — Pendiente de revisión",
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtml(),
        );
    }

    private function buildHtml(): string
    {
        $name = e($this->testimonial->client_name);
        $role = e($this->testimonial->client_role ?? '');
        $company = e($this->testimonial->client_company ?? '');
        $content = e($this->testimonial->content);
        $rating = $this->testimonial->rating;
        $stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
        $dashboardUrl = url('/dashboard/testimonials?status=pending');

        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head><meta charset="utf-8"></head>
        <body style="margin:0;padding:0;background:#f4f4f5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
        <div style="max-width:560px;margin:40px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.1);">

            <!-- Header -->
            <div style="background:#000;padding:32px;text-align:center;">
                <h1 style="margin:0;color:#facc15;font-size:20px;font-weight:900;text-transform:uppercase;letter-spacing:2px;">
                    Nueva Reseña
                </h1>
                <p style="margin:8px 0 0;color:rgba(255,255,255,.5);font-size:11px;text-transform:uppercase;letter-spacing:3px;">
                    Pendiente de revisión
                </p>
            </div>

            <!-- Body -->
            <div style="padding:32px;">

                <!-- Rating -->
                <div style="text-align:center;margin-bottom:24px;">
                    <span style="font-size:28px;color:#facc15;letter-spacing:4px;">{$stars}</span>
                    <p style="margin:4px 0 0;color:#a1a1aa;font-size:12px;">{$rating}/5 estrellas</p>
                </div>

                <!-- Quote -->
                <div style="background:#fafafa;border-left:4px solid #facc15;padding:20px;border-radius:0 8px 8px 0;margin-bottom:24px;">
                    <p style="margin:0;color:#27272a;font-size:15px;line-height:1.6;font-style:italic;">
                        "{$content}"
                    </p>
                </div>

                <!-- Author -->
                <table style="width:100%;margin-bottom:24px;">
                    <tr>
                        <td style="width:48px;">
                            <div style="width:40px;height:40px;border-radius:50%;background:#f4f4f5;display:flex;align-items:center;justify-content:center;">
                                <span style="font-size:16px;font-weight:900;color:#52525b;text-transform:uppercase;">
                                    {$name[0]}
                                </span>
                            </div>
                        </td>
                        <td style="padding-left:12px;">
                            <p style="margin:0;font-size:14px;font-weight:700;color:#18181b;text-transform:uppercase;">
                                {$name}
                            </p>
                            <p style="margin:2px 0 0;font-size:12px;color:#a1a1aa;">
                                {$role}{$company ? ", {$company}" : ''}
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- CTA -->
                <div style="text-align:center;">
                    <a href="{$dashboardUrl}"
                       style="display:inline-block;background:#000;color:#fff;padding:14px 32px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">
                        Revisar Reseña →
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div style="padding:16px 32px;background:#fafafa;text-align:center;border-top:1px solid #e4e4e7;">
                <p style="margin:0;font-size:11px;color:#a1a1aa;">
                    Este email fue enviado desde nuwesoft.nuwesomme.cloud
                </p>
            </div>

        </div>
        </body>
        </html>
        HTML;
    }
}
