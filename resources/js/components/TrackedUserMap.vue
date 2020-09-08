<template>
    <div class="flex-grow h-full">
        <div v-show="loading" class="flex flex-col items-center justify-center flex-grow h-full">
            <font-awesome-icon icon="circle-notch" size="3x" spin></font-awesome-icon>
        </div>
        <div v-show="!loading" class="h-full flex flex-col lg:flex-row">
            <div class="h-full lg:w-3/4">
                <div class="w-full goldenRationPad" id="map"></div>
                <div class="w-full h-auto pt-12 pl-16 pr-16">
                    <vue-slider
                        v-model="timeSelection.selected"
                        :min="timeSelection.startTime"
                        :max="timeSelection.endTime"
                        :interval="timeSelection.stepSize"
                        :tooltip-formatter="timeSelection.formatter">
                    </vue-slider>
                </div>
            </div>
            <div class="h-full lg:w-1/4">
                <user-selection
                    :users="this.users"
                    checkbox_column_identifier="map.show"
                    user_column_identifier="users.id"
                    v-on:box-clicked="userToggled($event)"
                ></user-selection>
            </div>
        </div>
    </div>
</template>

<script>
    import { LMap, LTileLayer, LMarker, LPopup} from 'vue2-leaflet';
    import VueSlider from 'vue-slider-component';
    import gql from 'graphql-tag';
    import UserSelection from "@/components/UserSelection";

    export default {
        name: 'TrackedUserMap',
        components: {
            UserSelection,
            LMap,
            LTileLayer,
            LMarker,
            LPopup,
            VueSlider,
        },
        data() {
            return {
                users: [],
                loading: true,
                timeSelection: {
                    selected: 0,
                    startTime: 0,
                    endTime: 0,
                    stepSize: 20 * 60,
                    formatter: v => `${new Date(v*1000).toISOString()}`,
                    timeIndexArea: 1
                },
                iconWithoutShadow: null,
                map: null,
                tileLayer: null,
                markerLayer: null,
                timeLineMarkers: [],
                mutex: false
            };
        },

        apollo: {
            allCoords: {
                    query: gql`query {
                        allCoords{
                            u
                            t
                            lo
                            la
                        }
                }`,
            }
        },
        mounted() {
            this.iconWithoutShadow = new L.Icon.Default();
            this.iconWithoutShadow.options.shadowSize = [0,0];
            this.initMap();
        },
        methods: {
            initMap() {
                this.map = L.map('map').setView([49.878708, 8.646927], 13);
                this.tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    zoomSnap: 0.5,
                    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
                });

                this.markerLayer = L.layerGroup();
                this.tileLayer.addTo(this.map);
                this.markerLayer.addTo(this.map);
            },

            queueMarkerUpdate() {
                if (this.mutex) return;
                this.mutex = true;
                this.updateShownMarkers();
                this.mutex = false;
            },
            async updateShownMarkers() {
                this.markerLayer.clearLayers();

                let start = this.timeToIndex(this.timeSelection.selected) - this.timeSelection.timeIndexArea;
                start = start >= 0 ? start : 0;

                let end = start + this.timeSelection.timeIndexArea * 2;
                end = end <= this.timeLineMarkers.length ? end : this.timeLineMarkers.length;

                let markers = []
                for (let i = start; i < end; i++) {
                    markers.push(...this.timeLineMarkers[i]);
                }

                let diff;
                for (let m of markers) {
                    if (!m.user.selected) continue;

                    diff = Math.abs(this.timeSelection.selected - m.time);
                    if (diff < 900) {
                        m.marker.setOpacity(1);
                    } else if(diff < 1800) {
                        m.marker.setOpacity(0.75);
                    } else if(diff < 2000) {
                        m.marker.setOpacity(0.5);
                    } else if(diff < 3000) {
                        m.marker.setOpacity(0.25);
                    } else {
                        continue;
                    }

                    this.markerLayer.addLayer(m.marker);
                }
            },

            userToggled: function(event) {
                event.user.selected = event.checked;
                this.queueMarkerUpdate();
            },

            addNewUser(user) {
                let index = this.users.length;
                this.users.push({
                    id: user,
                    value: index,
                    label: user,
                    selected: false,
                });
                return index;
            },

            addNewMarker(dataPoint, user) {
                let marker = L.marker([dataPoint.la, dataPoint.lo], {
                    icon: this.iconWithoutShadow,
                }).bindPopup(dataPoint.u + ": " + dataPoint.t);

                this.addMarkerToTimeline(dataPoint.t, user, marker);
            },

            timeToIndex(time) {
                return Math.round((time - this.timeSelection.startTime) / this.timeSelection.stepSize);
            },

            addMarkerToTimeline(time, user, marker) {
                let timeLineIndex = this.timeToIndex(time);
                this.timeLineMarkers[timeLineIndex].push({
                    user: user,
                    marker: marker,
                    time: time,
                });
            }
        },
        computed: {
        },
        watch: {
            allCoords: {
                handler(coords) {
                    if (coords === null) {
                        return;
                    } else if(coords.length === 0) {
                        this.loading = false;
                        return;
                    }

                    this.timeSelection.endTime = coords[coords.length - 1].t
                        - (coords[coords.length - 1].t % this.timeSelection.stepSize)
                        + this.timeSelection.stepSize;

                    this.timeSelection.startTime = coords[0].t - (coords[0].t % this.timeSelection.stepSize);

                    this.timeSelection.selected = this.timeSelection.startTime;

                    this.edgesTimeline = Array.from(Array(this.timeToIndex(this.timeSelection.endTime) + 1),() => []);

                    let userIndex = 0;
                    for (let p of coords) {
                        userIndex = this.users.findIndex(o => o.id === p.u);
                        if (userIndex === -1) {
                            userIndex = this.addNewUser(p.u);
                        }
                        this.addNewMarker(p, this.users[userIndex]);

                    }
                    this.queueMarkerUpdate();
                    this.loading = false;
                }
            },
            timeSelection: {
                deep: true,
                handler() {
                    this.queueMarkerUpdate();
                }
            }
        },
    };
</script>

<style scoped>
    .goldenRationPad {
        padding-top: 61.803398875%;
    }
</style>
