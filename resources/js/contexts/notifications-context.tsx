import React, { useEffect } from "react";
import { useGroups } from "@/contexts/groups-context";
import { usePage } from "@inertiajs/react";
import Pusher from "pusher-js";
import Echo from "laravel-echo";
import { getJwtFromCookies } from "@/services/instance";
import { listen } from "@/services/notify-service";
import { useToast } from "@/hooks/use-toast";

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    auth: {
        headers: {
            Authorization: `Bearer ${getJwtFromCookies()}`,
        },
    },
});

const NotificationsContext =
    React.createContext<NotificationsContextProvider | null>(null);

export const NotificationsContextProvider: React.FC<{
    children: React.ReactNode;
}> = ({ children }) => {
    const value = useProviderNotifications();

    return (
        <NotificationsContext.Provider value={value}>
            {children}
        </NotificationsContext.Provider>
    );
};

const useProviderNotifications = () => {
    const { groups } = useGroups();

    const { toast } = useToast();

    // @ts-ignore
    const auth = usePage().props.auth.user;

    useEffect(() => {
        const channels = [
            `user-notify.${auth.id}`,
            ...groups.map((group) => `group.${group.id}`),
        ];

        const pusher = echo;

        for (const channel of channels) {
            pusher
                .private(channel)
                .listenToAll((event: string, data: any) =>
                    listen(toast, event, data),
                );
        }

        return () => {
            pusher.leaveAllChannels();
        };
    }, [groups, auth]);

    return {};
};

type NotificationsContextProvider = ReturnType<typeof useProviderNotifications>;
