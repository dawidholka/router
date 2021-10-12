<template>
    <div class="map"/>
</template>

<script>
import GoogleMapsService from '../Services/GoogleMapsService'

export default {
    name: "MapWithMarkers",
    props: {
        apiKey: {
            type: String,
            required: true
        },
        markers: {
            type: Array,
            required: true
        },
        driverLocations: {
            type: Array,
            default: () => [],
        }
    },
    createdMarkers: [],
    data() {
        return {
            createdMap: null,
        }
    },
    async mounted() {
        try {
            const google = await GoogleMapsService(this.apiKey);
            const geocoder = new google.maps.Geocoder();
            const map = new google.maps.Map(this.$el, {
                zoom: 6,
                center: {
                    lat: parseFloat(this.markers[0].lat),
                    lng: parseFloat(this.markers[0].lng)
                },
                mapTypeControl: false,
                streetViewControl: false
            });

            this.createdMap = map;

            this.setMarkers(map);

            if (this.driverLocations.length) {
                this.setDriverLocations(map);
            }
        } catch (error) {
            console.error(error);
        }
    },
    methods: {
        setMarkers(map) {
            this.createdMarkers = [];

            for (let i = 0; i < this.markers.length; i++) {
                const mapWaypoint = this.markers[i];
                const marker = new google.maps.Marker({
                    position: {lat: parseFloat(mapWaypoint.lat), lng: parseFloat(mapWaypoint.lng)},
                    map: map,
                    label: mapWaypoint.stop_number.toString(),
                    title: mapWaypoint.id.toString(),
                    zIndex: parseInt(mapWaypoint.stop_number, 10),
                    icon: {
                        url: this.route('map-pin', mapWaypoint.color),
                        labelOrigin: new google.maps.Point(12, 14)
                    }
                });
                const infoWindow = new google.maps.InfoWindow({
                    content: mapWaypoint.address,
                });
                marker.addListener('click', () => {
                    infoWindow.open({
                        anchor: marker,
                        map,
                        shouldFocus: false,
                    })
                })

                this.createdMarkers.push(marker);
            }
        },
        clearMarkers() {
            for (let i = 0; i < this.createdMarkers.length; i++) {
                this.createdMarkers[i].setPosition(null);
                this.createdMarkers[i].setMap(null);
                this.createdMarkers[i] = null;
            }
            this.createdMarkers = [];
        },
        reloadMarkers() {
            this.clearMarkers();
            this.setMarkers(this.createdMap);
        },
        setDriverLocations(map) {
            const driverPath = new google.maps.Polyline({
                path: this.driverLocations,
                geodesic: true,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 2,
            });

            driverPath.setMap(map);

            const driverPin = new google.maps.Marker({
                position: {
                    lat: parseFloat(this.driverLocations[0].lat),
                    lng: parseFloat(this.driverLocations[0].lng),
                },
                icon: {
                    url: '/images/driver.svg',
                    labelOrigin: new google.maps.Point(12, 14)
                }
            });

            driverPin.setMap(map);

        }
    }
}
</script>

<style scoped>
.map {
    width: 100%;
    height: 500px;
}
</style>
