export type FaseKey = 'terminal' | 'berkas' | 'layar';

export type Fase = {
    key: FaseKey;
    label: string;
    dot: string;
    text: string;
    surface: string;
    edge: string;
    focusEdge: string;
};

const fases: Record<FaseKey, Fase> = {
    terminal: {
        key: 'terminal',
        label: 'Di terminal',
        dot: 'bg-fase-terminal',
        text: 'text-fase-terminal',
        surface: 'bg-fase-terminal-soft',
        edge: 'border-fase-terminal',
        focusEdge: 'focus:border-fase-terminal',
    },
    berkas: {
        key: 'berkas',
        label: 'Menulis berkas',
        dot: 'bg-fase-berkas',
        text: 'text-fase-berkas',
        surface: 'bg-fase-berkas-soft',
        edge: 'border-fase-berkas',
        focusEdge: 'focus:border-fase-berkas',
    },
    layar: {
        key: 'layar',
        label: 'Hasil di layar',
        dot: 'bg-fase-layar',
        text: 'text-fase-layar',
        surface: 'bg-fase-layar-soft',
        edge: 'border-fase-layar',
        focusEdge: 'focus:border-fase-layar',
    },
};

const byStep: Record<string, FaseKey> = {
    install: 'terminal',
    migration: 'terminal',
    model: 'berkas',
    controller: 'berkas',
    routes: 'berkas',
    coding: 'layar',
    done: 'layar',
};

export function faseFor(stepKey: string): Fase {
    return fases[byStep[stepKey] ?? 'berkas'];
}
