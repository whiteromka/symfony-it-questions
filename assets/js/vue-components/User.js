const User = {
    template: `
        <div class="user cp" :class="selectedUserByEmail === user.email ? 'bordered' : ''">
            <div class="row">
                <div class="col-sm-8">
                    <span> Имя: {{ user.name }} {{ user.lastName }} {{ user.email }}</span>
                </div>
                <div class="col-sm-4 text-end">
                    <button class="btn btn-primary" @click="$emit('add-estate', user)">
                        <i class="fa fa-address-card" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-danger" @click="$emit('remove-user', user.email)">
                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div v-if="user.estates && user.estates.length > 0">
                <ul>
                    <li v-for="(estate, idx) in user.estates" :key="idx">
                    {{ estate.estate }} {{ estate.cost }}
                    </li>
                </ul>
            </div>
        </div>
    `,

    props: ['user', 'selectedUserByEmail'],
    methods: {
        removeUser() {
            this.$emit('remove-user', this.user.email);
        }
    }
}