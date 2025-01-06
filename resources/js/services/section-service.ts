import { queryRequest } from "@/services/instance";
import { Lesson, Section } from "@/types";
import { z } from "zod";

export const createSectionSchema = z.object({
    name: z.string().min(2).max(50),
});

export async function getOwnedSections() {
    return queryRequest<Array<Section>>({
        url: `/section/owner`,
    });
}

export async function removeSection(id: number) {
    return queryRequest({
        url: `/section/${id}`,
        method: "DELETE",
    });
}

export async function createSection(data: z.infer<typeof createSectionSchema>) {
    return queryRequest({
        url: "/section",
        method: "POST",
        data,
    });
}

export const createLessonSchema = z.object({
    title: z.string().min(2).max(50),
});

export async function createNewLessonForSection(
    sectionId: number,
    data: z.infer<typeof createLessonSchema>,
) {
    return queryRequest<Lesson>({
        url: `/section/${sectionId}/lesson`,
        method: "POST",
        data,
    });
}

export async function removeLesson(sectionId: number, id: number) {
    return queryRequest({
        url: `/section/${sectionId}/lesson/${id}`,
        method: "DELETE",
    });
}
