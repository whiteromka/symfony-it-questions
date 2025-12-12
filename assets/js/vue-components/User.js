const User = {
    template: `
        <div class="user-card mb-3 p-3 border rounded" 
             :class="{ 'border-primary bg-light': selectedUser && selectedUser.id === user.id }">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="mb-1">{{ user.name }} {{ user.lastName }}</h6>
                    <p class="text-muted mb-1 small">{{ user.email }}</p>
                    <p class="text-muted mb-1 small" v-if="user.phone"> {{ user.phone }}</p>
                    
                    <!-- Навыки пользователя -->
                    <div v-if="user.skills && user.skills.length > 0" class="mt-2">
                        <div class="d-flex flex-wrap gap-1">
                            <span v-for="skill in user.skills" :key="skill.id" 
                                  class="badge bg-success d-flex align-items-center">
                                {{ skill.name }}
                                 <!-- Кнопка удалить навык -->
                                <button @click="detachSkill(skill.name)" 
                                        class="btn-close btn-close-white ms-1"
                                        style="font-size: 0.7rem;"></button>
                            </span>
                        </div>
                    </div>
                    <div v-else class="text-muted small mt-1">
                        Нет навыков
                    </div>
                </div>
                
                <div class="col-md-4 text-end">
                    <button class="btn btn-outline-primary btn-sm me-1" 
                            @click="$emit('add-skill', user)"
                            title="Добавить навык">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm" 
                            @click="confirmDelete"
                            title="Удалить пользователя">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `,

    props: ['user', 'selectedUser'],
    methods: {
        confirmDelete() {
            if (confirm(`Удалить пользователя ${this.user.name}?`)) {
                this.$emit('remove-user', this.user.id);
            }
        },
        detachSkill(skillName) {
            this.$emit('detach-skill', {
                userId: this.user.id,
                skillName: skillName
            });
        }
    }
}