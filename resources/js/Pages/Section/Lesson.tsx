import { type Lesson, Path, Section } from "@/types";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Head } from "@inertiajs/react";
import React from "react";
import { DndProvider } from "react-dnd";
import { HTML5Backend } from "react-dnd-html5-backend";
import { Plate } from "@udecode/plate/react";
import { Editor, EditorContainer } from "@/Components/plate-ui/editor";

interface PageProps {
    section: Section;
    lesson: Lesson;
}

export default function Lesson({ section, lesson }: PageProps) {
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

    //@ts-ignore
    const editor = useCreateEditor(lesson.content);
    editor.api.setNormalizing(true);

    return (
        <DashboardLayout path={path}>
            <Head title={`${lesson.title} - ${section.name}`} />
            <header>
                <h1 className="text-xl font-semibold">{lesson.title}</h1>
            </header>
            <div className="w-full max-w-full mt-2">
                <DndProvider backend={HTML5Backend}>
                    <Plate editor={editor} readOnly={true}>
                        <EditorContainer>
                            <Editor variant="fullWidth" />
                        </EditorContainer>
                    </Plate>
                </DndProvider>
            </div>
        </DashboardLayout>
    );
}
