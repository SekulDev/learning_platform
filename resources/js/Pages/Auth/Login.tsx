import { LoginForm } from "@/Components/login-form";
import { useDarkMode } from "@/hooks/use-dark-mode";

export default function Login() {
    useDarkMode();

    return (
        <div className="flex min-h-svh flex-col items-center justify-center gap-6 bg-muted p-6 md:p-10">
            <div className="flex w-full max-w-sm flex-col gap-6">
                <LoginForm />
            </div>
        </div>
    );
}
