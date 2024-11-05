<?php

namespace App\Jobs;

use App\Mail\InviteToEventMail;
use App\Mail\Users\RegistrationUserMail;
use App\Mail\Users\ResetToPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

require_once dirname(__DIR__) . '/Domain/Constants/FieldConst.php';

class MailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $mode;
    protected array $attributes;

    public function __construct(string $mode, array $attributes)
    {
        $this->mode = $mode;
        $this->attributes = $attributes;
    }

    public function handle(): void
    {
        switch ($this->mode) {
            case RegistrationUserMail::class:
                Mail::to($this->attributes[FIELD_EMAIL])->send(new RegistrationUserMail());
                break;
            case ResetToPasswordMail::class:
                Mail::to($this->attributes[FIELD_EMAIL])->send(new ResetToPasswordMail($this->attributes[FIELD_PASSWORD]));
                break;
            case InviteToEventMail::class:
                Mail::to($this->attributes[FIELD_EMAIL])->send(new InviteToEventMail($this->attributes));
                break;
        }

    }
}
