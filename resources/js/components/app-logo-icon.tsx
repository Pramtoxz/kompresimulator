import type { SVGAttributes } from 'react';

export default function AppLogoIcon(props: SVGAttributes<SVGElement>) {
    return (
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" {...props}>
            <path d="M9 1h6v2H9z" />
            <path
                fillRule="evenodd"
                clipRule="evenodd"
                d="M12 4a9 9 0 100 18 9 9 0 000-18zm0 2.2a6.8 6.8 0 110 13.6 6.8 6.8 0 010-13.6z"
            />
            <path d="M11 8.5h2V13h3.5v2H11z" />
        </svg>
    );
}
