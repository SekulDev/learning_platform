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
import { createGroup, createGroupSchema } from "@/services/group-service";
import { zodResolver } from "@hookform/resolvers/zod";

export default function CreateGroupForm({
    closeDialog,
}: {
    closeDialog: () => void;
}) {
    const queryClient = useQueryClient();
    const { toast } = useToast();
    const [isLoading, setLoading] = useState<boolean>(false);

    const form = useForm<z.infer<typeof createGroupSchema>>({
        resolver: zodResolver(createGroupSchema),
        defaultValues: {
            name: "",
        },
    });

    const createGroupMutation = useMutation({
        mutationFn: createGroup,
        onSuccess: () => {
            setLoading(false);
            queryClient.invalidateQueries({ queryKey: ["admin-groups"] });
            closeDialog();
        },
        onError: () => {
            setLoading(false);
            toast({
                title: "There was an error during creating group",
            });
        },
    });

    function onSubmit(values: z.infer<typeof createGroupSchema>) {
        setLoading(true);
        createGroupMutation.mutate(values);
    }

    return (
        <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)}>
                <div className="grid gap-6">
                    <FormField
                        control={form.control}
                        name="name"
                        render={({ field }) => (
                            <FormItem className="grid gap-2">
                                <FormLabel>Name</FormLabel>
                                <FormControl>
                                    <Input
                                        placeholder="java group"
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
