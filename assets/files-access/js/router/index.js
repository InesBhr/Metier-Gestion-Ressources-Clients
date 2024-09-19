export const routes = [
  {
    path: "/files-access",
    name: "files_access",
    component: () => import("../../../files-access/js/views/FilesAccess.vue"),
    props: { userPermissions: userPermissions },
  },
];
