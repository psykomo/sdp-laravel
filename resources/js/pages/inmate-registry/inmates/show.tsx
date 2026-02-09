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

type ShowPageProps = {
    inmate: InmateRecord;
};

export default function ShowInmate({ inmate }: ShowPageProps) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Inmate Management',
            href: inmatesRoutes.index().url,
        },
        {
            title: inmate.inmate_number,
            href: inmatesRoutes.show({ inmate: inmate.id }).url,
        },
    ];

    const deleteInmate = (): void => {
        if (! window.confirm('Delete this inmate record?')) {
            return;
        }

        router.delete(inmatesRoutes.destroy({ inmate: inmate.id }).url);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Inmate ${inmate.inmate_number}`} />

            <div className="space-y-6 p-4">
                <div className="flex items-start justify-between gap-4">
                    <Heading title="Inmate Detail" description="Review inmate profile information." />
                    <div className="flex gap-2">
                        <Button variant="outline" asChild>
                            <Link href={inmatesRoutes.edit({ inmate: inmate.id })}>Edit</Link>
                        </Button>
                        <Button variant="destructive" onClick={deleteInmate}>
                            Delete
                        </Button>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>{inmate.full_name}</CardTitle>
                        <CardDescription>{inmate.inmate_number}</CardDescription>
                    </CardHeader>
                    <CardContent className="grid gap-4 md:grid-cols-2">
                        <div>
                            <p className="text-xs uppercase text-muted-foreground">Gender</p>
                            <p className="capitalize">{inmate.gender}</p>
                        </div>
                        <div>
                            <p className="text-xs uppercase text-muted-foreground">Birth Date</p>
                            <p>{inmate.birth_date ?? '-'}</p>
                        </div>
                        <div>
                            <p className="text-xs uppercase text-muted-foreground">Nationality</p>
                            <p>{inmate.nationality ?? '-'}</p>
                        </div>
                        <div>
                            <p className="text-xs uppercase text-muted-foreground">Public ID</p>
                            <p className="break-all">{inmate.public_id}</p>
                        </div>
                    </CardContent>
                </Card>

                <Button variant="outline" asChild>
                    <Link href={inmatesRoutes.index()}>Back to List</Link>
                </Button>
            </div>
        </AppLayout>
    );
}
