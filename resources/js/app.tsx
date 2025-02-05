import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/react";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createRoot } from "react-dom/client";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import React from "react";
import { ConfirmDialogProvider } from "@omit/react-confirm-dialog";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.tsx`,
            import.meta.glob("./Pages/**/*.tsx"),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        const queryClient = new QueryClient();

        root.render(
            <QueryClientProvider client={queryClient}>
                <ConfirmDialogProvider>
                    <App {...props} />
                </ConfirmDialogProvider>
            </QueryClientProvider>,
        );
    },
    progress: {
        color: "#4B5563",
    },
});
