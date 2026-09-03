<?php

namespace App\Listeners;

use App\Models\LoginEvent;
use App\Models\User;
use App\Support\DeviceName;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;

class RecordLoginEvent
{
    public function __construct(private Request $request) {}

    public function onLogin(Login $event): void
    {
        $this->store('login', $event->user instanceof User ? $event->user : null, null);
    }

    public function onLogout(Logout $event): void
    {
        $this->store('logout', $event->user instanceof User ? $event->user : null, null);
    }

    public function onFailed(Failed $event): void
    {
        $email = $event->credentials['email'] ?? null;

        $this->store('failed', null, is_string($email) ? $email : null);
    }

    private function store(string $event, ?User $user, ?string $email): void
    {
        $agent = $this->request->userAgent();

        LoginEvent::create([
            ...DeviceName::parse($agent),
            'user_id' => $user?->id,
            'email' => $email ?? $user?->email,
            'event' => $event,
            'ip_address' => $this->request->ip(),
            'user_agent' => $agent,
            'created_at' => now(),
        ]);
    }
}
