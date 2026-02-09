import { Head, Link, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
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
import inmatesRoutes from '@/routes/inmate-registry/inmates';
import type { BreadcrumbItem } from '@/types';

type GenderValue = 'male' | 'female';

type InmateRecord = {
    id: string;
    public_id: string;
    full_name: string;
    inmate_number: string;
    gender: GenderValue;
    birth_date: string | null;
    nationality: string | null;
    created_at: string | null;
};

type InmateFormData = {
    full_name: string;
    inmate_number: string;
    gender: GenderValue;
    birth_date: string;
    nationality: string;
};

type EditPageProps = {
    inmate: InmateRecord;
    genders: Array<{
        value: GenderValue;
        label: string;
    }>;
};

export default function EditInmate({ inmate, genders }: EditPageProps) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Inmate Management',
            href: inmatesRoutes.index().url,
        },
        {
            title: inmate.inmate_number,
            href: inmatesRoutes.show({ inmate: inmate.id }).url,
        },
        {
            title: 'Edit',
            href: inmatesRoutes.edit({ inmate: inmate.id }).url,
        },
    ];

    const form = useForm<InmateFormData>({
        full_name: inmate.full_name,
        inmate_number: inmate.inmate_number,
        gender: inmate.gender,
        birth_date: inmate.birth_date ?? '',
        nationality: inmate.nationality ?? '',
    });

    const handleSubmit = (event: FormEvent<HTMLFormElement>): void => {
        event.preventDefault();

        form.patch(inmatesRoutes.update({ inmate: inmate.id }).url);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit ${inmate.inmate_number}`} />

            <div className="space-y-6 p-4">
                <Heading title="Edit Inmate" description="Update inmate profile information." />

                <Card>
                    <CardHeader>
                        <CardTitle>Inmate Profile</CardTitle>
                        <CardDescription>Make necessary changes and save.</CardDescription>
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
                                <Label htmlFor="inmate_number">Inmate number</Label>
                                <Input
                                    id="inmate_number"
                                    value={form.data.inmate_number}
                                    onChange={(event) =>
                                        form.setData('inmate_number', event.target.value)
                                    }
                                    required
                                />
                                {form.errors.inmate_number && (
                                    <p className="text-sm text-destructive">{form.errors.inmate_number}</p>
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
                                    Save Changes
                                </Button>
                                <Button type="button" variant="outline" asChild>
                                    <Link href={inmatesRoutes.show({ inmate: inmate.id })}>Cancel</Link>
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
