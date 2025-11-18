const User = {
    template: `
        <div class="user cp" :class="selectedUserByEmail === user.email ? 'bordered' : ''">
            <div class="row">
                <div class="col-sm-8">
                    <span> Имя: {{ user.name }} {{ user.lastName }} {{ user.email }}</span>
                </div>
                <div class="col-sm-4 text-end">
                    <button class="btn btn-primary" @click="$emit('add-skill', user)">
                        <i class="fa fa-address-card" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-danger" @click="$emit('remove-user', user.email)">
                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div v-if="user.skills && user.skills.length > 0">
                <ul>
                    <li v-for="(skill, idx) in user.skills" :key="idx"> {{ skill.skill }} </li>
                </ul>
            </div>
        </div>
    `,

    props: ['user', 'selectedUserByEmail'],
}