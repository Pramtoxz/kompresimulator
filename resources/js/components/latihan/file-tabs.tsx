import type { WorkspaceFile } from '@/types/latihan';

type Props = {
    files: WorkspaceFile[];
    active: string;
    onSelect: (path: string) => void;
};

function basename(path: string): string {
    return path.split('/').pop() ?? path;
}

export default function FileTabs({ files, active, onSelect }: Props) {
    return (
        <div
            role="tablist"
            aria-label="Berkas"
            className="-mx-1 flex gap-1 overflow-x-auto px-1 pb-1"
        >
            {files.map((file) => (
                <button
                    key={file.path}
                    type="button"
                    role="tab"
                    aria-selected={file.path === active}
                    onClick={() => onSelect(file.path)}
                    title={file.path}
                    className={`shrink-0 rounded-md px-3 py-2 font-mono text-xs whitespace-nowrap transition-colors ${
                        file.path === active
                            ? 'bg-primary text-primary-foreground'
                            : 'bg-muted text-muted-foreground hover:text-foreground'
                    }`}
                >
                    {basename(file.path)}
                </button>
            ))}
        </div>
    );
}
