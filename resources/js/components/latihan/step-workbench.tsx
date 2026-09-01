import { Button } from '@/components/ui/button';
import type {
    WorkspaceCheck,
    WorkspaceDatabase,
    WorkspaceFile,
} from '@/types/latihan';
import CheckResults from './check-results';
import DatabasePreview from './database-preview';
import EditorCard from './editor-card';
import PreviewFrame, { type PreviewHandle } from './preview-frame';

type Props = {
    stepKey: string;
    file: WorkspaceFile | undefined;
    onSaveFile: (content: string) => void;
    onRunMigration: () => void;
    onCreateTable: () => void;
    onSubmitRow: (data: Record<string, string>) => void;
    onRunChecks: () => void;
    previewRef: React.RefObject<PreviewHandle | null>;
    preview: string;
    database: WorkspaceDatabase;
    checks: WorkspaceCheck[];
    checking: boolean;
    guided: boolean;
};

export default function StepWorkbench({
    stepKey,
    file,
    onSaveFile,
    onRunMigration,
    onCreateTable,
    onSubmitRow,
    onRunChecks,
    previewRef,
    preview,
    database,
    checks,
    checking,
    guided,
}: Props) {
    if (stepKey === 'install' || stepKey === 'done') {
        return null;
    }

    return (
        <div className="space-y-6">
            {file && (
                <EditorCard
                    path={file.path}
                    content={file.content}
                    onSave={onSaveFile}
                />
            )}

            {stepKey === 'migration' && (
                <section className="space-y-3">
                    <div className="flex flex-wrap items-center justify-between gap-3">
                        <h2 className="text-base font-medium">Tabel latihan</h2>
                        <Button
                            variant="secondary"
                            onClick={file ? onRunMigration : onCreateTable}
                            className="h-11"
                        >
                            {file ? 'Jalankan migration' : 'Buat tabel'}
                        </Button>
                    </div>

                    {!file && (
                        <p className="text-muted-foreground text-sm leading-relaxed">
                            Di ujian nanti tabelnya kamu buat sendiri lewat
                            SQLyog. Di sini tekan Buat tabel, dan tabel yang
                            sama dibuatkan langsung dari soal supaya tombol
                            Simpan nanti benar-benar menyimpan.
                        </p>
                    )}

                    <DatabasePreview database={database} />
                </section>
            )}

            {stepKey === 'coding' && (
                <>
                    <section className="space-y-2">
                        <h2 className="text-base font-medium">Pratinjau</h2>
                        <PreviewFrame
                            ref={previewRef}
                            html={preview}
                            onSubmit={onSubmitRow}
                        />
                    </section>

                    <section className="space-y-2">
                        <h2 className="text-base font-medium">Data tersimpan</h2>
                        <DatabasePreview database={database} />
                    </section>

                    {!guided && (
                        <section className="space-y-3">
                            <div className="flex flex-wrap items-center justify-between gap-3">
                                <h2 className="text-base font-medium">
                                    Hasil pengecekan
                                </h2>
                                <Button
                                    onClick={onRunChecks}
                                    disabled={checking}
                                    className="h-11"
                                >
                                    {checking ? 'Mengecek...' : 'Cek pekerjaan'}
                                </Button>
                            </div>
                            <CheckResults checks={checks} />
                        </section>
                    )}
                </>
            )}
        </div>
    );
}
