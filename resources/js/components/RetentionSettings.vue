<template>
<div class="p-2 lg:p-6">
    <notifications group="retention" position="top center" />
    <div class="block mb-2">
        <span class="text-3xl font-bold">{{ $lang.get('retention.title') }}</span>
        <div class="inline-flex">
            <dk-button
                @click="updatePolicy"
                class="px-4"
            >
                {{ $lang.get('general.save') }}
            </dk-button>
        </div>
    </div>

    <div
        class="w-full"
        v-if="errors.length > 0"
    >
        <p class="mb-0">
            <strong>Whoops! </strong>{{ $lang.get('retention.something-wrong') }}
        </p>
        <ul
            class="text-red-700"
        >
            <li
                v-for="error in errors"
                :key="error"
            >
                {{ error }}
            </li>
        </ul>
    </div>

    <div class="mt-4">
        <span class="text-xl font-bold">
            <label class="flex items-center m-2">
                <input name="enableGlobalRetention" type="checkbox" class="form-checkbox" v-model="retentionData.enableGlobalRetention">

                <span class="ml-2">{{ $lang.get('retention.global-title') }}</span>

                <ul
                    v-if="errors['enableGlobalRetention'] !== undefined"
                    class="text-red-700 mb-2"
                >
                    <li
                        v-for="error in errors['enableGlobalRetention']"
                        :key="error"
                    >
                        {{ error }}
                    </li>
                </ul>
            </label>
        </span>

        <div class="ml-8 border-bgprimary border-l-4 px-4 py-2 bg-white inline-block">
            {{ $lang.get('retention.global-description') }}
        </div>

        <div :class="[
            'ml-8',
            retentionData.enableGlobalRetention ? '' : 'opacity-25',
        ]">
            <div class="flex items-center m-2">
                <span :class="retentionData.globalRetentionExecuted ? 'text-green-400' : 'text-red-500'"><font-awesome-icon icon="circle"></font-awesome-icon></span>
                <span class="text-gray-800 font-bold ml-2">{{ $lang.get('retention.global-executed') }}</span>
            </div>

            <span class="block m-2">
                <span class="text-gray-800 mb-2 block">{{ $lang.get('retention.global-date') }}</span>
                <ul
                    v-if="errors['globalRetentionDate'] !== undefined"
                    class="text-red-700 mb-2"
                >
                    <li
                        v-for="error in errors['globalRetentionDate']"
                        :key="error"
                    >
                        {{ error }}
                    </li>
                </ul>
                <dk-datepicker name="globalRetentionDate" :disabled="retentionData.enableGlobalRetention === false" v-model="retentionData.globalRetentionDate" :config="{
                    enableTime: true,
                    dateFormat: 'Y-m-d H:i:ss',
                }"></dk-datepicker>
            </span>
        </div>
    </div>

    <div class="mt-4">
        <span class="text-xl font-bold">
            <label class="flex items-center m-2">

                <input name="enableDefaultRetention" type="checkbox" class="form-checkbox" v-model="retentionData.enableDefaultRetention">

                <span class="ml-2">{{ $lang.get('retention.default-title') }}</span>

                <ul
                    v-if="errors['enableDefaultRetention'] !== undefined"
                    class="text-red-700 mb-2"
                >
                    <li
                        v-for="error in errors['enableDefaultRetention']"
                        :key="error"
                    >
                        {{ error }}
                    </li>
                </ul>
            </label>
        </span>

        <div class="ml-8 border-bgprimary border-l-4 px-4 py-2 bg-white inline-block">
            {{ $lang.get('retention.default-description') }}
        </div>

        <div :class="[
            'ml-8',
            retentionData.enableDefaultRetention ? '' : 'opacity-25'
        ]">
            <label class="block m-2">
                <span class="text-gray-800 block mb-2">{{ $lang.get('retention.default-days') }}</span>
                <ul
                    v-if="errors['defaultRetentionDays'] !== undefined"
                    class="text-red-700 mb-2"
                >
                    <li
                    v-for="error in errors['defaultRetentionDays']"
                    :key="error"
                    >
                        {{ error }}
                    </li>
                </ul>
                <input
                    type="number"
                    name="defaultRetentionDays"
                    class="flex-grow rounded py-2 px-3 appearance-none border text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    :disabled="retentionData.enableDefaultRetention === false"
                    v-model="retentionData.defaultRetentionDays"
                >
                {{ $lang.choice('general.day', retentionData.defaultRetentionDays) }}
            </label>
        </div>
    </div>

    </div>
</template>
<script>

export default {
    data()  {
        return {
            retentionData: {},
            errors: [],
        };
    },

    mounted() {
        this.getPolicy();
    },

    methods: {

        // retrieves the data from the backend
        getPolicy() {
            axios.get('/retention/get')
                .then(response => {
                    if (typeof response.data === 'object') {
                        this.retentionData = response.data;
                    }

                    console.log("Retrieved new data...");
                })
                .catch(error => {
                    this.$notify({
                        group: 'retention',
                        title: lang.get('general.error'),
                        text: error,
                        type: 'error'
                    });
                });
        },

        // updates the data with the user input
        updatePolicy() {
            axios.post('/retention/update', this.retentionData)
                .then(response => {
                    this.showConfirm = false;
                    this.$notify({
                        group: 'retention',
                        title: lang.get('retention.save-success'),
                        type: 'success'
                    });
                })
                .catch(error => {
                    if (error.response !== undefined && typeof error.response.data === 'object') {
                        if (error.response.data.errors !== undefined) {
                            this.errors = error.response.data.errors;
                        } else if (error.response.data.message !== undefined) {
                            this.errors = [error.response.data.message];
                        } else {
                            this.errors = [error.response.status + ' ' + error.response.statusText];
                        }
                    } else {
                        console.log(error)
                        this.errors = [lang.get('general.try-again')];
                    }
                });
            this.getPolicy();
        }
    }
}
</script>
