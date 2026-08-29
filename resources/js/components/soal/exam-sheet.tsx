import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type {
    FormField,
    LookupTable as Lookup,
    PracticeRule,
} from '@/types/latihan';
import FormMockup from './form-mockup';
import LookupTable from './lookup-table';
import RulesList from './rules-list';

type Props = {
    title: string | null;
    brief: string | null;
    requirements: string[];
    formFields: FormField[];
    lookup: Lookup;
    rules: PracticeRule[];
    table: string | null;
    showExpression?: boolean;
};

export default function ExamSheet({
    title,
    brief,
    requirements,
    formFields,
    lookup,
    rules,
    table,
    showExpression = false,
}: Props) {
    return (
        <div className="space-y-4">
            <Card>
                <CardHeader className="pb-3">
                    <CardTitle>{title ?? 'Soal'}</CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                    <p className="text-sm">{brief}</p>

                    {requirements.length > 0 && (
                        <ul className="list-inside list-disc space-y-1 text-sm">
                            {requirements.map((requirement) => (
                                <li key={requirement}>{requirement}</li>
                            ))}
                        </ul>
                    )}

                    {table && (
                        <p className="text-muted-foreground text-sm">
                            Simpan data ke tabel{' '}
                            <span className="text-foreground font-mono">
                                {table}
                            </span>
                            .
                        </p>
                    )}
                </CardContent>
            </Card>

            {formFields.length > 0 && (
                <Card>
                    <CardHeader className="pb-3">
                        <CardTitle className="text-base">
                            Bentuk form yang harus dibuat
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <FormMockup fields={formFields} />
                    </CardContent>
                </Card>
            )}

            {lookup.rows.length > 0 && (
                <Card>
                    <CardHeader className="pb-3">
                        <CardTitle className="text-base">
                            Tabel acuan
                        </CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-2">
                        <LookupTable lookup={lookup} />
                        {lookup.key_field && (
                            <p className="text-muted-foreground text-xs">
                                Memilih{' '}
                                <span className="font-mono">
                                    {lookup.key_field}
                                </span>{' '}
                                mengisi field lain secara otomatis.
                            </p>
                        )}
                    </CardContent>
                </Card>
            )}

            {rules.length > 0 && (
                <Card>
                    <CardHeader className="pb-3">
                        <CardTitle className="text-base">
                            Aturan hitung
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <RulesList
                            rules={rules}
                            showExpression={showExpression}
                        />
                    </CardContent>
                </Card>
            )}
        </div>
    );
}
