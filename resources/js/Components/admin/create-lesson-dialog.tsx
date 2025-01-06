import React, { ReactNode, useState } from "react";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from "@/Components/ui/dialog";
import { Section } from "@/types";
import CreateLessonForm from "@/Components/admin/create-lesson-form";

export default function CreateLessonDialog({
    children,
    section,
}: {
    children: ReactNode;
    section: Section;
}) {
    const [open, setOpen] = useState<boolean>(false);

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            {children}
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Create new lesson</DialogTitle>
                </DialogHeader>
                <CreateLessonForm
                    section={section}
                    closeDialog={() => setOpen(false)}
                />
            </DialogContent>
        </Dialog>
    );
}
