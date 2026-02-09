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
import wbpRoutes from '@/routes/wbp-registry/wbp';
import type { BreadcrumbItem } from '@/types';

type WbpRecord = {
    id: string;
    public_id: string;
    full_name: string;
    wbp_number: string;
    gender: string;
    birth_date: string | null;
    nationality: string | null;
    created_at: string | null;
};

type WbpIndexProps = {
    wbp: {
        data: WbpRecord[];
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
        title: 'WBP Management',
        href: wbpRoutes.index().url,
    },
];

export default function WbpIndex({ wbp }: WbpIndexProps) {
    const previousPage = wbp.meta.current_page > 1 ? wbp.meta.current_page - 1 : null;
    const nextPage = wbp.meta.current_page < wbp.meta.last_page ? wbp.meta.current_page + 1 : null;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="WBP Management" />

            <div className="space-y-6 p-4">
                <div className="flex items-start justify-between gap-4">
                    <Heading
                        title="WBP Management"
                        description="Manage Warga Binaan Pemasyarakatan records."
                    />

                    <Button asChild>
                        <Link href={wbpRoutes.create()}>Add WBP</Link>
                    </Button>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>WBP List</CardTitle>
                        <CardDescription>Total records: {wbp.meta.total}</CardDescription>
                    </CardHeader>
                    <CardContent className="overflow-x-auto">
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="border-b">
                                    <th className="px-2 py-3 text-left">WBP Number</th>
                                    <th className="px-2 py-3 text-left">Full Name</th>
                                    <th className="px-2 py-3 text-left">Gender</th>
                                    <th className="px-2 py-3 text-left">Birth Date</th>
                                    <th className="px-2 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {wbp.data.map((record) => (
                                    <tr key={record.id} className="border-b">
                                        <td className="px-2 py-3">{record.wbp_number}</td>
                                        <td className="px-2 py-3">{record.full_name}</td>
                                        <td className="px-2 py-3 capitalize">{record.gender}</td>
                                        <td className="px-2 py-3">{record.birth_date ?? '-'}</td>
                                        <td className="px-2 py-3">
                                            <div className="flex gap-2">
                                                <Button size="sm" variant="outline" asChild>
                                                    <Link href={wbpRoutes.show({ wbp: record.id })}>View</Link>
                                                </Button>
                                                <Button size="sm" variant="outline" asChild>
                                                    <Link href={wbpRoutes.edit({ wbp: record.id })}>Edit</Link>
                                                </Button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                                {wbp.data.length === 0 && (
                                    <tr>
                                        <td className="px-2 py-6 text-center text-muted-foreground" colSpan={5}>
                                            No WBP records found.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>

                        <div className="mt-4 flex items-center justify-between gap-3">
                            <p className="text-xs text-muted-foreground">
                                Page {wbp.meta.current_page} of {wbp.meta.last_page}
                            </p>
                            <div className="flex gap-2">
                                {previousPage === null ? (
                                    <Button size="sm" variant="outline" disabled>
                                        Previous
                                    </Button>
                                ) : (
                                    <Button size="sm" variant="outline" asChild>
                                        <Link href={wbpRoutes.index({ query: { page: previousPage } })}>
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
                                        <Link href={wbpRoutes.index({ query: { page: nextPage } })}>
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
