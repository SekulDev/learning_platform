import { Group } from "@/types";
import { Toast } from "@/hooks/use-toast";

type ToastFunction = (props: Toast) => void;

type NotifyStrategy = {
    user_added_to_group: (
        toast: ToastFunction,
        metadata: { group: Group },
    ) => void;
} & {
    [key: string]: (toast: ToastFunction, metadata: any) => void;
};

const notifyStrategy: NotifyStrategy = {
    user_added_to_group: (toast, { group }) => {
        toast({
            title: `You have been added to group ${group.name}`,
        });
    },
};

export const listen = (toast: ToastFunction, event: string, data: any) => {
    const cb = notifyStrategy[event.substring(1)];
    if (!cb) return;
    cb(toast, data);
};
