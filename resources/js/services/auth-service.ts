import { z } from "zod";
import { instance } from "@/services/instance";

export async function redirectOauth(provider: string) {
    window.location.href = `/api/auth/${provider}`;
}

export const loginFormSchema = z.object({
    email: z.string().email(),
    password: z.string().min(6),
});

export async function login(email: string, password: string) {
    interface LoginResponse {
        access_token: string;
        token_type: string;
        expires_in: number;
    }

    try {
        const { data } = await instance.post<LoginResponse>("/auth/login", {
            email,
            password,
        });
        return data;
    } catch (e) {
        return null;
    }
}
