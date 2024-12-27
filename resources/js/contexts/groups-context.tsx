import React, { useCallback, useContext, useEffect, useState } from "react";
import { Group } from "@/types";
import { useQuery } from "@tanstack/react-query";
import { getGroups, getSectionsForGroup } from "@/services/group-service";

const GroupsContext = React.createContext<GroupsContextProvider | null>(null);

export const GroupsContextProvider: React.FC<{ children: React.ReactNode }> = ({
    children,
}) => {
    const value = useProviderGroups();

    return (
        <GroupsContext.Provider value={value}>
            {children}
        </GroupsContext.Provider>
    );
};

const useProviderGroups = () => {
    const [groups, setGroups] = useState<Array<Group>>([]);
    const [isGroupLoading, setGroupLoading] = useState<boolean>(true);

    const query = useQuery({
        queryKey: ["groups"],
        queryFn: getGroups,
        retry: false,
    });

    useEffect(() => {
        setGroupLoading(query.isLoading);
        if (query.data) {
            setGroups(query.data);
        }
    }, [query.isLoading, query.data]);

    const loadSectionsForGroup = useCallback(
        async (id: number) => {
            const sections = await getSectionsForGroup(id);
            if (sections && sections.length > 0) {
                setGroups((prev) => {
                    return prev.map((group) => {
                        if (group.id === id) {
                            return { ...group, sections };
                        }
                        return group;
                    });
                });
            }
        },
        [setGroups],
    );

    return {
        groups,
        setGroups,
        isGroupLoading,
        setGroupLoading,
        loadSectionsForGroup,
    };
};

type GroupsContextProvider = ReturnType<typeof useProviderGroups>;

export const useGroups = () => {
    const groups = useContext(GroupsContext);
    if (!groups) {
        throw new Error("useGroups must be used inside GroupsProvider");
    }
    return groups;
};
