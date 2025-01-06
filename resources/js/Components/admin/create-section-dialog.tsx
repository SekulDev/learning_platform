import React, { ReactNode, useState } from "react";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog";
import CreateSectionForm from "@/Components/admin/create-section-form";

export default function CreateSectionDialog({
    children,
}: {
    children: ReactNode;
}) {
    const [open, setOpen] = useState<boolean>(false);

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>{children}</DialogTrigger>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Create new section</DialogTitle>
                </DialogHeader>
                <CreateSectionForm closeDialog={() => setOpen(false)} />
            </DialogContent>
        </Dialog>
    );
}
