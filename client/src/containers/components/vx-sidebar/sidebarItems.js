export default [
    {
        url: "/",
        name: "Painel",
        slug: "dashboard",
        icon: "HomeIcon",
    },
    {
        url: null,
        name: "Cadastros",
        slug: "recordss",
        icon: "FolderIcon",
        submenu: [
            {
                url: "/users",
                name: "Usuários",
                slug: "user",
                icon: "UsersIcon",
            },
        ]
    },
    {
        url: null,
        name: "Configurações",
        slug: "settings",
        icon: "SettingsIcon",
        submenu: [
            {
                url: "/customize",
                name: "Personalizar",
                slug: "customize",
                icon: "SettingsIcon",
            }
        ]
    }
]
