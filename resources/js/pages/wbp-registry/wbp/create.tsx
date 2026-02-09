import { Head, Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import wbpRoutes from '@/routes/wbp-registry/wbp';
import type { BreadcrumbItem } from '@/types';

type GenderValue = 'male' | 'female';

type WbpFormData = {
    full_name: string;
    wbp_number: string;
    gender: GenderValue;
    birth_date: string;
    nationality: string;
};

type CreatePageProps = {
    genders: Array<{
        value: GenderValue;
        label: string;
    }>;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'WBP Management',
        href: wbpRoutes.index().url,
    },
    {
        title: 'Add WBP',
        href: wbpRoutes.create().url,
    },
];

export default function CreateWbp({ genders }: CreatePageProps) {
    const form = useForm<WbpFormData>({
        full_name: '',
        wbp_number: '',
        gender: 'male',
        birth_date: '',
        nationality: 'Indonesia',
    });

    const handleSubmit = (event: FormEvent<HTMLFormElement>): void => {
        event.preventDefault();

        form.post(wbpRoutes.store().url);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Add WBP" />

            <div className="space-y-6 p-4">
                <Heading title="Add WBP" description="Create a new WBP record." />

                <Card>
                    <CardHeader>
                        <CardTitle>WBP Profile</CardTitle>
                        <CardDescription>Fill in WBP details below.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form className="grid gap-4 md:grid-cols-2" onSubmit={handleSubmit}>
                            <div className="grid gap-2">
                                <Label htmlFor="full_name">Full name</Label>
                                <Input
                                    id="full_name"
                                    value={form.data.full_name}
                                    onChange={(event) => form.setData('full_name', event.target.value)}
                                    required
                                />
                                {form.errors.full_name && (
                                    <p className="text-sm text-destructive">{form.errors.full_name}</p>
                                )}
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="wbp_number">WBP number</Label>
                                <Input
                                    id="wbp_number"
                                    value={form.data.wbp_number}
                                    onChange={(event) => form.setData('wbp_number', event.target.value)}
                                    required
                                />
                                {form.errors.wbp_number && (
                                    <p className="text-sm text-destructive">{form.errors.wbp_number}</p>
                                )}
                            </div>

                            <div className="grid gap-2">
                                <Label>Gender</Label>
                                <Select
                                    value={form.data.gender}
                                    onValueChange={(value: GenderValue) => form.setData('gender', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {genders.map((gender) => (
                                            <SelectItem key={gender.value} value={gender.value}>
                                                {gender.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {form.errors.gender && (
                                    <p className="text-sm text-destructive">{form.errors.gender}</p>
                                )}
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="birth_date">Birth date</Label>
                                <Input
                                    id="birth_date"
                                    type="date"
                                    value={form.data.birth_date}
                                    onChange={(event) => form.setData('birth_date', event.target.value)}
                                />
                                {form.errors.birth_date && (
                                    <p className="text-sm text-destructive">{form.errors.birth_date}</p>
                                )}
                            </div>

                            <div className="grid gap-2 md:col-span-2">
                                <Label htmlFor="nationality">Nationality</Label>
                                <Input
                                    id="nationality"
                                    value={form.data.nationality}
                                    onChange={(event) => form.setData('nationality', event.target.value)}
                                />
                                {form.errors.nationality && (
                                    <p className="text-sm text-destructive">{form.errors.nationality}</p>
                                )}
                            </div>

                            <div className="flex gap-2 md:col-span-2">
                                <Button type="submit" disabled={form.processing}>
                                    Save WBP
                                </Button>
                                <Button type="button" variant="outline" asChild>
                                    <Link href={wbpRoutes.index()}>Cancel</Link>
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
