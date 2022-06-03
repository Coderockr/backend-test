<template>
<div class="relative" id="navbar">
	<div class="vx-navbar-wrapper">
		<vs-navbar class="vx-navbar navbar-custom" :color="navbarColor" :class="classObj">

			<!-- SM - OPEN SIDEBAR BUTTON -->
			<feather-icon class="sm:inline-flex xl:hidden cursor-pointer mr-1" icon="MenuIcon" @click.stop="showSidebar"></feather-icon>

			<template v-if="breakpoint != 'md'">
				<!-- STARRED PAGES - FIRST 10 -->
				<ul class="vx-navbar__starred-pages flex">
                    <li class="starred-page" v-for="page in starredPagesLimited" :key="page.url">
                        <tooltip placement="bottom" :content="page.label" :delay="300">
                            <feather-icon svgClasses="h-6 w-6" class="p-2 cursor-pointer" :icon="page.labelIcon"
                                              @click="$router.push(page.url)"></feather-icon>
                        </tooltip>
                    </li>
                </ul>

				<!-- STARRED PAGES MORE -->
				<div class="vx-navbar__starred-pages--more-dropdown" v-if="starredPagesMore.length">
					<vs-dropdown vs-custom-content vs-trigger-click>
                            <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4"
                                          class="cursor-pointer p-2"></feather-icon>
                            <vs-dropdown-menu>
                                <ul class="vx-navbar__starred-pages-more--list">
                                    <li class="starred-page--more flex items-center cursor-pointer"
                                        v-for="page in starredPagesMore" :key="page.url"
                                        @click="$router.push(page.url)">
                                        <feather-icon svgClasses="h-5 w-5" class="ml-2 mr-1"
                                                      :icon="page.labelIcon"></feather-icon>
                                        <span class="px-2 pt-2 pb-1">{{ page.label }}</span>
                                    </li>
                                </ul>
                            </vs-dropdown-menu>
                        </vs-dropdown>
				</div>

				<div class="bookmark-container">
					<feather-icon icon="StarIcon" :svgClasses="['stoke-current text-warning', {'text-white': navbarColor != '#fff'}]" class="cursor-pointer p-2" @click.stop="showBookmarkPagesDropdown = !showBookmarkPagesDropdown" />
                    <div class="absolute bookmark-list w-1/3 xl:w-1/4 mt-4" v-if="showBookmarkPagesDropdown">
                        <vx-auto-suggest :autoFocus="true" :data="navbarSearchAndPinList" @selected="selected" @actionClicked="actionClicked" inputClassses="w-full" show-action show-pinned background-overlay></vx-auto-suggest>
					</div>
				</div>
				<div v-show="loading" class="bookmark-container mt-3 ml-2">
                    <Spin fix class="spin-col">
                        <Icon type="ios-loading" class="spin-icon-load"></Icon>
                    </Spin>
                </div>
				<div v-show="loading" class="bookmark-container mt-1 ml-4">
                    <label>Importando dados</label>
                </div>
			</template>

			<vs-spacer></vs-spacer>

			<!-- USER META -->
			<div class="the-navbar__user-meta flex items-center sm:ml-5 ml-2">
                <Badge :count="1" class-name="bg-warning" class="mt-1 mr-4">
                    <Poptip trigger="click" placement="bottom" style="color:#5e5e5e;">
                        <feather-icon class="cursor-pointer" icon="BellIcon"></feather-icon>
                        <div slot="title">
                            <label class="vs-list--title">Notificações</label>
                        </div>
                        <div slot="content">
                            <vs-list>
                                <vs-list-item icon="question_answer" title="Menção" subtitle="@baronbrown você pode me ..."></vs-list-item>
                                <vs-list-item icon="warning" title="Pendencia" subtitle="verificar proposta 4425135 proponente com ..."></vs-list-item>
                            </vs-list>
                        </div>
                    </Poptip>
                </Badge>
                <div class="text-right leading-tight hidden sm:block">
                    <p class="font-semibold">{{getName}}</p>
                    <small>{{userRole}}</small>
                </div>
                <vs-dropdown vs-custom-content vs-trigger-click class="cursor-pointer">
                    <div class="con-img ml-2">
                        <vs-avatar size="40px" color="fff" :src="currentUser.photo || require(`@/assets/images/${getSrc}`)"/>
                    </div>
                    <vs-dropdown-menu>
                        <ul style="min-width: 9rem">
                            <li class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
                                @click="$router.push(`/user/${idUser}`)">
                                <feather-icon icon="UserIcon" svgClasses="w-4 h-4"></feather-icon>
                                <span class="ml-2">Perfil</span></li>
                            <vs-divider class="m-1"></vs-divider>
                            <li class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
                                @click="logout">
                                <feather-icon icon="LogOutIcon" svgClasses="w-4 h-4"></feather-icon>
                                <span class="ml-2">Sair</span></li>
                        </ul>
                    </vs-dropdown-menu>
                </vs-dropdown>
            </div>

		</vs-navbar>
	</div>
