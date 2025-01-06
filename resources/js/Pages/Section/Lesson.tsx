import { Lesson, Path, Section } from "@/types";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Head } from "@inertiajs/react";
import React from "react";

interface PageProps {
    section: Section;
    lesson: Lesson;
}

export default function LessonEdit({ section, lesson }: PageProps) {
    const path: Path[] = [
        {
            label: section.name,
            url: "#",
        },
        {
            label: lesson.title,
            url: `/section/${section.id}/lesson/${lesson.id}`,
        },
    ];

    return (
        <DashboardLayout path={path}>
            <Head title={`${lesson.title} - ${section.name}`} />
            <header>
                <h1 className="text-xl font-semibold">{lesson.title}</h1>
            </header>
        </DashboardLayout>
    );
}
