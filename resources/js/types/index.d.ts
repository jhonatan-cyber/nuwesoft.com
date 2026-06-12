export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    created_at: string;
    updated_at: string;
}

export interface Technology {
    id: number;
    name: string;
    slug: string;
    logo_url: string;
    logo_public_id?: string;
    description_es: string;
    description_en: string;
    invert_dark: boolean;
    is_active: boolean;
}

export interface ProjectImage {
    id: number;
    project_id: number;
    image_url: string;
    image_public_id?: string;
    order_index: number;
}

export interface Project {
    id: number;
    name: string;
    slug: string;
    description_es: string;
    description_en: string;
    project_url?: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    images?: ProjectImage[];
    technologies?: Technology[];
}

export interface Setting {
    id: number;
    key: string;
    value: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User | null;
    };
    settings: Record<string, string>;
    locale: string;
    flash: {
        success: string | null;
        error: string | null;
    };
};

declare module '@inertiajs/core' {
    interface PageProps {
        auth: {
            user: User | null;
        };
        settings: Record<string, string>;
        locale: string;
        flash: {
            success: string | null;
            error: string | null;
        };
    }
}

declare module '@inertiajs/vue3' {
    interface PageProps {
        auth: {
            user: User | null;
        };
        settings: Record<string, string>;
        locale: string;
        flash: {
            success: string | null;
            error: string | null;
        };
    }
}