</div>
</template>

<script>
import {mapGetters} from "vuex"
export default {
    name: 'the-navbar',
    props: {
        navbarColor: {
            type: String,
            default: "#fff",
        },
    },
    components: {
        VxAutoSuggest:()=>import('@/components/vx-auto-suggest/VxAutoSuggest')
    },
    data: () => ({
        searchQuery: '',
        showFullSearch: false,
        settings: { // perfectscrollbar settings
            maxScrollbarLength: 60,
            wheelSpeed: .60,
        },
        autoFocusSearch: false,
        showBookmarkPagesDropdown: false,
        loading:false
    }),
    watch: {
        '$route'() {
            if (this.showBookmarkPagesDropdown) this.showBookmarkPagesDropdown = false
        },
        '$parent.loading'(value){
            this.loading = false
            this.$nextTick(()=>{
                this.loading = value
            })
        }
    },
    computed: {
        ...mapGetters(['idUser', 'sidebarWidth', 'breakpoint', 'currentUser','navbarSearchAndPinList','userRole']),
        
        // NAVBAR STYLE
        classObj() {
            if (this.sidebarWidth == "default") return "navbar-default"
            else if (this.sidebarWidth == "reduced") return "navbar-reduced"
            else if (this.sidebarWidth) return "navbar-full"
            return ""
        },

        starredPages() {
            return this.$store.state.starredPages;
        },

        starredPagesLimited: {
            get() {
                return this.starredPages.slice(0, 10);
            },
            set(list) {
                this.$store.dispatch('arrangeStarredPagesLimited', list);
            }
        },

        starredPagesMore: {
            get() {
                return this.starredPages.slice(10);
            },
            set(list) {
                this.$store.dispatch('arrangeStarredPagesMore', list);
            }
        },
        getSrc() {
            const url = this.currentUser.photo
            return url ? 'uploads/'+url: 'portrait/person.png'
        },
        getName(){
            const first_name = this.currentUser.name.substr(0, this.currentUser.name.indexOf(' '))
            if(first_name){
                return first_name + this.currentUser.name.substr(first_name.length, first_name.length + this.currentUser.name.indexOf(' '))
            }else{
                return this.currentUser.name
            }
        }
    },
    methods: {
        showSidebar() {
            this.$store.commit('TOGGLE_IS_SIDEBAR_ACTIVE', true);
        },
        selected(item) {
            this.$router.push(item.url)
            this.showFullSearch = false;
        },
        actionClicked(item) {
            this.$store.dispatch('updateStarredPage', { index: item.index, val: !item.highlightAction });
        },
        async logout() {
            await this.$store.dispatch("logout");
            this.$axios.interceptors.request.use(config => {
                delete config.headers.Authorization;
                return config;
            })
            this.$router.push('/login')
        },
    },
}
</script>
<style lang="scss">
    #navbar{
        .ivu-badge-dot{
            top: 1px;
            right: -2px;
        }
    }
</style>