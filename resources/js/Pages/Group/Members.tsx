import DashboardLayout from "@/Layouts/DashboardLayout";
import { Head } from "@inertiajs/react";
import React from "react";
import { Group, Path } from "@/types";
import ManageMembers from "@/Components/admin/manage-members";

interface PageProps {
    group: Group;
}

export default function GroupMembers({ group }: PageProps) {
    const path: Path[] = [
        {
            label: group.name,
            url: `/group/${group.id}/member`,
        },
    ];

    return (
        <DashboardLayout path={path}>
            <Head title={`${group.name} members`} />
            <header>
                <h1 className="text-xl font-semibold">{group.name} members</h1>
            </header>
            <ManageMembers group={group} />
        </DashboardLayout>
    );
}
