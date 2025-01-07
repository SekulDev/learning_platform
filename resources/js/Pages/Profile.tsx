import DashboardLayout from "@/Layouts/DashboardLayout";
import React from "react";
import { Head, usePage } from "@inertiajs/react";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Path, User } from "@/types";
import ProfileForm from "@/Components/profile-form";

export default function Profile() {
    // @ts-ignore
    const user: User = usePage().props.auth.user;

    const path: Path[] = [
        {
            url: "/profile",
            label: "Your Profile",
        },
    ];

    return (
        <DashboardLayout path={path}>
            <Head title="Your profile" />
            <header>
                <h1 className="text-xl font-semibold">Your profile</h1>
            </header>
            <div className="flex flex-col items-center gap-5 mt-5 w-full">
                <Card className="w-full max-w-sm">
                    <CardHeader className="text-center">
                        <CardTitle className="text-xl>">{user.name}</CardTitle>
                        <CardDescription>{user.email}</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <ProfileForm user={user} />
                    </CardContent>
                </Card>
            </div>
        </DashboardLayout>
    );
}
