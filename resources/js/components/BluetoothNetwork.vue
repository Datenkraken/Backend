<template>
    <div class="flex-grow h-full">
        <div v-show="loading" class="flex flex-col items-center justify-center flex-grow h-full">
            <font-awesome-icon icon="circle-notch" size="3x" spin></font-awesome-icon>
        </div>
        <div v-show="!loading" class="h-full flex flex-col lg:flex-row">
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
                <div class="w-auto h-auto pt-8 pl-8 pb-16 pr-2">
                    <label>
                        <input @click="toggleMailLabels($event.target.checked)"
                               class="form-checkbox"
                               type="checkbox"
                               name="select-box"/>
                        {{$lang.get('bluetoothgraph.mail')}}
                    </label>
                </div>
            </div>
            <div class="h-full lg:w-1/4">
                <user-selection
                    :users="this.userSelection"
                    checkbox_column_identifier="bluetoothgraph.show"
                    :user_column_identifier="this.user_column_identifier"
                    v-on:box-clicked="boxChecked($event)"
                ></user-selection>
            </div>
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
                edges: [],
                nodes: [],
                edgesTimeline: [],
                userSelection: [],
                mutex: false,
                loading: true,
                encounters_loaded: false,
                users_loaded: false,
                user_column_identifier: "users.id",
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
                        physics: false,
                        font: {
                            color: "#f56565",
                        },
                        color: {
                            border: "#2D3748",
                        }
                    },
                    edges: {
                        font: {
                            color: "#ffffff",
                            size: 20,
                            strokeWidth: 0,
                        },
                        smooth: false,
                        physics: false,
                        value: 1,
                    }
                }
            };
        },

        apollo: {
            userEncounters: gql`query{
                userEncounters(orderBy: [{field: END_TIME, order: ASC }]) {
                    start_time,
                    end_time,
                    participants {
                        id,
                    },
                }
            }`,
            users: gql`query{
                users {
                    id,
                    email
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
            boxChecked(event) {
                let index = this.nodes.findIndex(el => el.id === event.user.id);
                this.nodes[index].hidden = !event.checked;
                event.user.selected = event.checked;
                if(event.checked) {
                    this.queueGraphUpdate();
                }
            },
            addNodes(encounters) {
                let users = new Map();
                let selection = null;
                for (let enc of encounters) {
                    for (let user of enc.participants) {
                        if (!users.has(user.id)) {
                            selection = this.addUser(user.id);
                            users.set(user.id, selection);
                        }
                    }
                }
                return users;
            },
            addUser(userId) {
                let user = {
                    id: userId,
                    value: userId,
                    label: userId,
                    email: "",
                    selected: false,
                };
                this.userSelection.push(user)
                this.nodes.push({
                    id: userId,
                    label: userId,
                    email: "",
                    physics: false,
                    hidden: true,
                    color: {
                        background: this.strToRGB(userId),
                    },
                });
                return user;
            },
            timeToIndex(time) {
                return Math.round((time - this.timeScale.startTime) / this.timeScale.stepSize);
            },
            async computeEdgesInInterval() {
                let edges = new Map();
                let endIndex = this.timeToIndex(this.timeScale.selected[1]);
                let currentEdge = null;
                for (let i = this.timeToIndex(this.timeScale.selected[0]); i <= endIndex; i++) {
                    for (let edge of this.edgesTimeline[i]) {
                        if (!edge.from.selected || !edge.to.selected) {
                            continue;
                        }
                        if (edges.has(edge.from.id  + edge.to.id)) {
                            currentEdge = edges.get(edge.from.id + edge.to.id);
                            currentEdge.value += 1;
                        } else {
                            edges.set(edge.from.id + edge.to.id, {
                                from: edge.from.id,
                                to: edge.to.id,
                                label: "",
                                value: 1,
                                physics: false
                            })
                        }
                    }
                }

                this.edges = Array.from(edges.values());
                let label = this.$lang.get('bluetoothgraph.encounter') + ": ";
                for (let edge of this.edges) {
                    edge.label = label + edge.value;
                }
            },
            queueGraphUpdate() {
                if (this.mutex) return;

                this.mutex = true;
                this.computeEdgesInInterval().then(() => {
                    this.mutex = false;
                });
            },
            addMailLabels(map) {
                for (let n of this.nodes) {
                    n.email = map.get(n.id);
                }
                for (let u of this.userSelection) {
                    u.email = map.get(u.id);
                }
            },
            toggleMailLabels(checked) {
                if (checked) {
                    for (let n of this.nodes) {
                        n.label = n.email
                    }
                    for (let u of this.userSelection) {
                        u.label = u.email;
                    }
                    this.user_column_identifier = "users.user-email"
                } else {
                    for (let n of this.nodes) {
                        n.label = n.id
                    }
                    for (let u of this.userSelection) {
                        u.label = u.id;
                    }
                    this.user_column_identifier = "users.id"
                }
            }
        },
        functions: {

        },
        watch: {
            userEncounters: {
              handler(encounters) {
                  if (encounters === null || this.encounters_loaded) {
                      return;
                  } else if (encounters.length === 0) {
                      this.loading = false;
                      this.encounters_loaded = true;
                      return;
                  }

                  let users = this.addNodes(encounters);

                  this.timeScale.endTime = encounters[encounters.length - 1].end_time
                    - (encounters[encounters.length - 1].end_time % this.timeScale.stepSize)
                    + this.timeScale.stepSize;
                  let startTime = encounters[0].start_time - (encounters[0].start_time % this.timeScale.stepSize);
                  this.timeScale.selected = [startTime, startTime]
                  this.timeScale.startTime = startTime;

                  let timeline = Array.from(Array(this.timeToIndex(this.timeScale.endTime) + 1),() => []);

                  for (let i = 0; i < encounters.length; i++) {
                      timeline[this.timeToIndex(Math.round((encounters[i].start_time + encounters[i].end_time) / 2))].push({
                          from: users.get(encounters[i].participants[0].id),
                          to: users.get(encounters[i].participants[1].id),
                      })
                      encounters[i] = null;
                  }

                  this.edgesTimeline = timeline;
                  if (this.users_loaded) {
                      let map = new Map();
                      for (let user of this.users) {
                          map.set(user.id, user.email);
                      }

                      this.addMailLabels(map);
                      this.loading = false;
                      this.users = null;
                  }

                  this.encounters_loaded = true;
                  this.userEncounters = null;
              }
            },
            timeScale: {
                deep: true,
                handler() {
                    this.queueGraphUpdate();
                }
            },
            users: {
                handler(newUsers) {
                    if (newUsers == null || this.users_loaded || !this.encounters_loaded) {
                        return;
                    }

                    let map = new Map();
                    for (let user of newUsers) {
                        map.set(user.id, user.email);
                    }

                    this.addMailLabels(map);
                    this.loading = false;
                    this.users_loaded = true;
                    this.users = null;
                }
            }
        }
    };

</script>

<style scoped>
    @import '~vue-slider-component/theme/antd.css';
</style>
