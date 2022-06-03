import sidebarItems from './vx-sidebar/sidebarItems';

const items = sidebarItems
.filter(item => !item.submenu)
.flatMap(item => item);

const submenu = sidebarItems
    .filter(item => item.submenu && !!item.submenu.length)
    .flatMap(item => item.submenu);

export default {
    actionIcon: 'StarIcon',
    highlightColor: 'warning',
    data: items.concat(submenu).map((item, index) => {
        item.index = index;
        item.labelIcon = item.icon;
        item.label = item.name;
        item.highlightAction = false;
        return item;
    }),
}