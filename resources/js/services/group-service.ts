import { instance, queryRequest } from "@/services/instance";
import { Group, Section, User } from "@/types";
import { z } from "zod";

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
export const createGroupSchema = z.object({
    name: z.string().min(2).max(50),
});

export async function createGroup(data: z.infer<typeof createGroupSchema>) {
    return queryRequest({
        url: "/group",
        method: "POST",
        data,
    });
}

export async function getGroupMembers(id: number) {
    return queryRequest<Array<User>>({
        url: `/group/${id}/member`,
    });
}

export async function removeMemberFromGroup(id: number, userId: number) {
    return queryRequest({
        url: `/group/${id}/member/${userId}`,
        method: "DELETE",
    });
}

export const addMemberSchema = z.object({
    email: z.string().email().max(250),
});

export async function addMemberToGroup(
    id: number,
    data: z.infer<typeof addMemberSchema>,
) {
    return queryRequest({
        url: `/group/${id}/member`,
        method: "POST",
        data,
    });
}

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
