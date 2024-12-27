import React from "react";
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuAction,
    SidebarMenuButton,
    SidebarMenuItem,
} from "@/Components/ui/sidebar";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Folder, MoreHorizontal, PlusIcon, Trash2, User } from "lucide-react";
import { useIsMobile } from "@/hooks/use-mobile";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { getAdminGroups, removeGroup } from "@/services/group-service";
import { useToast } from "@/hooks/use-toast";
import CreateGroupDialog from "@/Components/admin/create-group-dialog";

export default function AppSidebarAdmin() {
    const queryClient = useQueryClient();
    const { toast } = useToast();

    const { data } = useQuery({
        queryKey: ["admin-groups"],
        queryFn: getAdminGroups,
        retry: false,
    });

    const groups = data;

    const deleteGroup = useMutation({
        mutationFn: removeGroup,
        onSuccess: () => {
            toast({
                title: "Group removed",
            });
            queryClient.invalidateQueries({ queryKey: ["admin-groups"] });
        },
        onError: () => {
            toast({
                title: "There was an error during removing group",
            });
        },
    });

    const isMobile = useIsMobile();

    return (
        <SidebarGroup className="group-data-[collapsible=icon]:hidden">
            <SidebarGroupLabel>Admin</SidebarGroupLabel>
            <SidebarMenu>
                {groups?.map((group) => (
                    <SidebarMenuItem key={group.name}>
                        <SidebarMenuButton asChild>
                            <a href="#">
                                <User />
                                <span>{group.name}</span>
                            </a>
                        </SidebarMenuButton>
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <SidebarMenuAction showOnHover>
                                    <MoreHorizontal />
                                    <span className="sr-only">More</span>
                                </SidebarMenuAction>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent
                                className="w-48 rounded-lg"
                                side={isMobile ? "bottom" : "right"}
                                align={isMobile ? "end" : "start"}
                            >
                                <DropdownMenuItem asChild>
                                    <a href={`/group/${group.id}/member`}>
                                        <User />
                                        <span>Group members</span>
                                    </a>
                                </DropdownMenuItem>
                                <DropdownMenuItem>
                                    <Folder className="text-muted-foreground" />
                                    <span>Group sections</span>
                                </DropdownMenuItem>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem
                                    onClick={() => deleteGroup.mutate(group.id)}
                                >
                                    <Trash2 className="text-muted-foreground" />
                                    <span>Delete group</span>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </SidebarMenuItem>
                ))}
                <SidebarMenuItem>
                    <CreateGroupDialog>
                        <SidebarMenuButton className="text-sidebar-foreground/70">
                            <PlusIcon />
                            <span>New Group</span>
                        </SidebarMenuButton>
                    </CreateGroupDialog>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroup>
    );
}
