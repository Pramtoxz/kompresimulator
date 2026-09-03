import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { index } from '@/routes/admin/auth-log';

type Baris = {
    id: number;
    event: string;
    name: string | null;
    email: string | null;
    ip_address: string;
    browser: string;
    platform: string;
    device: string;
    at: string | null;
};

type Props = {
    rows: Baris[];
    event: string;
    totals: { login: number; logout: number; failed: number };
};

const label: Record<string, string> = {
    login: 'Masuk',
    logout: 'Keluar',
    failed: 'Gagal',
};

const saringan = [
    { value: '', text: 'Semua' },
    { value: 'login', text: 'Masuk' },
    { value: 'logout', text: 'Keluar' },
    { value: 'failed', text: 'Gagal' },
];

export default function AuthLogIndex({ rows, event, totals }: Props) {
    return (
        <>
            <Head title="Riwayat masuk" />

            <div className="safe-x mx-auto flex w-full max-w-6xl flex-1 flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
                <Heading
                    title="Riwayat masuk"
                    description="Catatan setiap percobaan masuk, keluar, dan gagal, beserta alamat IP dan perangkatnya."
                />

                <div className="grid gap-3 sm:grid-cols-3">
                    {(['login', 'logout', 'failed'] as const).map((jenis) => (
                        <Card key={jenis}>
                            <CardContent>
                                <p className="text-muted-foreground text-sm">
                                    {label[jenis]}
                                </p>
                                <p className="font-mono text-2xl">
                                    {totals[jenis]}
                                </p>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                <div className="flex flex-wrap gap-2">
                    {saringan.map((pilihan) => (
                        <Button
                            key={pilihan.value}
                            asChild
                            size="sm"
                            variant={
                                event === pilihan.value ? 'default' : 'outline'
                            }
                        >
                            <Link
                                href={
                                    pilihan.value === ''
                                        ? index()
                                        : index({
                                              query: { event: pilihan.value },
                                          })
                                }
                            >
                                {pilihan.text}
                            </Link>
                        </Button>
                    ))}
                </div>

                <div className="overflow-x-auto rounded-lg border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Waktu</TableHead>
                                <TableHead>Peristiwa</TableHead>
                                <TableHead>Pengguna</TableHead>
                                <TableHead>Alamat IP</TableHead>
                                <TableHead>Peramban</TableHead>
                                <TableHead>Sistem</TableHead>
                                <TableHead>Perangkat</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {rows.length === 0 && (
                                <TableRow>
                                    <TableCell
                                        colSpan={7}
                                        className="text-muted-foreground py-8 text-center"
                                    >
                                        Belum ada catatan.
                                    </TableCell>
                                </TableRow>
                            )}

                            {rows.map((baris) => (
                                <TableRow key={baris.id}>
                                    <TableCell className="font-mono text-xs whitespace-nowrap">
                                        {baris.at ?? '—'}
                                    </TableCell>
                                    <TableCell>
                                        <Badge
                                            variant={
                                                baris.event === 'failed'
                                                    ? 'destructive'
                                                    : 'secondary'
                                            }
                                        >
                                            {label[baris.event] ?? baris.event}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <span className="block">
                                            {baris.name ?? '—'}
                                        </span>
                                        <span className="text-muted-foreground text-xs">
                                            {baris.email ?? ''}
                                        </span>
                                    </TableCell>
                                    <TableCell className="font-mono text-xs">
                                        {baris.ip_address}
                                    </TableCell>
                                    <TableCell>{baris.browser}</TableCell>
                                    <TableCell>{baris.platform}</TableCell>
                                    <TableCell>{baris.device}</TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </div>

                <p className="text-muted-foreground text-xs">
                    Menampilkan 150 catatan terbaru.
                </p>
            </div>
        </>
    );
}
