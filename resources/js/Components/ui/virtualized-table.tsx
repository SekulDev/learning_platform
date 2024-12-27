import {
    ColumnDef,
    flexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getSortedRowModel,
    SortingState,
    useReactTable,
} from "@tanstack/react-table";

import {
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import React, { forwardRef, useState } from "react";
import { cn } from "@/lib/utils";
import { useVirtualizer } from "@tanstack/react-virtual";
import { Input } from "@/Components/ui/input";

// Original Table is wrapped with a <div> (see https://ui.shadcn.com/docs/components/table#radix-:r24:-content-manual),
// but here we don't want it, so let's use a new component with only <table> tag
const TableComponent = forwardRef<
    HTMLTableElement,
    React.HTMLAttributes<HTMLTableElement>
>(({ className, ...props }, ref) => (
    <table
        ref={ref}
        className={cn("w-full caption-bottom text-sm", className)}
        {...props}
    />
));
TableComponent.displayName = "TableComponent";

interface DataTableProps<TData, TValue>
    extends React.HTMLAttributes<HTMLDivElement> {
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
    searchPlaceholder?: string;
}

export function DataTable<TData, TValue>({
    columns,
    data,
    searchPlaceholder,
    ...props
}: DataTableProps<TData, TValue>) {
    const [sorting, setSorting] = useState<SortingState>([]);
    const [globalFilter, setGlobalFilter] = useState<any>([]);

    const table = useReactTable({
        data,
        columns,
        onGlobalFilterChange: setGlobalFilter,
        globalFilterFn: "includesString",
        state: {
            sorting,
            globalFilter,
        },
        onSortingChange: setSorting,
        getFilteredRowModel: getFilteredRowModel(),
        getCoreRowModel: getCoreRowModel(),
        getSortedRowModel: getSortedRowModel(),
    });

    const { rows } = table.getFilteredRowModel();

    const parentRef = React.useRef<HTMLDivElement>(null);

    const virtualizer = useVirtualizer({
        count: rows.length,
        getScrollElement: () => parentRef.current,
        estimateSize: () => 34,
        overscan: 20,
    });

    return (
        <div className="w-full">
            <div className="flex items-center py-4">
                <Input
                    placeholder={searchPlaceholder}
                    value={globalFilter ?? ""}
                    onChange={(e) =>
                        table.setGlobalFilter(String(e.target.value))
                    }
                    className="max-w-sm"
                />
            </div>
            <div className="rounded-md border" ref={parentRef} {...props}>
                <div style={{ height: `${virtualizer.getTotalSize()}px` }}>
                    <TableComponent>
                        <TableHeader>
                            {table.getHeaderGroups().map((headerGroup) => (
                                <TableRow key={headerGroup.id}>
                                    {headerGroup.headers.map((header) => {
                                        return (
                                            <TableHead key={header.id}>
                                                {header.isPlaceholder
                                                    ? null
                                                    : flexRender(
                                                          header.column
                                                              .columnDef.header,
                                                          header.getContext(),
                                                      )}
                                            </TableHead>
                                        );
                                    })}
                                </TableRow>
                            ))}
                        </TableHeader>
                        <TableBody>
                            {virtualizer.getVirtualItems().length > 0 ? (
                                virtualizer
                                    .getVirtualItems()
                                    .map((virtualRow, index) => {
                                        const row = rows[virtualRow.index];
                                        return (
                                            <TableRow
                                                key={row.id}
                                                data-state={
                                                    row.getIsSelected() &&
                                                    "selected"
                                                }
                                                style={{
                                                    height: `${virtualRow.size}px`,
                                                    transform: `translateY(${
                                                        virtualRow.start -
                                                        index * virtualRow.size
                                                    }px)`,
                                                }}
                                            >
                                                {row
                                                    .getVisibleCells()
                                                    .map((cell) => (
                                                        <TableCell
                                                            key={cell.id}
                                                        >
                                                            {flexRender(
                                                                cell.column
                                                                    .columnDef
                                                                    .cell,
                                                                cell.getContext(),
                                                            )}
                                                        </TableCell>
                                                    ))}
                                            </TableRow>
                                        );
                                    })
                            ) : (
                                <TableRow>
                                    <TableCell
                                        colSpan={columns.length}
                                        className="h-24 text-center"
                                    >
                                        No results.
                                    </TableCell>
                                </TableRow>
                            )}
                        </TableBody>
                    </TableComponent>
                </div>
            </div>
        </div>
    );
}
