import { Group } from "@/types";
import { Toast } from "@/hooks/use-toast";
import { instance, queryRequest } from "@/services/instance";

export interface Notify {
    id: number;
    eventName: string;
    metadata: any;
    read: boolean;
    createdAt: string;
}

export async function getUnreadedCount(): Promise<number> {
    try {
        const { data } = await instance.get<{ count: number }>(
            "/notification/count",
        );
        return data.count;
    } catch (e) {
        return 0;
    }
}

export async function makeAsRead(id: number): Promise<void> {
    try {
        await instance.post(`/notification/${id}/read`);
    } catch (e) {
        return;
    }
}

export async function readAll(): Promise<void> {
    try {
        await instance.post("/notification/read-all");
    } catch (e) {
        return;
    }
}

export async function getNotifications() {
    return queryRequest<Array<Notify>>({
        url: "/notification",
    });
}

type ToastFunction = (props: Toast) => void;

type NotifyTitleStrategy = {
    user_added_to_group: (metadata: { group: Group }) => string;
    user_removed_from_group: (metadata: { group: Group }) => string;
} & {
    [key: string]: (metadata: any) => string;
};

export const notifyStrategy: NotifyTitleStrategy = {
    user_added_to_group: ({ group }) =>
        `You have been added to group ${group.name}`,
    user_removed_from_group: ({ group }) =>
        `You have been removed from group ${group.name}`,
};

export const listen = (toast: ToastFunction, event: string, data: any) => {
    const cb = notifyStrategy[event.substring(1)];
    if (!cb) return;
    toast({
        title: cb(data.metadata),
    });
    makeAsRead(data.id);
};
