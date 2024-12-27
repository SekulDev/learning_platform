import React, { ReactNode, useState } from "react";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog";
import CreateGroupForm from "@/Components/admin/create-group-form";

export default function CreateGroupDialog({
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
                    <DialogTitle>Create new group</DialogTitle>
                </DialogHeader>
                <CreateGroupForm closeDialog={() => setOpen(false)} />
            </DialogContent>
        </Dialog>
    );
}
