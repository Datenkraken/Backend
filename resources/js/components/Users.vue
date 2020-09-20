<template>
<div class="flex-grow h-full">
    <div v-show="loading" class="flex flex-col items-center justify-center flex-grow h-full">
        <font-awesome-icon icon="circle-notch" size="3x" spin></font-awesome-icon>
    </div>
    <div v-show="!loading" class="flex-grow h-full bg-gray-100">
        <p v-if="users.length == 0">
            {{ $lang.get('users.no-users') }}
        </p>
        <table v-else>
            <thead>

            <tr>
                <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-900 border-b-2 border-gray-300">
                    {{ $lang.get('users.email') }}
                </th>
                <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-gray-900 border-b-2 border-grey-300">
                    {{ $lang.get('users.role') }}
                </th>
                <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-gray-900 border-b-2 border-grey-300">
                    {{ $lang.get('users.created') }}
                </th>
                <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-gray-900 border-b-2 border-grey-300"></th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="user in users"
                :key="user._id"
                class="hover:bg-gray-200 border-b border-gray-300"
            >
                <label >
                    <td class="py-4 px-6">
                        <input :data-user="user._id" @click="boxChecked()" class="form-checkbox" type="checkbox" name="select-box" />
                    </td>
                    <td class="py-4 px-6">
                        {{ user.email }}
                    </td>
                </label>
                <td class="py-4 px-6">
                    <span v-if="user.is_admin" class="text-primary border-red-600 border semibold p-1 rounded text-sm">
                        {{ $lang.get('users.admin') }}
                    </span>
                </td>
                <td class="py-4 px-6">{{ formatDate(user.created_at) }}</td>
                <td class="py-4 px-6 flex">
                    <td class="py-4 px-6">
                    <button-link
                        color="blue-600"
                        hover-color="blue-600"
                        :url="'/user/' + user._id"
                    >
                        <font-awesome-icon icon="info-circle"></font-awesome-icon>
                    </button-link>
                    <dk-button
                        @click="deleteUsers([user._id])"
                        :padding="false"
                        class="px-3 py-1"
                        color="red-600"
                        hoverColor="red-600"
                        >
                        <font-awesome-icon icon="trash"></font-awesome-icon>
                    </dk-button>
                    <dk-button
                        @click="toggleAdmin(user._id)"
                        class="p-1 flex-grow"
                        :color="user.is_admin ? 'red-600' : 'green-600'"
                        :hoverColor="user.is_admin ? 'red-600' : 'green-600'"
                        >
                        <font-awesome-icon icon="user-shield"></font-awesome-icon>
                        <span>{{ user.is_admin ? $lang.get('users.admin-remove') : $lang.get('users.admin-add') }}</span>
                    </dk-button>
                </td>
            </tr>
            </tbody>
        </table>
        <div>
            <dk-button
                @click="showDeleteAll = true"
                class="px-4 py-2 m-4"
                :padding="false"
                color="red-600"
                hoverColor="red-600"
            >
                {{ $lang.get('users.delete-all') }}
            </dk-button>
        </div>
        <div :class="{ hidden : !checked }" class="fixed bg-bgprimary p-2 w-full shadow-lg bottom-0">
            <dk-button
                @click="showDeleteSelected = true"
                color="primary bgsecondary"
                hoverColor="red-500"
                hoverTextColor="bgprimary"
                :padding="false"
                class="px-4 py-2"
            >
                {{ $lang.get('users.delete-selected') }}
            </dk-button>
            <dk-button
                @click="unselectAll()"
                color="primary bgsecondary"
                hoverColor="red-500"
                hoverTextColor="bgprimary"
                :padding="false"
                class="px-4 py-2"
            >
                {{ $lang.get('users.unselect') }}
            </dk-button>
        </div>

        <dk-confirm-modal :show="showDeleteAll" @close="showDeleteAll = false" @confirm="deleteAllNonAdmin()">
            {{ $lang.get('users.ask-delete-all') }}
        </dk-confirm-modal>

        <dk-confirm-modal :show="showDeleteSelected" @close="showDeleteSelected = false" @confirm="deleteSelected()">
            {{ $lang.get('users.ask-delete-selected') }}
        </dk-confirm-modal>

        <dk-confirm-modal v-if="showToggleAdmin" :show="showToggleAdmin" @close="showToggleAdmin = false" @confirm="sendToggleAdmin()">
            {{ $lang.get(adminToggleSelection.is_admin ? 'users.ask-remove-admin' : 'users.ask-promote-admin') }}
        </dk-confirm-modal>
    </div>
</div>
</template>

<script>
import moment from 'moment';

export default {
  /*
   * The component's data.
   */
  data() {
    return {
      loading: true,
      users: [],
      user: null,
      checked: false,
      showDeleteAll: false,
      showDeleteSelected: false,
      showToggleAdmin: false,
      adminToggleSelection: null,
    };
  },

  /**
   * Prepare the component (Vue 2.x).
   */
  mounted() {
    this.prepareComponent();
  },

  methods: {
    /**
     * Prepare the component.
     */
    prepareComponent() {
      this.getUsers();
    },

    /**
     * Get all users.
     */
    getUsers() {
      this.loading = true;
      axios.get('/users/all')
        .then(response => {
            this.users = response.data;
            this.loading = false;
        });
    },

    /*
     * Returns the time differece between now and date.
     */
    formatDate(date) {
        return moment(date).fromNow();
    },

    /*
     * Checked box handler
     */
    boxChecked() {
        var checkedBoxes = document.querySelectorAll('input[name=select-box]:checked');
        this.checked = checkedBoxes.length > 0;
    },

    /*
     * Deselects all selected rows.
     */
    unselectAll() {
        var checkedBoxes = document.querySelectorAll('input[name=select-box]:checked');

        for (var i = 0; i < checkedBoxes.length; i++) {
            checkedBoxes[i].checked = false;
        }
        this.checked = false;
    },

    /*
     * Delete all selected users.
     */
    deleteSelected() {
        var checkedBoxes = document.querySelectorAll('input[name=select-box]:checked');
        var idsToDelete = [];
        this.showDeleteSelected = false;
        for (var i = 0; i < checkedBoxes.length; i++) {
            idsToDelete.push(checkedBoxes[i].getAttribute('data-user'));
        }
        this.deleteUsers(idsToDelete);
        this.unselectAll();
    },

    /*
     * Delete given user ids.
     */
    deleteUsers(users) {
        axios.delete('/users/delete', {data: {ids: users}})
        .then(response => {
          this.getUsers();
        });
    },

    /*
     * Delete all non admin users
     */
    deleteAllNonAdmin() {
        var usersToDelete = [];
        this.showDeleteAll = false;
        for (var i = 0; i < this.users.length; i++) {
            if (!this.users[i].is_admin) {
                usersToDelete.push(this.users[i]._id);
            }
        }
        this.deleteUsers(usersToDelete);
    },

    toggleAdmin(userId) {
        this.adminToggleSelection = this.users.find(x => x._id === userId);
        this.showToggleAdmin = true;
    },

    sendToggleAdmin() {
        this.showToggleAdmin = false;
        axios.post('/users/role', {id: this.adminToggleSelection._id, is_admin: !this.adminToggleSelection.is_admin})
        .then(response => {
            this.getUsers();
        })
    }
  },
};
</script>
