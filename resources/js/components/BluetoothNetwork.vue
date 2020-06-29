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
                    userId
                    timestamp
                    name
                    address
                    known
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
            hashCode(str) {
                let hash = 7;
                for (let i = 0; i < str.length; i++) {
                    hash = str.charCodeAt(i) + (hash << 5 - hash);
                }
                return hash;
            }
        },
        functions: {

        },
        watch: {
            bluetoothDeviceScanned: {
                handler(devices) {
                    for (let dev of devices) {
                        if (this.nodes.findIndex(o=>o.id===dev.userId) === -1) {
                            this.nodes.push({
                                id: dev.userId,
                                name: dev.userId,
                                _color: this.strToRGB(dev.userId),
                            });
                        }

                        if (this.dev.findIndex(o=>o.address===dev.address) === -1) {
                            this.dev.push({
                                address: dev.address,
                                user: []
                            });
                        }
                        let users = this.dev.find(o=>o.address===dev.address).user;
                        if (users.findIndex(o=>o.id===dev.userId) === -1) {
                            users.push({
                                id:  dev.userId,
                                rel: 1,
                            });
                        }

                        users.find(o=>o.id===dev.userId).rel++;
                    }

                    for (let dev of this.dev) {
                        for(let i = 0; i < dev.user.length - 1; i++) {
                            for(let j = i; j < dev.user.length; j++) {
                                let u1 = this.hashCode(dev.user[i].id);
                                let u2 = this.hashCode(dev.user[j].id);
                                let source = (u1 > u2 ? u2 : u1);
                                let target = (u1 < u2 ? u2 : u1);
                                let id = source.toString() + ":" + target;
                                let index = this.edges.findIndex(o=>o.id===id);
                                if (index === -1) {
                                    this.edges.push({
                                        id: dev.user[i].id + ":" + dev.user[j].id,
                                        name: dev.address,
                                        tid: dev.user[j].id,
                                        sid: dev.user[i].id,
                                        _svgAttrs: {
                                            'stroke-width': dev.user[i].rel + dev.user[j].rel,
                                        }
                                    })
                                } else {
                                    this.edges[index].name += "\n" + dev.address;
                                    this.edges[index]._svgAttrs['stroke-width'] += dev.user[i].rel + dev.user[j].rel;
                                }

                            }
                        }
                    }
                }
            }
        }
    };

</script>

<style scoped>
    canvas{position:absolute;top:0;left:0}.net{height:100%;margin:0}.node{stroke:rgba(18,120,98,.7);stroke-width:3px;-webkit-transition:fill .5s ease;transition:fill .5s ease;fill:#dcfaf3}.node.selected{stroke:#caa455}.node.pinned{stroke:rgba(190,56,93,.6)}.link{stroke:rgba(18,120,98,.3)}.link,.node{stroke-linecap:round}.link:hover,.node:hover{stroke:#be385d;stroke-width:5px}.link.selected{stroke:rgba(202,164,85,.6)}.curve{fill:none}.link-label,.node-label{fill:#127862}.link-label{-webkit-transform:translateY(-.5em);transform:translateY(-.5em);text-anchor:middle}
</style>
