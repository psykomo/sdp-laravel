import { Head, Link, router } from '@inertiajs/react';
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

type ShowPageProps = {
    wbp: WbpRecord;
};

export default function ShowWbp({ wbp }: ShowPageProps) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'WBP Management',
            href: wbpRoutes.index().url,
        },
        {
            title: wbp.wbp_number,
            href: wbpRoutes.show({ wbp: wbp.id }).url,
        },
    ];

    const deleteWbp = (): void => {
        if (! window.confirm('Delete this WBP record?')) {
            return;
        }

        router.delete(wbpRoutes.destroy({ wbp: wbp.id }).url);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`WBP ${wbp.wbp_number}`} />

            <div className="space-y-6 p-4">
                <div className="flex items-start justify-between gap-4">
                    <Heading title="WBP Detail" description="Review WBP profile information." />
                    <div className="flex gap-2">
                        <Button variant="outline" asChild>
                            <Link href={wbpRoutes.edit({ wbp: wbp.id })}>Edit</Link>
                        </Button>
                        <Button variant="destructive" onClick={deleteWbp}>
                            Delete
                        </Button>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>{wbp.full_name}</CardTitle>
                        <CardDescription>{wbp.wbp_number}</CardDescription>
                    </CardHeader>
                    <CardContent className="grid gap-4 md:grid-cols-2">
                        <div>
                            <p className="text-xs uppercase text-muted-foreground">Gender</p>
                            <p className="capitalize">{wbp.gender}</p>
                        </div>
                        <div>
                            <p className="text-xs uppercase text-muted-foreground">Birth Date</p>
                            <p>{wbp.birth_date ?? '-'}</p>
                        </div>
                        <div>
                            <p className="text-xs uppercase text-muted-foreground">Nationality</p>
                            <p>{wbp.nationality ?? '-'}</p>
                        </div>
                        <div>
                            <p className="text-xs uppercase text-muted-foreground">Public ID</p>
                            <p className="break-all">{wbp.public_id}</p>
                        </div>
                    </CardContent>
                </Card>

                <Button variant="outline" asChild>
                    <Link href={wbpRoutes.index()}>Back to List</Link>
                </Button>
            </div>
        </AppLayout>
    );
}
