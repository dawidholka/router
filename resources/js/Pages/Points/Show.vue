<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div class="col-12 md:col-8 md:col-offset-2 lg:col-6 lg:col-offset-3">
                    <Card>
                        <template #title>
                            Dane
                        </template>
                        <template #content>
                            <p>Nazwa: {{ point.name }}</p>
                            <Divider v-if="point.phone"/>
                            <p v-if="point.phone">Telefon: {{ point.phone }}</p>
                            <Divider/>
                            <p>Ulica: {{ point.street }}</p>
                            <Divider/>
                            <p>Numer budynku: {{ point.building_number }}</p>
                            <Divider v-if="point.apartament"/>
                            <p v-if="point.apartament">Numer mieszkania: {{ point.apartament }}</p>
                            <Divider/>
                            <p>Miasto: {{ point.city }}</p>
                            <Divider/>
                            <p>Kod pocztowy: {{ point.postcode }}</p>
                            <Divider v-if="point.delivery_time"/>
                            <p v-if="point.delivery_time">Czas dostawy: {{ point.delivery_time }}</p>
                            <Divider v-if="point.intercom"/>
                            <p v-if="point.intercom">Kod do domofonu: {{ point.intercom }}</p>
                            <Divider v-if="point.last_used_at"/>
                            <p v-if="point.last_used_at">Ostatnie użycie: {{ point.last_used_at }}</p>
                            <Divider/>
                            <p>Data modyfikacji: {{ point.updated_at }}</p>
                            <Divider/>
                            <p>Data utworzenia: {{ point.created_at }}</p>
                        </template>
                        <template #footer>
                            <Button
                                v-if="$page.props.admin"
                                class="p-button-sm"
                                icon="pi pi-pencil"
                                label="Edytuj"
                                @click="edit"
                            />
                            <Button
                                v-if="$page.props.admin"
                                class="p-button-sm p-button-info ml-1"
                                icon="pi pi-map"
                                label="Geolokalizuj"
                                :loading="geolocationInProgress"
                                @click="onGeolocation"
                            />
                            <Button
                                v-if="$page.props.admin"
                                class="p-button-sm p-button-danger ml-1"
                                icon="pi pi-trash"
                                label="Usuń"
                                :loading="deletingModel"
                                @click="deletePoint"
                            />
                        </template>
                    </Card>
                    <Card v-if="point.note" class="mt-3">
                        <template #title>
                            Notatka
                        </template>
                        <template #content>
                            {{ point.note }}
                        </template>
                    </Card>
                    <Card v-if="point.driver_note" class="mt-3">
                        <template #title>
                            Notatka kierowcy
                        </template>
                        <template #content>
                            {{ point.driver_note }}
                        </template>
                    </Card>
                    <Card class="mt-3">
                        <template #title>
                            Geolokalizacja
                        </template>
                        <template #content>
                            <p>Szerokość geograficzna: {{ point.lat ?? 'Brak' }}</p>
                            <Divider/>
                            <p>Długość geograficzna: {{ point.long ?? 'Brak' }}</p>
                            <Divider/>
                            <p>Blokada zmiany: {{ point.lock_geo ? 'Tak' : 'Nie' }}</p>
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
                                        <h5>Trasy</h5>
                                    </div>
                                </template>
                                <Column field="route_id" header="Numer trasy"></Column>
                                <Column field="status" header="Status"></Column>
                                <Column field="delivered_time" header="Data dostarczenia"></Column>
                                <Column header="Opcje">
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
