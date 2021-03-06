<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div class="col-12 lg:col-6">
                    <Card>
                        <template #header>
                            <div class="flex justify-content-between align-content-center p-3">
                                <h5>{{ $t('common.route') }} #{{ viewRoute.id }}</h5>
                                <div>
                                    <Button
                                        class="p-button-sm ml-1 p-button-success"
                                        type="button"
                                        :label="$t('routes.editData')"
                                        @click="edit"
                                        aria-haspopup="true"
                                        aria-controls="overlay_tmenu"
                                    />
                                    <Button
                                        v-if="waypoints.length > 2 && viewRoute.status === 'ok'"
                                        class="p-button-sm ml-1 p-button-help"
                                        type="button"
                                        :label="$t('routes.optimizeRoute')"
                                        @click="openOptimizeDialog"
                                        aria-haspopup="true"
                                        aria-controls="overlay_tmenu"
                                    />
                                    <Button
                                        class="p-button-sm ml-1 p-button-info"
                                        type="button"
                                        :label="$t('routes.cloneRoute')"
                                        :loading="cloningRoute"
                                        @click="clone"
                                        aria-haspopup="true"
                                        aria-controls="overlay_tmenu"
                                    />
                                    <Button
                                        v-if="viewRoute.status !== 'ended'"
                                        class="p-button-sm ml-1 p-button-info"
                                        type="button"
                                        :label="$t('routes.checkStatus')"
                                        :loading="checkingStatus"
                                        @click="checkStatus"
                                        aria-haspopup="true"
                                        aria-controls="overlay_tmenu"
                                    />
                                    <Button
                                        class="p-button-sm ml-1"
                                        :class="viewRoute.status === 'ended' ? 'p-button-success' : 'p-button-warning'"
                                        type="button"
                                        :label="viewRoute.status === 'ended' ? $t('routes.resumeRoute') : $t('routes.endRoute')"
                                        :loading="endingRoute"
                                        @click="endOrResume"
                                        aria-haspopup="true"
                                        aria-controls="overlay_tmenu"
                                    />
                                    <Button
                                        class="p-button-sm ml-1 p-button-danger"
                                        type="button"
                                        :label="$t('common.delete')"
                                        @click="deleteRoute"
                                        aria-haspopup="true"
                                        aria-controls="overlay_tmenu"
                                    />
                                </div>
                            </div>
                        </template>
                        <template #content>
                            <div class="grid">
                                <div class="col-12 md:col-3 p-3">
                                    <div class="font-bold mb-2">{{ $t('common.deliveryTime') }}:</div>
                                    <div>{{ viewRoute.delivery_time }}</div>
                                </div>
                                <div class="col-12 md:col-3 p-3">
                                    <div class="font-bold mb-2">{{ $t('common.driver') }}:</div>
                                    <div>{{ viewRoute.driver?.name ?? 'Brak przypisania' }}</div>
                                </div>
                                <div class="col-12 md:col-3 p-3">
                                    <div class="font-bold mb-2">{{ $t('common.createdAt') }}:</div>
                                    <div>{{ viewRoute.created_at }}</div>
                                </div>
                                <div class="col-12 md:col-3 p-3">
                                    <div class="font-bold mb-2">{{ $t('common.updatedAt') }}:</div>
                                    <div>{{ viewRoute.updated_at }}</div>
                                </div>
                                <div class="col-12 md:col-3 p-3">
                                    <div class="font-bold mb-2">{{ $t('common.status') }}:</div>
                                    <div>{{ viewRoute.status_translated }}</div>
                                </div>
                                <div class="col-12 md:col-3 p-3">
                                    <div class="font-bold mb-2">{{ $t('common.optimization') }}:</div>
                                    <div>{{ viewRoute.optimize_status_translated }}</div>
                                </div>
                                <div class="col-12 md:col-3 p-3">
                                    <div class="font-bold mb-2">{{ $t('routes.estimatedDistance') }}:</div>
                                    <div>{{ viewRoute.distance ?? 'Brak' }}</div>
                                </div>
                                <div class="col-12 md:col-3 p-3">
                                    <div class="font-bold mb-2">{{ $t('routes.estimatedTime') }}:</div>
                                    <div>{{ viewRoute.time ?? 'Brak' }}</div>
                                </div>
                                <div class="col-12 md:col-3 p-3">
                                    <div class="font-bold mb-2">{{ $t('routes.lastDownloadByDriver') }}:</div>
                                    <div>{{ viewRoute.driver_downloaded_at ?? 'Brak pobrań' }}</div>
                                </div>
                                <div v-if="viewRoute.note" class="col-12 p-3">
                                    <div class="font-bold mb-2">{{ $t('common.note') }}</div>
                                    <div>{{ viewRoute.note }}</div>
                                </div>
                                <div
                                    v-if="viewRoute?.driver && viewRoute?.driver?.route !== viewRoute.id && viewRoute.status !== 'ended'"
                                    class="col-12 p-3">
                                    <Button
                                        class="p-button-sm"
                                        type="button"
                                        :label="$t('routes.setAsDriverCurrentRoute')"
                                        :loading="settingCurrentRoute"
                                        @click="setAsCurrentRoute"
                                        aria-haspopup="true"
                                        aria-controls="overlay_tmenu"
                                    />
                                </div>
                            </div>
                        </template>
                    </Card>
                </div>
                <div v-if="viewRoute.status === 'ok'" class="col-12 lg:col-6">
                    <MapWithMarkers
                        ref="map"
                        :api-key="$page.props.google_maps_api_key"
                        :markers="waypoints"
                    />
                </div>

                <div v-if="addFromDBCard" class="col-12">
                    <RouteAddPointsFromDB
                        :route-id="viewRoute.id"
                        @pointAdded="reloadWaypoints"
                    />
                </div>

                <div class="col-12">
                    <Card>
                        <template #content>
                            <DataTable
                                class="p-datatable-sm"
                                :value="routeWaypoints"
                                :loading="loadingWaypoints"
                                responsiveLayout="scroll"
                                :reorderableColumns="true"
                                striped-rows
                                data-key="id"
                                selection-mode="multiple"
                                :meta-key-selection="false"
                                v-model:selection="selectedWaypoints"
                                @rowReorder="onRowReorder"
                            >
                                <template #header>
                                    <div class="flex justify-content-between align-content-center">
                                        <h5>{{ $t('common.waypoints') }}</h5>
                                        <div>
                                            <Button
                                                class="p-button-sm ml-1 p-button-info"
                                                type="button"
                                                :label="$t('common.view')"
                                                @click="show"
                                                aria-haspopup="true"
                                                aria-controls="overlay_tmenu"
                                            />
                                            <Button
                                                class="p-button-sm ml-1 p-button-info"
                                                type="button"
                                                :label="$t('common.export')"
                                                @click="exportXlsx"
                                                aria-haspopup="true"
                                                aria-controls="overlay_tmenu"
                                            />
                                            <Button
                                                v-if="viewRoute.status !== 'ended'"
                                                class="p-button-sm ml-1 p-button-help"
                                                type="button"
                                                :label="$t('common.geocode')"
                                                @click="geocodeAll"
                                                aria-haspopup="true"
                                                aria-controls="overlay_tmenu"
                                            />
                                            <Button
                                                v-if="viewRoute.status !== 'ended'"
                                                class="p-button-sm ml-1"
                                                type="button"
                                                :label="$t('common.selected')"
                                                @click="toggleSelectedMenu"
                                                aria-haspopup="true"
                                                aria-controls="overlay_tmenu"
                                            />
                                            <TieredMenu
                                                id="overlay_tmenu"
                                                ref="selectedMenu"
                                                :model="selectedMenu"
                                                :popup="true"
                                            />
                                            <Button
                                                v-if="viewRoute.status !== 'ended'"
                                                class="p-button-sm ml-1"
                                                type="button"
                                                :label="$t('points.addPoint')"
                                                @click="toggleAddMenu"
                                                aria-haspopup="true"
                                                aria-controls="overlay_tmenu"
                                            />
                                            <TieredMenu
                                                id="overlay_tmenu"
                                                ref="addMenu"
                                                :model="addMenu"
                                                :popup="true"
                                            />
                                        </div>
                                    </div>
                                </template>
                                <Column :rowReorder="true" headerStyle="width: 3rem" :reorderableColumn="false"/>
                                <Column field="stop_number" :header="$t('common.stopNumber')"></Column>
                                <Column field="name" :header="$t('common.name')"></Column>
                                <Column field="address" :header="$t('common.address')"></Column>
                                <Column field="city" :header="$t('common.city')"></Column>
                                <Column field="geocoded" header="GEO">
                                    <template #body="slotProps">
                                        <i class="pi"
                                           :class="{'pi-check': slotProps.data.geocoded, 'pi-times': !slotProps.data.geocoded}"/>
                                    </template>
                                </Column>
                                <Column field="quantity" :header="$t('common.options')">
                                    <template #body="slotProps">
                                        <Button
                                            icon="pi pi-eye"
                                            class="p-button-info p-button-sm mr-1"
                                            @click="showPoint(slotProps.data.point_id)"
                                        />
                                    </template>
                                </Column>
                                <template #empty>
                                    {{ $t('routes.emptyWaypoints') }}
                                </template>
                            </DataTable>
                        </template>
                        <template #footer>
                            <Button
                                v-if="viewRoute.status !== 'ended'"
                                :label="$t('routes.saveOrder')"
                                class="p-button-sm"
                                :loading="reorderForm.processing"
                                @click="saveOrder"
                            />
                        </template>
                    </Card>
                </div>
            </div>

            <div class="speeddial-circle-demo">
                <SpeedDial
                    v-if="viewRoute.status !== 'ended'"
                    :model="selectedMenu"
                    :radius="120"
                    direction="up-left"
                    type="quarter-circle"
                    buttonClass="p-button-success"
                    :tooltipOptions="{position: 'left'}"
                    showIcon="pi pi-ellipsis-h" hideIcon="pi pi-times"
                />
            </div>


            <Dialog
                :header="$t('routes.importXlsxFile')"
                v-model:visible="importXlsxDialog"
                :closable="false"
                modal
            >
                <FileUpload
                    mode="basic"
                    :maxFileSize="1000000"
                    :custom-upload="true"
                    @select="onUpload"
                />
                <small v-if="importForm.errors.file" class="p-invalid">
                    {{ importForm.errors.file }}
                </small>

                <template #footer>
                    <Button
                        :label="$t('common.cancel')"
                        @click="closeImportXlsxDialog"
                        class="p-button-text"
                    />
                    <Button
                        :label="$t('common.import')"
                        :loading="importForm.processing"
                        @click="importXlsx"
                    />
                </template>
            </Dialog>

            <Dialog
                :header="$t('routes.moveWaypointsToAnotherRoute')"
                v-model:visible="moveWaypointsDialog"
                :closable="false"
                modal
            >
                <div class="field">
                    <label>{{ $t('routes.targetRoute') }}</label>
                    <RouteAutoComplete
                        id="move_points_route_id"
                        class="w-full"
                        v-model="moveWaypointsForm.route"
                    />
                    <small v-if="moveWaypointsForm.errors.route" class="p-invalid">
                        {{ moveWaypointsForm.errors.route }}
                    </small>
                </div>
                <template #footer>
                    <Button
                        :label="$t('common.cancel')"
                        @click="closeMoveWaypointsDialog"
                        class="p-button-text"
                    />
                    <Button
                        :label="$t('common.submit')"
                        :loading="moveWaypointsForm.processing"
                        @click="moveWaypoints"
                    />
                </template>
            </Dialog>

            <DeleteDialog
                ref="deleteDialog"
                v-model:visible="deleteDialog"
                :loading="deletingModel"
                @delete="onDelete"
            />

            <RouteEditModal
                v-model:visible="editDialog"
                :model="viewRoute"
            />

            <RouteOptimizeModal
                v-model:visible="optimizeDialog"
                :route-id="viewRoute.id"
                @optimized="reloadWaypoints"
            />
        </AppLayout>
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import Card from "primevue/card";
import Divider from "primevue/divider";
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import DeleteDialog from "../../Components/DeleteDialog";
import TieredMenu from "primevue/tieredmenu";
import Dialog from "primevue/dialog";
import FileUpload from "primevue/fileupload";
import MapWithMarkers from "../../Components/MapWithMarkers";
import RouteEditModal from "../../Components/RouteEditModal";
import RouteOptimizeModal from "../../Components/RouteOptimizeModal";
import FlashMessage from "../../Services/FlashMessage";
import RouteAddPointsFromDB from "../../Components/RouteAddPointsFromDB";
import SpeedDial from 'primevue/speeddial';
import RouteAutoComplete from "../../Components/RouteAutoComplete";

