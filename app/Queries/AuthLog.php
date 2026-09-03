<?php

namespace App\Queries;

use App\Models\LoginEvent;

class AuthLog
{
    /**
     * @return array<string, mixed>
     */
    public function handle(?string $event): array
    {
        $rows = LoginEvent::query()
            ->with('user:id,name,role')
            ->when($event !== null && $event !== '', fn ($query) => $query->where('event', $event))
            ->latest('id')
            ->limit(150)
            ->get()
            ->map(fn (LoginEvent $row) => [
                'id' => $row->id,
                'event' => $row->event,
                'name' => $row->user?->name,
                'email' => $row->email,
                'ip_address' => $row->ip_address ?? '—',
                'browser' => $row->browser ?? '—',
                'platform' => $row->platform ?? '—',
                'device' => $row->device ?? '—',
                'at' => $row->created_at?->format('d M Y H:i:s'),
            ])
            ->all();

        return [
            'rows' => $rows,
            'event' => $event ?? '',
            'totals' => [
                'login' => LoginEvent::where('event', 'login')->count(),
                'logout' => LoginEvent::where('event', 'logout')->count(),
                'failed' => LoginEvent::where('event', 'failed')->count(),
            ],
        ];
    }
}
