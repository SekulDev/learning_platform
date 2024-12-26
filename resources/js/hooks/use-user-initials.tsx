import { User } from "@/types";
import React from "react";

export function useUserInitials(user: User) {
    return React.useMemo<string>(() => {
        return user.name
            .split(" ")
            .slice(0, 2)
            .map((e) => e[0])
            .join("")
            .toUpperCase();
    }, [user]);
}
