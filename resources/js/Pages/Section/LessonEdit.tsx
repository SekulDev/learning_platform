import { Lesson, Path, Section } from "@/types";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Head } from "@inertiajs/react";
import React, { useEffect, useState } from "react";
import { useCreateEditor } from "@/Components/editor/use-create-editor";
import { HTML5Backend } from "react-dnd-html5-backend";
import { Plate } from "@udecode/plate/react";
import { Editor, EditorContainer } from "@/Components/plate-ui/editor";
import { DndProvider } from "react-dnd";
import { Value } from "@udecode/plate";

interface PageProps {
    section: Section;
    lesson: FullLesson;
}

interface FullLesson extends Lesson {
    content: Value;
}

export default function LessonEdit({ section, lesson }: PageProps) {
    const path: Path[] = [
        {
            label: section.name,
            url: "#",
        },
        {
            label: lesson.title,
            url: `/section/${section.id}/lesson/${lesson.id}/edit`,
        },
    ];

    const [lessonContent, setLessonContent] = useState<Value>(lesson.content);

    useEffect(() => {
        console.log("value changed", lessonContent);
    }, [lessonContent]);

    //@ts-ignore
    const editor = useCreateEditor(lesson.content);

    return (
        <DashboardLayout path={path}>
            <Head title={`${lesson.title} - ${section.name}`} />
            <header>
                <h1 className="text-xl font-semibold">{lesson.title} Editor</h1>
            </header>
            <div className="w-full max-w-full mt-2">
                <DndProvider backend={HTML5Backend}>
                    <Plate
                        editor={editor}
                        onChange={({ value }) => setLessonContent(value)}
                    >
                        <EditorContainer>
                            <Editor variant="fullWidth" />
                        </EditorContainer>
                    </Plate>
                </DndProvider>
            </div>
        </DashboardLayout>
    );
}
