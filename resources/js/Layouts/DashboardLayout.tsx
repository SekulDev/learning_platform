import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from "@/Components/ui/sidebar";
import React from "react";
import { Separator } from "@/Components/ui/separator";
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb";
import { AppSidebar } from "@/Components/app-sidebar/app-sidebar";
import { usePage } from "@inertiajs/react";
import { useDarkMode } from "@/hooks/use-dark-mode";
import { GroupsContextProvider } from "@/contexts/groups-context";
import { Toaster } from "@/Components/ui/toaster";
import { Path, User } from "@/types";
import { NotificationsContextProvider } from "@/contexts/notifications-context";

export default function DashboardLayout({
    children,
    path,
}: {
    children: React.ReactNode;
    path?: Path[];
}) {
    // @ts-ignore
    const user: User = usePage().props.auth.user;

    useDarkMode();

    return (
        <GroupsContextProvider>
            <Toaster />
            <NotificationsContextProvider>
                <SidebarProvider>
                    <AppSidebar user={user} />
                    <SidebarInset>
                        <header className="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12">
                            <div className="flex items-center gap-2 px-4">
                                <SidebarTrigger className="-ml-1" />
                                <Separator
                                    orientation="vertical"
                                    className="mr-2 h-4"
                                />
                                <Breadcrumb>
                                    <BreadcrumbList>
                                        <BreadcrumbItem className="hidden md:block">
                                            <BreadcrumbLink href="/">
                                                Learning platform
                                            </BreadcrumbLink>
                                        </BreadcrumbItem>
                                        {path && path.length > 0 && (
                                            <>
                                                <BreadcrumbSeparator className="hidden md:block" />
                                                {path.map((p, index, array) => (
                                                    <React.Fragment key={index}>
                                                        <BreadcrumbItem>
                                                            <BreadcrumbLink
                                                                href={p.url}
                                                            >
                                                                {p.label}
                                                            </BreadcrumbLink>
                                                        </BreadcrumbItem>
                                                        {index <
                                                            array.length -
                                                                1 && (
                                                            <BreadcrumbSeparator className="hidden md:block" />
                                                        )}
                                                    </React.Fragment>
                                                ))}
                                            </>
                                        )}
                                    </BreadcrumbList>
                                </Breadcrumb>
                            </div>
                        </header>
                        <div className="flex flex-1 flex-col gap-4 p-4 pt-0">
                            <div className="min-h-[100vh] flex-1 rounded-xl bg-muted/50 md:min-h-min p-5">
                                {children}
                            </div>
                        </div>
                    </SidebarInset>
                </SidebarProvider>
            </NotificationsContextProvider>
        </GroupsContextProvider>
    );
}
