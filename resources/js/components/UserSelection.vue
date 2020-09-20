<template>
    <div class="h-full w-100">
        <table>
            <thead>
            <tr>
                <th class="py-4 pl-6 bg-gray-100 font-bold uppercase text-sm text-gray-900 border-b-2 border-gray-300">
                    {{$lang.get(checkbox_column_identifier)}}
                </th>
                <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-900 border-b-2 border-gray-300">
                    {{$lang.get(user_column_identifier)}}
                </th>
                <th class="py-4 px-1 bg-gray-100 font-bold uppercase text-sm text-gray-900 border-b-2 border-gray-300">
                    {{$lang.get('users.user-info')}}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="user in users"
                class="hover:bg-gray-200 border-b border-gray-300"
            >
                <td class="py-4 pl-6">
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
                    {{ user.label }}
                </td>
                <td class="py-4 px-1">
                    <button-link
                        color="blue-600"
                        hover-color="blue-600"
                        :url="'/user/' + user.id"
                    >
                        <font-awesome-icon icon="info-circle"></font-awesome-icon>
                    </button-link>
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
            checkbox_column_identifier: String,
            user_column_identifier: String,
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
