import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import inmatesRoutes from '@/routes/inmate-registry/inmates';
import type { BreadcrumbItem } from '@/types';

type InmateRecord = {
    id: string;
    public_id: string;
    full_name: string;
    inmate_number: string;
    gender: string;
    birth_date: string | null;
    nationality: string | null;
    created_at: string | null;
};

type InmateIndexProps = {
    inmates: {
        data: InmateRecord[];
        meta: {
            current_page: number;
            per_page: number;
            total: number;
            last_page: number;
        };
    };
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Inmate Management',
        href: inmatesRoutes.index().url,
    },
];

export default function InmateIndex({ inmates }: InmateIndexProps) {
    const previousPage = inmates.meta.current_page > 1
        ? inmates.meta.current_page - 1
        : null;
    const nextPage = inmates.meta.current_page < inmates.meta.last_page
        ? inmates.meta.current_page + 1
        : null;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Inmate Management" />

            <div className="space-y-6 p-4">
                <div className="flex items-start justify-between gap-4">
                    <Heading
                        title="Inmate Management"
                        description="Manage inmate registry records."
                    />

                    <Button asChild>
                        <Link href={inmatesRoutes.create()}>Add Inmate</Link>
                    </Button>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Inmate List</CardTitle>
                        <CardDescription>Total records: {inmates.meta.total}</CardDescription>
                    </CardHeader>
                    <CardContent className="overflow-x-auto">
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="border-b">
                                    <th className="px-2 py-3 text-left">Inmate Number</th>
                                    <th className="px-2 py-3 text-left">Full Name</th>
                                    <th className="px-2 py-3 text-left">Gender</th>
                                    <th className="px-2 py-3 text-left">Birth Date</th>
                                    <th className="px-2 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {inmates.data.map((inmate) => (
                                    <tr key={inmate.id} className="border-b">
                                        <td className="px-2 py-3">{inmate.inmate_number}</td>
                                        <td className="px-2 py-3">{inmate.full_name}</td>
                                        <td className="px-2 py-3 capitalize">{inmate.gender}</td>
                                        <td className="px-2 py-3">{inmate.birth_date ?? '-'}</td>
                                        <td className="px-2 py-3">
                                            <div className="flex gap-2">
                                                <Button size="sm" variant="outline" asChild>
                                                    <Link href={inmatesRoutes.show({ inmate: inmate.id })}>View</Link>
                                                </Button>
                                                <Button size="sm" variant="outline" asChild>
                                                    <Link href={inmatesRoutes.edit({ inmate: inmate.id })}>Edit</Link>
                                                </Button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                                {inmates.data.length === 0 && (
                                    <tr>
                                        <td className="px-2 py-6 text-center text-muted-foreground" colSpan={5}>
                                            No inmate records found.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>

                        <div className="mt-4 flex items-center justify-between gap-3">
                            <p className="text-xs text-muted-foreground">
                                Page {inmates.meta.current_page} of {inmates.meta.last_page}
                            </p>
                            <div className="flex gap-2">
                                {previousPage === null ? (
                                    <Button size="sm" variant="outline" disabled>
                                        Previous
                                    </Button>
                                ) : (
                                    <Button size="sm" variant="outline" asChild>
                                        <Link
                                            href={inmatesRoutes.index({
                                                query: { page: previousPage },
                                            })}
                                        >
                                            Previous
                                        </Link>
                                    </Button>
                                )}

                                {nextPage === null ? (
                                    <Button size="sm" variant="outline" disabled>
                                        Next
                                    </Button>
                                ) : (
                                    <Button size="sm" variant="outline" asChild>
                                        <Link
                                            href={inmatesRoutes.index({
                                                query: { page: nextPage },
                                            })}
                                        >
                                            Next
                                        </Link>
                                    </Button>
                                )}
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
