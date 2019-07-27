<template>
    <YggDropdown class="YggActionsDropdown">
        <template slot="text">
            <slot name="text" />
        </template>
        <template v-for="group in actionGroups">
            <YggDropdownItem v-for="action in group" @click="handleActionClicked(action)" :key="action.key">
                {{ action.label }}
                <div v-if="action.description" class="YggActionsDropdown__description mt-1">
                    {{ action.description }}
                </div>
            </YggDropdownItem>
            <YggDropdownSeparator />
        </template>
    </YggDropdown>
</template>

<script>
    import YggDropdown from '../dropdown/Dropdown';
    import YggDropdownItem from '../dropdown/DropdownItem';
    import YggDropdownSeparator from '../dropdown/DropdownSeparator';

    export default {
        name: 'YggActionsDropdown',

        components: {
            YggDropdown,
            YggDropdownItem,
            YggDropdownSeparator,
        },

        props: {
            // 2D Array of action groups
            actions: {
                type: Array,
            }
        },

        computed: {
            actionGroups() {
                return this.actions.filter(group => group.length > 0);
            }
        },

        methods: {
            handleActionClicked(action) {
                this.$emit('select', action);
            }
        }
    }
</script>