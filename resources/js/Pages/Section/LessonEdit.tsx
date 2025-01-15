import { Lesson, Path, Section } from "@/types";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Head } from "@inertiajs/react";
import React, { useState } from "react";
import { useCreateEditor } from "@/Components/editor/use-create-editor";
import { HTML5Backend } from "react-dnd-html5-backend";
import { Plate } from "@udecode/plate/react";
import { Editor, EditorContainer } from "@/Components/plate-ui/editor";
import { DndProvider } from "react-dnd";
import { Value } from "@udecode/plate";
import { Toaster } from "@/Components/ui/sonner";
import { toast } from "sonner";
import { updateLesson } from "@/services/section-service";

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

    //@ts-ignore
    const editor = useCreateEditor(lesson.content);
    editor.api.setNormalizing(true);

    const [lessonContent, setLessonContent] = useState<Value>(lesson.content);
    const [sonner, setSonner] = useState<string | number | null>(null);

    function updateContent(value: Value) {
        setLessonContent(value);
        if (JSON.stringify(value) !== JSON.stringify(lessonContent)) {
            const newSonner = toast(
                "You have unsaved changes. Do you want to save it?",
                {
                    id: sonner ? sonner : undefined,
                    duration: Infinity,
                    action: {
                        label: "Save",
                        onClick: async () => {
                            console.log("save", lessonContent);
                            await updateLesson(
                                section.id,
                                lesson.id,
                                undefined,
                                lessonContent,
                            );
                            toast.dismiss(newSonner);
                            setSonner(null);
                        },
                    },
                },
            );
            setSonner(newSonner);
        }
    }

    return (
        <DashboardLayout path={path}>
            <Head title={`${lesson.title} - ${section.name}`} />
            <header>
                <h1 className="text-xl font-semibold">{lesson.title} Editor</h1>
            </header>
            <Toaster />
            <div className="w-full max-w-full mt-2">
                <DndProvider backend={HTML5Backend}>
                    <Plate
                        editor={editor}
                        onChange={({ value }) => updateContent(value)}
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
