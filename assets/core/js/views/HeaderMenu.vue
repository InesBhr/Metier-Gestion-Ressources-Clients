<template>
  <vsm-menu v-if="menu.length !== 0" :dropdown-offset="10" :menu="menu" element="header">
    <template #default="{ item }">
      <component :is="item.component" />
    </template>

    <template #after-nav>
      <vsm-mob>
        <div class="p-4" style="height: 100vh">
          <template v-for="item in menu">
            <template v-if="item.component">
              <div class="border-bottom border-light text-center h2 pb-2">
                {{ item.title }}
              </div>
              <component :is="item.component" />
            </template>

            <template v-if="item.element === 'router-link'">
              <div class="px-4 py-2">
                <router-link :to="item.attributes.to">{{
    item.title
  }}</router-link>
              </div>
            </template>
          </template>
        </div>
      </vsm-mob>
    </template>
  </vsm-menu>
</template>

<script>
import { VsmMenu } from "vue-stripe-menu";
// import DocumentationDropdown from "../../../documentation/js/views/DocumentationDropdown.vue";
import WorkflowDropdown from "../../../workflow/js/views/WorkflowDropdown.vue";


export default {
  name: "HeaderMenu",
  components: {
    VsmMenu, WorkflowDropdown
  },
  data() {
    return {
      menu: [],
    };
  },
  async beforeMount() {
    this.menu = [
      // { title: 'Documentation', dropdown: 'documentation', component: 'DocumentationDropdown' },
      // { title: 'Accès aux fichiers', attributes: { href: '/files-access', class: [{ 'vsm-selected': window.location.pathname.startsWith('/files-access') }] } },
      { title: 'Workflow', dropdown: 'workflow', component: 'WorkflowDropdown' },
    ];
  },
  mounted() {
    onload = () => this.identityLink();
  },
  updated() {
    this.identityLink();
  },
  methods: {
    /**
     * Remove vsm-selected class from dropdown links
     */
    deactivateLink() {
      let elementsFound = document.querySelectorAll(
        ".vsm-link.vsm-selected.vsm-has-dropdown"
      );
      elementsFound.forEach((item) => item.classList.remove("vsm-selected"));
    },
    /**
     * Found and add sm-selected to current dropdown link
     * @param el
     */
    activeLink(el) {
      while (true) {
        if (el.tagName === "HEADER") {
          return;
        }

        if (el.parentNode.querySelector(".vsm-link-container")) {
          let elementsFound = el.parentNode
            .querySelector(".vsm-link-container")
            .parentNode.querySelectorAll(".vsm-link");

          elementsFound.forEach((element) => {
            if (
              window.location.pathname.includes(element.dataset["dropdown"])
            ) {
              element.classList.add("vsm-selected");
            }
          });
        }

        el = el.parentNode;
      }
    },
    /**
     * Identify dropdown link from current page
     */
    identityLink() {
      let linkSelected = document.querySelector("#header_menu a.vsm-selected");

      this.deactivateLink();
      if (linkSelected !== null && linkSelected !== undefined) {
        this.activeLink(linkSelected);
      }
    },
  },
  watch: {
    "$route.name"() {
      this.identityLink();
    },
  },
};
</script>

<style>
.vsm-menu {
  perspective: unset;
  position: relative;
}

.vsm-selected {
  color: #f16e00;
}
</style>
