<template>
 <div class="h-full w-full">
     <d3-network
         :net-nodes="nodes"
         :net-links="edges"
         :options="options">

     </d3-network>
 </div>
</template>

<script>
    import D3Network from 'vue-d3-network';
    import gql from 'graphql-tag';

    export default {
        name: "BluetoothNetwork",
        components: {
            D3Network
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
                    startTime: null,
                    endTime: null,
                    stepSize: 20 * 60 * 1000,
                },
                options: {
                    nodeSize: 30,
                    nodeLabels: true,
                    force: 10000,
                    linkWidth: 0,
                    linkLabels: true,
                    canvas: false
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
                console.log("computing Matrix...");
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

                console.log("got " + devScans.length + " devices, created " + debugCounter + "data points, urghs..")
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
                while (index < this.scan_matrix[n1][n2].length && this.scan_matrix[n1][n2][index][0] < endTime) {
                    edgeValue++; //TODO: Weighting
                    index++;
                }
                this.edges.push({
                    id: n1 + ":" + n2,
                    name: "",
                    tid: this.users[n1],
                    sid: this.users[n2],
                    _svgAttrs: {
                        'stroke-width': edgeValue
                    }
                })
            },
            computeEdgesInInterval(startTime, endTime) {
                console.log("computing edges for Interval: " + startTime + " - " + endTime + " with an matrix size of " + this.scan_matrix.length)
                for (let i = 0; i < this.scan_matrix.length; i++) {
                    for (let j = i + 1; j < this.scan_matrix.length; j++) {
                        this.addToEdgesInInterval(i, j, startTime, endTime);
                    }
                }
            },
        },
        functions: {

        },
        watch: {
            bluetoothDeviceScanned: {
                handler(transformedData) {
                    if (transformedData === null) {
                        return;
                    }
                    this.dev = transformedData.devices;
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
                            name: u,
                            _color: this.strToRGB(u),
                        })
                    }
                    this.users = transformedData.users;
                    this.scans = transformedData.scans;

                    if (this.timeScale.startTime == null || this.timeScale.endTime == null) {
                        this.timeScale.startTime = transformedData.scans[0].t - (transformedData.scans[0].t % this.timeScale.stepSize);
                        this.timeScale.endTime = transformedData.scans[transformedData.scans.length - 1].t
                        - (transformedData.scans[transformedData.scans.length - 1].t % this.timeScale.stepSize)
                        + this.timeScale.stepSize;
                    }
                    this.computeMatrix();
                    this.computeEdgesInInterval(this.timeScale.startTime, this.timeScale.endTime);
                }
            }
        }
    };

</script>

<style scoped>
    canvas{position:absolute;top:0;left:0}.net{height:100%;margin:0}.node{stroke:rgba(18,120,98,.7);stroke-width:3px;-webkit-transition:fill .5s ease;transition:fill .5s ease;fill:#dcfaf3}.node.selected{stroke:#caa455}.node.pinned{stroke:rgba(190,56,93,.6)}.link{stroke:rgba(18,120,98,.3)}.link,.node{stroke-linecap:round}.link:hover,.node:hover{stroke:#be385d;stroke-width:5px}.link.selected{stroke:rgba(202,164,85,.6)}.curve{fill:none}.link-label,.node-label{fill:#127862}.link-label{-webkit-transform:translateY(-.5em);transform:translateY(-.5em);text-anchor:middle}
</style>
