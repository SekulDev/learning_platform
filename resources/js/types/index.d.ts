export interface User {
    id: number;
    name: string;
    email: string;
    roles: string[];
}

export interface Lesson {
    id: number;
    title: string;
}

export interface Section {
    id: number;
    name: string;
    lessons: Array<Lesson>;
}

export interface Group {
    id: number;
    name: string;
    sections?: Array<Section>;
}

export interface Path {
    label: string;
    url: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};
