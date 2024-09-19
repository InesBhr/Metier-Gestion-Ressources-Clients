export const routes = [
  {
    path: "/documentation/modop-formation",
    name: "documentation_modop",
    component: () => import("/assets/documentation/js/views/OperatingMode.vue"),
    props: { userPermissions: userPermissions },
  },
  {
    path: "/documentation/consignes-process",
    name: "documentation_consignes",
    component: () =>
      import("/assets/documentation/js/views/InstructionProcess.vue"),
    props: { userPermissions: userPermissions },
  },
];
