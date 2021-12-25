<template>
    <div>
        <AppLayout>
            <div class="speeddial-circle-demo">
                <Button
                    v-if="mapMode === 'ready'"
                    class="zones-editor__start-edit-btn"
                    label="Edit Zones"
                    @click="startZonesEdit"
                />
            </div>

            <google-map
                ref="mapRef"
                class="google-map-wrapper"
                :center="mapCenter"
                :api-key="$page.props.google_maps_api_key"
                :zoom="7"
                map-type-id="roadmap"
                style="display:flex; justify-content: center; align-items: flex-start;"
                :options="{
                    zoomControl: true,
                    mapTypeControl: false,
                    scaleControl: false,
                    streetViewControl: false,
                    rotateControl: false,
                    fullscreenControl: false,
                    disableDefaultUi: false,
                    draggable: mapDraggable,
                    draggableCursor: mapCursor
                  }"
            >
                <template #visible>
                    <drawing-manager
                        ref="drawManager"
                        :map-mode="mapMode"
                        :rectangle-options="rectangleOptions"
                        :circle-options="circleOptions"
                        :polyline-options="polylineOptions"
                        :polygon-options="polygonOptions"
                        :shapes="shapes"
                    >
                        <template v-slot="on">
                            <DrawingToolbar
                                v-if="mapMode==='edit'"
                                @drawingmode_changed="on.setDrawingMode($event)"
                                @delete_selection="on.deleteSelection()"
                                @save="saveZones"
                            />
                        </template>
                    </drawing-manager>
                </template>
            </google-map>
        </AppLayout>
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import GoogleMap from "../../Components/GoogleMaps/GoogleMap";
import DrawingManager from "../../Components/GoogleMaps/DrawingManager";
import SpeedDial from "primevue/speeddial";
import Button from "primevue/button";
import DrawingToolbar from "../../Components/GoogleMaps/DrawingToolbar";
import {getCoordsFromPolygon} from "../../Components/GoogleMaps/utils/helpers";


export default {
    name: "Create",
    components: {
        DrawingManager,
        GoogleMap,
        SpeedDial,
        Button,
        AppLayout,
        DrawingToolbar
    },
    props: {
        zones: {
            type: Array,
            default: null
        },
        mapCenter: {
            type: Object,
            default: {lat: 0, lng: 0},
        }
    },
    data() {
        return {
            mapMode: null,
            mapDraggable: true,
            mapCursor: null,
            shapes: this.mapZonesToShapes(),
            rectangleOptions: {
                fillColor: '#777',
                fillOpacity: 0.4,
                strokeWeight: 2,
                strokeColor: '#999',
                draggable: false,
                editable: false,
                clickable: true
            },
            circleOptions: {
                fillColor: '#777',
                fillOpacity: 0.4,
                strokeWeight: 2,
                strokeColor: '#999',
                draggable: false,
                editable: false,
                clickable: true
            },
            polylineOptions: {
                fillColor: '#777',
                fillOpacity: 0.4,
                strokeWeight: 2,
                strokeColor: '#999',
                draggable: false,
                editable: false,
                clickable: true
            },
            polygonOptions: {
                fillColor: '#777',
                fillOpacity: 0.4,
                strokeWeight: 2,
                strokeColor: '#999',
                draggable: false,
                editable: false,
                clickable: true
            },
            zonesForm: this.$inertia.form({
                zones: null
            })
        }
    },
    methods: {
        startZonesEdit() {
            this.mapMode = 'edit';
        },
        saveZones() {
            this.mapMode = 'ready';
            const finalShapes = this.$refs.drawManager.finalShapes;
            const zones = [];

            for (let i = 0; i < finalShapes.length; i++) {
                zones.push({
                    id: finalShapes[i]?.id,
                    coords: getCoordsFromPolygon(finalShapes[i].overlay)
                })
            }

            this.zonesForm.zones = zones;

            this.zonesForm.post(this.route('zones.bulk-update'), {
                onSuccess: () => {
                    console.info('saved');
                }
            })
        },
        mapZoneToShape(zone) {
            return {
                id: zone.id,
                type: "polygon",
                name: zone.name,
                overlay: {
                    paths: zone.coords,
                    strokeColor: zone.color,
                    strokeOpacity: 0.8,
                    strokeWeight: 1,
                    fillColor: zone.color,
                    fillOpacity: 0.35
                }
            };
        },
        mapZonesToShapes() {
            const shapes = [];

            for (let i = 0; i < this.zones.length; i++) {
                shapes.push(this.mapZoneToShape(this.zones[i]));
            }

            return shapes;
        }
    },
    watch: {
        mapMode(newMode, oldMode) {
            if (newMode === 'ready') {
                if (oldMode === 'edit') {
                    this.mapDraggable = true;
                    this.mapCursor = null;
                    return;
                }
            }

            if (newMode === 'edit') {
                this.mapDraggable = false;
                this.mapCursor = 'default';
            }
        }
    },
    mounted() {
        this.mapMode = 'ready';
    }
}
</script>

<style lang="scss" scoped>
.google-map-wrapper {
    height: 100vh;
    width: 100vw;
    top: 0;
    left: 0;
    position: absolute;
}

.zones-editor__start-edit-btn {
    position: absolute;
    z-index: 1000;
}

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
