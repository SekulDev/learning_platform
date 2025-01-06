import React from "react";
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuAction,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from "@/Components/ui/sidebar";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import {
    ChevronRight,
    Folder,
    MoreHorizontal,
    PlusIcon,
    Trash2,
    User,
} from "lucide-react";
import { useIsMobile } from "@/hooks/use-mobile";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { getAdminGroups, removeGroup } from "@/services/group-service";
import { useToast } from "@/hooks/use-toast";
import CreateGroupDialog from "@/Components/admin/create-group-dialog";
import { Link } from "@inertiajs/react";
import { useConfirm } from "@omit/react-confirm-dialog";
import { Group, Section } from "@/types";
import { getOwnedSections, removeSection } from "@/services/section-service";
import CreateSectionDialog from "@/Components/admin/create-section-dialog";
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from "@/Components/ui/collapsible";
import CreateLessonDialog from "@/Components/admin/create-lesson-dialog";
import { DialogTrigger } from "@/Components/ui/dialog";

export default function AppSidebarAdmin() {
    const queryClient = useQueryClient();
    const { toast } = useToast();
    const confirm = useConfirm();

    const { data: groups } = useQuery({
        queryKey: ["admin-groups"],
        queryFn: getAdminGroups,
        retry: false,
    });

    const { data: sections } = useQuery({
        queryKey: ["owned-sections"],
        queryFn: getOwnedSections,
        retry: false,
    });

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

    const deleteSection = useMutation({
        mutationFn: removeSection,
        onSuccess: () => {
            toast({
                title: "Section removed",
            });
            queryClient.invalidateQueries({ queryKey: ["owned-sections"] });
        },
        onError: () => {
            toast({
                title: "There was an error during removing section",
            });
        },
    });

    async function onDeleteGroup(group: Group) {
        const isConfirmed = await confirm({
            title: "Delete group",
            description: `Are you sure you want to delete ${group.name} group?`,
            confirmText: "Delete",
            cancelText: "Cancel",
            cancelButton: {
                variant: "outline",
            },
        });

        if (isConfirmed) {
            deleteGroup.mutate(group.id);
        }
    }

    async function onDeleteSection(section: Section) {
        const isConfirmed = await confirm({
            title: "Delete section",
            description: `Are you sure you want to delete ${section.name} section?`,
            confirmText: "Delete",
            cancelText: "Cancel",
            cancelButton: {
                variant: "outline",
            },
        });

        if (isConfirmed) {
            deleteSection.mutate(section.id);
        }
    }

    const isMobile = useIsMobile();

    return (
        <>
            <SidebarGroup>
                <SidebarGroupLabel>Admin</SidebarGroupLabel>
                <SidebarMenu>
                    {groups?.map((group) => (
                        <SidebarMenuItem key={group.name}>
                            <SidebarMenuButton tooltip={group.name} asChild>
                                <Link href="#">
                                    <User />
                                    <span>{group.name}</span>
                                </Link>
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
                                        <Link
                                            href={`/group/${group.id}/member`}
                                        >
                                            <User />
                                            <span>Group members</span>
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        <Folder className="text-muted-foreground" />
                                        <span>Group sections</span>
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem
                                        onClick={() => onDeleteGroup(group)}
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
                            <SidebarMenuButton
                                tooltip="New Group"
                                className="text-sidebar-foreground/70"
                            >
                                <PlusIcon />
                                <span>New Group</span>
                            </SidebarMenuButton>
                        </CreateGroupDialog>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
            <SidebarGroup>
                <SidebarGroupLabel>Sections</SidebarGroupLabel>
                <SidebarMenu>
                    {sections?.map((section) => (
                        <CreateLessonDialog section={section} key={section.id}>
                            <Collapsible
                                asChild
                                defaultOpen={false}
                                className="group/collapsible"
                            >
                                <SidebarMenuItem>
                                    <DropdownMenu>
                                        <CollapsibleTrigger asChild>
                                            <SidebarMenuButton
                                                tooltip={section.name}
                                            >
                                                <Folder />
                                                <span>{section.name}</span>

                                                <ChevronRight className="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                                            </SidebarMenuButton>
                                        </CollapsibleTrigger>

                                        <DropdownMenuTrigger asChild>
                                            <SidebarMenuAction>
                                                <MoreHorizontal />
                                                <span className="sr-only">
                                                    More
                                                </span>
                                            </SidebarMenuAction>
                                        </DropdownMenuTrigger>

                                        <DropdownMenuContent
                                            className="w-48 rounded-lg"
                                            side={isMobile ? "bottom" : "right"}
                                            align={isMobile ? "end" : "start"}
                                        >
                                            <DialogTrigger asChild>
                                                <DropdownMenuItem>
                                                    <PlusIcon className="text-muted-foreground" />
                                                    <span>New lesson</span>
                                                </DropdownMenuItem>
                                            </DialogTrigger>
                                            <DropdownMenuItem
                                                onClick={() =>
                                                    onDeleteSection(section)
                                                }
                                            >
                                                <Trash2 className="text-muted-foreground" />
                                                <span>Delete section</span>
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                    <CollapsibleContent>
                                        <SidebarMenuSub>
                                            {section.lessons?.map((lesson) => (
                                                <SidebarMenuSubItem
                                                    key={lesson.id}
                                                >
                                                    <SidebarMenuSubButton
                                                        asChild
                                                    >
                                                        <Link
                                                            href={`/section/${section.id}/lesson/${lesson.id}/edit`}
                                                        >
                                                            <span>
                                                                {lesson.title}
                                                            </span>
                                                        </Link>
                                                    </SidebarMenuSubButton>
                                                </SidebarMenuSubItem>
                                            ))}
                                        </SidebarMenuSub>
                                    </CollapsibleContent>
                                </SidebarMenuItem>
                            </Collapsible>
                        </CreateLessonDialog>
                    ))}
                    <SidebarMenuItem>
                        <CreateSectionDialog>
                            <SidebarMenuButton
                                tooltip="New section"
                                className="text-sidebar-foreground/70"
                            >
                                <PlusIcon />
                                <span>New Section</span>
                            </SidebarMenuButton>
                        </CreateSectionDialog>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
        </>
    );
}
