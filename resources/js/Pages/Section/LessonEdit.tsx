import { Lesson, Path, Section } from "@/types";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Head } from "@inertiajs/react";
import React, { useMemo, useState } from "react";
import { useCreateEditor } from "@/Components/editor/use-create-editor";
import { HTML5Backend } from "react-dnd-html5-backend";
import { Plate } from "@udecode/plate/react";
import { Editor, EditorContainer } from "@/Components/plate-ui/editor";
import { DndProvider } from "react-dnd";
import { Value } from "@udecode/plate";
import { Toaster } from "@/Components/ui/sonner";
import { toast } from "sonner";
import { updateLesson } from "@/services/section-service";
import { Pencil } from "lucide-react";
import { cn } from "@/lib/utils";
import EditLessonDialog from "@/Components/admin/edit-lesson-dialog";
import { DialogTrigger } from "@/Components/ui/dialog";

interface PageProps {
    section: Section;
    lesson: FullLesson;
}

interface FullLesson extends Lesson {
    content: Value;
}

export default function LessonEdit({ section, lesson }: PageProps) {
    const [lessonTitle, setLessonTitle] = useState<string>(lesson.title);

    const path = useMemo<Path[]>(
        () => [
            {
                label: section.name,
                url: "#",
            },
            {
                label: lessonTitle,
                url: `/section/${section.id}/lesson/${lesson.id}/edit`,
            },
        ],
        [lessonTitle],
    );

    //@ts-ignore
    const editor = useCreateEditor(lesson.content, true);
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
            <Head title={`${lessonTitle} - ${section.name}`} />
            <EditLessonDialog
                section={section}
                lesson={lesson}
                currentTitle={lessonTitle}
                updateTitle={setLessonTitle}
            >
                <header>
                    <DialogTrigger asChild>
                        <h1 className="text-xl font-semibold hover:underline hover:cursor-pointer group/title">
                            {lessonTitle}
                            <Pencil
                                size="18"
                                className={cn(
                                    "ml-2 inline-block opacity-0 group-hover/title:opacity-100 transition-opacity text-muted-foreground",
                                )}
                            />
                        </h1>
                    </DialogTrigger>
                </header>
            </EditLessonDialog>
            <Toaster />
            <div className="w-full max-w-full mt-6">
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
