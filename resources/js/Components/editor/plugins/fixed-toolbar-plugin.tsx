"use client";

import { createPlatePlugin } from "@udecode/plate/react";

import { FixedToolbar } from "@/Components/plate-ui/fixed-toolbar";
import { FixedToolbarButtons } from "@/Components/plate-ui/fixed-toolbar-buttons";

export const FixedToolbarPlugin = createPlatePlugin({
    key: "fixed-toolbar",
    render: {
        beforeEditable: () => (
            <FixedToolbar>
                <FixedToolbarButtons />
            </FixedToolbar>
        ),
    },
});
