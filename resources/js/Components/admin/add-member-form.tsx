import { useForm } from "react-hook-form";
import { z } from "zod";
import { addMemberSchema, addMemberToGroup } from "@/services/group-service";
import { zodResolver } from "@hookform/resolvers/zod";
import { useMutation, useQueryClient } from "@tanstack/react-query";
import React, { useState } from "react";
import { useToast } from "@/hooks/use-toast";
import { Group } from "@/types";
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

export function AddMemberForm({
    closeDialog,
    group,
    queryKey,
}: {
    closeDialog: () => void;
    group: Group;
    queryKey: string;
}) {
    const queryClient = useQueryClient();
    const { toast } = useToast();
    const [isLoading, setLoading] = useState<boolean>(false);

    const form = useForm<z.infer<typeof addMemberSchema>>({
        resolver: zodResolver(addMemberSchema),
        defaultValues: {
            email: "",
        },
    });

    const addMemberMutation = useMutation({
        mutationFn: (data: z.infer<typeof addMemberSchema>) =>
            addMemberToGroup(group.id, data),
        onSuccess: () => {
            setLoading(false);
            queryClient.invalidateQueries({ queryKey: [queryKey] });
            toast({
                title: `Added member to group: ${group.name}`,
            });
            closeDialog();
        },
        onError: () => {
            setLoading(false);
            toast({
                title: "There was an error during adding member",
            });
        },
    });

    function onSubmit(values: z.infer<typeof addMemberSchema>) {
        setLoading(true);
        addMemberMutation.mutate(values);
    }

    return (
        <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)}>
                <div className="grid gap-6">
                    <FormField
                        control={form.control}
                        name="email"
                        render={({ field }) => (
                            <FormItem className="grid gap-2">
                                <FormLabel>Email</FormLabel>
                                <FormControl>
                                    <Input
                                        placeholder="test@test.com"
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
                        Add member
                    </Button>
                </div>
            </form>
        </Form>
    );
}
