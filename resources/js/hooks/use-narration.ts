import { useCallback, useEffect, useRef, useState } from 'react';

const STORAGE_KEY = 'narasi';

export type UseNarrationReturn = {
    readonly enabled: boolean;
    readonly playing: boolean;
    readonly play: (urls: (string | null)[]) => void;
    readonly stop: () => void;
    readonly toggle: () => void;
};

export default function useNarration(): UseNarrationReturn {
    const audioRef = useRef<HTMLAudioElement | null>(null);
    const queueRef = useRef<string[]>([]);
    const [enabled, setEnabled] = useState(true);
    const [playing, setPlaying] = useState(false);

    useEffect(() => {
        try {
            setEnabled(window.localStorage.getItem(STORAGE_KEY) !== 'mati');
        } catch {
            setEnabled(true);
        }
    }, []);

    const advance = useCallback(() => {
        const next = queueRef.current.shift();

        if (next === undefined) {
            setPlaying(false);

            return;
        }

        const audio = audioRef.current ?? new Audio();
        audioRef.current = audio;
        audio.onended = advance;
        audio.src = next;

        audio
            .play()
            .then(() => setPlaying(true))
            .catch(() => setPlaying(false));
    }, []);

    const stop = useCallback(() => {
        queueRef.current = [];

        if (audioRef.current) {
            audioRef.current.onended = null;
            audioRef.current.pause();
        }

        setPlaying(false);
    }, []);

    const play = useCallback(
        (urls: (string | null)[]) => {
            const usable = urls.filter(
                (url): url is string => typeof url === 'string' && url !== '',
            );

            if (usable.length === 0) {
                return;
            }

            stop();
            queueRef.current = usable;
            advance();
        },
        [advance, stop],
    );

    const toggle = useCallback(() => {
        setEnabled((current) => {
            const next = !current;

            try {
                window.localStorage.setItem(STORAGE_KEY, next ? 'nyala' : 'mati');
            } catch {
                return next;
            }

            return next;
        });

        stop();
    }, [stop]);

    useEffect(() => stop, [stop]);

    return { enabled, playing, play, stop, toggle };
}
