import { useGroups } from "@/contexts/groups-context";
import React, { useCallback } from "react";
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from "@/Components/ui/sidebar";
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from "../ui/collapsible";
import { ChevronRight, Loader2, SquareTerminal } from "lucide-react";
import { Link } from "@inertiajs/react";

export default function AppSidebarGroups() {
    const { groups, isGroupLoading, loadSectionsForGroup } = useGroups();

    const onOpen = useCallback(
        (isOpen: boolean, id: number) => {
            if (!isOpen) return;
            loadSectionsForGroup(id);
        },
        [loadSectionsForGroup],
    );

    return (
        <SidebarGroup>
            <SidebarGroupLabel>Your groups</SidebarGroupLabel>
            {isGroupLoading ? (
                <>
                    <Loader2 className="animate-spin" />
                </>
            ) : (
                <>
                    <SidebarMenu>
                        {groups.map((group) => (
                            <Collapsible
                                key={group.id}
                                asChild
                                defaultOpen={false}
                                className="group/collapsible"
                                onOpenChange={(val) => onOpen(val, group.id)}
                            >
                                <SidebarMenuItem>
                                    <CollapsibleTrigger asChild>
                                        <SidebarMenuButton tooltip={group.name}>
                                            <SquareTerminal />
                                            <span>{group.name}</span>
                                            <ChevronRight className="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                                        </SidebarMenuButton>
                                    </CollapsibleTrigger>
                                    <CollapsibleContent>
                                        <SidebarMenuSub>
                                            {group.sections?.map((section) => (
                                                <SidebarMenuSubItem
                                                    key={section.name}
                                                >
                                                    <SidebarMenuSubButton
                                                        asChild
                                                    >
                                                        <Link href="#">
                                                            <span>
                                                                {section.name}
                                                            </span>
                                                        </Link>
                                                    </SidebarMenuSubButton>
                                                </SidebarMenuSubItem>
                                            ))}
                                        </SidebarMenuSub>
                                    </CollapsibleContent>
                                </SidebarMenuItem>
                            </Collapsible>
                        ))}
                    </SidebarMenu>
                </>
            )}
        </SidebarGroup>
    );
}
