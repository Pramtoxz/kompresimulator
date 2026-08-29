import { Badge } from '@/components/ui/badge';

const labels: Record<string, string> = {
    queued: 'Antre',
    ready: 'Siap',
    failed: 'Gagal',
};

const variants: Record<string, 'default' | 'secondary' | 'destructive'> = {
    queued: 'secondary',
    ready: 'default',
    failed: 'destructive',
};

export default function StatusBadge({ status }: { status: string }) {
    return (
        <Badge variant={variants[status] ?? 'secondary'}>
            {labels[status] ?? status}
        </Badge>
    );
}
