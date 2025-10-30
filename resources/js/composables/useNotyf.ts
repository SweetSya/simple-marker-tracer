import { Notyf } from 'notyf';

let notyf: Notyf;

export function useNotyf() {
    if (!notyf) {
        initNotyf();
    }

    const success = (message: string) => {
        notyf.success(message);
    };

    const error = (message: string) => {
        notyf.error(message);
    };

    const info = (message: string) => {
        notyf.open({
            type: 'info',
            message,
            background: '#3b82f6',
            icon: false,
        });
    };

    const warning = (message: string) => {
        notyf.open({
            type: 'warning',
            message,
            background: '#f59e0b',
            icon: false,
        });
    };

    const custom = (options: {
        type?: string;
        message: string;
        duration?: number;
        background?: string;
        className?: string;
    }) => {
        notyf.open(options);
    };

    return {
        success,
        error,
        info,
        warning,
        custom,
    };
}

export function initNotyf() {
    notyf = new Notyf({
        duration: 2000,
        position: {
            x: 'center',
            y: 'bottom',
        },
        dismissible: true,
        ripple: true,
        types: [
            {
                type: 'info',
                background: '#3b82f6',
                icon: false,
            },
            {
                type: 'warning',
                background: '#f59e0b',
                icon: false,
            },
        ],
    });
}
