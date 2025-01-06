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
import { useMutation, useQueryClient } from "@tanstack/react-query";
import { useToast } from "@/hooks/use-toast";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import {
    createLessonSchema,
    createNewLessonForSection,
} from "@/services/section-service";
import { Lesson, Section } from "@/types";

import { router } from "@inertiajs/react";

export default function CreateLessonForm({
    closeDialog,
    section,
}: {
    closeDialog: () => void;
    section: Section;
}) {
    const queryClient = useQueryClient();
    const { toast } = useToast();
    const [isLoading, setLoading] = useState<boolean>(false);

    const form = useForm<z.infer<typeof createLessonSchema>>({
        resolver: zodResolver(createLessonSchema),
        defaultValues: {
            title: "",
        },
    });

    const createLessonMutation = useMutation({
        mutationFn: (data: z.infer<typeof createLessonSchema>) =>
            createNewLessonForSection(section.id, data),
        onSuccess: (data: Lesson) => {
            setLoading(false);
            router.push({
                url: `/section/${section.id}/lesson/${data.id}/edit`,
            });
            queryClient.invalidateQueries({ queryKey: ["owned-sections"] });
            closeDialog();
        },
        onError: () => {
            setLoading(false);
            toast({
                title: "There was an error during creating lesson",
            });
        },
    });

    function onSubmit(values: z.infer<typeof createLessonSchema>) {
        setLoading(true);
        createLessonMutation.mutate(values);
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
                        Create
                    </Button>
                </div>
            </form>
        </Form>
    );
}
