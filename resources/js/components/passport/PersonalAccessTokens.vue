<template>
  <div>
    <div class="p-2">
      <!-- No Tokens Notice -->
      <p
        class="mb-0 block text-white-700 text-sm font-bold"
        v-if="tokens.length === 0"
      >
        {{ $lang.get('oauth.no-api-keys') }}
      </p>

      <!-- Personal Access Tokens -->
      <table
        class="mb-0 w-full"
        v-if="tokens.length > 0"
      >
        <thead class="border-b">
          <tr class="text-xl">
            <th class="text-left">
                {{ $lang.get('oauth.api-key-name') }}
            </th>
            <th class="text-right">
                {{ $lang.get('oauth.api-key-action') }}
            </th>
          </tr>
        </thead>

        <tbody>
          <tr
            class="my-1"
            v-for="token in tokens"
            :key="token.id"
          >
            <!-- Client Name -->
            <td class="px-2 py-3">
              {{ token.name }}
            </td>

            <!-- Delete Button -->
            <td class="text-right p-1 py-3">
              <dk-button
                class="mx-1"
                color="red-600"
                hover-color="red-600"
                @click="revoke(token)"
              >
                {{ $lang.get('general.delete') }}
              </dk-button>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="flex flex-col mt-6">
        <dk-button
          class="py-2 px-4 mx-1 self-end"
          :padding="false"
          @click.stop="dialogCreate = true;"
        >
            {{ $lang.get('oauth.api-key-create') }}
        </dk-button>
      </div>
    </div>

    <!-- Create Token Modal -->
    <dk-modal
      :show="dialogCreate"
      @close="dialogCreate = false"
    >
      <div class="bg-white rounded text-black p-5">
        <div class="mb-3">
          <span class="text-xl">{{ $lang.get('oauth.api-key-create') }}</span>
        </div>
        <div class="text-gray-900">
          <div>
            <!-- Form Errors -->
            <div
              class="w-full"
              v-if="form.errors.length > 0"
            >
              <p class="mb-0">
                <strong>Whoops!</strong>{{ $lang.get('oauth.somethin-wrong') }}
              </p>
              <ul
                class="text-red-700"
              >
                <li
                  v-for="error in form.errors"
                  :key="error"
                >
                  {{ error }}
                </li>
              </ul>
            </div>

            <!-- Create Token Form -->
            <form
              role="form"
              class="w-full"
              @submit.prevent="store"
            >
              <!-- Name -->
              <label
                for="name"
                class="block mb-2"
              >{{ $lang.get('oauth.api-key-name-special') }}</label>
              <ul
                v-if="form.errors['name'] !== undefined"
                class="text-red-700 mb-2"
              >
                <li
                  v-for="error in form.errors['name']"
                  :key="error"
                >
                  {{ error }}
                </li>
              </ul>
              <input
                id="create-token-name"
                name="name"
                type="text"
                class="form-input w-full block"
                required
                v-model="form.name"
              >

              <!-- Scopes -->
              <div v-if="scopes.length > 0">
                <label class="block">{{ $lang.get('oauth.api-key-scopes') }}</label>
                <div
                  v-for="scope in scopes"
                  :key="scope"
                >
                  <div class="checkbox">
                    <label>
                      <input
                        type="checkbox"
                        class="form-checkbox"
                        @click="toggleScope(scope.id)"
                        :checked="scopeIsAssigned(scope.id)"
                      >

                      {{ scope.id }}
                    </label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <small>{{ $lang.get('oauth.api-key-required') }}</small>
        </div>
        <div class="mt-2 flex justify-end">
          <dk-button
            class="py-2 px-4 mx-1"
            hover-color="red-500"
            :padding="false"
            @click="dialogCreate = false"
          >
            {{ $lang.get('general.close') }}
          </dk-button>
          <dk-button
            class="py-2 px-4 mx-1"
            :padding="false"
            @click="store"
          >
            {{ $lang.get('general.save') }}
          </dk-button>
        </div>
      </div>
    </dk-modal>

    <!-- Access Token Modal -->
    <dk-modal
      :show="dialogShowToken"
      @close="dialogShowToken = false"
    >
      <div class="bg-white rounded text-black p-5">
        <div class="mb-3">
          <span class="text-xl">{{ $lang.get('oauth.api-key-yours') }}</span>
        </div>
        <div class="text-gray-900">
          <p class="w-full mb-3">
            {{ $lang.get('oauth.api-key-warning') }}
          </p>
          <textarea
            class="w-full shadow border-2 p-3"
            rows="10"
            v-model="accessToken"
          />
        </div>
        <div class="mt-2 flex justify-end">
          <dk-button
            class="py-2 px-4 mx-1"
            :padding="false"
            hover-color="red-600"
            @click="dialogShowToken = false"
          >
            {{ $lang.get('general.close') }}
          </dk-button>
        </div>
      </div>
    </dk-modal>
  </div>
</template>

<script>
import _ from 'lodash';

export default {
  /*
   * The component's data.
   */
  data() {
    return {
      accessToken: null,
      dialogCreate: false,
      dialogShowToken: false,
      tokens: [],
      scopes: [],
      form: {
        name: '',
        scopes: [],
        errors: [],
      },
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
      this.getTokens();
      this.getScopes();
    },

    /**
     * Get all of the personal access tokens for the user.
     */
    getTokens() {
      axios.get('/oauth/personal-access-tokens')
        .then(response => {
          this.tokens = response.data;
        });
    },

    /**
     * Get all of the available scopes.
     */
    getScopes() {
      axios.get('/oauth/scopes')
        .then(response => {
          this.scopes = response.data;
        });
    },

    /**
     * Create a new personal access token.
     */
    store() {
      this.accessToken = null;

      this.form.errors = [];

      axios.post('/oauth/personal-access-tokens', this.form)
        .then(response => {
          this.form.name = '';
          this.form.scopes = [];
          this.form.errors = [];

          this.tokens.push(response.data.token);

          this.showAccessToken(response.data.accessToken);
        })
        .catch(error => {
          if (typeof error.response.data === 'object') {
            if (error.response.data.errors !== undefined) {
              this.form.errors = error.response.data.errors;
            } else if (error.response.data.message !== undefined) {
              this.form.errors = [error.response.data.message];
            } else {
              this.form.errors = [error.response.status + ' ' + error.response.statusText];
            }
          } else {
            this.form.errors = ['Something went wrong. Please try again!'];
          }
        });
    },

    /**
     * Toggle the given scope in the list of assigned scopes.
     */
    toggleScope(scope) {
      if (this.scopeIsAssigned(scope)) {
        this.form.scopes = _.reject(this.form.scopes, s => s === scope);
      } else {
        this.form.scopes.push(scope);
      }
    },

    /**
     * Determine if the given scope has been assigned to the token.
     */
    scopeIsAssigned(scope) {
      return _.indexOf(this.form.scopes, scope) >= 0;
    },

    /**
     * Show the given access token to the user.
     */
    showAccessToken(accessToken) {
      this.dialogCreate = false;
      this.accessToken = accessToken;
      this.dialogShowToken = true;
    },

    /**
     * Revoke the given token.
     */
    revoke(token) {
      axios.delete('/oauth/personal-access-tokens/' + token.id)
        .then(response => {
          this.getTokens();
        });
    },
  },
};
</script>
