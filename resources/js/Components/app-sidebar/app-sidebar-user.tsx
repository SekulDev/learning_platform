import { User } from "@/types";
import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from "@/Components/ui/sidebar";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Avatar, AvatarFallback } from "@/Components/ui/avatar";
import {
    BadgeCheck,
    Bell,
    ChevronsUpDown,
    LogOut,
    Moon,
    Sun,
} from "lucide-react";
import { useUserInitials } from "@/hooks/use-user-initials";
import { useDarkMode } from "@/hooks/use-dark-mode";
import { logout } from "@/services/auth-service";
import { Badge } from "@/Components/ui/badge";
import { useNotifications } from "@/contexts/notifications-context";
import React, { useEffect, useState } from "react";
import NotificationsPopover from "@/Components/notifications/notifications-popover";
import { cn } from "@/lib/utils";

export default function AppSidebarUser({ user }: { user: User }) {
    const { isMobile } = useSidebar();
    const [darkMode, setDarMode] = useDarkMode();
    const initials = useUserInitials(user);

    const [isOpen, setIsOpen] = useState<boolean>(false);
    const [showNotifications, setShowNotifications] = useState<boolean>(false);
    const { unreadedCount } = useNotifications();

    useEffect(() => {
        setShowNotifications(false);
    }, [isOpen]);

    return (
        <SidebarMenu>
            <SidebarMenuItem>
                <DropdownMenu open={isOpen} onOpenChange={setIsOpen}>
                    <DropdownMenuTrigger asChild>
                        <SidebarMenuButton
                            size="lg"
                            className="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                        >
                            <Avatar className="h-8 w-8 rounded-lg">
                                <AvatarFallback className="rounded-lg bg-primary text-primary-foreground">
                                    {initials}
                                </AvatarFallback>
                            </Avatar>
                            <div className="grid flex-1 text-left text-sm leading-tight">
                                <span className="truncate font-semibold">
                                    {user.name}
                                    {unreadedCount > 0 && (
                                        <Badge className="ml-2 !py-0 !px-1.5 text-xs">
                                            {unreadedCount}
                                        </Badge>
                                    )}
                                </span>
                                <span className="truncate text-xs">
                                    {user.email}
                                </span>
                            </div>
                            <ChevronsUpDown className="ml-auto size-4" />
                        </SidebarMenuButton>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent
                        className={cn(
                            "w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg",
                            showNotifications && "w-auto",
                        )}
                        side={isMobile ? "bottom" : "right"}
                        align="end"
                        sideOffset={4}
                    >
                        {!showNotifications ? (
                            <>
                                <DropdownMenuLabel className="p-0 font-normal">
                                    <div className="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                        <Avatar className="h-8 w-8 rounded-lg">
                                            <AvatarFallback className="rounded-lg bg-primary text-primary-foreground">
                                                {initials}
                                            </AvatarFallback>
                                        </Avatar>
                                        <div className="grid flex-1 text-left text-sm leading-tight">
                                            <span className="truncate font-semibold">
                                                {user.name}
                                            </span>
                                            <span className="truncate text-xs">
                                                {user.email}
                                            </span>
                                        </div>
                                    </div>
                                </DropdownMenuLabel>
                                <DropdownMenuSeparator />
                                <DropdownMenuGroup>
                                    <DropdownMenuItem>
                                        <BadgeCheck />
                                        Account
                                    </DropdownMenuItem>

                                    <DropdownMenuItem
                                        onSelect={(e) => {
                                            e.preventDefault();
                                            setShowNotifications(true);
                                        }}
                                    >
                                        <Bell />
                                        Notifications{" "}
                                        {unreadedCount > 0 && (
                                            <Badge className="ml-2 !py-0 !px-1.5 text-xs">
                                                {unreadedCount}
                                            </Badge>
                                        )}
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem
                                        onClick={() => {
                                            setDarMode((prev) => !prev);
                                        }}
                                    >
                                        {darkMode ? (
                                            <>
                                                <Sun />
                                                Light Theme
                                            </>
                                        ) : (
                                            <>
                                                <Moon />
                                                Dark Theme
                                            </>
                                        )}
                                    </DropdownMenuItem>
                                </DropdownMenuGroup>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem onClick={() => logout()}>
                                    <LogOut />
                                    Log out
                                </DropdownMenuItem>
                            </>
                        ) : (
                            <>
                                <NotificationsPopover />
                            </>
                        )}
                    </DropdownMenuContent>
                </DropdownMenu>
            </SidebarMenuItem>
        </SidebarMenu>
    );
}
