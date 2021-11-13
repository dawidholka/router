<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div class="col-12 md:col-8 md:col-offset-2 lg:col-6 lg:col-offset-3">
                    <Card>
                        <template #title>
                            {{ $t('common.data') }}
                        </template>
                        <template #content>
                            <p>{{ $t('common.name') }}: {{ point.name }}</p>
                            <Divider v-if="point.phone"/>
                            <p v-if="point.phone">{{ $t('common.phone') }}: {{ point.phone }}</p>
                            <Divider/>
                            <p>{{ $t('common.street') }}: {{ point.street }}</p>
                            <Divider/>
                            <p>{{ $t('common.buildingNumber') }}: {{ point.building_number }}</p>
                            <Divider v-if="point.apartament"/>
                            <p v-if="point.apartament">{{ $t('common.apartment') }}: {{ point.apartament }}</p>
                            <Divider/>
                            <p>{{ $t('common.city') }}: {{ point.city }}</p>
                            <Divider/>
                            <p>{{ $t('common.postcode') }}: {{ point.postcode }}</p>
                            <Divider v-if="point.delivery_time"/>
                            <p v-if="point.delivery_time">{{ $t('common.delivery_time') }}: {{
                                    point.delivery_time
                                }}</p>
                            <Divider v-if="point.intercom"/>
                            <p v-if="point.intercom">{{ $t('common.intercom') }}: {{ point.intercom }}</p>
                            <Divider v-if="point.last_used_at"/>
                            <p v-if="point.last_used_at">{{ $t('common.lastUsedAt') }}: {{ point.last_used_at }}</p>
                            <Divider/>
                            <p>{{ $t('common.updatedAt') }}: {{ point.updated_at }}</p>
                            <Divider/>
                            <p>{{ $t('common.createdAt') }}: {{ point.created_at }}</p>
                        </template>
                        <template #footer>
                            <Button
                                v-if="$page.props.admin"
                                class="p-button-sm"
                                icon="pi pi-pencil"
                                :label="$t('common.edit')"
                                @click="edit"
                            />
                            <Button
                                v-if="$page.props.admin"
                                class="p-button-sm p-button-info ml-1"
                                icon="pi pi-map"
                                :label="$t('common.geocode')"
                                :loading="geolocationInProgress"
                                @click="onGeolocation"
                            />
                            <Button
                                v-if="$page.props.admin"
                                class="p-button-sm p-button-danger ml-1"
                                icon="pi pi-trash"
                                :label="$t('common.delete')"
                                :loading="deletingModel"
                                @click="deletePoint"
                            />
                        </template>
                    </Card>
                    <Card v-if="point.note" class="mt-3">
                        <template #title>
                            {{ $t('common.note') }}
                        </template>
                        <template #content>
                            {{ point.note }}
                        </template>
                    </Card>
                    <Card v-if="point.driver_note" class="mt-3">
                        <template #title>
                            {{ $t('common.driverNote') }}
                        </template>
                        <template #content>
                            {{ point.driver_note }}
                        </template>
                    </Card>
                    <Card class="mt-3">
                        <template #title>
                            {{ $t('common.geocoding') }}
                        </template>
                        <template #content>
                            <p>{{ $t('common.lat') }}: {{ point.lat ?? '-' }}</p>
                            <Divider/>
                            <p>{{ $t('common.lng') }}: {{ point.long ?? '-' }}</p>
                            <Divider/>
                            <p>{{ $t('common.lockGeo') }}: {{ point.lock_geo ? $t('common.yes') : $t('common.no') }}</p>
                        </template>
                    </Card>
                    <div v-if="point.routes.length">
                        <div class="card mt-3">
                            <DataTable
                                class="p-datatable-sm"
                                :value="point.routes"
                                responsiveLayout="scroll"
                                striped-rows
                                data-key="id"
                            >
                                <template #header>
                                    <div class="flex justify-content-between align-content-center">
                                        <h5>{{ $t('common.routes') }}</h5>
                                    </div>
                                </template>
                                <Column field="route_id" :header="$t('common.route')"></Column>
                                <Column field="status" :header="$t('common.status')"></Column>
                                <Column field="delivered_time" :header="$t('common.deliveredTime')"></Column>
                                <Column :header="$t('common.options')">
                                    <template #body="slotProps">
                                        <Button
                                            v-if="slotProps.data.photo_uploaded"
                                            icon="pi pi-image"
                                            class="p-button-info p-button-sm mr-1"
                                            @click="showPhoto(slotProps.data.id)"
                                        />
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </div>
                </div>
            </div>

            <DeleteDialog
                ref="deleteDialog"
                v-model:visible="deleteDialog"
                :loading="deletingModel"
                @delete="onDelete"
            />
        </AppLayout>
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import Card from "primevue/card";
import Divider from "primevue/divider";
import Button from "primevue/button";
import DeleteDialog from "../../Components/DeleteDialog";
import DataTable from "primevue/datatable";
import Column from "primevue/column";

export default {
    name: "Show",
    components: {
        AppLayout,
        Card,
        Divider,
        Button,
        DeleteDialog,
        DataTable,
        Column
    },
    props: {
        point: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            deleteDialog: false,
            deletingModel: false,
            geolocationInProgress: false
        }
    },
    methods: {
        edit() {
            this.$inertia.get(this.route('points.edit', this.point.id));
        },
        deletePoint() {
            this.deleteDialog = true;
        },
        onGeolocation() {
            this.geolocationInProgress = true;
            this.$inertia.post(this.route('points.geolocation', this.point.id), {}, {
                onSuccess: () => {
                    this.geolocationInProgress = false;
                }
            })
        },
        onDelete() {
            this.deletingModel = true;
            this.$inertia.delete(this.route('points.destroy', this.point.id), {
                onSuccess: () => {
                    this.deletingModel = false;
                    this.deleteDialog = false;
                    this.$inertia.get(this.route('points.index'));
                }
            })
        },
        showPhoto(id) {
            window.open(this.route('waypoints.photo', id), '_blank');
        },
    }
}
</script>

<style scoped>

</style>
