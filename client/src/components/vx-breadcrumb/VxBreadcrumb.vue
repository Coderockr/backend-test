<template>
    <Breadcrumb>
        <BreadcrumbItem v-for="(item, index) in itens" :key="index" :to="item.url">
            <feather-icon :icon="item.icon" class="breadcrumb-item w-4"/>
            {{item.label}}
            <vx-dropdown v-if="item.children" :id="index" :options="getItems(item.options)" :onSelect="onUrl"></vx-dropdown>
        </BreadcrumbItem>
    </Breadcrumb>
</template>

<script>
export default {
    props:{
        itens:Array
    },
    methods:{
        onUrl(option) {
            this.$router.push(option.url)
        },
        getItems(items){
            return items.filter(item => {
                return item.url !== this.$route.path
            })
        }
    }
}
</script>