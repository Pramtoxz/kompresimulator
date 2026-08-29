import InputError from '@/components/input-error';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { FrameworkOption, StudentFormValues } from '@/types/admin';

type Props = {
    errors: Record<string, string>;
    frameworks: FrameworkOption[];
    student?: StudentFormValues;
    passwordHint: string;
};

export default function StudentFormFields({
    errors,
    frameworks,
    student,
    passwordHint,
}: Props) {
    return (
        <div className="grid gap-6">
            <div className="grid gap-2">
                <Label htmlFor="name">Nama lengkap</Label>
                <Input
                    id="name"
                    name="name"
                    defaultValue={student?.name}
                    required
                    autoFocus
                />
                <InputError message={errors.name} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="email">Email</Label>
                <Input
                    id="email"
                    name="email"
                    type="email"
                    defaultValue={student?.email}
                    required
                />
                <InputError message={errors.email} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="password">Kata sandi</Label>
                <Input id="password" name="password" type="text" />
                <p className="text-muted-foreground text-xs">{passwordHint}</p>
                <InputError message={errors.password} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="thesis_title">Judul skripsi</Label>
                <Input
                    id="thesis_title"
                    name="thesis_title"
                    defaultValue={student?.thesis_title ?? ''}
                    required
                />
                <p className="text-muted-foreground text-xs">
                    Judul ini dipakai AI untuk menyusun soal, jadi tulis
                    selengkap mungkin.
                </p>
                <InputError message={errors.thesis_title} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="framework">Framework</Label>
                <select
                    id="framework"
                    name="framework"
                    defaultValue={student?.framework ?? ''}
                    required
                    className="border-input bg-background focus-visible:ring-ring h-9 rounded-md border px-3 py-1 text-sm shadow-xs focus-visible:ring-1 focus-visible:outline-none"
                >
                    <option value="" disabled>
                        Pilih framework
                    </option>
                    {frameworks.map((framework) => (
                        <option key={framework.value} value={framework.value}>
                            {framework.label}
                        </option>
                    ))}
                </select>
                <InputError message={errors.framework} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="target_minutes">Target menit</Label>
                <Input
                    id="target_minutes"
                    name="target_minutes"
                    type="number"
                    min={5}
                    max={120}
                    defaultValue={student?.target_minutes ?? 30}
                    required
                />
                <InputError message={errors.target_minutes} />
            </div>
        </div>
    );
}
