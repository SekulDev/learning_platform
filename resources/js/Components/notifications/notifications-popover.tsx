import { useQuery } from "@tanstack/react-query";
import {
    getNotifications,
    notifyStrategy,
    readAll,
} from "@/services/notify-service";
import { useEffect, useMemo } from "react";
import { Loader2 } from "lucide-react";
import { cn } from "@/lib/utils";
import { useNotifications } from "@/contexts/notifications-context";

export default function NotificationsPopover() {
    const { data, isLoading } = useQuery({
        queryKey: ["notifications"],
        queryFn: getNotifications,
        retry: false,
    });

    const { setUnreadedCount } = useNotifications();

    useEffect(() => {
        if (data === undefined) return;
        readAll();
        setUnreadedCount(0);
    }, [data]);

    const notifications = useMemo<
        Array<{ title: string; date: string; read: boolean }>
    >(() => {
        if (!data) return [];

        return data
            .map((notification) => {
                const cb = notifyStrategy[notification.eventName];
                const title = cb(notification.metadata);

                if (title) {
                    return {
                        title: title,
                        date: notification.createdAt,
                        read: notification.read,
                    };
                } else {
                    return undefined;
                }
            })
            .filter((e) => e !== undefined);
    }, [data]);

    return (
        <div className="w-80 h-80 p-4 overflow-y-scroll">
            {data !== undefined ? (
                <>
                    {notifications.map((notification) => (
                        <div className="py-2 px-1 grid flex-1 text-left text-sm leading-tight border-b">
                            <span
                                className={cn(
                                    "font-semibold",
                                    !notification.read
                                        ? "text-primary"
                                        : "text-muted-foreground",
                                )}
                            >
                                {notification.title}
                            </span>
                            <span className="truncate text-xs">
                                {notification.date}
                            </span>
                        </div>
                    ))}
                </>
            ) : (
                <>
                    <div className="w-full h-full flex items-center justify-center text-3xl">
                        <Loader2 className="animate-spin animate" />
                    </div>
                </>
            )}
        </div>
    );
}
