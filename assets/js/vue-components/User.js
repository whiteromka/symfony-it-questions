const User = {
    template: `
        <div class="user">
            <span> Имя: {{ user.name }} {{ user.lastName }} {{ user.email }}</span>
            <button class="btn btn-danger" @click="removeUser()">x</button>
        </div>
    `,

    props: ['user'],
    methods: {
        removeUser() {
            this.$emit('remove-user', this.user.email);
        }
    }
}