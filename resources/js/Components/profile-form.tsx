import { useForm } from "react-hook-form";
import { z } from "zod";
import { updateUser, updateUserFormSchema } from "@/services/auth-service";
import { zodResolver } from "@hookform/resolvers/zod";
import { User } from "@/types";
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
    FormRootError,
} from "@/Components/ui/form";
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import React from "react";

export default function ProfileForm({ user }: { user: User }) {
    const form = useForm<z.infer<typeof updateUserFormSchema>>({
        resolver: zodResolver(updateUserFormSchema),
        defaultValues: {
            name: user.name,
        },
    });

    async function onSubmit(values: z.infer<typeof updateUserFormSchema>) {
        const result = await updateUser(values);
        if (result) {
            window.location.reload();
            return;
        } else {
            form.setError("root", {
                message: "There was an error during updating user profile",
            });
        }
    }

    return (
        <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)}>
                <div className="grid gap-6">
                    <div className="grid gap-6">
                        <div className="relative text-center text-sm after:absolute after:inset-0 after:top-1/2 after:z-0 after:flex after:items-center after:border-t after:border-border">
                            <span className="relative z-10 bg-background px-2 text-muted-foreground">
                                Edit your profile
                            </span>
                        </div>
                    </div>
                    <div className="grid gap-6">
                        <FormField
                            control={form.control}
                            name="name"
                            render={({ field }) => (
                                <FormItem className="grid gap-2">
                                    <FormLabel>Name</FormLabel>
                                    <FormControl>
                                        <Input
                                            placeholder="John Test"
                                            defaultValue={user.name}
                                            {...field}
                                        />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            )}
                        />
                        <FormRootError />
                        <Button type="submit" className="w-full">
                            Update
                        </Button>
                    </div>
                </div>
            </form>
        </Form>
    );
}
