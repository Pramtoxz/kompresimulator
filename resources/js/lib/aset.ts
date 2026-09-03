import ba384 from '@/assets/images/ba-384.png';
import logo256 from '@/assets/images/logokompre-256.png';
import logo64 from '@/assets/images/logokompre-64.png';
import asistenUrl from '@/assets/lottie/assistant.json?url';
import terbangUrl from '@/assets/lottie/terbang.json?url';

export const aset = {
    asisten: { src: asistenUrl, alt: 'Bg Dito Ganteng' },
    terbang: { src: terbangUrl, alt: 'Sedang diproses' },
} as const;

export const logo = {
    kecil: logo64,
    besar: logo256,
    auth: ba384,
} as const;
