import { Group, User } from "@/types";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import React, { useMemo, useState } from "react";
import {
    getGroupMembers,
    removeMemberFromGroup,
} from "@/services/group-service";
import { useToast } from "@/hooks/use-toast";
import { ColumnDef } from "@tanstack/react-table";
import { ArrowUpDown, Loader2, MoreHorizontal } from "lucide-react";
import { Button } from "../ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { DataTable } from "@/Components/ui/virtualized-table";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog";
import { AddMemberForm } from "@/Components/admin/add-member-form";
import { useConfirm } from "@omit/react-confirm-dialog";

type Data = {
    key: string;
    groupId: number;
} & User;

const columns: ColumnDef<Data>[] = [
    {
        accessorKey: "name",
        size: 50,
        header: ({ column }) => {
            return (
                <Button
                    variant="ghost"
                    onClick={() =>
                        column.toggleSorting(column.getIsSorted() === "asc")
                    }
                >
                    Name
                    <ArrowUpDown />
                </Button>
            );
        },
        cell: ({ row }) => (
            <div className="capitalize">{row.getValue("name")}</div>
        ),
    },
    {
        accessorKey: "email",
        size: 50,
        header: ({ column }) => {
            return (
                <Button
                    variant="ghost"
                    onClick={() =>
                        column.toggleSorting(column.getIsSorted() === "asc")
                    }
                >
                    Email
                    <ArrowUpDown />
                </Button>
            );
        },
        cell: ({ row }) => (
            <div className="lowercase">{row.getValue("email")}</div>
        ),
    },
    {
        id: "actions",
        size: 10,
        enableHiding: false,
        cell: ({ row }) => {
            const data = row.original;
            const queryClient = useQueryClient();
            const confirm = useConfirm();
            const { toast } = useToast();

            const removeMember = useMutation({
                mutationFn: (data: { id: number; userId: number }) =>
                    removeMemberFromGroup(data.id, data.userId),
                onSuccess: () => {
                    toast({
                        title: "Member removed",
                    });
                    queryClient.invalidateQueries({ queryKey: [data.key] });
                },
                onError: () => {
                    toast({
                        title: "There was an error during removing member",
                    });
                },
            });

            async function onRemoveMember() {
                const isConfirmed = await confirm({
                    title: "Remove member",
                    description: `Are you sure you want to remove ${data.name} from group?`,
                    confirmText: "Delete",
                    cancelText: "Cancel",
                    cancelButton: {
                        variant: "outline",
                    },
                });

                if (isConfirmed) {
                    removeMember.mutate({
                        id: data.groupId,
                        userId: data.id,
                    });
                }
            }

            return (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="ghost" className="h-8 w-8 p-0">
                            <span className="sr-only">Actions</span>
                            <MoreHorizontal />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>Actions</DropdownMenuLabel>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem onClick={onRemoveMember}>
                            Delete
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            );
        },
    },
];

export default function ManageMembers({ group }: { group: Group }) {
    const [dialogOpen, setDialogOpen] = useState<boolean>(false);

    const key = useMemo<string>(() => {
        return `group-${group.id}-members`;
    }, [group.id]);

    const { data } = useQuery({
        queryKey: [key],
        queryFn: () => getGroupMembers(group.id),
        retry: false,
    });

    const members = useMemo<Array<Data> | undefined>(() => {
        if (!data) return undefined;
        return data.map((e) => {
            return { ...e, groupId: group.id, key: key };
        });
    }, [data]);

    return (
        <div className="flex flex-col items-center gap-5 mt-5 w-full">
            {members == undefined ? (
                <Loader2 className="animate-spin animate h-24" />
            ) : (
                <>
                    <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
                        <div className="w-full">
                            <DialogTrigger asChild>
                                <Button>Add member</Button>
                            </DialogTrigger>
                        </div>
                        <DialogContent>
                            <DialogHeader>
                                <DialogTitle>
                                    Add member to group {group.name}
                                </DialogTitle>
                            </DialogHeader>
                            <AddMemberForm
                                group={group}
                                queryKey={key}
                                closeDialog={() => setDialogOpen(false)}
                            />
                        </DialogContent>
                    </Dialog>
                    <DataTable
                        className="h-72 overflow-y-auto"
                        columns={columns}
                        data={members}
                        searchPlaceholder="Search members..."
                    />
                </>
            )}
        </div>
    );
}
