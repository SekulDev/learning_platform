import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/Components/ui/form";
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import { Loader2 } from "lucide-react";
import { useForm } from "react-hook-form";
import React, { useState } from "react";
import { useToast } from "@/hooks/use-toast";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { createLessonSchema, updateLesson } from "@/services/section-service";
import { Lesson, Section } from "@/types";

export default function EditLessonForm({
    closeDialog,
    section,
    lesson,
    currentTitle,
    updateTitle,
}: {
    closeDialog: () => void;
    section: Section;
    lesson: Lesson;
    currentTitle: string;
    updateTitle: React.Dispatch<React.SetStateAction<string>>;
}) {
    const { toast } = useToast();
    const [isLoading, setLoading] = useState<boolean>(false);

    const form = useForm<z.infer<typeof createLessonSchema>>({
        resolver: zodResolver(createLessonSchema),
        defaultValues: {
            title: currentTitle,
        },
    });

    async function onSubmit(values: z.infer<typeof createLessonSchema>) {
        setLoading(true);
        const result = await updateLesson(section.id, lesson.id, values.title);
        setLoading(false);
        if (result) {
            updateTitle(values.title);
            closeDialog();
        } else {
            toast({
                title: "There was an error during updating lesson",
            });
        }
    }

    return (
        <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)}>
                <div className="grid gap-6">
                    <FormField
                        control={form.control}
                        name="title"
                        render={({ field }) => (
                            <FormItem className="grid gap-2">
                                <FormLabel>Title</FormLabel>
                                <FormControl>
                                    <Input
                                        placeholder="1. Variables and loops"
                                        {...field}
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        )}
                    />
                    <Button
                        type="submit"
                        className="w-full"
                        disabled={isLoading}
                    >
                        {isLoading && <Loader2 className="animate-spin" />}
                        Update title
                    </Button>
                </div>
            </form>
        </Form>
    );
}
