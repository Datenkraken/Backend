<template>
    <div class="h-full w-100">
        <table>
            <thead>
            <tr>
                <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-900 border-b-2 border-gray-300">
                    {{$lang.get(action_string_identifier)}}
                </th>
                <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-900 border-b-2 border-gray-300">
                    {{$lang.get('user.id')}}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="user in users"
                class="hover:bg-gray-200 border-b border-gray-300"
            >
                <td class="py-4 px-6">
                    <label>
                        <input @click="$emit('box-clicked', {user: user, checked: $event.target.checked})"
                               @change="$emit('selected-Changed', selected_users)"
                               class="form-checkbox"
                               type="checkbox"
                               name="select-box"
                               :id="user.id"
                               :value="user.value"
                               v-model="selected_users"/>
                    </label>
                </td>
                <td class="py-4 px-6">
                    {{ user.id }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "UserSelection",

        props: {
            action_string_identifier: String,
            users: Array,
        },
        data() {
            return {
                selected_users: []
            };
        },
    }
</script>

<style scoped>

</style>
