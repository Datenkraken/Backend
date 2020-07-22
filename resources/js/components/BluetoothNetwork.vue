<template>
    <div class="h-full flex flex-col lg:flex-row">
        <div class="h-full lg:w-3/4">
            <div class="w-full h-auto bg-bgsecondary">
                <network
                    :nodes="nodes"
                    :edges="edges"
                    :options="options">
                </network>
            </div>
            <div class="w-full h-auto pt-8 pl-8 pr-8">
                <div class="bg-gray-400">
                    <vue-slider
                        v-model="timeScale.selected"
                        :min="timeScale.startTime"
                        :max="timeScale.endTime"
                        :interval="timeScale.stepSize"
                        :tooltip-formatter="timeScale.formatter"
                        :enable-cross="false"
                        :dotSize="28"
                        :height="8">
                    </vue-slider>
                </div>
            </div>
        </div>
        <div class="h-full lg:w-1/4">
            <table>
                <thead>
                <tr>
                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-900 border-b-2 border-gray-300">
                        {{$lang.get('map.show')}}
                    </th>
                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-900 border-b-2 border-gray-300">
                        {{$lang.get('map.user-id')}}
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="user in users"
                    :key="user"
                    class="hover:bg-gray-200 border-b border-gray-300"
                >
                    <td class="py-4 px-6">
                        <label>
                            <input @click="boxChecked(user)" class="form-checkbox" type="checkbox" name="select-box" />
                        </label>
                    </td>
                    <td class="py-4 px-6">
                        {{ user }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
    import {Network} from 'vue-vis-network';
    import VueSlider from 'vue-slider-component';
    import gql from 'graphql-tag';

    export default {
        name: "BluetoothNetwork",
        components: {
            Network,
            VueSlider
        },
        data() {
            return {
                nodes: [],
                edges: [],
                dev: [],
                scans: [],
                scan_matrix: [],
                users: [],
                timeScale: {
                    selected: [0,0],
                    startTime: 0,
                    endTime: 0,
                    stepSize: 20 * 60,
                    formatter: v => `${new Date(v * 1000).toISOString()}`,
                },
                options: {
                    width: '100%',
                    height: '800px',
                    nodes: {
                        shape: "dot",
                        mass: 1,
                        borderWidth: 4,
                        size: 12,
                        physics: true,
                        font: {
                            color: "#f56565",
                        },
                        color: {
                            border: "#2D3748",
                        }
                    },
                    edges: {
                        physics: false
                    }
                }
            };
        },

        apollo: {
            bluetoothDeviceScanned: gql`query{
                bluetoothDeviceScanned {
                    scans {
                        t
                        u
                        d
                        k
                    }
                    users
                    devices {
                        a
                        n
                    }
                }
            }`
        },
        methods: {
            strToRGB(str) {
                const m = 0x80000000;
                const a = 1103515245;
                const c = 12345;
                let hash = 7;

                for (let i = 0; i < str.length; i++) {
                    hash = str.charCodeAt(i) + (hash << 5 - hash);
                }

                hash = (a * hash + c) % m;
                const pat = (hash & 0x00FFFFFF).toString(16).toUpperCase()
                return "#000000".substring(0, 7 - pat.length) + pat;
            },

            addTimeStampToMatrix(n1, n2, timestamp) {
                if (n1 === n2) return;
                let modifier = (n1 < n2);
                if (!modifier) {
                    let n3 = n1;
                    n1 = n2;
                    n2 = n3;
                }
                this.scan_matrix[n1][n2].push([timestamp, modifier]);
            },

            computeMatrix() {
                let devScans = [];
                let debugCounter = 0;
                for (let s of this.scans) {
                    if (devScans.length < s.d || devScans[s.d] === null || devScans[s.d] === undefined) {
                        devScans[s.d] = new Set([s.u]);
                        debugCounter++;
                    } else if (!devScans[s.d].has(s.u)) {
                        devScans[s.d].add(s.u);
                        debugCounter++;
                    }
                }

                for (let s of this.scans) {
                    for (let n of devScans[s.d]) {
                        this.addTimeStampToMatrix(s.u, n, s.t);
                    }
                }
                this.scans = [];
            },

            addToEdgesInInterval(n1, n2, startTime, endTime) {
                let index = 0;
                while (index < this.scan_matrix[n1][n2].length && this.scan_matrix[n1][n2][index][0] < startTime) {
                    index++;
                }
                if (this.scan_matrix[n1][n2].length <= index
                    || this.scan_matrix[n1][n2][index][0] < startTime
                    || this.scan_matrix[n1][n2][index][0] > endTime) return;
                let edgeValue = 1;
                let modifier = null;
                while (index < this.scan_matrix[n1][n2].length && this.
                    scan_matrix[n1][n2][index][0] < endTime) {
                    edgeValue += modifier !== this.scan_matrix[n1][n2][1] ? 1 : 0.25
                    modifier = this.scan_matrix[n1][n2][1];
                    index++;
                }
                this.edges.push({
                    from: this.users[n1],
                    to: this.users[n2],
                    value: edgeValue,
                    physics: false
                })
            },

            computeEdgesInInterval(startTime, endTime) {
                this.edges = [];
                for (let i = 0; i < this.scan_matrix.length; i++) {
                    for (let j = i + 1; j < this.scan_matrix.length; j++) {
                        this.addToEdgesInInterval(i, j, startTime, endTime);
                    }
                }
            },

            boxChecked(user) {
                let index = this.nodes.findIndex(el => el.id === user);
                this.nodes[index].physics = !this.nodes[index].physics;
                this.nodes[index].hidden = !this.nodes[index].hidden;
            }
        },
        functions: {

        },
        watch: {
            bluetoothDeviceScanned: {
                handler(transformedData) {
                    if (transformedData === null) {
                        return;
                    }
                    this.scan_matrix = [transformedData.users.length];
                    for (let i = 0; i < transformedData.users.length; i++) {
                        this.scan_matrix[i] = [transformedData.users.length];
                        for (let j = 0; j < transformedData.users.length; j++) {
                            this.scan_matrix[i][j] = [];
                        }
                    }

                    for (let u of transformedData.users) {
                        this.nodes.push({
                            id: u,
                            label: u,
                            physics: false,
                            hidden: true,
                            color: {
                                background:  this.strToRGB(u),
                            },
                        })
                    }

                    this.users = transformedData.users;
                    this.scans = transformedData.scans;

                    this.timeScale.endTime = transformedData.scans[transformedData.scans.length - 1].t
                        - (transformedData.scans[transformedData.scans.length - 1].t % this.timeScale.stepSize)
                        + this.timeScale.stepSize;

                    this.timeScale.selected[1] = this.timeScale.endTime;
                    this.timeScale.selected[0] = this.timeScale.startTime;

                    this.timeScale.startTime = transformedData.scans[0].t - (transformedData.scans[0].t % this.timeScale.stepSize);

                    this.computeMatrix();
                    this.computeEdgesInInterval(this.timeScale.selected[0], this.timeScale.selected[1]);
                    transformedData.scans = [];
                    transformedData.users = [];
                    transformedData.devices = [];
                }
            },
            timeScale: {
                deep: true,
                handler() {
                    this.computeEdgesInInterval(this.timeScale.selected[0], this.timeScale.selected[1]);
                }
            }
        }
    };

</script>

<style scoped>
    @import '~vue-slider-component/theme/antd.css';
    div.vis-network {
        background-color: red;
    }
</style>