export default {
    name: "Edit",
    components: {
        RouteAutoComplete,
        RouteAddPointsFromDB,
        RouteOptimizeModal,
        RouteEditModal,
        MapWithMarkers,
        AppLayout,
        Card,
        Divider,
        Button,
        DeleteDialog,
        DataTable,
        Column,
        TieredMenu,
        Dialog,
        FileUpload,
        SpeedDial
    },
    props: {
        viewRoute: {
            type: Object,
            required: true
        },
        waypoints: {
            type: Array,
            required: true
        }
    },
    mixins: [FlashMessage],
    data() {
        return {
            routeWaypoints: this.waypoints,
            loadingWaypoints: false,
            selectedWaypoints: null,
            addFromDBCard: false,
            reorderForm: this.$inertia.form({
                waypoints: null
            }),
            selectedMenu: [
                {
                    label: this.$t('common.selectAll'),
                    icon: 'pi pi-fw pi-check-circle',
                    command: () => this.selectAll()
                },
                {
                    label: this.$t('common.deselectAll'),
                    icon: 'pi pi-fw pi-circle-off',
                    command: () => this.deselectAll()
                },
                {
                    label: this.$t('common.moveWaypoints'),
                    icon: 'pi pi-fw pi-reply',
                    command: () => {
                        this.moveWaypointsDialog = true;
                    }
                },
                {
                    label: this.$t('common.deleteSelected'),
                    icon: 'pi pi-fw pi-trash',
                    command: () => this.deleteSelected()
                }
            ],
            addMenu: [
                {
                    label: this.$t('common.fromDatabase'),
                    icon: 'pi pi-fw pi-table',
                    command: () => {
                        this.addFromDBCard = true;
                    }
                },
                {
                    label: this.$t('common.importXlsx'),
                    icon: 'pi pi-fw pi-file',
                    command: () => this.openImportXlsxDialog()
                }
            ],
            importXlsxDialog: false,
            importForm: this.$inertia.form({
                file: null
            }),
            deleteDialog: false,
            deletingModel: false,
            editDialog: false,
            cloningRoute: false,
            optimizeDialog: false,
            checkingStatus: false,
            endingRoute: false,
            settingCurrentRoute: false,
            moveWaypointsDialog: false,
            moveWaypointsForm: this.$inertia.form({
                route: null,
                waypoints: null
            }),
        }
    },
    methods: {
        onRowReorder(event) {
            this.routeWaypoints = event.value;
        },
        saveOrder() {
            const ids = this.routeWaypoints.map((waypoint) => {
                return waypoint.id;
            });

            this.$inertia.post(this.route('routes.waypoints.order', this.viewRoute.id), {
                waypoint_ids: ids
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    this.flashSuccess(this.$t('routes.orderSaved'));
                    this.reloadWaypoints();
                }
            })
        },
        selectAll() {
            this.selectedWaypoints = this.routeWaypoints;
        },
        deselectAll() {
            this.selectedWaypoints = [];
        },
        deleteSelected() {
            const ids = this.selectedWaypoints.map((waypoint) => {
                return waypoint.id;
            });

            this.$inertia.post(this.route('routes.waypoints.destroy', this.viewRoute.id), {
                waypoint_ids: ids
            }, {
                onSuccess: () => {
                    this.deselectAll();
                    this.flashSuccess(this.$t('routes.waypointsDeleted'));
                    this.reloadWaypoints();
                }
            });
        },
        toggleSelectedMenu(event) {
            this.$refs.selectedMenu.toggle(event);
        },
        toggleAddMenu(event) {
            this.$refs.addMenu.toggle(event);
        },
        openImportXlsxDialog() {
            this.importXlsxDialog = true;
        },
        onUpload(event) {
            if (event.files && event.files[0]) {
                this.importForm.file = event.files[0];
            }
        },
        importXlsx() {
            this.importForm.post(this.route('routes.import-file', this.viewRoute.id), {
                onSuccess: () => {
                    this.closeImportXlsxDialog();
                    this.flashSuccess(this.$t('routes.waypointsImported'));
                    this.reloadWaypoints();
                }
            })
        },
        closeImportXlsxDialog() {
            this.importXlsxDialog = false;
        },
        reloadWaypoints() {
            this.loadingWaypoints = true;
            this.$inertia.reload({
                only: ['waypoints'], onSuccess: () => {
                    this.routeWaypoints = this.waypoints;
                    this.loadingWaypoints = false;
                    this.$refs.map.reloadMarkers();
                }
            });
        },
        geocodeAll() {
            this.$inertia.post(this.route('routes.waypoints.geocode', this.viewRoute.id), {}, {
                onSuccess: () => {
                    this.reloadWaypoints();
                    this.flashSuccess(this.$t('routes.waypointsGeocoded'));
                }
            })
        },
        show() {
            this.$inertia.get(this.route('routes.show', this.viewRoute.id));
        },
        deleteRoute() {
            this.deleteDialog = true;
        },
        onDelete() {
            this.deletingModel = true;
            this.$inertia.delete(this.route('routes.destroy', this.viewRoute.id), {
                onSuccess: () => {
                    this.flashSuccess(this.$t('routes.routeDeleted'));
                    this.deletingModel = false;
                }
            })
        },
        openOptimizeDialog() {
            this.optimizeDialog = true;
        },
        edit() {
            this.editDialog = true;
        },
        clone() {
            this.cloningRoute = true;
            this.$inertia.post(this.route('routes.clone', this.viewRoute.id), {}, {
                onSuccess: () => {
                    this.flashSuccess(this.$t('routes.routeCloned'));
                    this.cloningRoute = false;
                }
            })
        },
        checkStatus() {
            this.checkingStatus = true;
            this.$inertia.post(this.route('routes.check-status', this.viewRoute.id), {}, {
                onSuccess: () => {
                    this.flashSuccess(this.$t('routes.statusUpdated'));
                    this.checkingStatus = false;
                }
            })
        },
        endOrResume() {
            this.endingRoute = true;
            this.$inertia.post(this.route('routes.end-resume', this.viewRoute.id), {}, {
                onSuccess: () => {
                    this.flashSuccess(this.$t('routes.routeEndedOrResumed'));
                    this.endingRoute = false;
                }
            })
        },
        setAsCurrentRoute() {
            this.settingCurrentRoute = true;
            this.$inertia.post(this.route('routes.set-as-current', this.viewRoute.id), {}, {
                onSuccess: () => {
                    this.flashSuccess(this.$t('routes.routeSetAsCurrent'));
                    this.settingCurrentRoute = false;
                }
            })
        },
        showPoint(id) {
            this.$inertia.get(this.route('points.show', id));
        },
        exportXlsx() {
            window.open(this.route('routes.export-xlsx', this.viewRoute.id), '_blank');
        },
        moveWaypoints() {
            this.moveWaypointsForm.waypoints = this.selectedWaypoints;
            this.moveWaypointsForm.post(this.route('routes.move-waypoints'), {
                onSuccess: () => {
                    this.deselectAll();
                    this.flashSuccess(this.$t('routes.waypointsMoved'));
                    this.closeMoveWaypointsDialog();
                    this.reloadWaypoints();
                }
            })
        },
        closeMoveWaypointsDialog() {
            this.moveWaypointsForm.clearErrors();
            this.moveWaypointsForm.reset();
            this.moveWaypointsDialog = false;
        }
    }
}
</script>

<style lang="scss" scoped>
::v-deep(.speeddial-circle-demo) {
    .p-speeddial-circle {
        top: calc(50% - 2rem);
        left: calc(50% - 2rem);
    }

    .p-speeddial-semi-circle {
        &.p-speeddial-direction-up {
            left: calc(50% - 2rem);
            bottom: 0;
        }

        &.p-speeddial-direction-down {
            left: calc(50% - 2rem);
            top: 0;
        }

        &.p-speeddial-direction-left {
            right: 0;
            top: calc(50% - 2rem);
        }

        &.p-speeddial-direction-right {
            left: 0;
            top: calc(50% - 2rem);
        }
    }

    .p-speeddial-quarter-circle {
        &.p-speeddial-direction-up-left {
            position: fixed;
            right: 50px;
            bottom: 50px;
        }

        &.p-speeddial-direction-up-right {
            left: 0;
            bottom: 0;
        }

        &.p-speeddial-direction-down-left {
            right: 0;
            top: 0;
        }

        &.p-speeddial-direction-down-right {
            left: 0;
            top: 0;
        }
    }
}
</style>
