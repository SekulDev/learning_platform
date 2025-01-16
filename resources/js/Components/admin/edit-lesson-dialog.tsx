import React, { ReactNode, useState } from "react";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from "@/Components/ui/dialog";
import { Lesson, Section } from "@/types";
import EditLessonForm from "@/Components/admin/edit-lesson-form";

export default function EditLessonDialog({
    children,
    section,
    lesson,
    currentTitle,
    updateTitle,
}: {
    children: ReactNode;
    section: Section;
    lesson: Lesson;
    currentTitle: string;
    updateTitle: React.Dispatch<React.SetStateAction<string>>;
}) {
    const [open, setOpen] = useState<boolean>(false);

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            {children}
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Update lesson title</DialogTitle>
                </DialogHeader>
                <EditLessonForm
                    lesson={lesson}
                    updateTitle={updateTitle}
                    section={section}
                    currentTitle={currentTitle}
                    closeDialog={() => setOpen(false)}
                />
            </DialogContent>
        </Dialog>
    );
}
