import { z } from "zod";
import { instance } from "@/services/instance";

export interface AuthResponse {
    access_token: string;
    token_type: string;
    expires_in: number;
}

export async function redirectOauth(provider: string) {
    window.location.href = `/auth/${provider}`;
}

export const loginFormSchema = z.object({
    email: z.string().email().max(250),
    password: z.string().min(6).max(250),
});

export async function login(email: string, password: string) {
    try {
        const { data } = await instance.post<AuthResponse>("/auth/login", {
            email,
            password,
        });
        return data;
    } catch (e) {
        return null;
    }
}

export const registerFormSchema = z.object({
    name: z.string().min(2).max(250),
    email: z.string().email().max(250),
    password: z.string().min(6).max(250),
});

export async function register(name: string, email: string, password: string) {
    try {
        const { data } = await instance.post<AuthResponse>("/auth/register", {
            name,
            email,
            password,
        });
        return data;
    } catch (e) {
        return null;
    }
}

export async function logout() {
    try {
        await instance.post("/auth/logout");
        window.location.href = "/";
    } catch (e) {
        return null;
    }
}
