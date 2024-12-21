<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Container from '@/Components/Container.vue';
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-bold text-2xl leading-tight">Dashboard</h1>
        </template>
        <Container>
            <div class="py-4 md:py-8">
                <Card class="mb-5">
                    <template #content>
                        <p class="m-0">You are logged in!</p>
                    </template>
                </Card>

                <div class="card flex justify-center flex-wrap gap-4">
                    <Button
                        label="Primary"
                        severity="primary"
                        :pt="{
                            root: {
                                class: '!text-primary-contrast',
                            },
                        }"
                    />
                    <Button label="Secondary" severity="secondary" />
                    <Button label="Success" severity="success" />
                    <Button label="Info" severity="info" />
                    <Button label="Warn" severity="warn" />
                    <Button label="Help" severity="help" />
                    <Button label="Danger" severity="danger" />
                    <Button label="Contrast" severity="contrast" />
                </div>
            </div>
        </Container>
    </AuthenticatedLayout>
</template>
