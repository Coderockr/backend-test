<template>
    <div :class="[{'vs-sidebar-item-active':activeLink}, {'disabled-item pointer-events-none': isDisabled}]"
         class="vs-sidebar--item">
        <router-link v-if="to" :to="to" :class="[{'router-link-active': activeLink}]" :target="target" exact>
            <vs-icon v-if="!featherIcon" :icon-pack="iconPack" :icon="icon">
            </vs-icon>
            <feather-icon :icon="icon" :class="{'w-3 h-3': iconSmall}" v-else></feather-icon>
            <slot></slot>
        </router-link>
        <a v-else :target="target" :href="href">
            <vs-icon v-if="!featherIcon" :icon-pack="iconPack" :icon="icon">
            </vs-icon>
            <feather-icon :icon="icon" :class="{'w-3 h-3': iconSmall}" v-else></feather-icon>
            <slot></slot>
        </a>
    </div>
</template>

<script>
    export default {
        name: 'VxSidebarItem',
        props: {
            icon: {
                default: "",
                type: String
            },
            iconSmall: {
                default: false,
                type: Boolean,
            },
            iconPack: {
                default: 'material-icons',
                type: String
            },
            href: {
                default: '#',
                type: String
            },
            to: {
                default: null,
                type: String
            },
            index: {
                default: null,
                type: [String, Number]
            },
            featherIcon: {
                default: true,
                type: Boolean,
            },
            target: {
                default: '_self',
                type: String,
            },
            isDisabled: {
                default: false,
                type: Boolean
            }
        },
        data() {
            return {
                activeLink: false,
            }
        },
        watch: {
            '$route'() {
                this.CheckIsActive()
            }
        },
        methods: {
            CheckIsActive() {
                if (this.to) {
                    this.activeLink = !!(this.$route.path.slice(1).includes(this.to.slice(1)) && this.to.slice(1))
                        || (this.$route.meta.parent && this.$route.meta.parent.includes(this.to.slice(1)) && this.to.slice(1));
                }
            }
        },
        updated() {
            this.CheckIsActive();
        }
    }
</script>
