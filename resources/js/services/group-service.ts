import { instance, queryRequest } from "@/services/instance";
import { Group, Section } from "@/types";

export async function getGroups() {
    return queryRequest<Array<Group>>({
        url: "/group",
    });
}

export async function getSectionsForGroup(id: number) {
    try {
        const { data } = await instance.get<Array<Section>>(
            `/group/${id}/section`,
        );
        return data;
    } catch (e) {
        return null;
    }
}

// admin actions
export async function getAdminGroups() {
    return queryRequest<Array<Group>>({
        url: `/group/owner`,
    });
}

export async function removeGroup(id: number) {
    return queryRequest({
        url: `/group/${id}`,
        method: "DELETE",
    });
}
