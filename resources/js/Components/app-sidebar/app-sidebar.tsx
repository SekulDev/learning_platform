import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarRail,
} from "@/Components/ui/sidebar";
import React from "react";
import AppSidebarHeader from "@/Components/app-sidebar/app-sidebar-header";
import AppSidebarUser from "@/Components/app-sidebar/app-sidebar-user";
import { User } from "@/types";

export function AppSidebar({
    user,
    ...props
}: React.ComponentProps<typeof Sidebar> & { user: User }) {
    return (
        <Sidebar collapsible="icon" {...props}>
            <SidebarHeader>
                <AppSidebarHeader />
            </SidebarHeader>

            <SidebarContent></SidebarContent>
            <SidebarFooter>
                <AppSidebarUser user={user} />
            </SidebarFooter>
            <SidebarRail />
        </Sidebar>
    );
}
